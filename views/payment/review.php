<?php
$pageTitle = 'Payment Review';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1>Payment Review</h1>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>Rs. <?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rs. <?= number_format($item['total'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="mt-3">
        Grand Total: Rs. <?= number_format($total, 2) ?>
    </h3>

    <form method="POST" action="/Event/payment/form/<?= $eventId ?>">
        <button class="btn btn-primary mt-3">
            Pay!
        </button>
    </form>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
