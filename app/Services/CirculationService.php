<?php

namespace App\Services;

use App\Models\Circulation;
use App\Models\Member;
use App\Models\BookItem;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class CirculationService
{
    /**
     * Process borrowing/loan of a book item to a member.
     *
     * @param int $memberId
     * @param int $bookItemId
     * @param string|null $notes
     * @return Circulation
     * @throws Exception
     */
    public function loanBook(int $memberId, int $bookItemId, ?string $notes = null): Circulation
    {
        $member   = Member::findOrFail($memberId);
        $bookItem = BookItem::findOrFail($bookItemId);

        // 1. Validation checks
        if (!$member->is_active) {
            throw new Exception('Anggota tidak aktif.');
        }

        if ($member->is_expired) {
            throw new Exception('Keanggotaan sudah kedaluwarsa. Perbarui keanggotaan terlebih dahulu.');
        }

        if ($bookItem->status !== 'Tersedia') {
            throw new Exception('Eksemplar buku sedang tidak tersedia untuk dipinjam.');
        }

        $maxItems = (int) Setting::get('max_loan_items', 3);
        if ($member->activeCirculations()->count() >= $maxItems) {
            throw new Exception("Anggota sudah mencapai batas maksimal peminjaman ({$maxItems} eksemplar).");
        }

        $loanDays = (int) Setting::get('loan_duration', 14);

        // 2. Perform transaction
        return DB::transaction(function () use ($member, $bookItem, $loanDays, $notes) {
            $circulation = Circulation::create([
                'transaction_code' => Circulation::generateCode(),
                'member_id'        => $member->id,
                'book_item_id'     => $bookItem->id,
                'user_id'          => auth()->id() ?? 1, // Fallback to admin/first user if CLI/unauthenticated
                'loan_date'        => today(),
                'due_date'         => today()->addDays($loanDays),
                'status'           => 'Dipinjam',
                'notes'            => $notes,
            ]);

            $bookItem->update(['status' => 'Dipinjam']);

            return $circulation;
        });
    }

    /**
     * Process return of a book item.
     *
     * @param string $barcode
     * @param string|null $notes
     * @return Circulation
     * @throws Exception
     */
    public function returnBook(string $barcode, ?string $notes = null): Circulation
    {
        $bookItem = BookItem::where('barcode', $barcode)->first();

        if (!$bookItem) {
            // Fallback to ISBN, BUT it MUST be an item that is currently borrowed
            $bookItem = BookItem::whereHas('book', function ($query) use ($barcode) {
                $query->where('isbn', $barcode)
                      ->orWhere('isbn13', $barcode);
            })->where('status', 'Dipinjam')->first();
        }

        if (!$bookItem) {
            throw new Exception('Barcode atau ISBN eksemplar tidak ditemukan.');
        }

        $circulation = Circulation::where('book_item_id', $bookItem->id)
            ->where('status', 'Dipinjam')
            ->first();

        if (!$circulation) {
            throw new Exception('Buku ini tidak tercatat dalam transaksi peminjaman aktif.');
        }

        return DB::transaction(function () use ($circulation, $bookItem, $notes) {
            $fineAmount = 0;
            
            // Calculate fine if overdue
            if ($circulation->due_date->isPast()) {
                $daysOverdue = $circulation->due_date->diffInDays(today());
                $finePerDay = (float) Setting::get('fine_per_day', 1000);
                $fineAmount = $daysOverdue * $finePerDay;
            }

            $circulation->update([
                'return_date' => today(),
                'status'      => 'Dikembalikan',
                'fine_amount' => $fineAmount,
                'notes'       => $notes ? ($circulation->notes ? $circulation->notes . "\n" . $notes : $notes) : $circulation->notes,
            ]);

            $bookItem->update(['status' => 'Tersedia']);

            return $circulation;
        });
    }

    /**
     * Renew/extend a borrowing transaction.
     *
     * @param int $circulationId
     * @return Circulation
     * @throws Exception
     */
    public function renewLoan(int $circulationId): Circulation
    {
        $circulation = Circulation::findOrFail($circulationId);

        if ($circulation->status !== 'Dipinjam') {
            throw new Exception('Hanya peminjaman aktif yang dapat diperpanjang.');
        }

        $maxRenewals = (int) Setting::get('max_renewals', 2);
        if ($circulation->renewal_count >= $maxRenewals) {
            throw new Exception("Batas maksimal perpanjangan ({$maxRenewals} kali) telah tercapai.");
        }

        if ($circulation->due_date->isPast()) {
            throw new Exception('Peminjaman sudah terlambat tidak dapat diperpanjang. Selesaikan denda terlebih dahulu.');
        }

        $loanDays = (int) Setting::get('loan_duration', 14);

        return DB::transaction(function () use ($circulation, $loanDays) {
            $circulation->update([
                'due_date'      => $circulation->due_date->addDays($loanDays),
                'renewal_count' => $circulation->renewal_count + 1,
            ]);

            return $circulation;
        });
    }

    /**
     * Process fine payment for a returned transaction.
     *
     * @param int $circulationId
     * @return Circulation
     * @throws Exception
     */
    public function payFine(int $circulationId): Circulation
    {
        $circulation = Circulation::findOrFail($circulationId);

        if ($circulation->fine_paid) {
            throw new Exception('Denda untuk transaksi ini sudah lunas.');
        }

        if ($circulation->fine_amount <= 0) {
            throw new Exception('Tidak ada denda yang perlu dibayar pada transaksi ini.');
        }

        $circulation->update([
            'fine_paid'    => true,
            'fine_paid_at' => now(),
        ]);

        return $circulation;
    }
}
