document.addEventListener('DOMContentLoaded', () => {
    const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv'); // Chave pública
    const elements = stripe.elements();

    // Criar elemento de cartão
    const card = elements.create('card');
    card.mount('#card-element');

    // Configurar o formulário de pagamento
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const response = await fetch('/create-payment-intent.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                paymentMethodId: card._element.id, // Enviar o ID do método de pagamento
            }),
        });

        const { clientSecret, error } = await response.json();

        if (error) {
            console.error('Erro na criação do PaymentIntent:', error);
            alert('Erro: ' + error);
        } else {
            const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card,
                    billing_details: {
                        name: document.getElementById('name').value,
                    },
                },
            });

            if (confirmError) {
                console.error('Erro ao confirmar o pagamento:', confirmError);
                alert('Erro: ' + confirmError.message);
            } else if (paymentIntent.status === 'succeeded') {
                alert('Pagamento realizado com sucesso!');
                window.location.href = '/payment_success.php';
            }
        }
    });
});
