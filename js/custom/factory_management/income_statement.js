$(document).ready(function() {
    $('body').on('change', '#month-input', function() {
        var thisValue = $('#month-input').find('option:selected').val();

        if($('#month-input').find('option:selected').val() !== '' && $('#year-input').val() !== '') {
            openModal('loader-modal', 'static');

            $('#accounting-frame').attr('src', '../backend/pdf/generate_income_statement.php?month=' + $('#month-input').find('option:selected').val() + '&year=' + $('#year-input').val());
        } else {
            $('#accounting-frame').attr('src', '../partials/report_placeholder.php');
        }

        $('#accounting-frame').on('load', function() {
            closeModal('loader-modal');
        });
    });

    $('body').on('change', '#year-input', function() {
        var thisValue = $('#month-input').find('option:selected').val();

        if($('#month-input').find('option:selected').val() !== '' && $('#year-input').val() !== '') {
            openModal('loader-modal', 'static');

            $('#accounting-frame').attr('src', '../backend/pdf/generate_income_statement.php?month=' + $('#month-input').find('option:selected').val() + '&year=' + $('#year-input').val());
        } else {
            $('#accounting-frame').attr('src', '../partials/report_placeholder.php');
        }

        $('#accounting-frame').on('load', function() {
            closeModal('loader-modal');
        });
    });
});
