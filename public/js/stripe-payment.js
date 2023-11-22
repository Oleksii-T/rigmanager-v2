class StripePayment {
    constructor() {
        console.log(`StripePayment@constructor`); //! LOG

        this.selectors = {
            card: {
                number: '#cardNumber',
                exp: '#cardExp',
                cvc: '#cardCVC'
            },
        };

        this.routes = {
            intent: '/stripe/setup-intent',
            method: '/payment-methods',
            subscriptions: '/subscriptions'
        };

        this.init();
    }

    init() {
        this.stripe = Stripe(window.Laravel.stripe_public_key);

        var elements = this.stripe.elements();
        let styleObj = {
            style: {
                base: {lineHeight: '30px'}
            }
        };

        this.card = elements.create('cardNumber', styleObj);
        this.exp = elements.create('cardExpiry', styleObj);
        this.cvc = elements.create('cardCvc', styleObj);

        this.card.mount(this.selectors.card.number);
        this.exp.mount(this.selectors.card.exp);
        this.cvc.mount(this.selectors.card.cvc);
    }

    async setupIntent() {
        let response = await $.ajax({
            async: false,
            url: this.routes.intent,
            type: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
        });

        console.log('response', response);

        return response.data;
    }

    async savePaymentMethod(data) {
        data._token = $('meta[name="csrf-token"]').attr('content');
        let response = await $.ajax({
            async: false,
            url: this.routes.method,
            type: 'post',
            data: data,
        });

        return response;
    }

    async subscribe(data) {
        data._token = $('meta[name="csrf-token"]').attr('content');
        return await $.ajax({
            async: false,
            url: this.routes.subscriptions,
            type: 'post',
            data: data,
        });
    }

    // create stripe payment method and saves it to server
    async createPaymentMethod() {
        console.log('setupIntent');

        // create setup intent
        let intent_data = await this.setupIntent();

        console.log('createPaymentMethod');

        // creat stripe payment card
        let stripe_data = await this.stripe.createPaymentMethod({
            type: 'card',
            card: this.card,
            billing_details: {
                name: $("#user-data").data('name'),
                email: $("#user-data").data('email'),
            },
        });

        if(stripe_data.error){
            throw stripe_data.error.message;
        }

        console.log('intent_data', intent_data);

        // add intent info to stripe response
        stripe_data.client_secret = intent_data.client_secret;
        stripe_data.intent_id = intent_data.intent_id;
        stripe_data.use_as_default = 1;

        // 3ds
        let stripe_confirm = await this.stripe.confirmCardSetup(intent_data.client_secret, {
            payment_method: stripe_data.paymentMethod.id,
        });

        if(stripe_confirm.error){
            throw stripe_confirm.error.message;
        }

        // save hashed card data to server
        let response = await this.savePaymentMethod(stripe_data);

        return response;
    }
}

export default StripePayment
