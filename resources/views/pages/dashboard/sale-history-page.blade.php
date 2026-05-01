@extends('layout.sidenav-layout')

@section('content')

<style>
.sale-card{
    border:none;
    border-radius:8px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

/* TABLE */
.table{
    font-size:12px;
    margin-bottom:0;
}

.table thead th{
    font-weight:600;
    color:#2a3652;
    background:#f3f4f6;
    border-bottom:2px solid #e5e7eb;
    padding:8px 6px;
    white-space:nowrap;
    font-size:11px;
    letter-spacing:0.3px;
    text-transform: uppercase;
}

.table td{
    vertical-align:middle;
    padding:5px 6px;
    white-space:nowrap;
}

/* TEXT */
.customer-text{
    font-weight:500;
    color:#2c2f36;
    font-size:12px;
}

.mobile-text{
    color:#8a8f98;
    font-size:11px;
}

/* AMOUNT */
.amount{
    color:#111827;
    font-weight:700;
    font-size:12px;
}

/* BADGE */
.badge{
    font-size:10.5px;
    padding:3px 7px;
    font-weight:600;
    border-radius:5px;
}

/* HEADER */
.sale-header{
    border-bottom:1px solid #eee;
    padding-bottom:8px;
    margin-bottom:12px;
}

/* CLEAR BUTTON */
.search-box{
    position:relative;
}

.clear-btn{
    position:absolute;
    right:8px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:14px;
    color:#999;
}

.clear-btn:hover{
    color:#000;
}

/* INPUTS */
.form-control-sm {
    height: 31px;
    line-height: 31px;
    padding-top: 0;
    padding-bottom: 0;
}

.btn-sm {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    line-height: 31px;
}
</style>

<div class="container-fluid">

@if(session('success'))
<div class="alert alert-success shadow-sm auto-hide-alert">
    <i class="bi bi-check-circle me-1"></i>
    {{ session('success') }}
</div>
@endif

<!-- HEADER -->
<div class="row align-items-center mb-3 sale-header">

    <!-- LEFT TITLE -->
    <div class="col-4">
        <h5 class="mb-0 fw-semibold">Sell History</h5>
    </div>

    <!-- RIGHT SIDE (TOTAL + BUTTON) -->
    <div class="col-8 d-flex justify-content-end align-items-center gap-3">

        <!-- TOTAL SALES -->
        <div>
            <span class="text-muted small">Total Sales :</span>
            <span class="fw-bold text-primary ms-1">
                ${{ number_format($totalSales) }}
            </span>
        </div>

        <!-- DOWNLOAD BUTTON -->
        <a href="{{ route('sales.pdf', request()->all()) }}"
       class="card border-0 shadow-sm px-3 py-2 text-decoration-none">

        <div class="d-flex align-items-center gap-2">
            <div class="icon-circle bg-danger-subtle">
                <i class="bi bi-file-earmark-pdf text-danger"></i>
            </div>

            <div>
                <div class="summary-title text-dark">Download PDF</div>
            </div>
        </div>
    </a>

    </div>

</div>
<!-- FILTER -->
<!-- FILTER -->
<div class="card p-2 mb-3">

<form method="GET" id="filterForm">

<div class="row g-2">

<!-- SEARCH (FIXED WITH CLEAR BUTTON) -->
<div class="col-md-3">
    <div class="position-relative">

        <input type="text"
               name="search"
               id="searchInput"
               value="{{ request('search') }}"
               class="form-control form-control-sm"
               placeholder="Search supplier name or phone">

        @if(request('search'))
        <span class="clear-btn" onclick="clearSearch()">×</span>
        @endif

    </div>
</div>

<!-- DATE -->
<div class="col-md-3">
    <div class="position-relative">

        <input type="text"
               id="dateRange"
               class="form-control form-control-sm"
               placeholder="Select date range"
               readonly
               value="{{ request('start_date') && request('end_date')
                    ? request('start_date').' to '.request('end_date')
                    : '' }}">

        @if(request('start_date') || request('end_date'))
        <span class="clear-btn" onclick="clearDateRange()">×</span>
        @endif

    </div>

    <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
    <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
