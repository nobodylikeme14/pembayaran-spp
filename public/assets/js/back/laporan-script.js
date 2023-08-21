$(document).ready(function() {
    //Element
    const formElement = $('form[name="form-laporan"]');

    //Form Submit
    formElement.on('submit', function(event) {
        event.preventDefault();
        if (formElement[0].checkValidity() === false) {
            event.stopPropagation();
            formElement.addClass('was-validated');
            return;
        }
        var url = formElement.attr('action');
        var button = formElement.find("button[type=submit]");
        button.html('Cetak<i class="fas fa-print fa-flip ml-2"></i>').prop("disabled", true );
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: url,
            data: formElement.serialize(),
            success: function(response) {
                const link = document.createElement('a');
                link.href = response.pdf_url;
                link.download = response.pdf_name;
                link.click();
                link.remove();
            },
            error: function (err) {
                if (err.status == 422) {
                    formElement.find('small.text-danger').remove();
                    $.each(err.responseJSON.errors, function (i, error) {
                        var errorList = formElement.find('[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else if (err.status == 404) {
                    Swal.fire({
                        icon: "error",
                        title: err.responseJSON.message
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat mencetak laporan"
                    });
                }
            },
            complete: function () {
                button.html('Cetak<i class="fas fa-print ml-2"></i>').prop('disabled', false);
            }
        });
    });
});