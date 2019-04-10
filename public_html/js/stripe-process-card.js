$(function () {
    var $cardErrors = $('#card-errors');
    var $cardNumber = $('#card-number');
    var $expiryMonth = $('#expiry-month');
    var $expiryYear = $('#expiry-year');
    var $cvc = $('#cvc');
    var $submitBtn = $('.submit-btn');
    var submitButtonText = $submitBtn.text();

    $cardNumber.payment('formatCardNumber');
    $cvc.payment('formatCardCVC');

    $.validate({
        form: '#payment-form',
        modules: 'date',
        onSuccess: function (e) {
            //required fields validation
            if ($('#card-holder-name').val() == '') {
                $('#card-holder-name').focus();
                return false;
            } else if ($('#card-number').val() == '') {
                $('#card-number').focus();
                return false;
            } else if ($('#expiry-month').val() == '') {
                $('#expiry-month').focus();
                return false;
            } else if ($('#expiry-year').val() == '') {
                $('#expiry-year').focus();
                return false;
            } else if ($('#cvc').val() == '') {
                $('#cvc').focus();
                return false;
            }

            $cardErrors.empty().hide();
            $submitBtn.attr('disabled', true);
            var cardValid = $.payment.validateCardNumber($cardNumber.val());
            var expiryValid = $.payment.validateCardExpiry($expiryMonth.val(), $expiryYear.val()); //=> true
            if (!cardValid) {
                $cardErrors.text('Invalid Card number!').show();
                $submitBtn.removeAttr('disabled').html(submitButtonText);
                return false;
            }
            else if (!expiryValid) {
                $cardErrors.text('Invalid Card Expiry!').show();
                $submitBtn.removeAttr('disabled').html(submitButtonText);
                return false;
            }

            $submitBtn
                .find('span')
                .addClass('glyphicon glyphicon-refresh spinning')
                .text('');

            $submitBtn
                .attr('disabled',true)
                .empty()
                .html(spinner);

            Stripe.card.createToken({
                number: $cardNumber.val(),
                cvc: $cvc.val(),
                exp_month: $expiryMonth.val(),
                exp_year: $expiryYear.val(),
            }, stripeResponseHandler);

            return false;
        }
    });

});

function stripeResponseHandler(status, response) {
    // Grab the form:
    var $form = $('#payment-form');

    if (response.error) { // Problem!

        // Show the errors on the form:
        $('#card-errors').text(response.error.message).show();
        $('.submit-btn').removeAttr('disabled').html('Pay'); // Re-enable submission


    } else { // Token was created!

        // Get the token ID:
        var token = response.id;

        // Insert the token ID into the form so it gets submitted to the server:
        $form.append($('<input type="hidden" name="stripeToken">').val(token));

        // Submit the form:
        $form.get(0).submit();
    }
}