<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Event Registration and Ticketing System - Register for events in Sri Lanka">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>TicketFlow</title>

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="/Event/public/css/style.css">

</head>

<body>
    <!-- Include Navigation -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>