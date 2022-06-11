$(function() {
    $('.summary-show').each(function() {
        var html = $(this).text();
        $(this).html(html);
    });

    // $('.main-container').css('minHeight', $(window).height());
});

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

        search_email(search_val);
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
                if ($.isEmptyObject(resp.result)) {
                    $('.search-intro').hide();
                    $('.no-result').show();
                } else {
                    var html = '';
                    html += '<div class="text-center mt-5 search-title"><h2 class="text-light">Search result(s) for: <span class="search-value">' + search_val + '</span></h2></div>';
                    for (var i in resp.result) {
                        html += '<div class="col-xl-12 mb-4"><div class="card-header rounded bg-darkred"><div class="d-flex"><h3 class="m-0 mr-2 text-white">Breach date: </h3><p class="m-0 text-white">' + resp.result[i].date + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Source: </h3><p class="m-0 text-white">' + resp.result[i].source + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Summary: </h3><p class="m-0 text-white">' + resp.result[i].description + '</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Compromised data: </h3><p class="m-0 text-white">Email addresses, Passwords</p></div>';
                        html += '<div class="d-flex"><h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3><p class="col-9 m-0 p-0 text-white">' + resp.result[i].recommendation + '</p></div>';
                        html += '</div></div>';
                    }

                    $('.search-content').html(html);
                    $('.search-intro').hide();
                    $('.no-result').hide();
                }
            }
        });
    }
});

function search_email(email_list) {
    $.ajax({
        url: '/searchemail',
        data: {
            email: email_list,
            search_email_type: 1
        },
        dataType: 'json',
        success: function (resp) {
            if (resp.result[0].source == '') {
                $('.search-intro').hide();
                $('.no-result').show();
            } else {
                var html = '';
                html += '<div class="text-center mt-5 search-title"><h2 class="text-light">Search result(s) for: <span class="search-value">' + email_list + '</span></h2></div>';
                for (var i in resp.result) {
                    html += '<div class="col-xl-12 mb-4"><div class="card-header rounded bg-darkred"><div class="d-flex"><h3 class="m-0 mr-2 text-white">Breach date: </h3><p class="m-0 text-white">' + resp.result[i].date + '</p></div>';
                    var url = resp.result[i].source.match(/\(([^)]+)\)/);
                    var match = resp.result[i].source.match(/\[(.*?)\]/);
                    html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Source: </h3><a href="http://' + url[1] + '" target="_blank" class="m-0 text-white">' + match[1] + '</a></div>';
                    html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Summary: </h3><p class="m-0 text-white">' + resp.result[i].description + '</p></div>';
                    html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Compromised data: </h3><p class="m-0 text-white">Email addresses, Passwords</p></div>';
                    html += '<div class="d-flex"><h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3><p class="col-9 m-0 p-0 text-white">' + resp.result[i].recommendation + '</p></div>';
                    html += '</div></div>';
                }

                $('.search-content').html(html);
                $('.search-intro').hide();
                $('.no-result').hide();
            }
        }
    });
}