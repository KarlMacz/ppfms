$(document).ready(function() {
    $('body').on('change', '#type-input', function() {
        switch($(this).find('option:selected').val()) {
            case 'Face':
                $('#category-input').html('<option value="" selected disabled>Select an option...</option>\
                    <option value="Skin-Care Creams">Skin-Care Creams</option>\
                    <option value="Lipsticks">Lipsticks</option>\
                    <option value="Eye and Facial Makeup">Eye and Facial Makeup</option>\
                    <option value="Towelettes">Towelettes</option>\
                    <option value="Contact Lenses">Contact Lenses</option>');
                $('#category-input').attr('disabled', false);

                break;
            case 'Body':
                $('#category-input').html('<option value="" selected disabled>Select an option...</option>\
                    <option value="Deodorants">Deodorants</option>\
                    <option value="Lotions">Lotions</option>\
                    <option value="Powders">Powders</option>\
                    <option value="Perfumes">Perfumes</option>\
                    <option value="Baby Products">Baby Products</option>\
                    <option value="Bath Oils">Bath Oils</option>\
                    <option value="Bubble Baths">Bubble Baths</option>\
                    <option value="Bath Salts">Bath Salts</option>\
                    <option value="Body Butters">Body Butters</option>');
                $('#category-input').attr('disabled', false);

                break;
            case 'Hands/Nails':
                $('#category-input').html('<option value="" selected disabled>Select an option...</option>\
                    <option value="Fingernail and Toe Nail Polish">Fingernail and Toe Nail Polish</option>\
                    <option value="Hand Sanitizer">Hand Sanitizer</option>');
                $('#category-input').attr('disabled', false);

                break;
            case 'Hair':
                $('#category-input').html('<option value="" selected disabled>Select an option...</option>\
                    <option value="Permanent Chemicals">Permanent Chemicals</option>\
                    <option value="Hair Colors">Hair Colors</option>\
                    <option value="Hair Sprays">Hair Sprays</option>\
                    <option value="Gels">Gels</option>');
                $('#category-input').attr('disabled', false);

                break;
            default:
                $('#category-input').html('<option value="" selected disabled>Select an option...</option>');
                $('#category-input').attr('disabled', true);

                break
        }
    });
});
