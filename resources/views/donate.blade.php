<!DOCTYPE html>
<html>
<head>
    <title>Donate to Nathan Brace Campaign</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        #card-button {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
            color: white;
        }

        .fields {
            display: flex;
            flex-direction: column;
            text-align: start;
            padding-bottom: 10px;
        }

    </style>
</head>
<body>
<div class="container" style="max-width: 363px">
    <div class="content">
        <div class="image">
            {{--            <img src="http://www.gravatar.com/avatar/{{ md5("hello@laravel-news.com") }}?s=200" />--}}
        </div>
        <h1>Support the Campaign</h1>
        @if (! $amount)
            <form action="/donate" method="get">
                <div class="form-item">
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" placeholder="Enter a donation..." required>
                </div>
                <p>
                    <button type="submit">
                        <span style="display: block; min-height: 30px;">Donate</span>
                    </button>
                </p>
            </form>
        @else
            <input type="hidden" value="{{$amount}}" id="donation-amount">
            <div class="fields">
                <label for="name">Name</label>
                <input name="name" id="card-holder-name" type="text" required>

                <label for="email">Email (For Receipt)</label>
                <input name="email" id="card-holder-email" type="text">
            </div>

            <div id="card-element"></div>

            <div style="display: flex;justify-content: space-between;" class="pt-4">

                <button id="card-button" class="btn btn-primary">
                    Donate ${{$amount / 100}}
                </button>

                <img src="/images/powered_by_stripe.png"
                     style="height: 25px;"
                     alt="powered by stripe">

            </div>
        @endif
    </div>
</div>
<script>
    const stripe = Stripe('{{config('services.stripe.key')}}');

    const elements = stripe.elements();
    const cardElement = elements.create('card');
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const cardHolderEmail = document.getElementById('card-holder-email');
    const amountElement = document.getElementById('donation-amount');


    cardElement.mount('#card-element');


    cardButton.addEventListener('click', async (e) => {
        cardButton.innerText = 'Submitting Donation...'
        const {paymentMethod, error} = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: {name: cardHolderName.value}
            }
        );

        if (error) {
            cardButton.innerText = 'Submit'
            alert(error.message)
        } else {
            console.log(paymentMethod)
            axios.post('/donate', {
                'payment_method': paymentMethod.id,
                'amount': amountElement.value,
                'name': cardHolderName.value,
                'email': cardHolderEmail.value,
            }).then(function (response) {
                window.location.href = "/success";
            }).catch(function (response) {
                window.location.href = "/error";
            })
        }
    });
</script>
</body>
</html>
