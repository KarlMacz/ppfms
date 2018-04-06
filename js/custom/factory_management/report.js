$(document).ready(function() {
    $('body').on('change', '#report-input', function() {
        var thisValue = $(this).find('option:selected').val();

        if(thisValue !== '') {
            openModal('loader-modal', 'static');

            $('#report-frame').attr('src', thisValue);
        } else {
            $('#report-frame').attr('src', '../partials/report_placeholder.php');
        }

        $('#report-frame').on('load', function() {
            closeModal('loader-modal');
        });
    });
});
