<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/helpers.php';

echo "=== Migration: Add Quantity Column ===\n";

try {
    $sql = "ALTER TABLE registrations ADD COLUMN quantity INT NOT NULL DEFAULT 1 AFTER ticket_type_id";
    db_execute($sql);
    echo "✓ Added 'quantity' column to registrations table.\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "⚠ Column 'quantity' already exists.\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "Migration complete.\n";
