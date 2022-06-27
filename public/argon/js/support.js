var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

function send_support() {
    var subject = $('#subject').val();
    var detail = $('#detail').val();

    if (subject == '') {
        swal.fire({
            title: "Send Support",
            text: "Please type subject!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    if (detail == '') {
        swal.fire({
            title: "Send Support",
            text: "Please type request details!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    $.ajax({
        url: 'send_support',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            subject: subject,
            detail: detail,
            cc: $('#send_copy').is(':checked')
        },
        dataType: 'json',
        success: function(resp) {
            if(resp.status == 'success') {
                swal.fire({
                    title: "Send Support",
                    text: "Successfully sent!",
                    type: 'success',
                    icon: "success",
                    dangerMode: true,
                    confirmButtonColor: "#009683",
                    confirmButtonText: 'OK'
                });

                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else {
                swal.fire({
                    title: "Send Support",
                    text: "Failed to send support!",
                    type: 'error',
                    icon: "error",
                    dangerMode: true,
                    confirmButtonColor: "#009683",
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}

function resolve(sid) {
    $.ajax({
        url: 'resolve_support',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            sid: sid
        },
        dataType: 'json',
        success: function() {
            swal.fire({
                title: "Resolve Support",
                text: "Successfully resolved!",
                type: 'success',
                icon: "success",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            setTimeout(function () {
                location.reload();
            }, 2000);
        }
    })
}

function del_support(sid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Are you sure to delete this row?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009683',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'delete_support',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    sid: sid
                },
                dataType: 'json',
                success: function() {
                    swal.fire({
                        title: "Delete Support",
                        text: "Successfully deleted!",
                        type: 'success',
                        icon: "success",
                        dangerMode: true,
                        confirmButtonColor: "#009683",
                        confirmButtonText: 'OK'
                    });
        
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            });
        }
    });
}

function ping(sid) {
    $.ajax({
        url: 'ping',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            sid: sid
        },
        dataType: 'json',
        success: function() {
            swal.fire({
                title: "Ping",
                text: "Successfully sent!",
                type: 'success',
                icon: "success",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            setTimeout(function () {
                location.reload();
            }, 2000);
        }
    })
}