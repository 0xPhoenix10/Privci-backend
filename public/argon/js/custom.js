$(function() {
    $('.main-container').css('minHeight', $(window).height());

    $('input[name=tracking-company-email]').on('click', function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/save_email_tracking',
            data: {
                _token: CSRF_TOKEN,
                tracking: $(this).val()
            },
            type: 'POST',
            dataType: 'json',
            success: function () {
                
            }
        });
    });
});

$('.breach-panel').delegate('button.accordion', 'click', function() {
    $(this).toggleClass('active');
    var panel = $(this).next();

    if(panel.css('maxHeight') == '0px') {
        panel.css('maxHeight', panel.prop('scrollHeight') + 'px');
    } else {
        panel.css('maxHeight', '');
    }
});

$('.faq-upload .add-tab').on('click', function() {
    $(this).toggleClass('faq-active');
    var panel = $('.faq-panel');

    if(panel.css('maxHeight') == '0px') {
        panel.css('maxHeight', panel.prop('scrollHeight') + 'px');
    } else {
        panel.css('maxHeight', '');
    }
});

function onAddDocument() {
    var question = document.getElementById("doc_question");
    var answer = document.getElementById("doc_answer");
    document.getElementById("documents_uploaded").innerHTML +=
        "<div class='d-flex align-items-center theme-color'><p class='mr-2 theme-color'>" +
        question.value +
        "</p><i class='mr-1 fa fa-edit'></i><i class='fa fa-trash'></i></div>";
    question.value = "";
    answer.value = "";

    document.getElementsByClassName("faq-panel")[0].style.maxHeight = null;
}

function save_notification_email(email) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/save_notification_email',
        data: {
            _token: CSRF_TOKEN,
            email: email
        },
        type: 'POST',
        dataType: 'json',
        success: function () {
            
        }
    });
}

function save_notification_status(status) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/save_notification_status',
        data: {
            _token: CSRF_TOKEN,
            status: status
        },
        type: 'POST',
        dataType: 'json',
        success: function () {
            
        }
    });
}