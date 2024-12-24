<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* Styling */
    </style>
</head>
<body>
    <h3>Complete your payment</h3>
    <div id="payment-message"></div>
    <form id="payment-form">
        <div id="card-element"></div>
        <div id="card-errors" role="alert"></div>
        <button id="submit" type="submit">Pay</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv');
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const clientSecret = "<?= htmlspecialchars($_SESSION['client_secret'], ENT_QUOTES, 'UTF-8'); ?>";
                const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: "Customer Name",
                        },
                    },
                });

                if (error) {
                    document.getElementById('card-errors').textContent = error.message;
                } else {
                    alert('Payment successful!');
                    window.location.href = 'payment_success.php';
                }
            });
        });
    </script>
</body>
</html>
