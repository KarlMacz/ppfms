$(document).ready(function() {
    $('#report-frame').on('load', function() {
        closeModal('loader-modal');
    });

    $('body').on('change', '#report-input', function() {
        var thisValue = $(this).find('option:selected').val();

        if(thisValue !== '') {
            openModal('loader-modal', 'static');

            $('#report-frame').attr('src', thisValue);
        }
    });
});
