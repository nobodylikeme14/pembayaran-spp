$(document).ready(function() {
    //Element
    const tableElement = $('.table-data');
    const formElement = $('form[name="form-data"]');

    //DataTable Init
    var table = tableElement.DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json',
        },
        processing: true,
        serverSide: true,
        ajax: {
            dataType: "JSON",
            type: "POST",
            url: tableElement.attr("data-url"),
            beforeSend: function(request) {
                request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            dataSrc: function(json) {
                return json.data;
            }
        },
        columns: [{
                data: 'numrow',
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                name: 'nama',
                data: 'nama'
            },
            {
                name: 'email',
                data: 'email'
            },
            {
                name: 'username',
                data: 'username'
            },
            {
                name: 'opsi',
                "render": function(data, type, row) {
                    var element = `
                    <button type="button" data-id="${row.id}" class="btn btn-danger btn-sm mb-2 mb-lg-0 edit-data">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button type="button" data-id="${row.id}" class="btn btn-danger btn-sm mb-2 mb-lg-0 hapus-data">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>`;
                    return element;
                }
            },
        ],
        drawCallback: function() { 
            table.columns.adjust();
        },
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: -1
        }]
    });
    table.on('order.dt search.dt', function() {
        let i = 1;
        table.cells(null, 0, {
            search: 'applied',
            order: 'applied'
        }).every(function(cell) {
            this.data(i++);
        });
    }).draw();
    
    //Add data button
    $('button[name="add-data"]').on('click', function() {
        var formAction = $(this).attr('data-action');
        formElement.find('.form-title').text("Tambah Data Petugas");
        formElement.attr('action', formAction);
        formElement.find('input[type="password"]').prop('required', true);
        $('#dataTab button[data-target="#form-tab"]').tab('show');
    });

    //Back button
    $('button[name="button-back"]').on('click', function() {
        $('#dataTab button[data-target="#data-tab"]').tab('show');
    });

    //On hidden form tab
    $('button[data-target="#form-tab"]').on('hidden.bs.tab', function () {
        $('#form-tab').find('form')[0].reset();
        formElement.find('input[type="password"]').prop('required', false);
        formElement.removeClass('was-validated');
        formElement.find('.password-helper').addClass('d-none');
    });

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
        if (formElement[0].checkValidity() === false) {
            event.stopPropagation();
            formElement.addClass('was-validated');
            return;
        }
        var button = formElement.find("button[type=submit]");
        formElement.find('[name="password"], [name="password_confirmation"]').attr('type', 'password');
        formElement.find('i[name="show-password-icon"]').removeClass('fa-eye-slash').addClass('fa-eye');
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
                        title: response.message
                    });
                    $('#dataTab button[data-target="#data-tab"]').tab('show');
                    table.ajax.reload();
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
                        title: "Terjadi kesalahan saat proses menyimpan data petugas"
                    });
                }
            },
            complete: function() {
                button.html('Simpan<i class="fas fa-floppy-disk ml-2"></i>').prop('disabled', false);
            }
        });
    });
    
    //Edit data button
    $(document).on('click', '.table-data button.edit-data', function() {
        var button = $(this);
        var url = tableElement.attr('data-url-detail');
        var dataId = $(this).attr('data-id');
        button.html('<i class="fas fa-edit fa-flip mr-1"></i>Edit').prop('disabled', true);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: url,
            data: { id: dataId },
            type: "POST", 
            success: function(response) {
                if (response.status == "success") {
                    var formAction = tableElement.attr('data-url-edit');
                    formElement.find('.form-title').text("Edit Data Petugas");
                    formElement.attr('action', formAction);
                    $.each(response.data[0], function (key, value) {
                        formElement.find('[name="'+key+'"]').val(value);
                    });
                    formElement.find('.password-helper').removeClass('d-none');
                    $('#dataTab button[data-target="#form-tab"]').tab('show');
                }
            },
            error: function(err) {
                if (err.status == 422) {
                    var errorMessages = Object.values(err.responseJSON.errors);
                    if (errorMessages.length > 0) {
                        Swal.fire({
                            icon: "error",
                            title: errorMessages[0]
                        });
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat mendapatkan data petugas"
                    });
                }
            },
            complete: function() {
                button.html('<i class="fas fa-edit mr-1"></i>Edit').prop('disabled', false);
            }
        });
    });

    //Delete data button
    $(document).on('click', '.table-data button.hapus-data', function() {
        var button = $(this);
        var url = tableElement.attr('data-url-hapus');
        var dataId = $(this).attr('data-id');
        Swal.fire({
            icon: 'question',
            title: 'Anda yakin ingin menghapus data petugas ini ?',
            text: 'Semua entri pembayaran yang dilakukan petugas ini juga akan terhapus',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            position: 'center',
            showConfirmButton: true,
            showCancelButton: true,
            preConfirm: () => {
                button.html('<i class="fas fa-trash fa-flip mr-1"></i>Hapus').prop('disabled', true);
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url: url,
                    data: { id: dataId },
                    type: "POST", 
                    success: function(response) {
                        if (response.status == "success") {
                            table.ajax.reload();
                            Swal.fire({
                                icon: response.status,
                                title: response.message
                            });
                        }
                    },
                    error: function(err) {
                        if (err.status == 422) {
                            var errorMessages = Object.values(err.responseJSON.errors);
                            if (errorMessages.length > 0) {
                                Swal.fire({
                                    icon: "error",
                                    title: errorMessages[0]
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Terjadi kesalahan saat menghapus data petugas"
                            });
                        }
                    },
                    complete: function() {
                        button.html('<i class="fas fa-trash mr-1"></i>Hapus').prop('disabled', false);
                    }
                });
            }
        });
    });
});