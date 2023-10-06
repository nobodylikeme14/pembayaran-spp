$(document).ready(function() {
    //Ajax Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Element
    const formLinkElement = $('form[name="form-kirim-link"]');
    const formResetElement = $('form[name="form-reset-password"]');

    //Show Form Password
    $('.show-password-form').on('click', function() {
        var passwordInput = $(this).closest('.input-group').find('input');
        $(this).find('i.fas').toggleClass('fa-eye fa-eye-slash');
        var passwordType = passwordInput.attr('type');
        if (passwordType == 'password') {
            passwordInput.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
        }
    });

    //Show Alert
    function showAlert(message, type) {
        var alertType = type === 'success' ? 'success' : 'danger';
        var element = `<div class="alert shadow-sm" role="alert">
            <div class="d-flex justify-content-between">
                <div class="my-auto pr-2">
                    <div class="form-text text-${alertType} font-weight-bold">${message}</div>
                </div>
                <div class="my-auto">
                    <i class="fas fa-info-circle fa-2x text-${alertType}"></i>
                </div>
            </div>
        </div>`;
        $(document).find('.form-group').first().before($(element).delay(8000).fadeOut(500, function() {
            $(this).remove();
        }));
    }

    //Form Link Submit
    formLinkElement.submit(function(event){
        event.preventDefault();
        if (formLinkElement[0].checkValidity() === false) {
            event.stopPropagation();
            formLinkElement.addClass('was-validated');
            return;
        }
        var button = formLinkElement.find("button[type=submit]");
        $.ajax({
            beforeSend: function(){
                button.html('Kirim Link<i class="fas fa-paper-plane fa-flip ml-1"></i>').prop('disabled', true);
                formLinkElement.removeClass('was-validated');
                formLinkElement.find('.alert, small.text-danger').remove();
            },
            url: formLinkElement.attr("action"),
            data: formLinkElement.serialize(),
            type: "POST", 
            success: function(response) {
                showAlert(response.message, 'success');
            },
            error: function(err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var errorList = formLinkElement.find('[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else {
                    showAlert(err.responseJSON.message, 'error');
                }
            },
            complete: function() {
                button.html('Kirim Link<i class="fas fa-paper-plane ml-1"></i>').prop('disabled', false);
            }
        });
    });

    //Form Reset Submit
    formResetElement.submit(function(event){
        event.preventDefault();
        if (formResetElement[0].checkValidity() === false) {
            event.stopPropagation();
            formResetElement.addClass('was-validated');
            return;
        }
        var button = formResetElement.find("button[type=submit]");
        $.ajax({
            beforeSend: function(){
                button.html('Reset Password<i class="fas fa-arrow-rotate-right fa-spin ml-1"></i>').prop('disabled', true);
                formResetElement.find('[name="password"], [name="password_confirmation"]').attr('type', 'password');
                formResetElement.find('i[name="show-password-icon"]').removeClass('fa-eye-slash').addClass('fa-eye');
                formResetElement.removeClass('was-validated');
                formResetElement.find('.alert, small.text-danger').remove();
            },
            url: formResetElement.attr("action"),
            data: formResetElement.serialize(),
            type: "POST", 
            success: function(response) {
                return window.location = response.url;
            },
            error: function(err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var errorList = formResetElement.find('[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else {
                    showAlert(err.responseJSON.message, 'error');
                }
            },
            complete: function() {
                button.html('Reset Password<i class="fas fa-arrow-rotate-right ml-1"></i>').prop('disabled', false);
            }
        });
    });
});