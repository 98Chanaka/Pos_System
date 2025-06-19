<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Item Code *</label>
                            <input type="text" name="item_code" class="form-control" value="{{ $item->item_code ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Item Name *</label>
                            <input type="text" name="item_name" class="form-control" value="{{ $item->item_name ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>M/F Date</label>
                            <input type="date" name="mfd_date" class="form-control" value="{{ $item->mfd_date ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Exp Date *</label>
                            <input type="date" name="exp_date" class="form-control" value="{{ $item->exp_date ?? '' }}" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $item->company_name ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" width="100" class="mt-2">
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Quantity *</label>
                            <input type="number" name="quantity" class="form-control" value="{{ $item->quantity ?? 0 }}" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Cost Price *</label>
                            <input type="number" name="cost_price" class="form-control" value="{{ $item->cost_price ?? 0 }}" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Selling Price *</label>
                            <input type="number" name="selling_price" class="form-control" value="{{ $item->selling_price ?? 0 }}" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// When the page loads, show the edit modal automatically
$(document).ready(function() {
    $('#editItemModal').modal('show');

    // Close modal and redirect back when dismissed
    $('#editItemModal').on('hidden.bs.modal', function () {
        window.location.href = "{{ route('items.index') }}";
    });
});
</script>
