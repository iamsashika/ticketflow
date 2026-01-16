<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - Ticket Types</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f172a;
            color: #f1f5f9;
            padding: 2rem;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #1e293b;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        h1 {
            color: #6366f1;
            margin-bottom: 1rem;
        }
        .warning {
            background: rgba(239, 68, 68, 0.15);
            border-left: 4px solid #ef4444;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0.5rem;
        }
        .success {
            background: rgba(16, 185, 129, 0.15);
            border-left: 4px solid #10b981;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0.5rem;
        }
        .output {
            background: #334155;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            margin: 1rem 0;
        }
        button {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
        }
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎫 Ticket Types Migration</h1>
        
        <?php
        require_once __DIR__ . '/../../config/database.php';
        
        $migrated = false;
        $output = "";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $output .= "=== Ticket Types Migration Script ===\n\n";
                
                // Step 1: Create ticket_types table
                $output .= "Step 1: Creating ticket_types table...\n";
                $sql = "CREATE TABLE IF NOT EXISTS ticket_types (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    event_id INT NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    description TEXT,
                    price DECIMAL(10, 2) NOT NULL,
                    capacity INT NOT NULL,
                    available_seats INT NOT NULL,
                    display_order INT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
                    INDEX idx_event (event_id),
                    INDEX idx_order (display_order)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                
                db_execute($sql);
                $output .= "✓ ticket_types table created successfully\n\n";
                
                // Step 2: Alter registrations table
                $output .= "Step 2: Adding ticket_type_id to registrations table...\n";
                try {
                    // Check if column already exists
                    $check = db_query_one("SHOW COLUMNS FROM registrations LIKE 'ticket_type_id'");
                    if (!$check) {
                        db_execute("ALTER TABLE registrations ADD COLUMN ticket_type_id INT");
                        db_execute("ALTER TABLE registrations ADD FOREIGN KEY (ticket_type_id) REFERENCES ticket_types(id) ON DELETE SET NULL");
                        db_execute("ALTER TABLE registrations ADD INDEX idx_ticket_type (ticket_type_id)");
                        $output .= "✓ registrations table updated successfully\n\n";
                    } else {
                        $output .= "⚠ Column already exists, skipping\n\n";
                    }
                } catch (Exception $e) {
                    $output .= "⚠ " . $e->getMessage() . "\n\n";
                }
                
                // Step 3: Migrate existing events
                $output .= "Step 3: Migrating existing events to ticket types...\n";
                $sql = "INSERT INTO ticket_types (event_id, name, description, price, capacity, available_seats, display_order)
                        SELECT 
                            id as event_id,
                            'General Admission' as name,
                            'Standard entry to the event' as description,
                            ticket_price as price,
                            capacity,
                            available_seats,
                            0 as display_order
                        FROM events
                        WHERE id NOT IN (SELECT DISTINCT event_id FROM ticket_types)";
                
                db_execute($sql);
                $output .= "✓ Existing events migrated\n\n";
                
                // Step 4: Update existing registrations
                $output .= "Step 4: Updating existing registrations...\n";
                $sql = "UPDATE registrations r
                        INNER JOIN ticket_types tt ON r.event_id = tt.event_id AND tt.name = 'General Admission'
                        SET r.ticket_type_id = tt.id
                        WHERE r.ticket_type_id IS NULL";
                
                db_execute($sql);
                $output .= "✓ Existing registrations updated\n\n";
                
                // Verification
                $output .= "=== Verification ===\n";
                
                $events_count = db_query_one("SELECT COUNT(*) as count FROM events");
                $ticket_types_count = db_query_one("SELECT COUNT(DISTINCT event_id) as count FROM ticket_types");
                $registrations_without_type = db_query_one("SELECT COUNT(*) as count FROM registrations WHERE ticket_type_id IS NULL");
                
                $output .= "Total Events: " . $events_count['count'] . "\n";
                $output .= "Events with Ticket Types: " . $ticket_types_count['count'] . "\n";
                $output .= "Registrations without ticket type: " . $registrations_without_type['count'] . "\n\n";
                
                if ($registrations_without_type['count'] == 0) {
                    $output .= "✓ Migration completed successfully!\n";
                    $migrated = true;
                } else {
                    $output .= "⚠ Warning: Some registrations don't have ticket types assigned\n";
                }
                
            } catch (Exception $e) {
                $output .= "✗ Error: " . $e->getMessage() . "\n";
            }
        }
        ?>
        
        <?php if (!$migrated && $_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <div class="warning">
                <strong>⚠️ Important:</strong> This will create the ticket_types table and migrate your existing events to use ticket types.
                <br><br>
                <strong>What this does:</strong>
                <ul>
                    <li>Creates a new <code>ticket_types</code> table</li>
                    <li>Adds <code>ticket_type_id</code> column to registrations</li>
                    <li>Creates "General Admission" ticket type for all existing events</li>
                    <li>Links all existing registrations to these ticket types</li>
                </ul>
            </div>
            
            <form method="POST">
                <button type="submit">Run Migration</button>
            </form>
        <?php endif; ?>
        
        <?php if ($output): ?>
            <div class="<?php echo $migrated ? 'success' : ''; ?>">
                <div class="output"><?php echo htmlspecialchars($output); ?></div>
            </div>
            
            <?php if ($migrated): ?>
                <a href="/Event/" style="color: #6366f1; text-decoration: none; font-weight: 600;">← Back to Home</a>
            <?php else: ?>
                <button onclick="location.reload()">Try Again</button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
