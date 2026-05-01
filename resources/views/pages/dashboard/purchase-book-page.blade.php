

@extends('layout.sidenav-layout')

@section('content')

<style>
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
</style>

<div class="container-fluid">

@if(session('success'))
<div class="alert alert-success shadow-sm auto-hide-alert">
    {{ session('success') }}
</div>
@endif

<!-- HEADER -->
<div class="row mb-3 align-items-center">

    <!-- LEFT SIDE -->
    <div class="col-6">
        <h5 class="fw-semibold mb-0">Purchase Book</h5>
    </div>

    <!-- RIGHT SIDE -->
    <div class="col-6 d-flex justify-content-end align-items-center gap-2">

        <!-- TOTAL PURCHASE -->
        <div>
            <span class="text-muted small">Total Purchase :</span>
            <span class="fw-bold text-primary">
                ${{ number_format($totalPurchase) }}
            </span>
        </div>

        <!-- DOWNLOAD PDF BUTTON -->
     <a href="javascript:void(0)"
   onclick="downloadPurchasePdf()"
   class="card border-0 shadow-sm px-3 py-2 text-decoration-none">

            <div class="d-flex align-items-center gap-2">

                <div class="icon-circle bg-danger-subtle">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                </div>

                <div>
                    <div class="summary-title text-dark">
                        Download PDF
                    </div>
                </div>

            </div>
        </a>

    </div>

</div>

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
<div class="card">
<div class="card-body p-2">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>
<tr>
<th>Supplier</th>
<th>Phone</th>
<th>Items</th>
<th>Amount</th>
<th>Paid</th>
<th>Due</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@foreach($purchases as $purchase)

@php
$status = ($purchase->paid_amount > 0 && $purchase->due_amount == 0)
    ? 'Cash'
    : (($purchase->paid_amount == 0 && $purchase->due_amount > 0)
        ? 'Due'
        : 'Cash+Due');
@endphp

<tr>

<td>{{ $purchase->supplier_name ?? '-' }}</td>
<td>{{ $purchase->supplier_phone ?? '-' }}</td>

<td>
@php
    $items = is_string($purchase->items)
        ? json_decode($purchase->items, true)
        : ($purchase->items ?? []);

    $totalQty = 0;

    if (is_array($items)) {
        foreach ($items as $item) {
            $totalQty += (float) ($item['qty'] ?? 0);
        }
    }
@endphp

{{ $totalQty }}
</td>

<td class="fw-bold" style="color:#111827;">
    ${{ number_format($purchase->total_cost ?? 0) }}
</td>

<td>${{ number_format($purchase->paid_amount ?? 0) }}</td>

<td>${{ number_format($purchase->due_amount ?? 0) }}</td>

<td class="text-muted">
{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y, h:i A') }}
</td>

<td>
@if($status == 'Cash')
<span class="badge bg-success">Cash</span>
@elseif($status == 'Due')
<span class="badge bg-danger">Due</span>
@else
<span class="badge bg-warning text-dark">Cash+Due</span>
@endif
</td>

<td>
<div class="dropdown">
<button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
<i class="bi bi-three-dots"></i>
</button>

<ul class="dropdown-menu">
<li>
<form method="POST" action="/purchase-book/delete/{{$purchase->id}}">
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
<div class="mt-3 d-flex justify-content-between">
<div>
Showing {{ $purchases->firstItem() }} - {{ $purchases->lastItem() }}
</div>

<div>
{{ $purchases->links() }}
</div>
</div>

</div>

<!-- SCRIPT -->
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

function downloadPurchasePdf() {

    let params = new URLSearchParams(window.location.search);

    let url = `/purchase/pdf?${params.toString()}`;

    window.open(url, '_blank');
}

setTimeout(() => {
    let alert = document.querySelector('.auto-hide-alert');
    if (alert) {
        alert.style.transition = "0.3s ease";
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-10px)";

        setTimeout(() => alert.remove(), 300);
    }
}, 1500);

</script>

@endsection
