document.addEventListener('DOMContentLoaded', () => {
    const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv'); // Chave pública
    const elements = stripe.elements();

    // Criar elemento de cartão
    const card = elements.create('card');
    card.mount('#card-element');

    // Referências para elementos de feedback do usuário
    const errorElement = document.getElementById('card-errors');
    const submitButton = document.getElementById('submit-payment');

    // Exibir erros de validação no elemento de erro
    card.on('change', (event) => {
        if (event.error) {
            errorElement.textContent = event.error.message;
        } else {
            errorElement.textContent = '';
        }
    });

    // Configurar o formulário de pagamento
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Desabilitar o botão enquanto o pagamento está em andamento
        submitButton.disabled = true;

        try {
            // Criar um PaymentMethod no Stripe
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                },
            });

            if (error) {
                // Mostrar erro ao usuário
                errorElement.textContent = error.message;
                submitButton.disabled = false;
                return;
            }

            // Enviar o PaymentMethod ID para o backend
            const response = await fetch('/create-payment-intent.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    paymentMethodId: paymentMethod.id,
                }),
            });

            const { clientSecret, error: backendError } = await response.json();

            if (backendError) {
                errorElement.textContent = backendError;
                submitButton.disabled = false;
                return;
            }

            // Confirmar o pagamento com o clientSecret
            const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(clientSecret);

            if (confirmError) {
                errorElement.textContent = confirmError.message;
                submitButton.disabled = false;
                return;
            }

            // Se o pagamento foi bem-sucedido
            if (paymentIntent.status === 'succeeded') {
                alert('Pagamento realizado com sucesso!');
                window.location.href = '/payment_success.php';
            }
        } catch (err) {
            console.error('Erro inesperado:', err);
            alert('Ocorreu um erro. Tente novamente mais tarde.');
        } finally {
            // Habilitar o botão novamente em caso de falha
            submitButton.disabled = false;
        }
    });
});

