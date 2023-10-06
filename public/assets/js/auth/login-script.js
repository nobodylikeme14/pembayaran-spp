$(document).ready(function() {
    //Ajax Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Element
    const formElement = $('form[name="form-login"]');

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
        formElement.find('.form-group').first().before($(element).delay(8000).fadeOut(500, function() {
            $(this).remove();
        }));
    }

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
    
    //Form Login Submit
    formElement.submit(function(event){
        event.preventDefault();
        if (formElement[0].checkValidity() === false) {
            event.stopPropagation();
            formElement.addClass('was-validated');
            return;
        }
        var button = formElement.find("button[type=submit]");
        $.ajax({
            beforeSend: function(){
                button.html('Login<i class="fas fa-right-to-bracket fa-flip ml-1"></i>').prop('disabled', true);
                formElement.removeClass('was-validated');
                formElement.find('.alert, small.text-danger').remove();
                formElement.find('[name="password"]').attr('type', 'password');
                formElement.find('i[name="show-password-icon"]').removeClass('fa-eye-slash').addClass('fa-eye');
            },
            url: formElement.attr("action"),
            data: formElement.serialize(),
            type: "POST", 
            success: function(response) {
                return window.location = response.url;
            },
            error: function(err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var errorList = formElement.find('[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else {
                    if (err.status == 401) {
                        formElement.find("input[type=password]").val('');
                    }
                    showAlert(err.responseJSON.message, 'error');
                }
            },
            complete: function() {
                button.html('Login<i class="fas fa-right-to-bracket ml-1"></i>').prop('disabled', false);
            }
        });
    });
});
