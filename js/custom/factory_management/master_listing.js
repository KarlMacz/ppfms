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

    $('body').on('click', '.view-buyer-button', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/modal_view_buyer.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            
            if(response.status === 'Ok') {
                setModalContent('view-buyer-modal', 'View Buyer\'s Information', response.output.body);
                openModal('view-buyer-modal');
            } else {
                setModalContent('status-modal', 'View Buyer\'s Information', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadBuyersTable((currentBuyersPaginationPage * tableBuyersLimit) - tableBuyersLimit, tableBuyersLimit);
                }, 2000);
            }
        });
    });

    $('body').on('click', '.delete-buyer-button', function() {
        openModal('delete-buyer-modal', 'static');

        $('#delete-buyer-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#delete-buyer-modal .negative-button', function() {
        $('#delete-buyer-modal').attr('data-id', '');

        closeModal('delete-buyer-modal');
    });

    $('body').on('click', '#delete-buyer-modal .positive-button', function() {
        closeModal('delete-buyer-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/delete_buyer.php', 'POST', {
            id: $('#delete-buyer-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Delete Buyer\'s Information', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadBuyersTable((currentBuyersPaginationPage * tableBuyersLimit) - tableBuyersLimit, tableBuyersLimit);
            }, 2000);
        });
    });

    $('body').on('click', '.view-supplier-button', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/modal_view_supplier.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            
            if(response.status === 'Ok') {
                setModalContent('view-supplier-modal', 'View Supplier\'s Information', response.output.body);
                openModal('view-supplier-modal');
            } else {
                setModalContent('status-modal', 'View Supplier\'s Information', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadSuppliersTable((currentSuppliersPaginationPage * tableSuppliersLimit) - tableSuppliersLimit, tableSuppliersLimit);
                }, 2000);
            }
        });
    });

    $('body').on('click', '.delete-supplier-button', function() {
        openModal('delete-supplier-modal', 'static');

        $('#delete-supplier-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#delete-supplier-modal .negative-button', function() {
        $('#delete-supplier-modal').attr('data-id', '');

        closeModal('delete-supplier-modal');
    });

    $('body').on('click', '#delete-supplier-modal .positive-button', function() {
        closeModal('delete-supplier-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/delete_supplier.php', 'POST', {
            id: $('#delete-supplier-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Delete Supplier', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadSuppliersTable((currentSuppliersPaginationPage * tableSuppliersLimit) - tableSuppliersLimit, tableSuppliersLimit);
            }, 2000);
        });
    });
});