</div>

<!-- BUTTON -->
<div class="col-md-2 d-flex justify-content-end">
    <button class="btn btn-primary btn-sm w-100">
        Apply
    </button>
</div>

</div>

</form>

</div>
<!-- TABLE -->
<div class="card sale-card">

<div class="card-body p-2">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>
<tr>
    <th>Date</th>
<th>Customer</th>
<th>Mobile</th>
<th>Items</th>
<th>Amount</th>
<th>Source</th>
<th>Status</th>
<th class="text-center">Action</th>
</tr>
</thead>

<tbody>

@foreach($sales as $sale)

@php
$status = $sale->source == 'Quick Sell'
? 'Quick Sell'
: (($sale->paid > 0 && $sale->due == 0)
? 'Cash'
: (($sale->paid == 0 && $sale->due > 0)
? 'Due'
: 'Cash+Due'));
@endphp

<tr>

<td class="text-muted">
{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y, h:i A') }}
</td>

<td class="customer-text">
{{ $sale->customer_name ?? '-' }}
</td>

<td class="mobile-text">
{{ $sale->customer_mobile ?? '-' }}
</td>

<td>
@php
    $items = is_string($sale->items)
        ? json_decode($sale->items, true)
        : ($sale->items ?? []);

    $totalQty = 0;

    if (is_array($items)) {
        foreach ($items as $item) {
            $totalQty += (float) ($item['qty'] ?? 0);
        }
    }
@endphp

{{ $totalQty }}
</td>

<td class="amount">
${{ number_format($sale->total ?? 0) }}
</td>

<td>
    @if($sale->source == 'Sell')
        <span class="badge bg-primary">Sell</span>
    @elseif($sale->source == 'Quick Sell')
        <span class="badge bg-info text-dark">Quick Sell</span>
    @elseif($sale->source == 'Condition Sales')
        <span class="badge bg-warning text-dark">Condition</span>
    @else
        <span class="badge bg-secondary">Unknown</span>
    @endif
</td>

<td>
@if($status == 'Cash')
<span class="badge bg-success">Cash</span>
@elseif($status == 'Due')
<span class="badge bg-danger">Due</span>
@elseif($status == 'Cash+Due')
<span class="badge bg-warning text-dark">Cash+Due</span>
@else
<span class="badge bg-info text-dark">Cash</span>
@endif
</td>

<td class="text-center">
<div class="dropdown">
<button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
<i class="bi bi-three-dots"></i>
</button>

<ul class="dropdown-menu dropdown-menu-end">
<li>
<form method="POST" action="/sale-history/delete/{{$sale->id}}">
@csrf
@method('DELETE')

<button class="dropdown-item text-danger">
Delete
</button>

</form>
</li>
</ul>
</div>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>
</div>

<!-- PAGINATION -->
<div class="mt-3 d-flex justify-content-between align-items-center">

<div class="text-muted small">
Showing {{ $sales->firstItem() }} - {{ $sales->lastItem() }} of {{ $sales->total() }}
</div>

<div>
{{ $sales->onEachSide(1)->links() }}
</div>

</div>

</div>

<script>
function clearSearch(){
    document.getElementById('searchInput').value = '';
    document.getElementById('filterForm').submit();
}

function clearDateRange(){
    picker.clear();
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('filterForm').submit();
}

const startDate = "{{ request('start_date') }}";
const endDate = "{{ request('end_date') }}";

const picker = flatpickr("#dateRange", {
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


setTimeout(() => {
    let alert = document.querySelector('.auto-hide-alert');
    if (alert) {
        alert.style.transition = "0.3s ease";
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-10px)";

        setTimeout(() => alert.remove(), 200);
    }
}, 1400);
</script>

@endsection
