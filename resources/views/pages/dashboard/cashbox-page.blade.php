
@extends('layout.sidenav-layout')

@section('content')
<style>
.pagination{
gap:6px;
}

.pagination .page-link{
border-radius:6px;
font-size:13px;
padding:6px 12px;
color:#333;
}

.pagination .active .page-link{
background:#0d6efd;
border-color:#0d6efd;
color:#fff;
}

.pagination .page-link:hover{
background:#f2f4f7;
}
</style>

<div class="container-fluid py-2">

    <!-- Page Title -->
<div class="row mb-2 align-items-center">

<div class="col-6">
    <h5 class="fw-semibold mb-0">Cashbox</h5>
</div>

<div class="col-6 d-flex justify-content-end gap-2">

    <!-- DOWNLOAD PDF -->
    <a href="#" onclick="downloadCashboxPdf()" class="card border-0 shadow-sm px-3 py-2 text-decoration-none">

        <div class="d-flex align-items-center gap-2">
            <div class="icon-circle bg-danger-subtle">
                <i class="bi bi-file-earmark-pdf text-danger"></i>
            </div>

            <div>
                <div class="summary-title text-dark">Download PDF</div>
            </div>
        </div>
    </a>


    <!-- CASH IN -->
    <div class="card border-0 shadow-sm px-3 py-2 cursor-pointer"
         data-bs-toggle="modal" data-bs-target="#cashInModal">

        <div class="d-flex align-items-center gap-2">
            <div class="icon-circle bg-success-subtle">
                <i class="bi bi-arrow-down-circle text-success"></i>
            </div>

            <div>
                <div class="summary-title">Cash In</div>
            </div>
        </div>
    </div>


    <!-- CASH OUT -->
    <div class="card border-0 shadow-sm px-3 py-2 cursor-pointer"
         data-bs-toggle="modal" data-bs-target="#cashOutModal">

        <div class="d-flex align-items-center gap-2">
            <div class="icon-circle bg-danger-subtle">
                <i class="bi bi-arrow-up-circle text-danger"></i>
            </div>

            <div>
                <div class="summary-title">Cash Out</div>
            </div>
        </div>
    </div>

</div>

</div>



<!-- SUMMARY BOXES -->
<div class="row g-2 mb-2">

<!-- BALANCE -->
<div class="col-lg-4 col-md-4">
<div class="card shadow-sm border-0 bg-primary-subtle">

<div class="card-body py-3 px-3 d-flex justify-content-between align-items-center">

<div>
<div class="fw-semibold">Balance</div>
<div class="fw-bold fs-5 text-dark">
    ৳ {{ number_format($balance, 2) }}
</div>
</div>

<div class="icon-circle bg-white">
<i class="bi bi-wallet2 text-primary"></i>
</div>

</div>
</div>
</div>


<!-- CASH IN -->
<div class="col-lg-4 col-md-4">
<div class="card shadow-sm border-0">

<div class="card-body py-3 px-3 d-flex justify-content-between align-items-center">

<div>
<div class="fw-semibold">Cash In</div>
<div class="fw-bold fs-5 text-success">
    ৳ {{ number_format($cashIn, 2) }}
</div>
</div>

<div class="icon-circle bg-success-subtle">
<i class="bi bi-arrow-down-circle text-success"></i>
</div>

</div>
</div>
</div>


<!-- CASH OUT -->
<div class="col-lg-4 col-md-4">
<div class="card shadow-sm border-0">

<div class="card-body py-3 px-3 d-flex justify-content-between align-items-center">

<div>
<div class="fw-semibold">Cash Out</div>
<div class="fw-bold fs-5 text-danger">
    ৳ {{ number_format($cashOut, 2) }}
</div>
</div>

<div class="icon-circle bg-danger-subtle">
<i class="bi bi-arrow-up-circle text-danger"></i>
</div>

</div>
</div>
</div>

</div>



<!-- FILTER -->
<!-- FILTER -->
<div class="card shadow-sm border-0 mb-2">
<div class="card-body py-2 px-3">

<form method="GET">

<div class="row g-2 align-items-end">

<!-- LEFT FILTER AREA -->
<div class="col-lg-9">

<div class="row g-2">

<!-- TRANSACTION TYPE -->
<div class="col-lg-4">

<label class="form-label small mb-1 fw-semibold">
Transaction Type
</label>

<select class="form-select form-select-sm" name="type">

<option value="">All Transactions</option>

<option value="Cash In"
{{ request('type')=='Cash In'?'selected':'' }}>
Cash In
</option>

<option value="Cash Out"
{{ request('type')=='Cash Out'?'selected':'' }}>
Cash Out
</option>

