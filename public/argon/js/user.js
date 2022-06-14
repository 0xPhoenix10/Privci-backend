function edit_user(id) {
    location.href = 'user/edit/' + id;
}

function delete_user(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009683',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            location.href = '/user/delete/' + id;
        }
    });
}