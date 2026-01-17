<?php
$pageTitle = 'Payment';
require_once __DIR__ . '/../layout/header.php';
?>

<style>
    .payment-page {
        font-family: inherit;
    }

    /* Card container */
    .payment-page .payment-container {
        width: 420px;
        margin: var(--spacing-xl) auto;
        padding: var(--spacing-lg);
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    /* Title */
    .payment-page .payment-title {
        text-align: center;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: var(--spacing-lg);
        color: var(--text-primary);
    }

    /* Amount box */
    .payment-page .amount-box {
        background: var(--bg-tertiary);
        padding: var(--spacing-md);
        border-radius: var(--radius-md);
        margin-bottom: var(--spacing-lg);
        color: var(--text-secondary);
        font-weight: 600;
    }

    .payment-page .amount-box .amount-value {
        float: right;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    /* Payment method */
    .payment-page .payment-method {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-md);
    }

    .payment-page .payment-method label {
        font-weight: 600;
        color: var(--text-primary);
    }

    .payment-page .card-icons img {
        height: 40px;
        margin-left: 6px;
        object-fit: contain;
    }

    /* Form */
    .payment-page .form-group {
        margin-bottom: var(--spacing-md);
    }

    .payment-page label {
        font-size: 0.9rem;
        margin-bottom: 4px;
        display: block;
        color: var(--text-secondary);
    }

    .payment-page input {
        width: 100%;
        padding: 10px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--bg-tertiary);
        background: var(--dark-color);
        color: var(--text-primary);
    }

    .payment-page input:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    /* Row */
    .payment-page .row {
        display: flex;
        gap: var(--spacing-sm);
    }

    /* Button */
    .payment-page button {
        width: 100%;
        padding: 14px;
        background: var(--gradient-success);
        color: var(--white);
        border: none;
        font-size: 1rem;
        font-weight: 600;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: var(--transition);
    }

    .payment-page button:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Errors */
    .payment-page .error {
        color: var(--danger-color);
        font-size: 0.75rem;
        margin-top: 3px;
    }

    /* Footer text */
    .payment-page .privacy-text {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: var(--spacing-md);
        text-align: center;
    }
</style>

<main class="container payment-page">
    <div class="payment-container">
        <h2 class="payment-title">Payment</h2>
        <div class="amount-box">
            Amount to Pay
            <span class="amount-value">Rs. <?= number_format($total, 2) ?></span>
        </div>

        <form method="POST"
            action="/Event/payment/process/<?= $eventId ?>"
            onsubmit="return validateForm()">

            <div class="payment-method">
                <label>Pay with Card</label>
                <div class="card-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
                </div>
            </div>

            <div class="form-group">
                <label>Card Number</label>
                <input type="text"
                    id="cardNumber"
                    name="cardNumber"
                    placeholder="5399 0000 0000 0000"
                    maxlength="19"
                    required
                    value="4890111570487942">
                <small class="error" id="cardError"></small>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Expiration Date</label>
                    <input type="text"
                        id="expiry"
                        name="expiry"
                        placeholder="MM/YY"
                        maxlength="5"
                        value="10/25"
                        required>
                    <small class="error" id="expiryError"></small>
                </div>

                <div class="form-group">
                    <label>CVV</label>
                    <input type="password"
                        id="cvv"
                        name="cvv"
                        placeholder="***"
                        maxlength="3"
                        value="123"
                        required>
                    <small class="error" id="cvvError"></small>
                </div>
            </div>

            <button type="submit">
                Pay Rs. <?= number_format($total, 2) ?>
            </button>

            <p class="privacy-text">
                Your personal data will be used to process your order and support your experience.
            </p>
        </form>


    </div>
</main>

<script>
    const amount = 100;

    // Card number formatting
    document.getElementById("cardNumber").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, "").substring(0, 16);
        e.target.value = value.replace(/(.{4})/g, "$1 ").trim();
    });

    // Expiry MM/YY
    document.getElementById("expiry").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, "");
        if (value.length >= 3) {
            value = value.substring(0, 4);
            value = value.replace(/(\d{2})(\d{1,2})/, "$1/$2");
        }
        e.target.value = value;
    });

    // CVV
    document.getElementById("cvv").addEventListener("input", function(e) {
        e.target.value = e.target.value.replace(/\D/g, "").substring(0, 3);
    });

    // Form validation
    function validateForm() {
        let valid = true;

        const card = document.getElementById("cardNumber").value.replace(/\s/g, "");
        const expiry = document.getElementById("expiry").value;
        const cvv = document.getElementById("cvv").value;

        document.getElementById("cardError").innerText = "";
        document.getElementById("expiryError").innerText = "";
        document.getElementById("cvvError").innerText = "";

        if (card.length !== 16) {
            document.getElementById("cardError").innerText = "Invalid card number";
            valid = false;
        }

        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            document.getElementById("expiryError").innerText = "Invalid expiry date";
            valid = false;
        }

        if (cvv.length !== 3) {
            document.getElementById("cvvError").innerText = "Invalid CVV";
            valid = false;
        }

        return valid;
    }
</script>

<?php

require_once __DIR__ . '/../layout/footer.php';
?>