<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$request = new Illuminate\Http\Request();
$request->merge(['member_id' => 124, 'book_item_ids' => [13, 14], 'notes' => 'Test']);
$controller = app(App\Http\Controllers\CirculationController::class);
try {
    $response = $controller->storeLoan($request);
    echo "Response Class: " . get_class($response) . "\n";
    if (method_exists($response, 'getSession')) {
        $session = $response->getSession();
        if ($session) {
            echo "Success: " . $session->get('success') . "\n";
            echo "Error: " . $session->get('error') . "\n";
        } else {
            echo "No session bound to response\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
