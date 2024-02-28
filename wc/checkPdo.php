<?php

// Check if PDO extension is loaded
if (extension_loaded('pdo')) {
    echo "PDO extension is installed.\n";

    // Check if specific PDO drivers are available
    $availableDrivers = PDO::getAvailableDrivers();
    if (!empty($availableDrivers)) {
        echo "Available PDO drivers:\n";
        foreach ($availableDrivers as $driver) {
            echo "- $driver\n";
        }
    } else {
        echo "No PDO drivers are available.\n";
    }
} else {
    echo "PDO extension is not installed.\n";
}
