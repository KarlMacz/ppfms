$(document).ready(function() {
    var today = new Date();
    var yearToday = new String(today.getFullYear());
    var monthToday = new String(today.getMonth() + 1);
    var dateToday = new String(today.getDate());

    $('body').on('submit', '#register-form', function() {
        var thisElement = $(this);
        var ageLimit = 18;
        var beforeToday = (parseInt(yearToday) - ageLimit) + '-' + (monthToday.length < 2 ? '0' + monthToday : month) + '-' + (dateToday.length < 2 ? '0' + dateToday : dateToday);

        validateInputs(thisElement.serializeArray(), {
            'username': 'alphanumeric',
            'email': 'email',
            'first_name': 'name',
            'middle_name': 'name',
            'last_name': 'name',
            'birth_date': 'date_before|' + beforeToday
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
