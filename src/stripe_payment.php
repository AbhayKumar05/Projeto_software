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
    <div id="payment-request-button"></div>

    <h3>Pay with Card</h3>
    <form id="payment-form">
        <input type="text" id="name" placeholder="Seu nome" required />
        <div id="card-element"></div>
        <button type="submit">Pagar</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv');
            const elements = stripe.elements();
            const clientSecret = '<?php echo $_SESSION["client_secret"]; ?>';

            // Create a card element
            const card = elements.create('card');
            card.mount('#card-element');

            // Card Form Submit
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                const { error } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card,
                        billing_details: {
                            name: document.getElementById('name').value,
                        },
                    },
                });

                if (error) {
                    alert('Erro no pagamento: ' + error.message);
                } else {
                    alert('Pagamento realizado com sucesso!');
                    window.location.href = '/payment_success.php';
                }

                submitButton.disabled = false;
            });
        });
    </script>
</body>
</html>
