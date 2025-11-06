document.addEventListener('DOMContentLoaded', () => {
    // Define confirmDelete in the global scope
    window.confirmDelete = function() {
        const deleteAction = document.querySelector('meta[name="delete-action"]').getAttribute('content');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        Swal.fire({
            title: 'Are you sure?',
            html: `
                <p class="text-left text-gray-700 mb-3">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                <p class="text-left text-gray-700 mb-4">Please enter your password to confirm you would like to permanently delete your account:</p>
                <div class="text-left">
                    <label for="swal-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="swal-password" name="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter your password">
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete account',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            preConfirm: () => {
                const password = document.getElementById('swal-password').value;
                if (!password) {
                    Swal.showValidationMessage('Please enter your password');
                    return false;
                }
                return password;
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteAction;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                const passwordInput = document.createElement('input');
                passwordInput.type = 'hidden';
                passwordInput.name = 'password';
                passwordInput.value = result.value;
                form.appendChild(passwordInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    // Attach event listener to the delete button
    const deleteButton = document.querySelector('[data-delete-button]');
    if (deleteButton) {
        deleteButton.addEventListener('click', (event) => {
            event.preventDefault();
            window.confirmDelete();
        });
    }
});