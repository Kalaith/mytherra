<?php
require __DIR__ . '/vendor/autoload.php';

// Mock app setup
$app = require __DIR__ . '/public/index.php';

use App\Utils\ValidationHelper;
use App\Models\DivineBet;

echo "Testing ValidationHelper directly...\n";
try {
    ValidationHelper::validateString("test", "field");
    echo "ValidationHelper::validateString passed\n";
} catch (\Throwable $e) {
    echo "ValidationHelper::validateString failed: " . $e->getMessage() . "\n";
}

echo "\nTesting DivineBet class loading...\n";
if (class_exists(DivineBet::class)) {
    echo "DivineBet class loaded\n";
} else {
    echo "DivineBet class NOT loaded\n";
}

echo "\nDone debugging.\n";
