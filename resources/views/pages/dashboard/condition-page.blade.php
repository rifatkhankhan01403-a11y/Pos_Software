@extends('layout.sidenav-layout')

@section('content')

<style>
.table {
    border-collapse: separate;
    border-spacing: 0 8px;
}

.table thead th {
    background: #f1f3f5;
    border: none;
    font-weight: 600;
    font-size: 13px;
}
.table th {
    text-align: center;
}

.table td {
    text-align: center;
    vertical-align: middle;
}

.table tbody tr {
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    border-radius: 8px;
}

.table tbody td {
    border: none;
    vertical-align: middle;
    font-size: 13px;
}

/* row hover */
.table tbody tr:hover {
    background: #f8f9fa;
}
.badge-pending {
    background: #fff3cd;
    color: #856404;
    padding: 4px 10px;
    border-radius: 4px; /* square style */
    font-size: 12px;
    display: inline-block;
    border: 1px solid #ffeeba;
}

.badge-paid {
    background: #d4edda;
    color: #155724;
    padding: 4px 10px;
    border-radius: 4px; /* square style */
    font-size: 12px;
    display: inline-block;
    border: 1px solid #c3e6cb;
}

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



.list-group{
    max-height:200px;
    overflow-y:auto;
    z-index:999;
}

</style>

<div class="page-wrap">

<div class="row mb-3 align-items-center">

    <!-- LEFT -->
    <div class="col-md-6">
        <h6 class="mb-0">Conditional Sales</h6>
    </div>

    <!-- RIGHT -->
    <div class="col-md-6 d-flex justify-content-end gap-2">

        <!-- PDF DOWNLOAD CARD STYLE -->
        <a href="#"
           onclick="downloadCodPdf()"
           class="card border-0 shadow-sm px-3 py-2 text-decoration-none">

            <div class="d-flex align-items-center gap-2">
                <div class="icon-circle bg-danger-subtle">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                </div>

                <div>
                    <div class="text-dark fw-semibold" style="font-size:13px;">
                        Download PDF
                    </div>
                </div>
            </div>
        </a>

    </div>
</div>

<!-- ================= TOP CARDS ================= -->
<div class="row mb-3">

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Pending Sales</h6>
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
<h6>Total Condition Sales</h6>
<h5 id="totalCod">0</h5>
</div>
</div>

<div class="col-md-3 col-6 mb-3">
<div class="stat-card">
<h6>Total Sales Received</h6>
<h5 id="totalReceived">0</h5>
</div>
</div>

</div>

<div class="row">

<!-- ================= LEFT FORM ================= -->
<div class="col-lg-8 mb-3">

<div class="box">

<h6 class="mb-2">Create Sale</h6>

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
<div class="list-group position-absolute w-100 product-list"></div>
</td>

<td><input class="form-control form-control-sm stock" readonly></td>
<td><input class="form-control form-control-sm qty"></td>
<td><input class="form-control form-control-sm price"></td>
<td><input class="form-control form-control-sm total" readonly></td>
<td><button class="btn btn-danger btn-sm remove-row">x</button></td>
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

<label>Today's Date</label>
<input id="date" type="date" class="form-control form-control-sm">

<div class="row mt-2">
    <div class="col-6">
        <label>Note</label>
        <input id="note" class="form-control form-control-sm">
    </div>

    <div class="col-6">
        <label>Paid Date</label>
        <input id="paid_date" type="date" class="form-control form-control-sm">
    </div>
</div>

<hr>

<div class="d-flex justify-content-between">
<span>Total</span>
<strong id="grandTotal">0</strong>
</div>

<div class="d-flex justify-content-between mt-2">
<span>Discount</span>
<input id="discount" class="form-control form-control-sm w-50" placeholder="0">
</div>

<hr>

<div class="d-flex justify-content-between">
<span>Final Amount to Pay</span>
<strong id="finalTotal">0</strong>
</div>

<button class="btn btn-success w-100 mt-3" onclick="confirmSaveCOD()">
    Save Sale
