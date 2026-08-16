import Swal from 'sweetalert2';

const brandTheme = {
    confirmButtonColor: '#e6b422',
    cancelButtonColor: '#2c2c2c',
    buttonsStyling: true,
};

window.SimbaAlert = {
    toast(icon, title) {
        return Swal.fire({
            toast: true,
            position: 'top-end',
            icon,
            title,
            showConfirmButton: false,
            timer: 3200,
            timerProgressBar: true,
            ...brandTheme,
        });
    },

    success(title, text = '') {
        return Swal.fire({
            icon: 'success',
            title,
            text: text || undefined,
            confirmButtonText: 'OK',
            ...brandTheme,
        });
    },

    error(title, text = '') {
        return Swal.fire({
            icon: 'error',
            title,
            text: text || undefined,
            confirmButtonText: 'OK',
            ...brandTheme,
        });
    },

    async confirm(options = {}) {
        const result = await Swal.fire({
            icon: options.icon || 'warning',
            title: options.title || 'Are you sure?',
            text: options.text || 'This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Yes, continue',
            cancelButtonText: options.cancelText || 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            ...brandTheme,
            confirmButtonColor: options.danger ? '#b91c1c' : brandTheme.confirmButtonColor,
        });

        return result.isConfirmed;
    },
};

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-swal-confirm]');

    if (! form || form.dataset.swalConfirmed === '1') {
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    const confirmed = await window.SimbaAlert.confirm({
        title: form.dataset.swalTitle || 'Are you sure?',
        text: form.dataset.swalText || 'This action cannot be undone.',
        confirmText: form.dataset.swalConfirmText || 'Yes, delete',
        danger: form.dataset.swalDanger !== '0',
        icon: form.dataset.swalIcon || 'warning',
    });

    if (! confirmed) {
        return;
    }

    form.dataset.swalConfirmed = '1';
    form.requestSubmit();
});

document.addEventListener('DOMContentLoaded', () => {
    const success = document.body.dataset.flashSuccess;
    const error = document.body.dataset.flashError;
    const errors = document.body.dataset.flashErrors;

    if (success) {
        window.SimbaAlert.toast('success', success);
    }

    if (error) {
        window.SimbaAlert.error('Something went wrong', error);
    }

    if (errors) {
        try {
            const list = JSON.parse(errors);
            if (Array.isArray(list) && list.length) {
                window.SimbaAlert.error('Please fix the following', list.join('\n'));
            }
        } catch (e) {
            // ignore malformed payload
        }
    }
});
