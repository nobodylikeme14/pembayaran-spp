$(document).ready(function() {
    const formElement = $('form[name="form-login"]');
    
    formElement.on('submit', function(event) {
        event.preventDefault();
        if (formElement[0].checkValidity() === false) {
            event.stopPropagation();
            formElement.addClass('was-validated');
            return;
        }
        var button = formElement.find("button[type=submit]");
        button.html('Login<i class="fas fa-right-to-bracket fa-flip ml-1"></i>').prop('disabled', true);
        formElement.find('[name="password"]').attr('type', 'password');
        formElement.find('i[name="show-password-icon"]').removeClass('fa-eye-slash').addClass('fa-eye');
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: formElement.attr("action"),
            data: formElement.serialize(),
            type: "POST",
            success: function(response) {
                if (response.status == "success") {
                    return window.location = response.url;
                } else {
                    formElement.find("input[type=password]").val('');
                    errorAlert(response.message);
                }
            },
            error: function(err) {
                if (err.status == 422) {
                    formElement.find('small.text-danger').remove();
                    $.each(err.responseJSON.errors, function (i, error) {
                        var errorList = formElement.find('input[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else if (err.status == 429) {
                    errorAlert("Terlalu banyak permintaan. Silakan coba lagi nanti.");
                } else {
                    errorAlert("Terjadi kesalahan saat melakukan proses login.");
                }
            },
            complete: function() {
                button.html('Login<i class="fas fa-right-to-bracket ml-1"></i>').prop('disabled', false);
            }
        });
    });

    function errorAlert(message) {
        formElement.find('.alert').remove();
        var element = `<div class="alert shadow-sm" role="alert">
            <div class="d-flex justify-content-between">
                <div class="my-auto pr-2">
                    <div class="form-text text-danger font-weight-bold">${message}</div>
                </div>
                <div class="my-auto">
                    <i class="fas fa-info-circle fa-2x text-danger"></i>
                </div>
            </div>
        </div>`;
        formElement.find('.form-group').first().before($(element).delay(8000).fadeOut(500, function() {
            $(this).remove();
        }));
    }

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
});
