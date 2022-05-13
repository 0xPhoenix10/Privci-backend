$('#search_option').on('change', function () {
    if ($(this).val() == 1) {
        $('#search-form').attr('placeholder', 'Enter an email address...');
        $('#search-form').attr('type', 'email');
    } else {
        $('#search-form').attr('placeholder', 'Enter domain, for example: privci.com...');
        $('#search-form').attr('type', 'text');
    }
});

$('#search-btn').on('click', function () {
    var search_val = $('#search-form').val();
    if ($('#search_option').val() == 1) {
        if (search_val == "") {
            swal.fire({
                title: "Search by Email",
                text: "Please enter Email address",
                type: 'warning',
                icon: "warning",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }

        $.ajax({
            url: '/searchbyemail',
            type: 'GET',
            data: {
                search: search_val
            },
            dataType: 'json',
            success: function () {

            }
        });
    }
});
