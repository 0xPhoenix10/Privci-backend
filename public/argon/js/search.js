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
            url: '/searchemail',
            data: {
                email: search_val
            },
            dataType: 'json',
            success: function (resp) {
                $('.search-value').text(search_val);
                if (resp.result[0].source == '') {
                    $('.search-content').hide();
                    $('.no-result').show();
                } else {
                    var html = '';
                    for (var i in resp.result) {
                        html += '<div class="col-xl-12 mb-4"><div class="card-header rounded bg-dark"><div class="d-flex"><h3 class="m-0 mr-2 text-white">Breach date: </h3><p class="m-0 text-white">' + resp.result[i].date + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Source: </h3><p class="m-0 text-white">' + resp.result[i].source + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Summary: </h3><p class="m-0 text-white">' + resp.result[i].description + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Compromised data: </h3><p class="m-0 text-white">Email addresses, Passwords</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3><p class="col-9 m-0 text-white">' + resp.result[i].recommendation + '</p></div>';
                        html += '</div></div>';
                    }

                    $('.search-content').html(html);
                    $('.search-content').show();
                    $('.no-result').hide();
                }
            }
        });
    } else {
        if (search_val == "") {
            swal.fire({
                title: "Search by Domain",
                text: "Please enter Domain",
                type: 'warning',
                icon: "warning",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }

        $.ajax({
            url: '/searchdomain',
            data: {
                domain: search_val
            },
            dataType: 'json',
            success: function (resp) {
                $('.search-value').text(search_val);
                if ($.isEmptyObject(resp.result)) {
                    $('.search-content').hide();
                    $('.no-result').show();
                } else {
                    var html = '';
                    for (var i in resp.result) {
                        html += '<div class="col-xl-12 mb-4"><div class="card-header rounded bg-dark"><div class="d-flex"><h3 class="m-0 mr-2 text-white">Breach date: </h3><p class="m-0 text-white">' + resp.result[i].date + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Source: </h3><p class="m-0 text-white">' + resp.result[i].source + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Summary: </h3><p class="m-0 text-white">' + resp.result[i].description + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Compromised data: </h3><p class="m-0 text-white">Email addresses, Passwords</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3><p class="col-9 m-0 text-white">' + resp.result[i].recommendation + '</p></div>';
                        html += '</div></div>';
                    }

                    $('.search-content').html(html);
                    $('.search-content').show();
                    $('.no-result').hide();
                }
            }
        });
    }
});
