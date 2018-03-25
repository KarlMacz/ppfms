var currentBuyersPaginationPage = 1;
var currentSuppliersPaginationPage = 1;
var tableBuyersLimit = 10;
var tableSuppliersLimit = 10;

function loadBuyersTable(start, limit) {
    ajaxRequest('../backend/ajax/table_buyers.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#buyers-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#buyers-table tbody').append('<tr>\
                        <td>' + response.data[ctr].full_name + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#buyers-table tbody').append('<tr>\
                    <td class="text-center" colspan="4">No results found.</td>\
                </tr>');
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('.buyers-pagination').html('<li' + (currentBuyersPaginationPage === 1 ? ' class="disabled"' : '') + '><a href="#"><span>&laquo;</span></a></li>');

        for(var i = 0; i < Math.ceil(response.data_total_count / limit); i++) {
            $('.buyers-pagination').append('<li' + (currentBuyersPaginationPage === (i + 1) ? ' class="active"' : '') + '><a href="#" data-page="' + (i + 1) + '"><span>' + (i + 1) + '</span></a></li>');
        }

        $('.buyers-pagination').append('<li' + (currentBuyersPaginationPage === Math.ceil(response.data_total_count / limit) ? ' class="disabled"' : '') + '><a href="#"><span>&raquo;</span></a></li>');
    });
}

function loadSuppliersTable(start, limit) {
    ajaxRequest('../backend/ajax/table_suppliers.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#suppliers-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#suppliers-table tbody').append('<tr>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#suppliers-table tbody').append('<tr>\
                    <td class="text-center" colspan="4">No results found.</td>\
                </tr>');
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('.suppliers-pagination').html('<li' + (currentSuppliersPaginationPage === 1 ? ' class="disabled"' : '') + '><a href="#"><span>&laquo;</span></a></li>');

        for(var i = 0; i < Math.ceil(response.data_total_count / limit); i++) {
            $('.suppliers-pagination').append('<li' + (currentSuppliersPaginationPage === (i + 1) ? ' class="active"' : '') + '><a href="#" data-page="' + (i + 1) + '"><span>' + (i + 1) + '</span></a></li>');
        }

        $('.suppliers-pagination').append('<li' + (currentSuppliersPaginationPage === Math.ceil(response.data_total_count / limit) ? ' class="disabled"' : '') + '><a href="#"><span>&raquo;</span></a></li>');
    });
}

$(document).ready(function() {
    loadBuyersTable((currentBuyersPaginationPage * tableBuyersLimit) - tableBuyersLimit, tableBuyersLimit);
    loadSuppliersTable((currentSuppliersPaginationPage * tableSuppliersLimit) - tableSuppliersLimit, tableSuppliersLimit);

    $('body').on('change', '.buyers-filter-table', function() {
        tableBuyersLimit = parseInt($(this).find('option:selected').val());
        currentBuyersPaginationPage = 1;

        loadBuyersTable((currentBuyersPaginationPage * tableBuyersLimit) - tableBuyersLimit, tableBuyersLimit);

        return false;
    });

    $('body').on('change', '.suppliers-filter-table', function() {
        tableSuppliersLimit = parseInt($(this).find('option:selected').val());
        currentSuppliersPaginationPage = 1;

        loadSuppliersTable((currentSuppliersPaginationPage * tableSuppliersLimit) - tableSuppliersLimit, tableSuppliersLimit);

        return false;
    });

    $('body').on('click', '.buyers-pagination a', function() {
        currentBuyersPaginationPage = parseInt($(this).attr('data-page'));

        loadBuyersTable((currentBuyersPaginationPage * tableBuyersLimit) - tableBuyersLimit, tableBuyersLimit);

        return false;
    });

    $('body').on('click', '.suppliers-pagination a', function() {
        currentSuppliersPaginationPage = parseInt($(this).attr('data-page'));

        loadSuppliersTable((currentSuppliersPaginationPage * tableSuppliersLimit) - tableSuppliersLimit, tableSuppliersLimit);

        return false;
    });
});
