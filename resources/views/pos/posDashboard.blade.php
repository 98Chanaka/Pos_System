@extends('layouts.master')

@section('title', 'POS')

@section('content')
<div class="app-content">
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="fw-bold">POS Dashboard</h2>
        </div>

        {{-- Search Filters --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" id="item_name" class="form-control" placeholder="Item Name">
            </div>
            <div class="col-md-3">
                <input type="text" id="item_code" class="form-control" placeholder="Item Code">
            </div>
            <div class="col-md-3">
                <input type="text" id="company_name" class="form-control" placeholder="Company Name">
            </div>
            <div class="col-md-3">
                <button id="filter" class="btn btn-primary">Search</button>
                <button id="reset" class="btn btn-danger">Reset</button>

               <!-- Add Button -->
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    Add
                </button>

            </div>
        </div>

        {{-- DataTable --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="posTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mouse</td>
                            <td>ITM001</td>
                            <td>10</td>
                            <td>$15.00</td>
                            <td>$150.00</td>
                        </tr>
                        
                    </tbody>
                </table>



            </div>
        </div>
        {{-- Cancel & Print Buttons --}}
                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-danger me-2">Cancel</button>
                    <button class="btn btn-success" onclick="window.print()">Print</button>
                </div>

    </div>
</div>


<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Item Code</label>
                            <input type="text" name="item_code" class="form-control" required>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Include jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

{{-- Optional Script for DataTable AJAX --}}
{{--
<script>
    $(document).ready(function () {
        let table = $('#posTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '', // Your route here
                data: function (d) {
                    d.item_name = $('#item_name').val();
                    d.item_code = $('#item_code').val();
                    d.company_name = $('#company_name').val();
                }
            },
            columns: [
                { data: 'item_name', name: 'item_name' },
                { data: 'item_code', name: 'item_code' },
                { data: 'quantity', name: 'quantity' },
                { data: 'price', name: 'price' },
                { data: 'total', name: 'total' }
            ]
        });

        $('#filter').click(function () {
            table.ajax.reload();
        });

        $('#reset').click(function () {
            $('#item_name, #item_code, #company_name').val('');
            table.ajax.reload();
        });
    });
</script>
--}}
@endsection