</select>

</div>


<!-- DATE RANGE -->
<div class="col-lg-4">

<label class="form-label small mb-1 fw-semibold">
Date Range
</label>

<div class="position-relative">

<input
type="text"
id="dateRange"
class="form-control form-control-sm pe-5"
placeholder="Select Date Range"
value="{{ request('start_date') && request('end_date')
? request('start_date') . ' to ' . request('end_date')
: '' }}"
>

<button
type="button"
id="clearDate"
class="position-absolute top-50 end-0 translate-middle-y me-2 btn btn-sm p-0 border-0">

&times;

</button>

</div>

<input type="hidden" name="start_date" id="start_date">
<input type="hidden" name="end_date" id="end_date">

</div>

</div>

</div>


<!-- FILTER BUTTON -->
<div class="col-lg-3 d-flex align-items-end">

<button class="btn btn-primary w-100 btn-sm">
Filter
</button>

</div>

</div>

</form>

</div>
</div>

<!-- TABLE -->
<div class="card shadow-sm border-0">

<div class="card-header bg-white py-2 d-flex justify-content-between">

<small><b>Total Transactions: {{ $totalTransactions }}</b></small>

<small><b>Total Amount: {{ number_format($totalAmount) }}</b></small>

</div>


<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>
<tr>
<th>Type</th>
<th>Date & Time</th>
<th>Source</th>
<th>Note</th>
<th>Amount</th>

</tr>
</thead>

<tbody>

@foreach($transactions as $t)

<tr>
    <td>
@if($t['type'] == 'Cash In')
<span class="badge bg-success">Cash In</span>
@else
<span class="badge bg-danger">Cash Out</span>
@endif
</td>
<td>
    {{ $t['date'] }}
</td>

  <td>{{ $t['source'] }}</td>

<td>
{{ $t['note'] }}
</td>

<td>
<b>{{ number_format($t['amount']) }}</b>
</td>




</tr>

@endforeach

</tbody>

</table>


<div class="mt-3 d-flex justify-content-between align-items-center">

<div class="text-muted small ps-2">
Showing {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} of {{ $transactions->total() }}
</div>

<div>
{{ $transactions->onEachSide(1)->links() }}
</div>

</div>

</div>




</div>

</div>

</div>


<!-- CASH IN MODAL -->
<div class="modal fade" id="cashInModal" tabindex="-1">
<div class="modal-dialog modal-md modal-dialog-centered">

<div class="modal-content border-0 shadow">

<div class="modal-header bg-success-subtle border-0">
<h5 class="modal-title fw-semibold text-success">Cash In</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="mb-3">
<label class="form-label">Amount</label>
<input type="number" class="form-control form-control-lg">
</div>

<div class="mb-2">
<label class="form-label">Note</label>
<textarea class="form-control" rows="3"></textarea>
</div>

</div>

<div class="modal-footer border-0">
<button class="btn btn-success w-100">
Save Cash In
</button>
</div>

</div>
</div>
</div>

<!-- CASH OUT MODAL -->
<div class="modal fade" id="cashOutModal" tabindex="-1">
<div class="modal-dialog modal-md modal-dialog-centered">

<div class="modal-content border-0 shadow">

<div class="modal-header bg-danger-subtle border-0">
<h5 class="modal-title fw-semibold text-danger">Cash Out</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="mb-3">
<label class="form-label">Amount</label>
<input type="number" class="form-control form-control-lg">
</div>

<div class="mb-2">
<label class="form-label">Note</label>
<textarea class="form-control" rows="3"></textarea>
</div>

</div>

<div class="modal-footer border-0">
<button class="btn btn-danger w-100">
Save Cash Out
</button>
</div>

</div>
</div>
</div>


<script>

    function downloadCashboxPdf() {

    let params = new URLSearchParams(window.location.search);

    let url = `/cashbox/pdf?${params.toString()}`;

    window.open(url, '_blank');
}

const startDate = "{{ request('start_date') }}";
const endDate = "{{ request('end_date') }}";

const datePicker = flatpickr("#dateRange", {
    mode: "range",
    dateFormat: "Y-m-d",

    defaultDate: (startDate && endDate) ? [startDate, endDate] : null,

    onClose: function(selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
            document.getElementById('start_date').value =
                instance.formatDate(selectedDates[0], "Y-m-d");

            document.getElementById('end_date').value =
                instance.formatDate(selectedDates[1], "Y-m-d");
        }
    }
});
document.getElementById('clearDate').addEventListener('click', function () {
    datePicker.clear();

    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';

    document.getElementById('dateRange').value = '';
});
</script>
@endsection
