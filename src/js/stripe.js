const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv');
const elements = stripe.elements();
const cardElement = elements.create('card');

cardElement.mount('#card-element');

const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const { error, paymentMethod } = await stripe.createPaymentMethod('card', cardElement);

    if (error) {
        document.getElementById('card-errors').textContent = error.message; 
    } else {
        const response = await fetch('/create-payment-intent', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ paymentMethodId: paymentMethod.id })
        });

        const data = await response.json();

        if (data.error) {
            document.getElementById('card-errors').textContent = data.error; 
        } else {
            const { error: confirmError } = await stripe.confirmCardPayment(data.clientSecret);

            if (confirmError) {
                document.getElementById('card-errors').textContent = confirmError.message; 
            } else {
                alert('Payment Successful!');
                window.location.href = '/payment_success.php';
            }
        }
    }
});
