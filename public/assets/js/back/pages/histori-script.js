$(document).ready(function() {
    //DataTable Init
    const tableElement = $('.table-data');
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

    // Histori Search
    function debounce(func, delay) {
        let timer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                func.apply(context, args);
            }, delay);
        };
    }
    $('input[name="search"]').on('input', debounce(function() {
        var searchTerm = $(this).val();
        $.ajax({
            beforeSend: function(){
                $('.lihat-lainnya, .not-found-img').hide();
                $('.histori-transaksi-container').empty();
                $(".loading-animation").show();
            },
            url: $(this).data('url'),
            method: 'POST',
            data: { search: searchTerm },
            success: function(response) {
                showData(response);
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: xhr.responseJSON.message
                });
            },
            complete: function () {
                $(".loading-animation").hide();
            }
        });
    }, 400));

    //Data Histori
    function historiCardElement(value) {
        return `<section class="col-lg-6 col-12 mb-4 histori-transaksi">
        <div class="card border-left-danger shadow h-100 pt-2 px-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center px-3 pt-2">
                <div class="h5 mb-0 font-weight-bold text-gray-800 text-uppercase order-2 order-sm-1">
                    ${value.spp_dibayar}
                </div>
                <h5 class="order-1 order-sm-2">
                    <span class="badge badge-danger time-badge">
                    ${timeHistoriFormat(value.created_at)}
                    </span>
                </h5>
            </div>
            <div class="card-body px-3 pb-3 pt-0">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row text-sm font-weight-bold text-secondary mb-1">
                            <div class="col-4 my-auto">Nama Petugas</div>
                            <div class="col-auto my-auto">: ${value.nama_petugas}</div>
                        </div>
                        <div class="row text-sm font-weight-bold text-secondary mb-1">
                            <div class="col-4 my-auto">Tanggal Bayar</div>
                            <div class="col-auto my-auto">: ${value.tanggal_bayar}</div>
                        </div>
                        <div class="row text-sm font-weight-bold text-secondary mb-1">
                            <div class="col-4 my-auto">Jumlah Bayar</div>
                            <div class="col-auto my-auto">: ${value.jumlah_bayar}</div>
                        </div>
                        <div class="row text-sm font-weight-bold text-secondary mb-1">
                            <div class="col-4 my-auto">Status</div>
                            <div class="col-auto my-auto">: 
                                <span class="text-success text-uppercase">Lunas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block">
                        <i class="fas fa-file-contract fa-3x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>`;
    }
    function updateBadgeTime(section, created_at) {
        var timeBadge = section.find('.time-badge');
        var timestamp = created_at;
        var formattedTime = timeHistoriFormat(timestamp);
        timeBadge.text(formattedTime);
    }
    function showData(value) {
        $('.histori-transaksi-container').hide();
        if (value.length > 0) {
            $.each(value, function(subKey, subValue) {
                var element = historiCardElement(subValue);
                var section = $(element);
                $('.histori-transaksi-container').append(section).show();
                updateBadgeTime(section, subValue.created_at);
                setInterval(function() {
                    updateBadgeTime(section, subValue.created_at);
                }, 15000);
            });
            $('.not-found-img').hide();
        } else {
            $('.not-found-img').show();
        }
    }
    function timeHistoriFormat(timestamp) {
        var inputTimestamp = new Date(timestamp).getTime() / 1000;
        var now = Math.floor(new Date().getTime() / 1000);
        var distance = now - inputTimestamp;
        var seconds = distance;
        var minutes = Math.round(distance / 60);
        var hours = Math.round(distance / 3600);
        var times = '';
        if (seconds <= 59) {
            times = seconds + ' detik yang lalu';
        } else if (minutes <= 59) {
            times = minutes + ' menit yang lalu';
        } else if (hours <= 23) {
            times = hours + ' jam yang lalu';
        } else {
            var dateObj = new Date(inputTimestamp * 1000);
            var options = { 
                weekday: 'long', 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric'
            };
            times = dateObj.toLocaleDateString('id-ID', options);
        }
        return times;
    }
    updateDataHistori();
    function updateDataHistori() {
        $.ajax({
            beforeSend: function(){
                $('.histori-transaksi-container').empty();
                $(".not-found-img").hide();
                $(".loading-animation").show();
            },
            url: window.location,
            type: "POST", 
            success: function(response) {
                $.each(response, function (key, value) {
                    showData(value);
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: xhr.responseJSON.message
                });
            },
            complete: function () {
                $(".loading-animation").hide();
            }
        });
    }
});