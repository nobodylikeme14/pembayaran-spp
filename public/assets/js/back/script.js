$(document).ready(function() {
    //dataTable Config
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            url: 'assets/js/dataTable-id.json'
        }
    });

    //Ajax Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});