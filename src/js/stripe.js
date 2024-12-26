document.addEventListener('DOMContentLoaded', () => {
    const stripe = stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv');
    const elements = stripe.elements();

    // Create a card element
    const card = elements.create('card');
    card.mount('#card-element');

    // Payment Request Button
    const paymentRequest = stripe.paymentRequest({
        country: 'PT',
        currency: 'eur',
        total: {
            label: 'Total',
            amount: 1000, 
        },
        requestPayerName: true,
        requestPayerEmail: true,
    });

    const prButton = elements.create('paymentRequestButton', {
        paymentRequest,
    });

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
            // Ensure confirmCardPayment uses the correct clientSecret here
            const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(clientSecret);
    
            if (confirmError) {
                event.complete('fail');
                console.error(confirmError.message);
            } else {
                event.complete('success');
                alert('Payment Successful!');
                window.location.href = '/payment_success.php';
            }
        }
    });

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
});
