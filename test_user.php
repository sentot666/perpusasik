<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/users', 'POST', [
    'name' => 'Test User',
    'username' => 'testuser99',
    'email' => 'test99@example.com',
    'password' => 'password123',
    'role' => 'pustakawan'
]);

$controller = app(\App\Http\Controllers\UserController::class);
try {
    $response = $controller->store($request);
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "Success redirect. Session: " . json_encode($response->getSession()->all());
    }
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: ";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
