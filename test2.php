<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$book = App\Models\Book::doesntHave('authors')->first(); 
if($book) {
    echo "NO_AUTHOR: " . $book->items()->first()?->barcode . "\n";
} else {
    echo "ALL_HAVE_AUTHORS\n";
}
