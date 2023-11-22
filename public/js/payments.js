import StripePayment from './stripe-payment.js';

let stripePayment = new StripePayment();

$(document).ready(function () {
    $('form.subscribe-form').on('submit', async function (e) {
        e.preventDefault();
        loading();
        let response;

        try {
            response = await stripePayment.createPaymentMethod();
        } catch (error) {
            console.log(`error`, error); //! LOG
            let message = typeof error === 'object'
                ? (error.message ?? error.responseJSON.message)
                : error;
            showPopUp('Error', message, false);
            return;
        }

        response = await stripePayment.subscribe({
            plan_id: $("#user-data").data('plan')
        });

        swal.fire("Success!", response.message, 'success').then((result) => {
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }
        });

        return true;
    });
});
