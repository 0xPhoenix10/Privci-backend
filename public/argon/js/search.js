var blockHtml = '<div style="text-align: center; color: #009683"><h1 style="font-size: 70px">Searching...</h1><br><p><i class="fa-solid fa-radar" style="font-size: 50px;"></i></p><br><p style="font-size: 25px">Please Wait!</p></div>';

$(function() {
    $('.summary-show').each(function() {
        var html = $(this).text();
        $(this).html(html);
    });

    if($('#search_type').val() == 'multi') {
        $.blockUI({ 
            css: { 
                border: 'none', 
                padding: '15px', 
                width: '40%',
                backgroundColor: 'none', 
                opacity: .5, 
                color: '#fff' 
            },
            message: blockHtml 
        }); 

        var search_val = $('#email-list').val();

        search_email(search_val, 2);
    }
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
                text: "Please enter Email address!",
                type: 'warning',
                icon: "warning",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }

        if (!validateEmail(search_val)) {
            swal.fire({
                title: "Search by Email",
                text: "Wrong email format!",
                type: 'warning',
                icon: "warning",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }

        $.blockUI({ 
            css: { 
                border: 'none', 
                padding: '15px', 
                width: '40%',
                backgroundColor: 'none', 
                opacity: .5, 
                color: '#fff' 
            },
            message: blockHtml 
        }); 

        search_email(search_val, 1);
    } else {
        if (search_val == "") {
            swal.fire({
                title: "Search by Domain",
                text: "Please enter domain!",
                type: 'warning',
                icon: "warning",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }

        if(!validateDomain(search_val)) {
            swal.fire({
                title: "Search by Domain",
                text: "Wrong domain format!",
                type: 'warning',
                icon: "warning",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }

        $.blockUI({ 
            css: { 
                border: 'none', 
                padding: '15px', 
                width: '40%',
                backgroundColor: 'none', 
                opacity: .5, 
                color: '#fff' 
            },
            message: blockHtml 
        });

        $.ajax({
            url: '/searchdomain',
            data: {
                domain: search_val
            },
            dataType: 'json',
            success: function (rsp) {
                if(rsp.error == '') {
                    var resp = rsp.search_info;
                    if ($.isEmptyObject(resp.result)) {
                        $('.search-intro').hide();

                        var html = '';
                        html += '<div class="text-center mt-5 search-title"><h2 class="text-light">Search result(s) for: <span class="search-value">' + search_val + '</span></h2></div>';
                        html += '<div class="no-result"><div class="col-xl-12 mb-4"><div class="card-header rounded text-center theme-background-color"><h2 class="m-0 mr-2 text-light">No results found! </h3></div></div></div>';
                        $('.search-content').html(html);
                    } else {
                        var html = '';
                        html += '<div class="text-center mt-5 search-title"><h2 class="text-light">Search result(s) for: <span class="search-value">' + search_val + '</span></h2></div>';
                        for (var i in resp.result) {
                            html += '<div class="col-xl-12 mb-4"><div class="card-header rounded bg-darkred"><div class="d-flex"><h3 class="m-0 mr-2 text-white">Breach date: </h3><p class="m-0 text-white">' + resp.result[i].date + '</p></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Source: </h3><p class="m-0 text-white">' + resp.result[i].source + '</p></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Summary: </h3><p class="m-0 text-white">' + resp.result[i].description + '</p></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Compromised data: </h3><p class="m-0 text-white">' + resp.result[i].type + '</p></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3><p class="col-9 m-0 p-0 text-white">' + resp.result[i].recommendation + '</p></div>';
                            html += '</div></div>';
                        }

                        $('.search-content').html(html);
                        $('.search-intro').hide();
                        $('.no-result').hide();
                    }
                } else {
                    swal.fire({
                        title: "Search by Domain",
                        text: rsp.error,
                        type: 'warning',
                        icon: "warning",
                        dangerMode: true,
                        confirmButtonColor: "#009683",
                        confirmButtonText: 'OK'
                    });
                }
                
                $.unblockUI();
            }
        });
    }
});

function search_email(email_list, type) {
    $.ajax({
        url: '/searchemail',
        data: {
            email: email_list,
            search_email_type: type
        },
        dataType: 'json',
        success: function (rsp) {
            if(rsp.error == '') {
                var resp = rsp.search_info;
                if($.isEmptyObject(resp.result)) {
                    swal.fire({
                        title: "Search by Email",
                        text: "Wrong Email!",
                        type: 'warning',
                        icon: "warning",
                        dangerMode: true,
                        confirmButtonColor: "#009683",
                        confirmButtonText: 'OK'
                    });
                } else {
                    var html = '';
                    for (var i in resp.result) {
                        if(resp.result[i].source == '') {
                            html += '<div class="text-center mt-5 search-title"><h2 class="text-light">Search result(s) for: <span class="search-value">' + resp.result[i].account + '</span></h2></div>';    
                            html += '<div class="no-result"><div class="col-xl-12 mb-4"><div class="card-header rounded text-center theme-background-color"><h2 class="m-0 mr-2 text-light">No results found! </h3></div></div></div>';
                        } else {
                            html += '<div class="text-center mt-5 search-title"><h2 class="text-light">Search result(s) for: <span class="search-value">' + resp.result[i].account + '</span></h2></div>';
                            html += '<div class="col-xl-12 mb-4"><div class="card-header rounded bg-darkred"><div class="d-flex"><h3 class="m-0 mr-2 text-white">Breach date: </h3><p class="m-0 text-white">' + resp.result[i].date + '</p></div>';
                            var url = resp.result[i].source.match(/\(([^)]+)\)/);
                            var match = resp.result[i].source.match(/\[(.*?)\]/);
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Source: </h3><a href="http://' + url[1] + '" target="_blank" class="m-0 text-white">' + match[1] + '</a></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Summary: </h3><p class="m-0 text-white">' + resp.result[i].description + '</p></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 text-white">Compromised data: </h3><p class="m-0 text-white">' + resp.result[i].type + '</p></div>';
                            html += '<div class="d-flex"><h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3><p class="col-9 m-0 p-0 text-white">' + resp.result[i].recommendation + '</p></div>';
                            html += '</div></div>';
                            $('.no-result').hide();
                        }
                    }

                    $('.search-content').html(html);
                }
            } else {
                swal.fire({
                    title: "Search by Email",
                    text: rsp.error,
                    type: 'warning',
                    icon: "warning",
                    dangerMode: true,
                    confirmButtonColor: "#009683",
                    confirmButtonText: 'OK'
                });
            }

            $.unblockUI();
        }
    });
}

function validateEmail(email) {
    var filter = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if (filter.test(email)) {
        return true;
    } else {
        return false;
    }
}

function validateDomain(domain) {
    var filter = /^([A-Z0-9a-z])+\.([a-z])+/;
    if (filter.test(domain)) {
        return true;
    } else {
        return false;
    }
}