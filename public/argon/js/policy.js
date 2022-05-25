function analyze_uploaded_file() {
    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
        alert('The File APIs are not fully supported in this browser.');
        return;
    }

    var input = $('#formFile').prop('files');
    if (!input) {
        alert("Um, couldn't find the fileinput element.");
    } else if (!input) {
        alert("This browser doesn't seem to support the `files` property of file inputs.");
    } else if (!input[0]) {
        alert("Please select a file before clicking 'Load'");
    } else {
        var file = input[0];
        if (file.type == 'text/plain' || file.type == 'application/pdf' || file.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || file.type == 'application/msword') {
            var fr = new FileReader();

            fr.addEventListener("load", () => {
                // this will then display a text file
                $('#policy_content').val(fr.result);
            }, false);

            if (input) {
                fr.readAsBinaryString(file);
            }
        } else {
            swal.fire({
                title: "Extract Content",
                text: "Please upload correct extension!",
                type: 'error',
                icon: "error",
                dangerMode: true,
                confirmButtonColor: "#009683",
                confirmButtonText: 'OK'
            });

            return;
        }
    }
}

$('#save_btn').on('click', function () {
    var content = $('#policy_content').val();
    var title = $('#policy_title').val();

    if (content == '') {
        swal.fire({
            title: "Upload Policy",
            text: "Please type content!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    if (title == '') {
        swal.fire({
            title: "Upload Policy",
            text: "Please type title!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var pid = $('#policy_edit_number').val() != '' ? $('#policy_edit_number').val() : 0;

    $.ajax({
        url: '/upload_policy',
        data: {
            _token: CSRF_TOKEN,
            title: title,
            content: content,
            pid: pid
        },
        type: 'POST',
        dataType: 'json',
        success: function (resp) {
            if (resp.status == 'success') {
                swal.fire({
                    title: "Upload Policy",
                    text: resp.msg,
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
                    title: "Upload Policy",
                    text: resp.msg,
                    type: 'error',
                    icon: "error",
                    dangerMode: true,
                    confirmButtonColor: "#009683",
                    confirmButtonText: 'OK'
                });
            }
        }
    });
});

function onEditDoc(pid) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/edit_policy',
        data: {
            _token: CSRF_TOKEN,
            pid: pid
        },
        type: 'POST',
        dataType: 'json',
        success: function (resp) {
            $('#policy_content').val(resp.policy.content);
            $('#policy_title').val(resp.policy.title);
            $('#policy_link').val(resp.policy.link);
            $('#save_btn').html('Edit Policy');
            $('#policy_edit_number').val(resp.policy.id);

            $('#policy_content').focus();
        }
    });
}

function onDeleteDoc(pid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This policy will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009683',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        console.log(result);
        if (result.value) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/delete_policy',
                data: {
                    _token: CSRF_TOKEN,
                    pid: pid
                },
                type: 'POST',
                dataType: 'json',
                success: function (resp) {
                    if (resp.status == 'success') {
                        swal.fire({
                            title: "Delete Policy",
                            text: resp.msg,
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
                            title: "Delete Policy",
                            text: resp.msg,
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
    });
}

function onAddFaq() {
    var question = $('#doc_question').val();
    var answer = $('#doc_answer').val();

    if (question == '') {
        swal.fire({
            title: "Add Faq",
            text: "Please type question!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    if (question.length > 60) {
        swal.fire({
            title: "Add Faq",
            text: "Maximum length is 60 characters!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    if (answer == '') {
        swal.fire({
            title: "Add Faq",
            text: "Please type answer!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    if (answer.length > 1000) {
        swal.fire({
            title: "Add Faq",
            text: "Maximum length is 1000 characters!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    if ($('#faq_cnt').val() == 5) {
        swal.fire({
            title: "Add Faq",
            text: "You can't add more than 5 faqs!",
            type: 'warning',
            icon: "warning",
            dangerMode: true,
            confirmButtonColor: "#009683",
            confirmButtonText: 'OK'
        });

        return;
    }

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var fid = $('#faq_edit_number').val() != '' ? $('#faq_edit_number').val() : 0;

    $.ajax({
        url: '/add_faq',
        data: {
            _token: CSRF_TOKEN,
            question: question,
            answer: answer,
            fid: fid
        },
        type: 'POST',
        dataType: 'json',
        success: function (resp) {
            if (resp.status == 'success') {
                swal.fire({
                    title: "Add Faq",
                    text: resp.msg,
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
                    title: "Add Faq",
                    text: resp.msg,
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

function onEditFaq(fid) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/edit_faq',
        data: {
            _token: CSRF_TOKEN,
            fid: fid
        },
        type: 'POST',
        dataType: 'json',
        success: function (resp) {
            $('#doc_question').val(resp.faq.question);
            $('#doc_answer').val(resp.faq.answer);
            $('#save_faq_btn').html('Edit Faq');
            $('#faq_edit_number').val(resp.faq.id);

            $('#doc_question').focus();
        }
    });
}

function onDeleteFaq(fid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This faq will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009683',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/delete_faq',
                data: {
                    _token: CSRF_TOKEN,
                    fid: fid
                },
                type: 'POST',
                dataType: 'json',
                success: function (resp) {
                    if (resp.status == 'success') {
                        swal.fire({
                            title: "Delete Faq",
                            text: resp.msg,
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
                            title: "Delete Faq",
                            text: resp.msg,
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
    });
}
