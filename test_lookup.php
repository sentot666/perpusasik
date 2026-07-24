<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$req = new Illuminate\Http\Request();
$req->merge(['barcode'=>'B000152']);
$ctrl = app()->make(App\Http\Controllers\BookItemController::class);
$res = $ctrl->ajaxLookup($req);
echo $res->getContent();
