var selected_emails = [];

$(function () {
    // selected domain select
    $('.domain-list .form-check').each(function () {
        if ($(this).find('input[type=radio]').val() == $('#selected_domain').val()) {
            $(this).find('input[type=radio]').prop('checked', true);
        }
    });

    // when select domain then go to /get_by_domain
    $('.domain-list').delegate('label.form-check-label', 'click', function() {
        var domain = $(this).prev().val();

        location.href = '/get_by_domain/' + domain;
    });

    $('.domain-list').delegate('input[type=radio]', 'click', function() {
        var domain = $(this).val();

        location.href = '/get_by_domain/' + domain;
    });

    $('button.accordion').on('click', function () {
        if ($(this).find('i').hasClass('fa-caret-down')) {
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-right');
        } else if ($(this).find('i').hasClass('fa-caret-right')) {
            $(this).find('i').removeClass('fa-caret-right');
            $(this).find('i').addClass('fa-caret-down');
        }
    });

    $('.email-pane label.form-check-label').on('click', function () {
        if ($(this).find('input').prop('checked') == true) {
            $(this).find('input').prop('checked', false);
        } else {
            $(this).find('input').prop('checked', true);
        }
    });

    $('.email-pane .form-check-input').on('click', function () {
        if ($(this).prop('checked') == true) {
            $(this).prop('checked', false);
        } else {
            $(this).prop('checked', true);
        }
    });

    $('#select-all').on('change', function () {
        if ($(this).is(':checked')) {
            $('.email-pane label.form-check-label input').prop('checked', true);
        } else {
            $('.email-pane label.form-check-label input').prop('checked', false);
        }
    });

    // sort by az
    $('#sort_az').on('click', function() {
        $.ajax({
            url: '/sort_domain',
            data: {
                type: "monitoring_domain",
                order: $(this).data('order'),
                selected: $('#selected_domain').val()
            },
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
                $('.domain-list').html(resp.html);
                if(resp.order == 'asc') {
                    $('#sort_az').data('order', 'desc');
                    $('#sort_az i').removeClass('fa-arrow-down-z-a');
                    $('#sort_az i').addClass('fa-arrow-down-a-z');
                } else {
                    $('#sort_az').data('order', 'asc');
                    $('#sort_az i').removeClass('fa-arrow-down-a-z');
                    $('#sort_az i').addClass('fa-arrow-down-z-a');
                }
            }
        });
    });

    // sort by breach
    $('#sort_breach').on('click', function() {
        $.ajax({
            url: '/sort_domain',
            data: {
                type: "no_of_breaches",
                order: $(this).data('order'),
                selected: $('#selected_domain').val()
            },
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
                $('.domain-list').html(resp.html);
                if(resp.order == 'asc') {
                    $('#sort_breach').data('order', 'desc');
                    $('#sort_breach i').removeClass('fa-arrow-down-9-1');
                    $('#sort_breach i').addClass('fa-arrow-down-1-9');
                } else {
                    $('#sort_breach').data('order', 'asc');
                    $('#sort_breach i').removeClass('fa-arrow-down-1-9');
                    $('#sort_breach i').addClass('fa-arrow-down-9-1');
                }
            }
        });
    });

    // sort by email
    $('#sort_email').on('click', function() {
        $.ajax({
            url: '/sort_email',
            data: {
                type: "monitoring_domain",
                order: $(this).data('order'),
                selected: $('#selected_domain').val()
            },
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
                $('.domain-list').html(resp.html);
                if(resp.order == 'asc') {
                    $('#sort_email').data('order', 'desc');
                    $('#sort_email i').removeClass('fa-arrow-down-9-1');
                    $('#sort_email i').addClass('fa-arrow-down-1-9');
                } else {
                    $('#sort_email').data('order', 'asc');
                    $('#sort_email i').removeClass('fa-arrow-down-1-9');
                    $('#sort_email i').addClass('fa-arrow-down-9-1');
                }
            }
        });
    });

    // search by domain
    $('#search-domain').on('keyup', function() {
        var keyword = $(this).val();
        var type = $('#select-search-type').val();

        search_by_keyword(keyword, type);
    });

    $('#search-email').on('keyup', function() {
        var keyword = $(this).val();
        $.ajax({
            url: '/search_by_email',
            data: {
                keyword: keyword,
                selected: $('#selected_domain').val()
            },
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if(resp.no_email) {
                    $('.email-pane').html(resp.html);    
                } else {
                    var html = '<h4 class="mb-2 text-white">Users that may have submit or used their company email on <a class="theme-color" href="' + "https://" + resp.domain + '">' + resp.domain + '</a></h4>';
                    html += '<div class="row">';
                    html += resp.html;
                    html += '</div>';
                    $('.email-pane').html(html);    
                }
            }
        });
    });

    $('#select-search-type').on('change', function() {
        if($(this).val() == 'email') {
            $('#search-domain').prop('placeholder', 'Enter email');
        } else {
            $('#search-domain').prop('placeholder', 'Enter domain');
        }
    });

    $('.btn-check-email').on('click', function(){
        selected_emails = [];
        $check = email_select_check();

        if($check == 1) {
            $("#search-email-form").submit();
        }
    });

    $('.btn-send-email').on('click', function(e) {
        selected_emails = [];
        var send_emails = '';
        e.preventDefault();
        $check = email_select_check();

        if(selected_emails.length == 1) {
            send_emails = selected_emails[0];
        } else {
            var first_email = selected_emails[0];
            selected_emails.shift();
        }

        if($check == 1) {
            var send_emails = selected_emails.join(';');
            var mailto = "mailto:" + first_email + "?cc=" + send_emails;

            location.href = mailto;
        }
    });

    $('.btn-push-notify').on('click', function() {
        $check = email_select_check();

        if($check == 1) {
            function notify(title, callback) {

                var options = {
                    type: "list",
                    title: title,
                    message: "Primary message to display",
                    items: [{ title: "Item1", message: "This is item 1."},
                            { title: "Item2", message: "This is item 2."},
                            { title: "Item3", message: "This is item 3."}]
                };
            
                // The first argument is the ID, if left blank it'll be automatically generated.
                // The second argument is an object of options. More here: https://developer.chrome.com/extensions/notifications#type-NotificationOptions
                return chrome.notifications.create("", options, callback);
            
            }
            
            notify("Testing", function(notification){
                // Do whatever you want. Called after notification is created.
            });
        }
    });
});

function search_by_keyword(keyword, type) {
    $.ajax({
        url: '/search_by_keyword',
        data: {
            keyword: keyword,
            type: type,
            selected: $('#selected_domain').val()
        },
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            $('.domain-list').html(resp.html);
        }
    });
}

function email_select_check() {
    $('.email-pane label.form-check-label').each(function() {
        if ($(this).find('input').prop('checked') == true) {
            selected_emails.push($(this).find('input').val());
        }
    });

    if(selected_emails.length == 0) {
        swal.fire({
            title: "No email selected!",
            text: "You must select an email to use this feature",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return 0;
    }

    return 1;
}

