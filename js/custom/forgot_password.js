$(document).ready(function() {
    $('body').on('submit', '#forgot-password-form', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('backend/ajax/forgot_password.php', 'POST', $(this).serialize(), function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Forgot Password Status', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');

                if(response.status === 'Ok') {
                    window.location = response.data.url;
                }
            }, 2000);
        });

        return false;
    });
});
