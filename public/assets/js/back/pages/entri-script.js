$(document).ready(function() {
    //Element
    const tableElement = $('.table-data');
    const formElement = $('form[name="form-data"]');

    //DataTable Init
    var table = tableElement.DataTable({
        autoWidth: true,
        processing: true,
        serverSide: true,
        ajax: {
            dataType: "JSON",
            type: "POST",
            url: window.location,
            dataSrc: function(json) {
                return json.data;
            },
            error: function(err, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: err.responseJSON.message
                });
            }
        },
        columns: [
            {
                data: 'numrow',
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                name: 'nama_petugas',
                data: 'nama_petugas'
            },
            {
                name: 'siswa',
                data: 'siswa'
            },
            {
                name: 'tanggal_bayar',
                data: 'tanggal_bayar'
            },
            {
                name: 'spp_dibayar',
                data: 'spp_dibayar'
            },
            {
                name: 'status',
                "render": function(data, type, row) {
                    return `<span class="text-success text-uppercase font-weight-bold">Lunas</span>`; 
                }
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
    
    //Add data button
    $('button[name="add-data"]').on('click', function() {
        var formAction = $(this).attr('data-action');
        formElement.find('.form-title').text("Tambah Entri Pembayaran");
        formElement.attr('action', formAction);
        $('#dataTab button[data-target="#form-tab"]').tab('show');
    });

    //Back button
    $('button[name="button-back"]').on('click', function() {
        $('#dataTab button[data-target="#data-tab"]').tab('show');
    });

    //On hidden form tab
    $('button[data-target="#form-tab"]').on('hidden.bs.tab', function () {
        $('#form-tab').find('form')[0].reset();
        $('.selectpicker').selectpicker('val', '');
        formElement.removeClass('was-validated');
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
        $.ajax({
            beforeSend: function(){
                button.html('Simpan<i class="fas fa-floppy-disk fa-flip ml-2"></i>').prop('disabled', true);
                formElement.removeClass('was-validated');
                formElement.find('small.text-danger').remove();
            },
            url: formElement.attr("action"),
            data: formElement.serialize(),
            type: "POST", 
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: response.message
                });
                $('#dataTab button[data-target="#data-tab"]').tab('show');
                table.ajax.reload();
            },
            error: function(err, status, error) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var errorList = formElement.find('[name="'+i+'"]').closest(".form-group");
                        var element = `<small class="text-danger font-weight-bold">${error[0]}</small>`;
                        errorList.append($(element).delay(4000).fadeOut(500, function() {
                            $(this).remove();
                        }));
                    });
                } else {
                    if (err.responseText.includes('1062 Duplicate entry')) {
                        Swal.fire({
                            icon: 'error',
                            title: "Maaf, pembayaran SPP untuk bulan ini sudah lunas"
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        });
                    }
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
        $.ajax({
            beforeSend: function(){
                button.html('<i class="fas fa-edit fa-flip mr-1"></i>Edit').prop('disabled', true);
            },
            url: url,
            data: { id: dataId },
            type: "POST", 
            success: function(response) {
                var formAction = tableElement.attr('data-url-edit');
                formElement.find('.form-title').text("Edit Entri Pembayaran");
                formElement.attr('action', formAction);
                $.each(response.data[0], function (key, value) {
                    if (key == "id_siswa") {
                        formElement.find('.selectpicker[name="siswa"]').selectpicker('val', value);
                    } else if (key == "id_spp") {
                        formElement.find('.selectpicker[name="spp"]').selectpicker('val', value);
                    }
                    formElement.find('[name="'+key+'"]').val(value);
                });
                $('#dataTab button[data-target="#form-tab"]').tab('show');
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
                        icon: 'error',
                        title: err.responseJSON.message
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
            title: 'Anda yakin ingin menghapus entri pembayaran ini ?',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            position: 'center',
            showConfirmButton: true,
            showCancelButton: true,
            preConfirm: () => {
                $.ajax({
                    beforeSend: function(){
                        button.html('<i class="fas fa-trash fa-flip mr-1"></i>Hapus').prop('disabled', true);
                    },
                    url: url,
                    data: { id: dataId },
                    type: "POST", 
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: response.message
                        });
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
                                icon: 'error',
                                title: err.responseJSON.message
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

    //Delete all data
    $('button[name="delete-all-data"]').on('click', function() {
        var button = $(this);
        var url = button.attr('data-url');
        Swal.fire({
            icon: 'question',
            title: 'Anda yakin ingin menghapus semua entri pembayaran ?',
            confirmButtonText: 'Hapus Semua',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            position: 'center',
            showConfirmButton: true,
            showCancelButton: true,
            preConfirm: () => {
                $.ajax({
                    beforeSend: function(){
                        button.html('<i class="fas fa-trash fa-flip mr-2"></i>Hapus Semua').prop('disabled', true);
                    },
                    url: url,
                    type: "POST", 
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: response.message
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        });
                    },
                    complete: function() {
                        button.html('<i class="fas fa-trash mr-2"></i>Hapus Semua').prop('disabled', false);
                    }
                });
            }
        });
    });
});