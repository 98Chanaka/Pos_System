<!-- Add Item Modal -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
    Add Item
</button>

<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Register New Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addItemForm" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Item Code *</label>
                            <input type="text" name="item_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Item Name *</label>
                            <input type="text" name="item_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>M/F Date</label>
                            <input type="date" name="mfd_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Exp Date *</label>
                            <input type="date" name="exp_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Supplier</label>
                            <input type="text" name="company_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Quantity *</label>
                            <input type="number" name="quantity" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Cost Price *</label>
                            <input type="number" name="cost_price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Selling Price *</label>
                            <input type="number" name="selling_price" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Handle form submission with AJAX
    $('#addItemForm').submit(function(e) {
        e.preventDefault();

        // Show loading indicator
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Item has been added successfully',
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    timer: 3000
                }).then((result) => {
                    $('#addItemModal').modal('hide');
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.close();

                if (xhr.status === 422) {
                    // Validation errors
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = [];

                    for (var field in errors) {
                        errorMessages.push(errors[field][0]);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessages.join('<br>'),
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    // Other errors
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON.message || 'Failed to add item. Please try again.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            }
        });
    });

    // Reset form when modal is closed
    $('#addItemModal').on('hidden.bs.modal', function() {
        $('#addItemForm')[0].reset();
        $('#addItemForm').find('.is-invalid').removeClass('is-invalid');
        $('#addItemForm').find('.invalid-feedback').remove();
    });
});
</script>
