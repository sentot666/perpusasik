@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">Dashboard Admin</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Selamat datang,') }} <strong>{{ auth()->user()->name }}</strong> — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
</div>

{{-- ── Stat Cards Row 1 ─────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="btn-gradient-blue text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-journals"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['total_books']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Total Judul Buku') }}</div>
            </div>
        </div>
    </div>
    
    <div class="btn-gradient-green text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['total_members']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Total Anggota') }}</div>
            </div>
        </div>
    </div>
    
    <div class="btn-gradient-orange text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['active_loans']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Sedang Dipinjam') }}</div>
            </div>
        </div>
    </div>
    
    <div class="btn-gradient-red text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['overdue_loans']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Terlambat') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Stat Cards Row 2 ─────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-slate-800 hover:bg-slate-900 text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-collection"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['total_items']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Total Eksemplar') }}</div>
            </div>
        </div>
    </div>
    
    <div class="btn-gradient-cyan text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['available_items']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Eksemplar Tersedia') }}</div>
            </div>
        </div>
    </div>
    
    <div class="btn-gradient-purple text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['loans_today']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Pinjam Hari Ini') }}</div>
            </div>
        </div>
    </div>
    
    <div class="btn-gradient-yellow text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 p-5">
        <div class="items-center flex gap-4">
            <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-12 h-12 text-xl shadow-sm backdrop-blur-sm flex-shrink-0">
                <i class="bi bi-calendar-x"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-value text-white font-bold text-2xl truncate">{{ number_format($stats['returns_today']) }}</div>
                <div class="stat-label text-white/90 text-xs font-medium truncate">{{ __('Kembali Hari Ini') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Charts Row ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4"><i class="bi bi-graph-up text-blue-500 mr-2"></i>Statistik Kunjungan (7 Hari)</h3>
        <div class="relative h-64">
            <canvas id="visitorChart" data-labels="{{ json_encode($chartLabels) }}" data-values="{{ json_encode($chartData) }}"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4"><i class="bi bi-pie-chart-fill text-indigo-500 mr-2"></i>Kategori Buku Terfavorit</h3>
        <div class="relative h-64">
            <canvas id="categoryChart" data-labels="{{ json_encode($categoryLabels) }}" data-values="{{ json_encode($categoryCounts) }}"></canvas>
        </div>
    </div>
</div>

{{-- ── Tables Row ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    {{-- Recent Loans --}}
    <div class="lg:col-span-7">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 h-full overflow-hidden flex flex-col">
            <div class="justify-between items-center px-4 sm:px-6 py-4 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 flex">
                <span class="text-sm font-semibold flex items-center gap-2"><i class="bi bi-arrow-left-right text-amber-500"></i>{{ __('Peminjaman Terkini') }}</span>
                <a href="{{ route('circulations.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 hover:bg-slate-50 transition-colors px-3">{{ __('Lihat Semua') }}</a>
            </div>
            <div class="p-0 flex-1">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                        <thead>
                            <tr>
                                <th>{{ __('Anggota') }}</th>
                                <th>{{ __('Judul Buku') }}</th>
                                <th>{{ __('Tgl Pinjam') }}</th>
                                <th>{{ __('Jatuh Tempo') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLoans as $loan)
                            @if($loan instanceof \App\Models\ClassLoan)
                            <tr class="bg-indigo-50/30">
                                <td>
                                    <div class="font-medium flex items-center gap-1.5">
                                        <span class="bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded text-[10px] font-bold">KELAS</span>
                                        {{ $loan->borrower_name }}
                                    </div>
                                    <div class="text-slate-500 text-xs">{{ $loan->origin }}</div>
                                </td>
                                <td class="truncate max-w-[160px]" title="{{ $loan->book_type }}">
                                    {{ $loan->book_type }} <span class="text-slate-400 text-xs">({{ $loan->quantity }} eks)</span>
                                </td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td><span class="text-slate-400">-</span></td>
                                <td>
                                    @if($loan->status === 'Dikembalikan')
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Dikembalikan') }}</span>
                                    @else
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Dipinjam') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $loan->member->name }}</div>
                                    <div class="text-slate-500 text-xs">{{ $loan->member->member_code }}</div>
                                </td>
                                <td class="truncate max-w-[160px]" title="{{ $loan->bookItem?->book?->title }}">
                                    {{ $loan->bookItem?->book?->title ?? '-' }}
                                </td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="{{ $loan->is_overdue ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($loan->is_overdue)
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">{{ __('Terlambat') }} {{ $loan->days_overdue }}h</span>
                                    @elseif($loan->status === 'Dikembalikan')
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Dikembalikan') }}</span>
                                    @else
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Dipinjam') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr><td colspan="5" class="text-center text-slate-500 py-6">{{ __('Belum ada transaksi') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Overdue --}}
    <div class="lg:col-span-5 flex flex-col gap-6">
        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 text-sm flex items-center gap-2">
                <i class="bi bi-lightning-charge-fill text-amber-500"></i>{{ __('Aksi Cepat') }}
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('circulations.loan') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2.5 px-4 text-center">
                        <i class="bi bi-box-arrow-right"></i>{{ __('Peminjaman') }}
                    </a>
                    <a href="{{ route('circulations.return') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-green transition-colors text-white gap-2 py-2.5 px-4 text-center">
                        <i class="bi bi-box-arrow-in-left"></i>{{ __('Pengembalian') }}
                    </a>
                    <a href="{{ route('members.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors gap-2 py-2.5 px-4 text-center">
                        <i class="bi bi-person-plus"></i>{{ __('Tambah Anggota') }}
                    </a>
                    <a href="{{ route('books.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2.5 px-4 text-center">
                        <i class="bi bi-plus-circle"></i>{{ __('Tambah Buku') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Overdue List --}}
        @if($overdueLoans->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
            <div class="btn-gradient-red text-white px-4 sm:px-6 py-3.5 border-b border-red-200 font-semibold text-sm flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>{{ __('Terlambat') }} ({{ $overdueLoans->count() }})
            </div>
            <div class="p-0">
                <ul class="divide-y divide-slate-100 flex flex-col">
                    @foreach($overdueLoans->take(5) as $loan)
                    <li class="block py-3 px-4 hover:bg-red-50/50 transition-colors">
                        <div class="justify-between items-start flex gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold text-slate-800 text-xs sm:text-sm">{{ $loan->member->name }}</div>
                                <div class="text-slate-500 text-xs truncate">{{ $loan->bookItem?->book?->title }}</div>
                            </div>
                            <span class="inline-flex py-1 text-xs font-semibold rounded-md bg-red-100 text-red-700 px-2 flex-shrink-0">{{ $loan->days_overdue }}h</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let visitorChartInstance = null;
    let categoryChartInstance = null;

    function initDashboardCharts() {
        // Destroy existing instances if they exist
        if (visitorChartInstance) visitorChartInstance.destroy();
        if (categoryChartInstance) categoryChartInstance.destroy();

        // Visitor Chart
        const visitorCanvas = document.getElementById('visitorChart');
        if (visitorCanvas) {
            const visitorLabels = JSON.parse(visitorCanvas.dataset.labels || '[]');
            const visitorValues = JSON.parse(visitorCanvas.dataset.values || '[]');
            
            const visitorCtx = visitorCanvas.getContext('2d');
            visitorChartInstance = new Chart(visitorCtx, {
                type: 'line',
                data: {
                    labels: visitorLabels,
                    datasets: [{
                        label: 'Pengunjung',
                        data: visitorValues,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { stepSize: 1, color: '#94a3b8' },
                            grid: { color: '#f1f5f9', drawBorder: false }
                        },
                        x: {
                            ticks: { color: '#94a3b8' },
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });
        }

        // Category Chart
        const categoryCanvas = document.getElementById('categoryChart');
        if (categoryCanvas) {
            const categoryLabels = JSON.parse(categoryCanvas.dataset.labels || '[]');
            const categoryValues = JSON.parse(categoryCanvas.dataset.values || '[]');
            
            const categoryCtx = categoryCanvas.getContext('2d');
            categoryChartInstance = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryValues,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { 
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                color: '#64748b',
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 10,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initDashboardCharts);
    document.addEventListener('content-refreshed', initDashboardCharts);
</script>
@endpush
