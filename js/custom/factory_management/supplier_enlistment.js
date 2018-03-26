function addSuppliesProvided() {
    ajaxRequest('../backend/ajax/form_select_products.php', 'POST', {}, function(response) {
        if(response.data.length > 0) {
            var suppliesProvided = '';

            for(var i = 0; i < response.data.length; i++) {
                suppliesProvided += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
            }

            $('#supplies-provided-block').append('<div class="form-group">\
                    <div class="input-group">\
                        <select name="supplies[]" class="form-control">\
                            <option value="" selected disabled>Select an option...</option>\
                            ' + suppliesProvided + '\
                        </select>\
                        <span class="input-group-btn">\
                            <button type="button" class="remove-supply-button btn btn-danger"><span class="fas fa-trash fa-fw"></span></button>\
                        </span>\
                    </div>\
                </div>');
        }
    });
}

$(document).ready(function() {
    $('body').on('click', '.add-supply-button', function() {
        addSuppliesProvided();
        
        return false;
    });

    $('body').on('click', '.remove-supply-button', function() {
        $(this).parent().parent().parent().remove();
        
        return false;
    });
});
