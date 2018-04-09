$(document).ready(function() {
    $('body').on('submit', '#register-form', function() {
        var thisElement = $(this);

        validateInputs(thisElement.serializeArray(), {
            'username': 'alphanumeric',
            'email': 'email',
            'first_name': 'name',
            'middle_name': 'name',
            'last_name': 'name',
            'birth_date': 'date'
        }, '', function() {
            openModal('loader-modal', 'static');

            ajaxRequest('backend/ajax/register.php', 'POST', thisElement.serialize(), function(response) {
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
        });

        return false;
    });
});
