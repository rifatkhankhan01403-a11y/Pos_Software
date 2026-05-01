{{-- @extends('layout.sidenav-layout')

@section('content')

<style>

.page-wrap{
    padding:15px;
}

/* Cards */
.stat-card{
    border-radius:12px;
    padding:12px;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.stat-card h6{
    font-size:12px;
    color:#888;
}

.stat-card h4{
    margin:0;
    font-weight:600;
}

/* Form box */
.box{
    background:#fff;
    border-radius:10px;
    padding:18px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.form-control{
    border-radius:6px;
}

.btn{
    border-radius:6px;
}

/* Right summary */
.summary-box{
    background:#fafafa;
    border-radius:10px;
    padding:15px;
}

/* Status badge */
.badge-pending{
    background:#fff3cd;
    color:#856404;
    padding:5px 10px;
    border-radius:20px;
}

.badge-paid{
    background:#d4edda;
    color:#155724;
    padding:5px 10px;
    border-radius:20px;
}

</style>

<div class="page-wrap">

<h6 class="mb-1">Conditional Sales (COD)</h6>

<!-- TOP CARDS -->
<div class="row mb-3">

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Pending COD</h6>
<h5>85,000</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Received Today</h6>
<h5>25,000</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Total COD</h6>
<h5>245,000</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Total Received</h6>
<h5>160,000</h5>
</div>
</div>

</div>




<div class="row">

<!-- LEFT FORM -->
<div class="col-lg-8 mb-3">

<div class="box">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">

    <h6 class="mb-1">Create Sale</h6>

    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm">
            + Add Customer
        </button>

        <button class="btn btn-outline-success btn-sm">
            + Add Product
        </button>
    </div>

</div>

<!-- row 1 -->
<div class="row g-2">

<div class="col-md-4">
<label>Customer</label>
<input class="form-control" placeholder="Search name / phone">
</div>

<div class="col-md-4">
<label>Phone</label>
<input class="form-control">
</div>

<div class="col-md-4">
<label>Courier</label>
<input class="form-control">
</div>

</div>

<!-- PRODUCT TABLE -->
<div class="mt-3">

<table class="table table-sm table-bordered">

<thead>
<tr>
<th>Product</th>
<th width="80">Stock</th>
<th width="105">Qty</th>
<th width="125">Price</th>
<th width="125">Total</th>
<th width="10"></th>
</tr>
</thead>

<tbody>

<tr>
<td><input class="form-control form-control-sm" placeholder="Search product"></td>
<td><input class="form-control form-control-sm" value="120" readonly></td>
<td><input class="form-control form-control-sm"></td>
<td><input class="form-control form-control-sm"></td>
<td><input class="form-control form-control-sm" readonly></td>
<td><button class="btn btn-danger btn-sm">x</button></td>
</tr>

</tbody>

</table>

<button class="btn btn-outline-primary btn-sm">
+ Add
</button>

</div>

</div>

</div>


<!-- SUMMARY -->
<div class="col-lg-4">

<div class="summary-box">

<h6>Summary</h6>

<!-- DATE -->
<div class="mt-1">
<label>Date</label>
<input type="date" class="form-control form-control-sm">
</div>

<!-- PAYABLE DATE + NOTE (SAME ROW) -->
<div class="row mt-1 g-2">

<div class="col-5">
<label>Payable Date</label>
<input type="date" class="form-control form-control-sm">
</div>

<div class="col-7">
<label>Note</label>
<input type="text" class="form-control form-control-sm" placeholder="Short note">
</div>

</div>

<hr>

<!-- TOTAL -->
<div class="d-flex justify-content-between">
<span>Total</span>
<strong>0.00</strong>
</div>

<!-- COURIER -->
<div class="d-flex justify-content-between mt-2 align-items-center">
<span>Courier</span>
<input class="form-control form-control-sm w-50">
</div>

<!-- DISCOUNT -->
<div class="d-flex justify-content-between mt-2 align-items-center">
<span>Discount</span>
<input class="form-control form-control-sm w-50">
</div>

<hr>

<!-- FINAL -->
<div class="d-flex justify-content-between mt-1">
<span>Final Amount</span>
<strong class="text-primary">0.00</strong>
</div>

<!-- STATUS -->
<div class="mt-2">
<span class="badge-pending">Pending</span>
</div>

<!-- BUTTONS -->
<div class="mt-3 d-flex gap-2">
<button class="btn btn-secondary w-50 btn-sm">Reset</button>
<button class="btn btn-success w-50 btn-sm">Save</button>
</div>

</div>

</div>

</div>


<!-- TABLE -->
<div class="box mt-3">

<h6 class="mb-2">Sales List</h6>

<!-- FILTER -->
<div class="row g-2 mb-2">

<div class="col-md-3">
<input class="form-control form-control-sm" placeholder="Search name / phone">
</div>

<div class="col-md-2">
<select class="form-control form-control-sm">
<option>All</option>
<option>Pending</option>
<option>Paid</option>
</select>
</div>

<div class="col-md-2">
<input type="date" class="form-control form-control-sm">
</div>

</div>


<div class="table-responsive">

<table class="table table-sm table-striped align-middle">

<thead>
<tr>
<th>Date</th>
<th>Customer</th>
<th>Mobile</th>
<th>Qty</th>
<th>Price</th>
<th>Note</th>
<th>Courier</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<!-- PENDING ROW -->
<tr>
<td>20 May 2026</td>
<td>Rahim</td>
<td>017XXXXXXXX</td>
<td>200</td>
<td>40,000</td>
<td>Urgent delivery</td>
<td>Pathao</td>

<td>
<span class="badge-pending">Pending</span>
</td>

<td>
<button class="btn btn-success btn-sm">
Mark Paid
</button>
</td>

</tr>

<!-- PAID ROW -->
<tr>
<td>19 May 2026</td>
<td>Karim</td>
<td>018XXXXXXXX</td>
<td>120</td>
<td>25,000</td>
<td>-</td>
<td>Sundarban</td>

<td>
<span class="badge-paid">Paid</span>
</td>

<td class="text-muted">
—
</td>

</tr>

</tbody>

</table>

</div>

</div>

</div>

@endsection --}}


@extends('layout.sidenav-layout')

@section('content')

<style>

.page-wrap{ padding:15px; }

.stat-card{
    border-radius:12px;
    padding:12px;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.stat-card h6{ font-size:12px; color:#888; }
.stat-card h4{ margin:0; font-weight:600; }

.box{
    background:#fff;
    border-radius:10px;
    padding:18px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.summary-box{
    background:#fafafa;
    border-radius:10px;
    padding:15px;
}

.badge-pending{
    background:#fff3cd;
    color:#856404;
    padding:5px 10px;
    border-radius:20px;
}

.badge-paid{
    background:#d4edda;
    color:#155724;
    padding:5px 10px;
    border-radius:20px;
}

.list-group{
    max-height:200px;
    overflow-y:auto;
}

</style>

<div class="page-wrap">

<h6 class="mb-3">Conditional Sales (COD + POS SYSTEM)</h6>

<!-- ================= TOP CARDS ================= -->
<div class="row mb-3">

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Pending COD</h6>
<h5 id="pendingTotal">0</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Received Today</h6>
<h5 id="receivedToday">0</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Total COD</h6>
<h5 id="totalCod">0</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Total Received</h6>
<h5 id="totalReceived">0</h5>
</div>
</div>

</div>

<div class="row">

<!-- ================= LEFT FORM ================= -->
<div class="col-lg-8 mb-3">

<div class="box">

<h6 class="mb-2">Create Sale (COD POS)</h6>

<!-- CUSTOMER -->
<div class="row g-2 mb-2">

<div class="col-md-4 position-relative">
<label>Customer</label>
<input id="customerSearch" class="form-control" placeholder="Search name / phone">
<div id="customerList" class="list-group position-absolute w-100"></div>
</div>

<div class="col-md-4">
<label>Phone</label>
<input id="customerPhone" class="form-control">
</div>

<div class="col-md-4">
<label>Courier</label>
<input id="courier" class="form-control">
</div>

</div>

<!-- PRODUCT TABLE -->
<div class="mt-3">

<table class="table table-sm table-bordered">

<thead>
<tr>
<th>Product</th>
<th>Stock</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
<th></th>
</tr>
</thead>

<tbody id="productBody">

<tr>
<td class="position-relative">
<input class="form-control form-control-sm product-search">
<div class="list-group position-absolute w-100"></div>
</td>

<td><input class="form-control form-control-sm stock" readonly></td>
<td><input class="form-control form-control-sm qty"></td>
<td><input class="form-control form-control-sm price"></td>
<td><input class="form-control form-control-sm total" readonly></td>
<td><button class="btn btn-danger btn-sm">x</button></td>
</tr>

</tbody>

</table>

<button class="btn btn-outline-primary btn-sm" onclick="addRow()">+ Add</button>

</div>

</div>

</div>

<!-- ================= SUMMARY ================= -->
<div class="col-lg-4">

<div class="summary-box">

<label>Date</label>
<input id="date" type="date" class="form-control form-control-sm">

<label class="mt-2">Note</label>
<input id="note" class="form-control form-control-sm">

<hr>

<div class="d-flex justify-content-between">
<span>Total</span>
<strong id="grandTotal">0</strong>
</div>

<div class="d-flex justify-content-between mt-2">
<span>Discount</span>
<input id="discount" class="form-control form-control-sm w-50">
</div>

<hr>

<div class="d-flex justify-content-between">
<span>Final</span>
<strong id="finalTotal">0</strong>
</div>

<button class="btn btn-success w-100 mt-3" onclick="saveCOD()">Save COD</button>

</div>

</div>

</div>

<!-- ================= SALES TABLE (ALL DATA FROM DB) ================= -->
<div class="box mt-4">

<h6>Sales List (COD + Others)</h6>

<select id="filterSource" class="form-control form-control-sm w-25 mb-2" onchange="loadSales()">
<option value="">All</option>
<option value="COD">COD</option>
<option value="Sell">Sell</option>
<option value="Quick Sell">Quick Sell</option>
</select>

<div class="table-responsive">

<table class="table table-sm table-striped">

<thead>
<tr>
<th>Date</th>
<th>Customer</th>
<th>Mobile</th>
<th>Total</th>
<th>Paid</th>
<th>Due</th>
<th>Source</th>
<th>Status</th>
</tr>
</thead>

<tbody id="salesTable"></tbody>

</table>

</div>

</div>

</div>

<script>

/* ================= LOAD SALES ================= */
async function loadSales(){

    let source = document.getElementById("filterSource").value;

    let res = await axios.get("/cod-sale/list", {
        params: { source: source }
    });

    let tbody = document.getElementById("salesTable");
    tbody.innerHTML = "";

    let pending = 0;
    let total = 0;
    let received = 0;

    res.data.forEach(row => {

        total += Number(row.total || 0);
        received += Number(row.paid || 0);
        pending += Number(row.due || 0);

        tbody.innerHTML += `
        <tr>
            <td>${row.invoice_date ?? ''}</td>
            <td>${row.customer_name ?? ''}</td>
            <td>${row.customer_mobile ?? ''}</td>
            <td>${row.total}</td>
            <td>${row.paid}</td>
            <td>${row.due}</td>
            <td>${row.source}</td>
            <td>
                ${row.due > 0 ? '<span class="badge-pending">Pending</span>' : '<span class="badge-paid">Paid</span>'}
            </td>
        </tr>`;
    });

    document.getElementById("pendingTotal").innerText = pending;
    document.getElementById("totalCod").innerText = total;
    document.getElementById("totalReceived").innerText = received;
}

loadSales();

/* ================= SAVE COD ================= */
async function saveCOD(){

    let items = [];

    document.querySelectorAll("#productBody tr").forEach(row => {

        let product = row.querySelector(".product-search").value;
        let qty = row.querySelector(".qty").value;
        let price = row.querySelector(".price").value;

        if(product){
            items.push({ product, qty, price });
        }
    });

    let data = {
        customer_name: document.getElementById("customerSearch").value,
        customer_mobile: document.getElementById("customerPhone").value,
        courier: document.getElementById("courier").value,
        note: document.getElementById("note").value,
        items: items
    };

    let res = await axios.post("/cod-sale/store", data);

    alert(res.data.message);

    if(res.data.status){
        loadSales();
    }
}

/* ================= ADD ROW ================= */
function addRow(){

    let row = `
    <tr>
        <td class="position-relative">
            <input class="form-control form-control-sm product-search">
            <div class="list-group position-absolute w-100"></div>
        </td>

        <td><input class="form-control form-control-sm stock" readonly></td>
        <td><input class="form-control form-control-sm qty"></td>
        <td><input class="form-control form-control-sm price"></td>
        <td><input class="form-control form-control-sm total" readonly></td>
        <td><button class="btn btn-danger btn-sm">x</button></td>
    </tr>`;

    document.getElementById("productBody").insertAdjacentHTML("beforeend", row);
}

</script>

@endsection