</button>

</div>

</div>

</div>

<!-- ================= SALES TABLE ================= -->
<div class="box mt-4">

<h6>Condition Sales List</h6>

<div class="row mb-2">

    <div class="col-md-3">
        <input id="searchInput" class="form-control form-control-sm" placeholder="Search name / mobile">
    </div>

    <div class="col-md-2">
        <select id="statusFilter" class="form-control form-control-sm">
            <option value="">All (Status)</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
        </select>
    </div>

    <div class="col-md-3">
      <div class="position-relative">
    <input id="filterDate" class="form-control form-control-sm pe-4" placeholder="Select date range">
    <span id="clearDate" style="
        position:absolute;
        right:10px;
        top:50%;
        transform:translateY(-50%);
        cursor:pointer;
        font-weight:bold;
        color:#999;
        display:none;
    ">×</span>
</div>
    </div>

</div>
<div class="table-responsive">

<table class="table table-sm table-striped">

<thead>
<tr>
<th>Date</th>
<th>Customer</th>
<th>Mobile</th>
<th>Qty</th>
<th>Total</th>
<th>Courier</th>
<th>Note</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody id="salesTable"></tbody>

</table>
<div class="mt-3" id="pagination"></div>
</div>

</div>

</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>



<script>

async function confirmSaveCOD(){

    let confirmSave = await Swal.fire({
        title: 'Save Conditional Sale?',
        text: "Do you want to save this sale?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Save',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#198754'
    });

    if(confirmSave.isConfirmed){
        saveCOD();
    }
}



    function cleanNumber(val){
    return Number(val || 0).toFixed(0);
}

    // show/hide clear button
$('#filterDate').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(
        picker.startDate.format('YYYY-MM-DD') + ' to ' +
        picker.endDate.format('YYYY-MM-DD')
    );

    $('#clearDate').show(); // show cross
    loadSales();
});

$('#filterDate').on('cancel.daterangepicker', function() {
    $(this).val('');
    $('#clearDate').hide(); // hide cross
    loadSales();
});

// click clear button
document.getElementById("clearDate").addEventListener("click", function(){
    document.getElementById("filterDate").value = "";
    this.style.display = "none";
    loadSales();
});

$('#filterDate').daterangepicker({
    autoApply: true,
    autoUpdateInput: false, // ✅ KEY FIX
    locale: {
        cancelLabel: 'Clear'
    }
});

$('#filterDate').on('apply.daterangepicker', function(ev, picker) {

    $(this).val(
        picker.startDate.format('YYYY-MM-DD') + ' to ' +
        picker.endDate.format('YYYY-MM-DD')
    );

    loadSales();
});

$('#filterDate').on('cancel.daterangepicker', function() {
    $(this).val('');
    loadSales();
});


document.getElementById("discount").addEventListener("input", updateGrandTotal);
/* ================= CUSTOMER SEARCH ================= */
document.getElementById("customerSearch").addEventListener("keyup", async function(){

    let res = await axios.get("/search-customer", {
        params: { keyword: this.value }
    });

    let list = document.getElementById("customerList");
    list.innerHTML = "";

    res.data.forEach(c => {
        list.innerHTML += `
        <div class="p-2 border bg-white"
        onclick="selectCustomer('${c.name}','${c.mobile}')">
            ${c.name} - ${c.mobile}
        </div>`;
    });

});

function selectCustomer(name, mobile){
    document.getElementById("customerSearch").value = name;
    document.getElementById("customerPhone").value = mobile;
    document.getElementById("customerList").innerHTML = "";
}


/* ================= PRODUCT SEARCH ================= */
document.addEventListener("keyup", async function(e){

    if(e.target.classList.contains("product-search")){

        let input = e.target;

        let res = await axios.get("/search-product", {
            params: { keyword: input.value }
        });

        let list = input.nextElementSibling;
        list.innerHTML = "";

        res.data.forEach(p => {
            list.innerHTML += `
            <div class="p-2 border bg-white"
            onclick="selectProduct(this, ${p.id}, '${p.name}', ${p.quantity}, ${p.price})">
                ${p.name}
            </div>`;
        });
    }
});

