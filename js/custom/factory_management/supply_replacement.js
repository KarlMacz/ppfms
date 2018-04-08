$(document).ready(function() {
    $('body').on('change', '#product-input', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/form_select_supplies.php', 'POST', {
            id: $(this).find('option:selected').val()
        }, function(response) {
            closeModal('loader-modal');

            $('#supplier-input').html('<option value="" selected disabled>Select an option...</option>');

            if(response.status === 'Ok') {
                if(response.data.length > 0) {
                    $('#supplier-input').attr('disabled', false);

                    for(var ctr = 0; ctr < response.data.length; ctr++) {
                        $('#supplier-input').append('<option value="' + response.data[ctr].id + '">' + response.data[ctr].name + '</option>');
                    }
                }
            }
        });
    });
});
