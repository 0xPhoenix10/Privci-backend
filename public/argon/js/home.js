$(function () {
    $('.inner-card .form-check').each(function () {
        if ($(this).find('input[type=radio]').val() == $('#selected_domain').val()) {
            $(this).find('input[type=radio]').prop('checked', true);
        }
    });

    $('.domain-list label.form-check-label').on('click', function () {
        var domain = $(this).prev().val();

        location.href = '/get_by_domain/' + domain;
    });

    $('button.accordion').on('click', function () {
        if ($(this).find('i').hasClass('ni-bold-down')) {
            $(this).find('i').removeClass('ni-bold-down');
            $(this).find('i').addClass('ni-bold-up');
        } else if ($(this).find('i').hasClass('ni-bold-up')) {
            $(this).find('i').removeClass('ni-bold-up');
            $(this).find('i').addClass('ni-bold-down');
        }
    });

    $('.breach-panel label.form-check-label').on('click', function () {
        if ($(this).find('input').prop('checked') == true) {
            $(this).find('input').prop('checked', false);
        } else {
            $(this).find('input').prop('checked', true);
        }
    });

    $('#select-all').on('change', function () {
        if ($(this).is(':checked')) {
            $('.breach-panel label.form-check-label input').prop('checked', true);
        } else {
            $('.breach-panel label.form-check-label input').prop('checked', false);
        }
    });
});
