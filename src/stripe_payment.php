<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h3>Choose Payment Method</h3>

    <!-- Payment Request Button for Google Pay, Apple Pay, and other wallets -->
    <div id="payment-request-button"></div>

    <h3>Or Pay with Card</h3>
    <form id="payment-form">
        <div id="card-element"></div>
        <button type="submit">Pay</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv');
            const elements = stripe.elements();

            const clientSecret = '<?php echo $_SESSION["client_secret"]; ?>';

            // Create a card element
            const card = elements.create('card');
            card.mount('#card-element');

            // Create a Payment Request Button (for Google Pay, Apple Pay)
            /*const paymentRequest = stripe.paymentRequest({
                country: 'US', // Modify based on your country
                currency: 'eur',
                total: {
                    label: 'Total',
                    amount: <?php /*echo $cart_total * 100; */ ?>, // Total in cents
                },
                requestPayerName: true,
                requestPayerEmail: true,
            });

            const prButton = elements.create('paymentRequestButton', {
                paymentRequest,
            });

            // Check if Payment Request is available
            paymentRequest.canMakePayment().then((result) => {
                if (result) {
                    prButton.mount('#payment-request-button');
                } else {
                    document.getElementById('payment-request-button').style.display = 'none';
                }
            });

            paymentRequest.on('paymentmethod', async (event) => {
                const response = await fetch('/create-payment-intent', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ paymentMethodId: event.paymentMethod.id }),
                });

                const { clientSecret, error } = await response.json();

                if (error) {
                    event.complete('fail');
                    console.error(error.message);
                } else {
                    const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(clientSecret);

                    if (confirmError) {
                        console.error(confirmError.message);
                        event.complete('fail');
                    } else {
                        event.complete('success');
                        alert('Payment Successful!');
                        window.location.href = '/payment_success.php';
                    }
                }
            });*/

            // Card Form Submit
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
            card,
            billing_details: {
                name: document.getElementById('name').value,
            },
        },
    });

    if (error) {
        console.error(error.message);
    } else {
        alert('Payment Successful!');
        window.location.href = '/payment_success.php';
    }
});



const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const { error } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
            card,
            billing_details: {
                name: document.getElementById('name').value,
            },
        },
    });

    if (error) {
        console.error(error.message);
    } else {
        alert('Payment Successful!');
        window.location.href = '/payment_success.php';
    }
});


        });
    </script>
</body>
</html>
