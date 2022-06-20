var selected_emails = [];

$(function () {
    setDomailListHeight();    

    if($('.breach-pagination-btn').length == 1) {
        $('.breach-next').addClass('disabled');
    }

    // selected domain select
    $('.domain-list .form-check').each(function () {
        if ($(this).find('input[type=radio]').val() == $('#selected_domain').val()) {
            $(this).find('input[type=radio]').prop('checked', true);
        }
    });

    // when select domain then go to /get_by_domain
    $('.domain-list').delegate('label.form-check-label', 'click', function() {
        var domain = $(this).prev().val();
        $(this).prev().prop('checked', true);

        get_by_domain(domain);
    });

    check_selected_domain();
    
    $('.domain-list').delegate('input[type=radio]', 'click', function() {
        var domain = $(this).val();
        $(this).prop('checked', true);

        get_by_domain(domain);
    });

    $('.breach-panel').delegate('button.accordion', 'click', function () {
        if ($(this).find('i').hasClass('fa-caret-down')) {
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-right');
        } else if ($(this).find('i').hasClass('fa-caret-right')) {
            $(this).find('i').removeClass('fa-caret-right');
            $(this).find('i').addClass('fa-caret-down');
        }
    });

    $('.breach-panel').delegate('.breach-pagination-btn', 'click', function() {
        if(!$(this).hasClass('page-selected')) {
            breach_pagination($(this).data('index'));
        }
    });

    $('.breach-panel').delegate('p.pagination-button', 'click', function() {
        if(!$(this).hasClass('disabled')) {
            if($(this).hasClass('breach-previous')) {
                breach_pagination($('.breach-pagination-btn.page-selected').data('index') * 1 - 1);    
            } else {
                breach_pagination($('.breach-pagination-btn.page-selected').data('index') * 1 + 1);    
            }
        }
    });

    $('.breach-panel').delegate('.email-pane label.form-check-label', 'click', function() {
        if ($(this).find('input').prop('checked') == true) {
            $(this).find('input').prop('checked', false);
        } else {
            $(this).find('input').prop('checked', true);
        }
    });

    $('.breach-panel').delegate('.email-pane label.form-check-label', 'click', function() {
        if ($(this).prop('checked') == true) {
            $(this).prop('checked', false);
        } else {
            $(this).prop('checked', true);
        }
    });

    $('.breach-panel').delegate('#select-all', 'change', function() {
        if ($(this).is(':checked')) {
            $('.email-pane label.form-check-label input').prop('checked', true);
        } else {
            $('.email-pane label.form-check-label input').prop('checked', false);
        }
    });

    $('.breach-panel').delegate('.policy-link', 'click', function() {
        if($('.pol-link').hasClass('no-link')) {

        } else {
            window.open(
                $('.pol-link').text(),
                '_blank'
            );
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

    // $('.breach-panel').delegate('#search-email', 'keyup', function() {
    //     var keyword = $(this).val();
    //     $.ajax({
    //         url: '/search_by_email',
    //         data: {
    //             keyword: keyword,
    //             selected: $('#selected_domain').val()
    //         },
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(resp) {
    //             if(resp.no_email) {
    //                 $('.email-pane').html(resp.html);    
    //             } else {
    //                 var html = '<h4 class="mb-2 text-white">Users that may have submit or used their company email on <a class="theme-color" href="' + "https://" + resp.domain + '">' + resp.domain + '</a></h4>';
    //                 html += '<div class="row">';
    //                 html += resp.html;
                 
    //                 html += '</div>';
    //                 $('.email-pane').html(html);    
    //             }

    //             setDomailListHeight(); 
    //         }
    //     });
    // });

    $('#select-search-type').on('change', function() {
        if($(this).val() == 'email') {
            $('#search-domain').prop('placeholder', 'Enter email');
        } else {
            $('#search-domain').prop('placeholder', 'Enter domain');
        }
    });

    $('.breach-panel').delegate('.email-pagination-button', 'click', function() {
        if(!$(this).hasClass('disabled')) {
            get_emails_by_pagination($(this).data('index'));
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
            var body = "";
            body += "Dear Colleague";
            body += "<br/><br/>There is a possibility that the internet company <a href='https://securmind.com'>securmind.com</a>, to which you may have submitted personal information, such as filling out a form in the past, opening an account, or making a purchase, may have been involved in a data breach.";
            body += "<br><br>As a result, we recommend you take immediate action, including:";
            body += "<br>✔ Change your password to something strong, if you have an account with the website . Click here <add link> to review our password policy.";
            body += "<br>✔ Set up two-factor authentication. If the website provides such capability.";
            body += "<br>✔ Make sure you don't use your company email's password on this or any other site - a unique password for each site.";
            body += "<br><br>Reference: <please add url to reference>";
            body += "<br><br><br>Best Regards,";
            body += "<br><Please add your email signature>";
            var mailto = "mailto:" + first_email + "?cc=" + send_emails + "&body=" + body;

            location.href = mailto;
        }
    });

    // $('.btn-push-notify').on('click', function() {
    //     $check = email_select_check();

    //     if($check == 1) {
    //         function notify(title, callback) {

    //             var options = {
    //                 type: "list",
    //                 title: title,
    //                 message: "Primary message to display",
    //                 items: [{ title: "Item1", message: "This is item 1."},
    //                         { title: "Item2", message: "This is item 2."},
    //                         { title: "Item3", message: "This is item 3."}]
    //             };
            
    //             // The first argument is the ID, if left blank it'll be automatically generated.
    //             // The second argument is an object of options. More here: https://developer.chrome.com/extensions/notifications#type-NotificationOptions
    //             return chrome.notifications.create("", options, callback);
            
    //         }
            
    //         notify("Testing", function(notification){
    //             // Do whatever you want. Called after notification is created.
    //         });
    //     }
    // });
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
            text: "Note: You must select an email to use these features",
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

function get_by_domain(domain) {
    $.ajax({
        url: '/get_by_domain',
        data: {
            domain: domain
        },
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            $('.breach-panel .inner-card').html(resp.html);
            $('#selected_domain').val(resp.selected);

            setDomailListHeight();
            check_selected_domain();
        }
    });
}

function breach_pagination(page) {
    $.ajax({
        url: '/get_breach_info',
        data: {
            page: page,
            domain: $('#selected_domain').val()
        },
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            $('.breach-date').text(resp.breach_info['breach date']);
            $('.breach-no-of-records').text(resp.breach_info['no of records']);
            $('.breach-summary').text(resp.breach_info['breach summary']);
            $('.breach-reference').text(resp.breach_info['reference']);
            $('.breach-reference').attr('href', resp.breach_info['reference']);

            $('.breach-pagination-btn').each(function() {
                $(this).removeClass('page-selected');

                if($(this).data('index') == resp.index) {
                    $(this).addClass('page-selected');
                }
            });

            if(resp.index == 0) {
                if(!$('.breach-previous').hasClass('disabled')) {
                    $('.breach-previous').addClass('disabled');
                }
                $('.breach-next').removeClass('disabled');
            } else if(resp.index == $('.breach-pagination-btn').length - 1) {
                if(!$('.breach-next').hasClass('disabled')) {
                    $('.breach-next').addClass('disabled');
                }
                $('.breach-previous').removeClass('disabled');
            } else {
                $('p.pagination-button').removeClass('disabled');
            }

            var count = resp.index*1 + 1;
            $('.page-show').text(count + ' of ' + $('.breach-pagination-btn').length);

            setDomailListHeight(); 
        }
    });
}

function get_emails_by_pagination(page) {
    $.ajax({
        url: '/get_emails_by_pagination',
        data: {
            page: page,
            domain: $('#selected_domain').val()
        },
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            $('.email-list').html(resp.html);
            $('.start').text(resp.start);
            $('.end').text(resp.end);

            $('.email-pagination-button.next').addClass('disabled');

            if(resp.next) {
                $('.email-pagination-button.next').attr("data-index", resp.next);
                $('.email-pagination-button.previous').attr("data-index", resp.page);
                $('.email-pagination-button.next').removeClass('disabled');
            }

            if(resp.page == 0) {
                $('.email-pagination-button.previous').addClass('disabled');
                $('.email-pagination-button.next').removeClass('disabled');
            } else {
                $('.email-pagination-button.previous').removeClass('disabled');
            }

            setDomailListHeight(); 
        }
    });
}

function setDomailListHeight() {
    $('.main-card .card-col:first-child .inner-card').css('maxHeight', ($('.breach-panel .inner-card').height() + 15));
}

function check_selected_domain() {
    $('.domain-list .form-check .form-check-input').each(function() {
        if($(this).is(':checked')) {
            $(this).next().addClass('active');
        } else {
            $(this).next().removeClass('active');
        }
    });
}