function selectProduct(el, id, name, stock, price){

    let row = el.closest("tr");

    let input = row.querySelector(".product-search");
    input.value = name;
    input.dataset.id = id;

    row.querySelector(".stock").value = stock;
    row.querySelector(".price").value = price;

    // reset qty + total when new product selected
    row.querySelector(".qty").value = "";
    row.querySelector(".total").value = "";

    el.parentElement.innerHTML = "";

    updateGrandTotal();
}

/* ================= AUTO CALC ================= */
document.addEventListener("input", function(e){

    // ONLY run inside product table
    if (!e.target.closest("#productBody")) return;

    if (e.target.classList.contains("qty") || e.target.classList.contains("price")) {

        let row = e.target.closest("tr");

        let qty = Number(row.querySelector(".qty").value || 0);
        let price = Number(row.querySelector(".price").value || 0);
        let stock = Number(row.querySelector(".stock").value || 0);

        if (qty > stock) {
            alert("Not enough stock!");
            row.querySelector(".qty").value = stock;
            qty = stock;
        }

        row.querySelector(".total").value = qty * price;

        updateGrandTotal();
    }
});


function updateGrandTotal(){

    let total = 0;

    document.querySelectorAll(".total").forEach(el => {
        total += Number(el.value || 0);
    });

    let discount = Number(document.getElementById("discount").value || 0);

    let final = total - discount;

    if(final < 0) final = 0;

    document.getElementById("grandTotal").innerText = total;
    document.getElementById("finalTotal").innerText = final;
}


/* ================= SAVE COD ================= */
async function saveCOD(){

    let items = [];

    document.querySelectorAll("#productBody tr").forEach(row => {

        let product_id = row.querySelector(".product-search").dataset.id;
        let qty = row.querySelector(".qty").value;
        let price = row.querySelector(".price").value;

        if(product_id && qty){
            items.push({ product_id, qty, price });
        }
    });

    let total = Number(document.getElementById("grandTotal").innerText || 0);
    let discount = Number(document.getElementById("discount").value || 0);
    let final = Number(document.getElementById("finalTotal").innerText || 0);

    let data = {
        customer_name: document.getElementById("customerSearch").value,
        customer_mobile: document.getElementById("customerPhone").value,
        courier: document.getElementById("courier").value,
        note: document.getElementById("note").value,
        invoice_date: document.getElementById("date").value,
 paid_date: document.getElementById("paid_date").value || null, // ✅
        discount: discount,
        total: total,
        paid: final,   // ✅ IMPORTANT: final amount goes to paid

        items: items
    };

    let res = await axios.post("/cod-sale/store", data);

   if(res.data.status){
    successToast('COD Sale Created Successfully');

    resetForm();     // ✅ THIS LINE WAS MISSING
    loadSales();

}else{
    errorToast(res.data.message || "Request Failed!");
}
}

/* ================= ADD ROW ================= */
function addRow(){

    let row = `
    <tr>
        <td class="position-relative">
            <input class="form-control form-control-sm product-search">
            <div class="list-group position-absolute w-100 product-list"></div>
        </td>

        <td><input class="form-control form-control-sm stock" readonly></td>
        <td><input class="form-control form-control-sm qty"></td>
        <td><input class="form-control form-control-sm price"></td>
        <td><input class="form-control form-control-sm total" readonly></td>
        <td><button class="btn btn-danger btn-sm remove-row">x</button></td>
    </tr>`;

    document.getElementById("productBody").insertAdjacentHTML("beforeend", row);
}


/* ================= LOAD SALES ================= */
let currentPage = 1;

document.getElementById("discount").addEventListener("input", updateGrandTotal);

