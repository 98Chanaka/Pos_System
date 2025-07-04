@extends('layouts.master')

@section('title', 'POS')

@section('content')
<div class="app-content">
    <div class="container-fluid py-4">
        <!-- Invoice Header -->
        <div class="card mb-4">
            <div class="card-header  text-black">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Invoice #<span id="invoiceNumber">{{ $nextInvoiceNumber ?? '00001' }}</span></h5>
                    <div class="text-end">
                        <span id="currentDate">{{ now()->format('Y-m-d H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Card with Select2 Search -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-center align-items-center">
                <h5 class="card-title mb-0">Items Search</h5>
            </div>
            <div class="card-body">
                <form id="itemSearchForm">
                    <div class="row g-2 align-items-end">
                        <!-- Item Code Search with Select2 -->
                        <div class="col-md-3">
                            <label for="itemCode" class="form-label">Item Code</label>
                            <select class="form-select select2-item-code" id="itemCode" name="item_code">
                                <option></option> <!-- Empty option for placeholder -->
                            </select>
                        </div>

                        <!-- Item Name Search with Select2 -->
                        <div class="col-md-3">
                            <label for="itemName" class="form-label">Item Name</label>
                            <select class="form-select select2-item-name" id="itemName" name="item_name">
                                <option></option> <!-- Empty option for placeholder -->
                            </select>
                        </div>

                        <!-- Company Search with Select2 -->
                        <div class="col-md-3">
                            <label for="companyName" class="form-label">Company</label>
                            <select class="form-select select2-company" id="companyName" name="company_name">
                                <option></option> <!-- Empty option for placeholder -->
                                @foreach($companies as $company)
                                    <option value="{{ $company }}">{{ $company }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action buttons -->
                        <div class="col-md-1">
                            <button type="button" class="btn btn-success w-100 btn-sm" id="addItemBtn">Add</button>
                        </div>
                        <div class="col-md-1">
                            <button type="reset" class="btn btn-warning w-100 btn-sm" id="resetBtn">Reset</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-info w-100 btn-sm" id="searchItemBtn">Find</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-center align-items-center">
                <h5 class="card-title mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Company</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTable">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Subtotal:</td>
                                <td colspan="2" id="subtotal">LKR 0.00</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Discount:</td>
                                <td colspan="2">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discountInput" value="0" min="0" max="100" step="0.01">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Total:</td>
                                <td colspan="2" id="total">LKR 0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3 gap-2">
                    <button class="btn btn-outline-danger" id="cancelOrderBtn">Cancel Order</button>
                    <button class="btn btn-outline-primary" id="printInvoiceBtn">Complete Order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Item Selection -->
<div class="modal fade" id="itemSelectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Items will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Customer Details -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="customerDetailsForm">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerPhone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="customerPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">Email (Optional)</label>
                        <input type="email" class="form-control" id="customerEmail">
                    </div>
                    <div class="mb-3">
                        <label for="customerAddress" class="form-label">Address (Optional)</label>
                        <textarea class="form-control" id="customerAddress" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmPrintBtn">Complete Order</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for all dropdowns
    function initSelect2() {
        $('.select2-company').select2({
            placeholder: "Select a company",
            allowClear: true,
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('.select2-item-code').select2({
            placeholder: "Search by item code",
            allowClear: true,
            theme: 'bootstrap-5',
            width: '100%',
            ajax: {
                url: '{{ route("pos.searchItems") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        search_type: 'code'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.item_code,
                                text: item.item_code,
                                item_id: item.id,
                                item_name: item.item_name,
                                company_name: item.company_name,
                                selling_price: item.selling_price
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('.select2-item-name').select2({
            placeholder: "Search by item name",
            allowClear: true,
            theme: 'bootstrap-5',
            width: '100%',
            ajax: {
                url: '{{ route("pos.searchItems") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        search_type: 'name'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.item_name,
                                text: item.item_name,
                                item_id: item.id,
                                item_code: item.item_code,
                                company_name: item.company_name,
                                selling_price: item.selling_price
                            };
                        })
                    };
                },
                cache: true
            }
        });
    }

    initSelect2();

    // When item code is selected
    $('#itemCode').on('select2:select', function (e) {
        const data = e.params.data;
        $('#itemName').val(null).trigger('change');
        $('#companyName').val(data.company_name).trigger('change');

        // Auto-fill the item name
        const newOption = new Option(data.item_name, data.item_name, true, true);
        $('#itemName').append(newOption).trigger('change');
    });

    // When item name is selected
    $('#itemName').on('select2:select', function (e) {
        const data = e.params.data;
        $('#itemCode').val(null).trigger('change');
        $('#companyName').val(data.company_name).trigger('change');

        // Auto-fill the item code
        const newOption = new Option(data.item_code, data.item_code, true, true);
        $('#itemCode').append(newOption).trigger('change');
    });

    // Reset form
    $('#resetBtn').click(function() {
        $('#itemSearchForm')[0].reset();
        $('.select2-item-code').val(null).trigger('change');
        $('.select2-item-name').val(null).trigger('change');
        $('.select2-company').val(null).trigger('change');
    });

    // Search items
    $('#searchItemBtn').click(function() {
        const formData = $('#itemSearchForm').serialize();

        $.ajax({
            url: '{{ route("pos.searchItems") }}',
            type: 'GET',
            data: formData,
            success: function(response) {
                const itemsTable = $('#itemsTable tbody');
                itemsTable.empty();

                if(response.items.length > 0) {
                    response.items.forEach(function(item) {
                        itemsTable.append(`
                            <tr>
                                <td>${item.item_code}</td>
                                <td>${item.item_name}</td>
                                <td>${item.company_name}</td>
                                <td>LKR${item.selling_price}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary select-item"
                                        data-id="${item.id}"
                                        data-code="${item.item_code}"
                                        data-name="${item.item_name}"
                                        data-company="${item.company_name}"
                                        data-price="${item.selling_price}">
                                        Select
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#itemSelectionModal').modal('show');
                } else {
                    alert('No items found matching your criteria.');
                }
            }
        });
    });

    // Add selected item to order
    $(document).on('click', '.select-item', function() {
        const item = {
            id: $(this).data('id'),
            code: $(this).data('code'),
            name: $(this).data('name'),
            company: $(this).data('company'),
            price: $(this).data('price'),
            quantity: 1,
            discount: 0
        };

        addItemToOrder(item);
        $('#itemSelectionModal').modal('hide');
    });

    // Add item directly when Add button is clicked
    $('#addItemBtn').click(function() {
        const itemCode = $('#itemCode').select2('data')[0]?.item_id;
        const itemName = $('#itemName').select2('data')[0]?.item_id;

        if(itemCode || itemName) {
            const selectedItem = itemCode ? $('#itemCode').select2('data')[0] : $('#itemName').select2('data')[0];

            const item = {
                id: selectedItem.item_id,
                code: selectedItem.id,
                name: selectedItem.item_name || selectedItem.text,
                company: selectedItem.company_name,
                price: selectedItem.selling_price,
                quantity: 1,
                discount: 0
            };

            addItemToOrder(item);
        } else {
            alert('Please select an item first');
        }
    });

    // Function to add item to order table
    function addItemToOrder(item) {
        const orderTable = $('#orderItemsTable');
        const existingItem = orderTable.find(`tr[data-id="${item.id}"]`);

        if(existingItem.length > 0) {
            // Item already exists, increase quantity
            const quantityInput = existingItem.find('.item-quantity');
            const newQuantity = parseInt(quantityInput.val()) + 1;
            quantityInput.val(newQuantity);

            // Update total
            const totalCell = existingItem.find('.item-total');
            const price = parseFloat(existingItem.find('td:eq(4)').text().replace('LKR', ''));
            const discount = parseFloat(existingItem.find('.item-discount').val()) || 0;
            const discountedPrice = price - (price * (discount / 100));
            totalCell.text('LKR' + (discountedPrice * newQuantity).toFixed(2));
        } else {
            // Add new item
            const row = `
                <tr data-id="${item.id}">
                    <td>${orderTable.children().length + 1}</td>
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>${item.company}</td>
                    <td>LKR${item.price}</td>
                    <td>
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <input type="number" class="form-control form-control-sm item-discount"
                                   value="0" min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-quantity"
                               value="1" min="1" style="width: 70px;">
                    </td>
                    <td class="item-total">LKR${item.price}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item">Remove</button>
                    </td>
                </tr>
            `;
            orderTable.append(row);
        }

        updateOrderTotals();
    }

    // Remove item from order
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        updateOrderNumbers();
        updateOrderTotals();
    });

    // Update quantity and totals when changed
    $(document).on('change', '.item-quantity, .item-discount', function() {
        const row = $(this).closest('tr');
        const price = parseFloat(row.find('td:eq(4)').text().replace('LKR', ''));
        const quantity = parseInt(row.find('.item-quantity').val());
        const discount = parseFloat(row.find('.item-discount').val()) || 0;

        // Calculate discounted price
        const discountedPrice = price - (price * (discount / 100));
        const total = discountedPrice * quantity;

        row.find('.item-total').text('LKR' + total.toFixed(2));
        updateOrderTotals();
    });

    // Apply global discount when changed
    $('#discountInput').on('change', function() {
        updateOrderTotals();
    });

    // Update row numbers
    function updateOrderNumbers() {
        $('#orderItemsTable tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Calculate order totals
    function updateOrderTotals() {
        let subtotal = 0;

        $('.item-total').each(function() {
            subtotal += parseFloat($(this).text().replace('LKR', ''));
        });

        const discountPercentage = parseFloat($('#discountInput').val()) || 0;
        const discountAmount = subtotal * (discountPercentage / 100);
        const total = subtotal - discountAmount;

        $('#subtotal').text('LKR' + subtotal.toFixed(2));
        $('#total').text('LKR' + total.toFixed(2));
    }

    // Cancel order
    $('#cancelOrderBtn').click(function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will clear the current order!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Clear the order table
                $('#orderItemsTable').empty();
                // Reset the form
                $('#itemSearchForm')[0].reset();
                $('.select2').val(null).trigger('change');
                // Reset totals
                $('#discountInput').val(0);
                updateOrderTotals();

                Swal.fire(
                    'Cancelled!',
                    'Your order has been cancelled.',
                    'success'
                );
            }
        });
    });

    // Open customer details modal when Complete Order button is clicked
    $('#printInvoiceBtn').click(function() {
        // Check if there are items in the order
        if ($('#orderItemsTable tr').length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Empty Order',
                text: 'Please add items to the order before completing it.',
            });
            return;
        }

        $('#customerDetailsModal').modal('show');
    });

    // Complete order when confirm button is clicked in customer details modal
    $('#confirmPrintBtn').click(function() {
        const customerName = $('#customerName').val();
        //const customerPhone = $('#customerPhone').val();

        if (!customerName || !customerPhone) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please provide customer name and phone number.',
            });
            return;
        }

        // Prepare order data
        const items = [];
        $('#orderItemsTable tr').each(function() {
            const item = {
                id: $(this).data('id'),
                quantity: $(this).find('.item-quantity').val(),
                price: $(this).find('td:eq(4)').text().replace('LKR', ''),
                discount: $(this).find('.item-discount').val() || 0
            };
            items.push(item);
        });

        const orderData = {
            customer_name: customerName,
            customer_phone: customerPhone,
            customer_email: $('#customerEmail').val(),
            customer_address: $('#customerAddress').val(),
            items: items,
            subtotal: $('#subtotal').text().replace('LKR', ''),
            discount: $('#discountInput').val(),
            total: $('#total').text().replace('LKR', ''),
            invoice_number: $('#invoiceNumber').text()
        };

        // Here you would typically send the order to the server via AJAX
        console.log('Order data:', orderData);

        // For demo purposes, just show a success message
        Swal.fire({
            icon: 'success',
            title: 'Order Completed!',
            text: 'The order has been successfully processed.',
        }).then(() => {
            // Clear the order table
            $('#orderItemsTable').empty();
            // Reset the form
            $('#itemSearchForm')[0].reset();
            $('.select2').val(null).trigger('change');
            // Reset customer details
            $('#customerName').val('');
            $('#customerPhone').val('');
            $('#customerEmail').val('');
            $('#customerAddress').val('');
            // Reset totals
            $('#discountInput').val(0);
            updateOrderTotals();
            // Close modal
            $('#customerDetailsModal').modal('hide');

            // Increment invoice number (for demo purposes)
            const currentNum = parseInt($('#invoiceNumber').text());
            $('#invoiceNumber').text(String(currentNum + 1).padStart(5, '0'));
        });
    });
});
</script>
@endpush
