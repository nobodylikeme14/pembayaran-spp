$(document).ready(function() {
    //Element
    const formElement = $('form[name="form-data"]');

    //Show password
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

    //Form submit
    formElement.on('submit', function(event) {
        event.preventDefault();
        formElement.find('[name="password"], [name="password_confirmation"]').attr('type', 'password');
        formElement.find('i[name="show-password-icon"]').removeClass('fa-eye-slash').addClass('fa-eye');
        if (formElement[0].checkValidity() === false) {
            event.stopPropagation();
            formElement.addClass('was-validated');
            return;
        }
        Swal.fire({
            icon: 'question',
            title: 'Anda yakin ingin meyimpan info akun ?',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            position: 'center',
            showConfirmButton: true,
            showCancelButton: true,
            preConfirm: () => {
                var button = formElement.find("button[type=submit]");
                button.html('Simpan<i class="fas fa-floppy-disk fa-flip ml-2"></i>').prop('disabled', true);
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url: formElement.attr("action"),
                    data: formElement.serialize(),
                    type: "POST", 
                    success: function(response) {
                        if (response.status == "success") {
                            Swal.fire({
                                icon: response.status,
                                title: response.message,
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    return location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 422) {
                            formElement.find('small.text-danger').remove();
                            $.each(xhr.responseJSON.errors, function (i, error) {
                                var errorList = formElement.find('[name="'+i+'"]').closest(".form-group");
                                var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                                errorList.append($(element).delay(4000).fadeOut(500, function() {
                                    $(this).remove();
                                }));
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Terjadi kesalahan saat proses menyimpan info akun"
                            });
                        }
                    },
                    complete: function() {
                        button.html('Simpan<i class="fas fa-floppy-disk ml-2"></i>').prop('disabled', false);
                    }
                });
            }
        });
    });
});