/* ================= LOAD SALES ================= */
async function loadSales(page = 1){

    currentPage = page;

    let search = document.getElementById("searchInput").value;
    let status = document.getElementById("statusFilter").value;
    let date = document.getElementById("filterDate").value;

   let params = {
    page: page,
    search: search,
    status: status,
};

// ✅ DATE RANGE LOGIC
let dateRange = document.getElementById("filterDate").value;

if(dateRange){
    let parts = dateRange.split(" to ");
    params.start_date = parts[0];
    params.end_date = parts[1] || parts[0]; // single date support
}

    let res = await axios.get("/cod-sale/list", {
        params: params
    });

    let tbody = document.getElementById("salesTable");
    tbody.innerHTML = "";

    let pending = 0;
    let total = 0;
    let received = 0;

let rows = res?.data?.data?.data || [];


// ✅ IF NO DATA → SHOW MESSAGE AND STOP
if (rows.length === 0) {

    tbody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center text-muted py-3">
                No data found
            </td>
        </tr>
    `;

    document.getElementById("pendingTotal").innerText = 0;
    document.getElementById("totalCod").innerText = 0;
    document.getElementById("totalReceived").innerText = 0;

    renderPagination({
        current_page: 1,
        last_page: 1
    });

    return;
}


rows.forEach(row => {

let amount = row.due > 0 ? row.due : row.paid;
        total += Number(row.total || 0);
        received += Number(row.paid || 0);
        pending += Number(row.due || 0);

        let statusBadge = row.due > 0
            ? '<span class="badge-pending">Pending</span>'
            : '<span class="badge-paid">Paid</span>';

        let actionBtn = `
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                Action
            </button>

            <ul class="dropdown-menu">
        `;

        // pending → show mark paid
        if (row.due > 0) {
            actionBtn += `
                <li>
                    <a class="dropdown-item text-success mark-paid" data-id="${row.id}">
                        Make Paid
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
            `;
        }

        // always show delete
        actionBtn += `
                <li>
                    <a class="dropdown-item text-danger delete-sale" data-id="${row.id}">
                        Delete
                    </a>
                </li>
            </ul>
        </div>
        `;

        tbody.innerHTML += `
        <tr>
            <td>${formatDate(row.created_at)}</td>
            <td>${row.customer_name}</td>
            <td>${row.customer_mobile}</td>
         <td>${row.qty}</td>
<td>${cleanNumber(amount)}</td>

            <td>${row.courier ?? ''}</td>
            <td>${row.note ?? ''}</td>
            <td>${statusBadge}</td>
            <td>${actionBtn}</td>
        </tr>`;
    });
renderPagination(res.data.data);

   let summary = res?.data?.summary || {};

document.getElementById("pendingTotal").innerText = cleanNumber(summary.pending);
document.getElementById("totalCod").innerText = cleanNumber(summary.total_cod);
document.getElementById("totalReceived").innerText = cleanNumber(summary.total_received);
document.getElementById("receivedToday").innerText = cleanNumber(summary.received_today);
}

document.getElementById("searchInput").addEventListener("keyup", loadSales);
document.getElementById("statusFilter").addEventListener("change", loadSales);
document.getElementById("filterDate").addEventListener("change", loadSales);
//document.getElementById("filterDate").valueAsDate = new Date();
loadSales();
document.getElementById("date").valueAsDate = new Date();
document.addEventListener("click", function(e){

    if(e.target.classList.contains("remove-row")){

        let row = e.target.closest("tr");

        // keep at least 1 row
        if(document.querySelectorAll("#productBody tr").length > 1){
            row.remove();
        }else{
            row.querySelectorAll("input").forEach(input => input.value = "");
        }

        updateGrandTotal();
    }

});
document.addEventListener("click", async function(e){

    if(e.target.classList.contains("delete-sale")){

        let id = e.target.dataset.id;

        if(!confirm("Are you sure to delete this sale?")) return;

        let res = await axios.delete(`/cod-sale/delete/${id}`);

        if(res.data.status){
            successToast("Deleted Successfully");
            loadSales();
        }else{
            errorToast("Delete Failed");
        }
    }

});
function renderPagination(meta){

    let html = `<ul class="pagination justify-content-end mb-0">`;

    let current = meta.current_page;
    let last = meta.last_page;

    // ================= PREV =================
    if(current > 1){
        html += `
        <li class="page-item">
            <a class="page-link page-btn" data-page="${current - 1}">Prev</a>
        </li>`;
    }

    // ================= PAGE RANGE (SMART) =================
    let start = Math.max(1, current - 1);
    let end = Math.min(last, current + 1);

    // if at start, show 1-3
    if(current === 1){
        end = Math.min(3, last);
    }

    // if at end, show last 3 pages
    if(current === last){
        start = Math.max(1, last - 2);
    }

    for(let i = start; i <= end; i++){

        html += `
        <li class="page-item ${current == i ? 'active' : ''}">
            <a class="page-link page-btn" data-page="${i}">
                ${i}
            </a>
        </li>`;
    }

    // ================= NEXT =================
    if(current < last){
        html += `
        <li class="page-item">
            <a class="page-link page-btn" data-page="${current + 1}">Next</a>
        </li>`;
    }

    html += `</ul>`;

    document.getElementById("pagination").innerHTML = html;
}

document.addEventListener("click", function(e){

    let btn = e.target.closest(".page-btn");
    if(!btn) return;

    let page = btn.dataset.page;
    loadSales(page);
});
document.addEventListener("click", async function(e){

    if(e.target.classList.contains("mark-paid")){

        let id = e.target.dataset.id;

        let res = await axios.post(`/cod-sale/mark-paid/${id}`);

        if(res.data.status){
            successToast("Marked as Paid");
            loadSales();
        }else{
            errorToast("Failed");
        }
    }

});

function formatDate(datetime) {
    let d = new Date(datetime);

    let yyyy = d.getFullYear();
    let mm = String(d.getMonth() + 1).padStart(2, '0');
    let dd = String(d.getDate()).padStart(2, '0');

    let hh = String(d.getHours()).padStart(2, '0');
    let min = String(d.getMinutes()).padStart(2, '0');
    let ss = String(d.getSeconds()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd} ${hh}:${min}:${ss}`;
}

function downloadCodPdf() {

    let search = document.getElementById('searchInput').value || '';
    let status = document.getElementById('statusFilter').value || '';

    let start_date = '';
    let end_date = '';

    let dateRange = document.getElementById('filterDate').value;

    if(dateRange){
        let parts = dateRange.split(" to ");
        start_date = parts[0];
        end_date = parts[1] || parts[0];
    }

    let url = `/cod-sale/pdf?search=${search}`
        + `&status=${status}`
        + `&start_date=${start_date}`
        + `&end_date=${end_date}`;

    window.open(url, '_blank');
}

function resetForm(){

    // clear customer
    document.getElementById("customerSearch").value = "";
    document.getElementById("customerPhone").value = "";
    document.getElementById("courier").value = "";
    document.getElementById("note").value = "";

    // reset discount
    document.getElementById("discount").value = "";

    // reset table (keep 1 row)
    document.getElementById("productBody").innerHTML = `
    <tr>
        <td class="position-relative">
            <input class="form-control form-control-sm product-search">
            <div class="list-group position-absolute w-100 product-list"></div>
        </td>
        <td><input class="form-control form-control-sm stock" readonly></td>
        <td><input class="form-control form-control-sm qty"></td>
        <td><input class="form-control form-control-sm price"></td>
        <td><input class="form-control form-control-sm total" readonly></td>
        <td><button class="btn btn-danger btn-sm remove-row">x</button></td>
    </tr>`;



    // reset totals
    document.getElementById("paid_date").value = "";
    document.getElementById("grandTotal").innerText = 0;
    document.getElementById("finalTotal").innerText = 0;

    // reset date
    document.getElementById("date").valueAsDate = new Date();
}


</script>

@endsection
