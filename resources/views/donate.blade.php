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
            padding-bottom:10px;
        }

    </style>
</head>
<body>
<div class="container" style="max-width: 363px">
    <div class="content">
        <div class="image">
            {{--            <img src="http://www.gravatar.com/avatar/{{ md5("hello@laravel-news.com") }}?s=200" />--}}
        </div>
        <h1>Nathan Brace</h1>
        @if (! $amount)
        @else
            <div class="fields">
                <label for="name">Name</label>
                <input name="name" id="card-holder-name" type="text">
            </div> amount

            <div id="card-element"></div>

            <div style="display: flex" class="pt-4">
                <button id="card-button" class="btn btn-primary">
                    Submit
                </button>
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

    cardElement.mount('#card-element');


    cardButton.addEventListener('click', async (e) => {
        cardButton.innerText = 'Submitting Donation...'
        const {paymentMethod, error} = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: {name: cardHolderName.value}
            }
        );

        if (error) {
            alert(error.message);
        } else {
            console.log(paymentMethod)
            axios.post('/donate', {
                'payment_method': paymentMethod.id
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
