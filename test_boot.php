<?php
// Simulate Laravel Request
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

// Mock Request to Login
$request = Request::create('/login', 'GET');

try {
    $response = $kernel->handle($request);
    echo "Status Code: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() == 500) {
        echo "Error Content:\n";
        echo strip_tags(substr($response->getContent(), 0, 2000));
    } else {
        echo "Success!\n";
    }
} catch (Exception $e) {
    echo "Exception Caught: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
