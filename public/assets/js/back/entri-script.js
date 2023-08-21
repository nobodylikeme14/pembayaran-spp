$(document).ready(function() {
    //Element
    const tableElement = $('.table-data');
    const formElement = $('form[name="form-data"]');

    //Format Tanggal
    function formatDate(dateString) {
        var dateTimeParts = dateString.split(' ');
        var datePart = dateTimeParts[0];
        var dateParts = datePart.split('-');
        var year = parseInt(dateParts[0]);
        var month = parseInt(dateParts[1]) - 1;
        var day = parseInt(dateParts[2]);
        var dateObj = new Date(year, month, day);
        var options = {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        };
        var formatter = new Intl.DateTimeFormat('id-ID', options);
        return formatter.format(dateObj);
    }

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
                name: 'nama_petugas',
                data: 'nama_petugas'
            },
            {
                name: 'nama_siswa',
                "render": function(data, type, row) {
                    return row.nama_siswa + "<br>(" + row.kelas_siswa + ")";
                }
            },
            {
                data: 'tanggal_bayar',
                "render": function(data, type, row) {
                    return formatDate(data);
                }
            },
            {
                name: 'spp_dibayar',
                "render": function(data, type, row) {
                    return row.bulan_dibayar + " " + row.spp_dibayar.slice(-4); 
                }
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
                } else if (xhr.status == 500 && xhr.responseText.includes('1062 Duplicate entry')) {
                    Swal.fire({
                        icon: "error",
                        title: "Maaf, pembayaran SPP untuk bulan ini sudah lunas"
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat proses menyimpan entri pembayaran"
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
                        title: "Terjadi kesalahan saat mendapatkan entri pembayaran"
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
                                title: "Terjadi kesalahan saat menghapus entri pembayaran"
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
                button.html('<i class="fas fa-trash fa-flip mr-2"></i>Hapus Semua').prop('disabled', true);
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url: url,
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
                        if (err.status == 404) {
                            Swal.fire({
                                icon: "error",
                                title: err.responseJSON.message
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Terjadi kesalahan saat menghapus data SPP"
                            });
                        }
                    },
                    complete: function() {
                        button.html('<i class="fas fa-trash mr-2"></i>Hapus Semua').prop('disabled', false);
                    }
                });
            }
        });
    });
});