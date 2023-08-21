$(document).ready(function() {
    const formLinkElement = $('form[name="form-kirim-link"]');
    const formVerifyElement = $('form[name="form-reset-password"]');

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

    //Form Link Submit
    formLinkElement.submit(function(event){
        event.preventDefault();
        if (formLinkElement[0].checkValidity() === false) {
            event.stopPropagation();
            formLinkElement.addClass('was-validated');
            return;
        }
        var button = formLinkElement.find("button[type=submit]");
        formLinkElement.find('small').remove();
        button.html('Kirim Link<i class="fas fa-paper-plane fa-flip ml-1"></i>').prop('disabled', true);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: formLinkElement.attr("action"),
            data: formLinkElement.serialize(),
            type: "POST", 
            success: function(response) {
                if (response.status == "success") {
                    var element = `<small class="text-success font-weight-bold">${response.message}</small>`;
                    var errorList = formLinkElement.find('[name="email"]').closest(".form-group");
                    errorList.append($(element));
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status == 422) {
                    $.each(xhr.responseJSON.errors, function (i, error) {
                        var errorList = formLinkElement.find('input[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                }  else if (xhr.status == 429) {
                    $.each(xhr.responseJSON.errors, function (i, error) {
                        var errorList = formLinkElement.find('input[name="email"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else {
                    var element = `<small class="text-danger font-weight-bold">
                    Terjadi kesalahan saat mengirimkan link reset password. 
                    Silahkan coba beberapa saat lagi.</small>`;
                    var errorList = formLinkElement.find('[name="email"]').closest(".form-group");
                    errorList.append($(element));
                }
            },
            complete: function() {
                button.html('Kirim Link<i class="fas fa-paper-plane ml-1"></i>').prop('disabled', false);
            }
        });
    });

    //Form Reset Submit
    formVerifyElement.submit(function(event) {
        event.preventDefault();
        if (formVerifyElement[0].checkValidity() === false) {
            event.stopPropagation();
            formVerifyElement.addClass('was-validated');
            return;
        }
        var button = formVerifyElement.find("button[type=submit]");
        formVerifyElement.find('[name="password"], [name="password_confirmation"]').attr('type', 'password');
        formVerifyElement.find('i[name="show-password-icon"]').removeClass('fa-eye-slash').addClass('fa-eye');
        formVerifyElement.find('small').remove();
        button.html('Reset Password<i class="fas fa-arrow-rotate-right fa-spin ml-1"></i>').prop('disabled', true);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: formVerifyElement.attr("action"),
            data: formVerifyElement.serialize(),
            type: "POST", 
            success: function(response) {
                if (response.status == "success") {
                    return window.location = response.url;
                } else {
                    var element = `<small class="text-danger font-weight-bold">${response.message}</small>`;
                    formVerifyElement.find('[name="password_confirmation"]').closest(".input-group").after($(element));
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status == 422) {
                    $.each(xhr.responseJSON.errors, function (i, error) {
                        var errorList = formVerifyElement.find('input[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                }  else if (xhr.status == 429) {
                    var errorList = formVerifyElement.find('input[name="email"]').closest(".form-group");
                    var element = `<small class="text-danger font-weight-bold">${hr.responseJSON.message}</small>`;
                    errorList.append($(element).delay(4000).fadeOut(500, function() {
                        $(this).remove();
                    }));
                } else {
                    var element = `<small class="text-danger font-weight-bold">
                        Terjadi kesalahan saat melakukan proses reset password.
                    </small>`;
                    formVerifyElement.find('[name="password_confirmation"]').closest(".form-group").append($(element));
                }
            },
            complete: function() {
                button.html('Reset Password<i class="fas fa-arrow-rotate-right ml-1"></i>').prop('disabled', false);
            }
        });
    });
});