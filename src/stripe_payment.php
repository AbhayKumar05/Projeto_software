<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* Estilos básicos para uma melhor experiência do usuário */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        h3 {
            margin-bottom: 10px;
        }
        #card-element {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #payment-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #payment-form button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        #card-errors {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h3>Pay with Card</h3>
    <form id="payment-form">
        <!-- Campo para Nome -->
        <div>
            <label for="name">Nome:</label>
            <input type="text" id="name" placeholder="Seu Nome" required>
        </div>

        <!-- Campo para Email -->
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" placeholder="Seu Email" required>
        </div>

        <!-- Elemento do Cartão -->
        <div>
            <label for="card-element">Detalhes do Cartão:</label>
            <div id="card-element"></div>
        </div>

        <!-- Erros do Cartão -->
        <div id="card-errors" role="alert"></div>

        <!-- Botão de Enviar -->
        <button id="submit-payment" type="submit">Pagar</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stripe = Stripe('pk_test_51QXsEiDzsayfXOwXemJhhZbbbfLMHhBoRq8M8A33gwOwQk0IzPnA4n7u7OTuJUc6IALaLPRyvNnxfwOpQ2BsTCQ400TvhwGlRv');
            const elements = stripe.elements();

            // Criação do Card Element
            const card = elements.create('card', {
                hidePostalCode: true // Opcional: Esconde o campo de código postal
            });
            card.mount('#card-element');

            // Mensagens de erro
            card.on('change', (event) => {
                const errorElement = document.getElementById('card-errors');
                if (event.error) {
                    errorElement.textContent = event.error.message;
                } else {
                    errorElement.textContent = '';
                }
            });

            // Formulário de Pagamento
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Processando...';

                try {
                    // Obter clientSecret do backend (via sessão ou endpoint específico)
                    const clientSecret = '<?php echo $_SESSION["client_secret"]; ?>';
                    if (!clientSecret) {
                        throw new Error('Client secret não encontrado. Verifique a configuração do servidor.');
                    }

                    // Confirmar o pagamento com os dados de cobrança
                    const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card,
                            billing_details: {
                                name: document.getElementById('name').value,
                                email: document.getElementById('email').value,
                            },
                        },
                    });

                    if (error) {
                        throw new Error(error.message);
                    }

                    // Redireciona após pagamento bem-sucedido
                    alert('Pagamento realizado com sucesso!');
                    window.location.href = '/payment_success.php';

                } catch (err) {
                    document.getElementById('card-errors').textContent = err.message;
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Pagar';
                }
            });
        });
    </script>
</body>
</html>
