$(document).ready(function() {
    $('body').on('submit', '#register-form', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('backend/ajax/register.php', 'POST', $(this).serialize(), function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Registration Status', response.message);
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
