@extends('layout.sidenav-layout')

@section('content')

<style>
button[data-bs-target="#newDueModal"]{
    display:none !important;
}

.summary-card{
    border-radius:10px;
    padding:12px 16px;
}
.active-party {
    background: #ed3170 !important;  /* very soft pink */
    border-left: 4px solid #443539 !important;
    border-radius: 6px;
}

/* keep text NORMAL black */
.active-party strong,
.active-party small,
.active-party span {
    color: #212529 !important; /* bootstrap normal black */
}

/* optional: slight emphasis only on left border glow */
.active-party {
    box-shadow: inset 0 0 0 1px rgba(255, 122, 162, 0.15);
}

.party-list{
    max-height:520px;
    overflow-y:auto;
}

.table td,.table th{
    padding:10px;
}
.party-list .list-group-item{
    border-left:0;
    border-right:0;
}

.party-list strong{
    font-size:14px;
}

.party-list small{
    font-size:12px;
}

</style>

<div class="container-fluid py-2">

<!-- HEADER -->
<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="fw-bold mb-0">Due Book</h4>

    <div class="d-flex gap-2">


        <!-- NEW DUE BUTTON -->
     <button class="btn btn-primary btn-sm d-flex align-items-center"
        data-bs-toggle="modal"
        data-bs-target="#newDueModal">
    <i class="bi bi-plus-lg me-2"></i> New Due
</button>

    </div>

</div>


<!-- SUMMARY CARDS -->
<div class="row g-3 mb-3">

<div class="col-md-4">

<div class="card shadow-sm summary-card">

<div class="d-flex justify-content-between align-items-center">

<div>
<small class="text-muted">Receivable (Customer)</small>
<h5 class="mb-0 text-success">
    ${{ number_format($receivable) }}
</h5>
</div>

<i class="bi bi-people fs-3 text-success"></i>

</div>

</div>

</div>


<div class="col-md-4">

<div class="card shadow-sm summary-card">

<div class="d-flex justify-content-between align-items-center">

<div>
<small class="text-muted">Payable (Supplier)</small>
<h5 class="mb-0 text-danger">
    ${{ number_format($payable) }}
</h5>
</div>

<i class="bi bi-truck fs-3 text-danger"></i>

</div>

</div>

</div>


<div class="col-md-4">

<div class="card shadow-sm summary-card">

<div class="d-flex justify-content-between align-items-center">

<div>
<small class="text-muted">Net Balance</small>
<h5 class="mb-0
    {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
    ${{ number_format($net) }}
</h5>
</div>

<i class="bi bi-cash-stack fs-3 text-primary"></i>

</div>

</div>

</div>

</div>



<!-- FILTER + TABS -->
<div class="card shadow-sm border-0 p-3 mb-3">

<div class="row align-items-center g-2">

<!-- LEFT: SWITCH -->
<div class="col-md-4 d-flex align-items-center">

<div class="btn-group w-100" style="height: 31px;">

<button id="customerBtn"
class="btn btn-primary btn-sm h-100 d-flex align-items-center justify-content-center">
Customers
</button>

<button id="supplierBtn"
class="btn btn-outline-secondary btn-sm h-100 d-flex align-items-center justify-content-center">
Suppliers
</button>

</div>

</div>


<!-- RIGHT: FILTERS -->
<div class="col-md-8">

<div class="row g-2 align-items-center">

<div class="col-md-4 position-relative">
    <input
        type="text"
        id="phoneSearch"
        class="form-control form-control-sm pe-4"
        placeholder="search phone number">

    <i class="bi bi-x-circle-fill position-absolute top-50 end-0 translate-middle-y me-2 text-muted"
       id="clearPhone"
       style="cursor:pointer; display:none;"></i>
</div>

<div class="col-md-4 position-relative">
    <input type="text"
           id="dateRange"
           class="form-control form-control-sm py-2"
           style="height: 38px;"
           placeholder="Select date range">

    <i class="bi bi-x-circle-fill position-absolute top-50 end-0 translate-middle-y me-2 text-muted"
       id="clearDate"
       style="cursor:pointer; display:none;"></i>
</div>

{{-- <div class="col-md-3">
<select class="form-select form-select-sm">
<option>Status</option>
<option>Due</option>
<option>Paid</option>
</select>
</div> --}}

<div class="col-md-2">
<div class="btn-group w-100" style="height: 31px;">
<button id="applyFilterBtn"
class="btn btn-primary btn-sm h-100 d-flex align-items-center justify-content-center">
Apply
</button>

</div>

</div>
</div>
</div>

</div>

</div>


<div class="row">

<!-- LEFT PARTY LIST (SMALLER) -->
<div class="col-md-3">

    <div class="card shadow-sm h-100">

        <div class="card-header bg-light fw-semibold py-2">
            <span id="partyTitle">Customers</span>
        </div>

        <div class="list-group list-group-flush party-list" id="partyList">
            <!-- dynamic list will load here -->
        </div>

    </div>

</div>




<!-- LEDGER (BIGGER AREA) -->
<div class="col-md-9">

<div class="card shadow-sm h-100">

<div class="card-body d-flex flex-column">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-2">

<div>
<h5 class="mb-0" id="ledgerName">Select a Customer</h5>
<small class="text-muted" id="ledgerType">---</small>
<small class="text-muted" id="ledgerMobile"></small>
</div>

<h6 class="mb-0 fw-semibold small text-success" id="ledgerBalanceTop">
    Due Status: None
</h6>
</div>


<!-- TABLE (EXPANDS) -->
<div class="table-responsive flex-grow-1">

<table class="table table-sm align-middle">

<thead class="tabled-light">
<tr>
<th>Date</th>
<th>Payable</th>
<th>Paid</th>
<th>Due</th>
</tr>
</thead>

<tbody id="ledgerBody">
<tr>
    <td colspan="4" class="text-center text-muted py-3">
        Select a customer/supplier to view ledger
    </td>
</tr>
</tbody>

</table>

</div>


<!-- FOOTER -->
<div class="mt-2">

<div class="d-flex justify-content-between small">

<span>
Payable: <strong class="text-primary" id="totalPayable">0</strong>
</span>

<span>
Paid: <strong class="text-success" id="totalPaid">0</strong>
</span>

<span>
Due: <strong class="text-danger" id="totalDue">0</strong>
</span>

</div>


<div class="d-flex mt-3">

<button id="btnAddGiven" class="btn btn-danger w-50 me-2 btn-sm">
    Add Given
</button>

<button id="btnAddReceived" class="btn btn-success w-50 btn-sm">
    Add Received
</button>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<!-- NEW DUE MODAL -->
<div class="modal fade" id="newDueModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">New Due Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="dueForm">

<div class="row g-3">

    <!-- DATE -->
    <div class="col-md-4">
        <label>Date</label>
        <input type="date" name="date" id="dueDate" class="form-control form-control-sm">
    </div>

    <!-- TAKEN BY -->
    <div class="col-md-4">
        <label>Due Taken By</label>
        <select name="taken_by" class="form-select form-select-sm">
            <option value="Customer">Customer</option>
            <option value="Supplier">Supplier</option>
           {{-- <option value="Employee">Employee</option> --}}
        </select>
    </div>

    <!-- TYPE -->
    <div class="col-md-4">
        <label>Type</label>
        <select name="type" class="form-select form-select-sm">
            <option value="Due Given">Due Given</option>
            <option value="Due Taken">Due Recieved</option>
        </select>
    </div>

      <!-- AMOUNT -->
    <div class="col-md-4">
        <label>Due Amount *</label>
        <input type="text" name="amount" class="form-control form-control-sm" required>
    </div>


    <!-- NAME -->
    <div class="col-md-4">
        <label>Name</label>
        <input type="text" name="name" class="form-control form-control-sm">
    </div>

    <!-- MOBILE -->
    <div class="col-md-4">
        <label>Mobile</label>
        <input type="text" name="mobile" class="form-control form-control-sm re">
    </div>


    <!-- REPAYMENT DATE -->
    <div class="col-md-4">
        <label>Repayment Date</label>
        <input type="date" name="repayment_date" class="form-control form-control-sm">
    </div>

    <!-- NOTE -->
    <div class="col-md-8">
        <label>Note</label>
        <input type="text" name="note" class="form-control form-control-sm">
    </div>

</div>

</form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm">Save Due</button>
            </div>

        </div>
    </div>
</div>



{{-- //add given modal --}}
<div class="modal fade" id="givenDueModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Given Due</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="givenDueForm">

                    <input type="hidden" name="type" value="Due Given">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control form-control-sm given-date">
                        </div>

                        <div class="col-md-4">
                            <label>Due Taken By</label>
                            <select name="taken_by" class="form-select form-select-sm">
                                <option value="Customer">Customer</option>
                                <option value="Supplier">Supplier</option>
                            </select>
                        </div>



                        <div class="col-md-4">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control form-control-sm">
                        </div>
                         <div class="col-md-4">
                            <label>Amount</label>
                            <input type="text" name="amount" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control form-control-sm">
                        </div>



                        <div class="col-md-4">
                            <label>Note</label>
                            <input type="text" name="note" class="form-control form-control-sm">
                        </div>

                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm" id="saveGivenBtn">Save Given</button>
            </div>

        </div>
    </div>
</div>
{{--
add recived modal --}}
<div class="modal fade" id="receivedDueModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Received Due</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="receivedDueForm">

                    <input type="hidden" name="type" value="Due Recieved">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control form-control-sm received-date">
                        </div>

                        <div class="col-md-4">
                            <label>Due Taken By</label>
                            <select name="taken_by" class="form-select form-select-sm">
                                <option value="Customer">Customer</option>
                                <option value="Supplier">Supplier</option>
                            </select>
                        </div>
                         <div class="col-md-4">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-4">
                            <label>Amount</label>
                            <input type="text" name="amount" class="form-control form-control-sm" required>
                        </div>



                        <div class="col-md-4">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control form-control-sm">
                        </div>


                        <div class="col-md-4">
                            <label>Note</label>
                            <input type="text" name="note" class="form-control form-control-sm">
                        </div>

                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-sm" id="saveReceivedBtn">Save Received</button>
            </div>

        </div>
    </div>
</div>




{{-- js part --}}

<script>
let currentPage = 1;
let searchValue = "";
let currentType = "customer";

let startDate = null;
let endDate = null;

const phoneInput = document.getElementById("phoneSearch");
const clearPhone = document.getElementById("clearPhone");

const dateInput = document.getElementById("dateRange");
const clearDate = document.getElementById("clearDate");

phoneInput.addEventListener("input", function () {
    searchValue = this.value;
    clearPhone.style.display = this.value ? "block" : "none";

    clearTimeout(this.timer);
    this.timer = setTimeout(() => {
        currentPage = 1;
        loadParty(currentType, 1);
    }, 400);
});

clearPhone.addEventListener("click", function () {
    phoneInput.value = "";
    searchValue = "";
    this.style.display = "none";

    currentPage = 1;

    loadParty(currentType, 1); // ✅ auto refresh default list
});
// =========================
// DATE RANGE PICKER
// =========================
flatpickr("#dateRange", {
    mode: "range",
    dateFormat: "Y-m-d",

    onChange: function(selectedDates) {

        if (selectedDates.length === 2) {
            startDate = selectedDates[0].toISOString().split("T")[0];
            endDate = selectedDates[1].toISOString().split("T")[0];
            clearDate.style.display = "block";
        } else {
            startDate = null;
            endDate = null;
            clearDate.style.display = "none";
        }

        currentPage = 1;
        loadParty(currentType, 1);
    }
});

clearDate.addEventListener("click", function () {
    dateInput._flatpickr.clear();

    startDate = null;
    endDate = null;
    this.style.display = "none";

    currentPage = 1;

    loadParty(currentType, 1); // ✅ auto reset results
});

document.addEventListener("DOMContentLoaded", function () {

    let today = new Date().toISOString().split('T')[0];

    let ledgerCache = {};
    let modalMode = "new";
let selectedCustomer = null;
    let partyRequestId = 0;


    // =========================
    // APPLY FILTER
    // =========================
    document.getElementById("applyFilterBtn").addEventListener("click", function () {

        searchValue = document.querySelector("input[placeholder='search phone number']").value;

        currentPage = 1;

        loadParty(currentType, 1);
    });

    // =========================
    // LIVE SEARCH
    // =========================
    document.querySelector("input[placeholder='search phone number']")
    .addEventListener("input", function () {

        searchValue = this.value;

        clearTimeout(this.timer);

        this.timer = setTimeout(() => {
            currentPage = 1;
            loadParty(currentType, 1);
        }, 400);
    });

    // =========================
    // OPEN QUICK MODAL
    // =========================
    function openQuickDueModal(mode, customer) {

        let form = document.getElementById("dueForm");
        form.reset();

        document.getElementById("dueDate").value = today;

        form.name.value = customer.name || "";
        form.mobile.value = customer.mobile || "";

        const typeValue = (mode === "given") ? "Due Given" : "Due Recieved";

        form.type.value = typeValue;

        form.taken_by.value = customer.type === "supplier" ? "Supplier" : "Customer";

        new bootstrap.Modal(document.getElementById("newDueModal")).show();
    }

    // =========================
    // NEW DUE BUTTON
    // =========================
    document.querySelector("[data-bs-target='#newDueModal']")
    .addEventListener("click", function () {

        modalMode = "new";

        let form = document.getElementById("dueForm");
        form.reset();

        document.getElementById("dueDate").value = today;

        form.name.value = "";
        form.mobile.value = "";
        form.type.value = "Due Given";
    });

    // =========================
    // OPEN GIVEN MODAL
    // =========================
    function openGivenModal(customer) {

        let form = document.getElementById("givenDueForm");
        form.reset();

        document.querySelector("#givenDueModal input[type='date']").value = today;

        form.name.value = customer.name || "";
        form.mobile.value = customer.mobile || "";

        form.taken_by.value = customer.type === "supplier" ? "Supplier" : "Customer";

        new bootstrap.Modal(document.getElementById("givenDueModal")).show();
    }

    // =========================
    // OPEN RECEIVED MODAL
    // =========================
    function openReceivedModal(customer) {

        let form = document.getElementById("receivedDueForm");
        form.reset();

        document.querySelector("#receivedDueModal input[type='date']").value = today;

        form.name.value = customer.name || "";
        form.mobile.value = customer.mobile || "";

        form.taken_by.value = customer.type === "supplier" ? "Supplier" : "Customer";

        new bootstrap.Modal(document.getElementById("receivedDueModal")).show();
    }

    // =========================
    // CLEAR UI
    // =========================
    function clearLedgerUI(mode = "No Data") {

        document.getElementById("ledgerName").innerText =
            mode === "empty" ? "No Data Found" : "Loading...";

        document.getElementById("ledgerType").innerText = "";
        document.getElementById("ledgerMobile").innerText = "";

      let el = document.getElementById("ledgerBalanceTop");
el.innerText = "Due Status: None";
el.classList.remove("text-danger");
el.classList.add("text-success");

        const tbody = document.getElementById("ledgerBody");

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-3">
                    ${mode === "empty" ? "No data found" : "Loading"}
                </td>
            </tr>
        `;
    document.getElementById("totalPayable").innerText = "0";
        document.getElementById("totalPaid").innerText = "0";
        document.getElementById("totalDue").innerText = "0";
     //   document.getElementById("totalBalance").innerText = "0";
    }

    // =========================
    // RENDER LEDGER
    // =========================
 function renderLedger(data) {

    document.getElementById("ledgerName").innerText = data.name ?? "Unknown";
    document.getElementById("ledgerType").innerText = data.type ?? "";

    document.getElementById("ledgerMobile").innerText =
        data.mobile ? `Mobile: ${data.mobile}` : "Mobile: N/A";

    let tbody = document.getElementById("ledgerBody");

    if (!Array.isArray(data.transactions) || data.transactions.length === 0) {
        clearLedgerUI("empty");
        return;
    }

    let rows = "";

    for (let t of data.transactions) {
        rows += `
            <tr>
                <td>${t.date}</td>
                <td><span class="badge bg-primary">${t.payable}</span></td>
                <td><span class="badge bg-success">${t.paid}</span></td>
                <td><span class="badge bg-danger">${t.due}</span></td>
            </tr>
        `;
    }

    tbody.innerHTML = rows;

let payable = data.total_payable ?? 0;
let paid = data.total_paid ?? 0;
let due = payable - paid;

let el = document.getElementById("ledgerBalanceTop");

if(due > 0){
    el.innerText = "Due Status: Have";
    el.classList.remove("text-success");
    el.classList.add("text-danger");
}else{
    el.innerText = "Due Status: None";
    el.classList.remove("text-danger");
    el.classList.add("text-success");
}
document.getElementById("totalPayable").innerText = payable;
document.getElementById("totalPaid").innerText = paid;

// show Have / None instead of number
document.getElementById("totalDue").innerText = due;
    // optional if you want payable display
}
    // =========================
    // SAVE DUE (MAIN MODAL)
    // =========================
    document.querySelector(".modal-footer .btn-primary").addEventListener("click", async function () {

        let form = document.getElementById("dueForm");

        let amount = form.amount.value.trim();
        let name = form.name.value.trim();
        let mobile = form.mobile.value.trim();

        if (!amount) return errorToast("Amount is required !");
        if (!name) return errorToast("Name is required !");
        if (!mobile) return errorToast("Mobile is required !");
        if (isNaN(amount)) return errorToast("Amount must be a number !");

        let res = await fetch("{{ route('due.store') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: new FormData(form)
        });

        if (!res.ok) {
            errorToast("Server error");
            return;
        }

        let data = await res.json();

        if (data.status) {

            successToast(data.message);

            let modal = bootstrap.Modal.getInstance(
                document.getElementById('newDueModal')
            );

            modal.hide();
            form.reset();

            loadParty(currentType);

        } else {
            errorToast(data.error || "Failed to save due");
        }
    });

    // =========================
    // SAVE GIVEN
    // =========================
    document.getElementById("saveGivenBtn").addEventListener("click", async function () {

        let form = document.getElementById("givenDueForm");

        let amount = form.amount.value.trim();
        let name = form.name.value.trim();
        let mobile = form.mobile.value.trim();

        if (!amount) return errorToast("Amount is required !");
        if (!name) return errorToast("Name is required !");
        if (!mobile) return errorToast("Mobile is required !");
        if (isNaN(amount)) return errorToast("Amount must be a number !");

        let res = await fetch("{{ route('due.store') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: new FormData(form)
        });

        let data = await res.json();

        if (data.status) {

            successToast(data.message);

            bootstrap.Modal.getInstance(
                document.getElementById('givenDueModal')
            ).hide();

            loadParty(currentType);

        } else {
            errorToast(data.error || "Failed");
        }
    });

    // =========================
    // SAVE RECEIVED
    // =========================
    document.getElementById("saveReceivedBtn").addEventListener("click", async function () {

        let form = document.getElementById("receivedDueForm");

        let amount = form.amount.value.trim();
        let name = form.name.value.trim();
        let mobile = form.mobile.value.trim();

        if (!amount) return errorToast("Amount is required !");
        if (!name) return errorToast("Name is required !");
        if (!mobile) return errorToast("Mobile is required !");
        if (isNaN(amount)) return errorToast("Amount must be a number !");

        let formData = new FormData(form);
        formData.set("type", "Due Taken");

        let res = await fetch("{{ route('due.store') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: formData
        });

        let data = await res.json();

        if (data.status) {

            successToast(data.message);

            bootstrap.Modal.getInstance(
                document.getElementById('receivedDueModal')
            ).hide();

            loadParty(currentType);

        } else {
            errorToast(data.error || "Failed");
        }
    });

    // =========================
    // LOAD PARTY
    // =========================
    async function loadParty(type, page = 1) {

        const requestId = ++partyRequestId;

        let url = `/party-list?type=${type}&page=${page}&search=${searchValue}`;

        if (startDate && endDate) {
            url += `&start_date=${startDate}&end_date=${endDate}`;
        }

        const res = await fetch(url);

        if (!res.ok) {
            errorToast("Failed to load data");
            return;
        }

        const result = await res.json();

        if (requestId !== partyRequestId) return;

        let data = result.data;

        let partyList = document.getElementById("partyList");

        partyList.innerHTML = "";

        document.getElementById("partyTitle").innerText =
            type === "customer" ? "Customers" : "Suppliers";

        document.querySelectorAll(".active-party").forEach(el => {
            el.classList.remove("active-party");
        });

        if (!data || data.length === 0) {

            partyList.innerHTML = `
                <div class="text-center text-muted py-3">
                    No ${type} found
                </div>
            `;

            clearLedgerUI();
            return;
        }

        let firstItem = null;

        data.forEach((p) => {

            let el = document.createElement("a");
            el.href = "#";
            el.className = "list-group-item py-2 party-item";

            el.dataset.name = p.name;
            el.dataset.mobile = p.mobile;
            el.dataset.type = p.type;

          el.innerHTML = `
<div>
    <strong class="d-block">${p.name}</strong>
    <small class="text-muted">${p.mobile}</small>
</div>
`;

            el.addEventListener("click", async function (e) {
                e.preventDefault();

                document.querySelectorAll(".party-item")
                    .forEach(i => i.classList.remove("active-party"));

                el.classList.add("active-party");
selectedCustomer = {
    name: p.name,
    mobile: p.mobile,
    type: p.type
};
                clearLedgerUI();

                const res2 = await fetch(
                    `/party-ledger?type=${p.type}&mobile=${p.mobile}`
                );

                const ledger = await res2.json();
                renderLedger(ledger);
            });

            partyList.appendChild(el);

            if (!firstItem) firstItem = el;
        });

        if (firstItem && data.length > 0) {
            firstItem.click();
        }

        // PAGINATION
        let pagination = document.createElement("div");
        pagination.className = "p-2 text-center pagination-compact";

        let maxVisible = 2;
        let start = Math.max(1, result.current_page - 1);
        let end = Math.min(result.last_page, start + maxVisible - 1);

        if (result.current_page > 1) {
            let prev = document.createElement("button");
            prev.className = "btn btn-sm btn-outline-primary px-2 py-1 me-1";
prev.innerText = "«";
prev.style.fontSize = "12px";
            prev.innerText = "Prev";

            prev.onclick = () => {
                currentPage--;
                loadParty(currentType, currentPage);
            };

            pagination.appendChild(prev);
        }

        for (let i = start; i <= end; i++) {

            let btn = document.createElement("button");

            btn.className = "btn btn-sm px-2 py-1 me-1 " +
    (i === result.current_page ? "btn-primary" : "btn-outline-primary");

btn.style.fontSize = "12px";
btn.style.minWidth = "32px";

            btn.innerText = i;

            btn.onclick = () => {
                currentPage = i;
                loadParty(currentType, i);
            };

            pagination.appendChild(btn);
        }

        if (result.current_page < result.last_page) {

            let next = document.createElement("button");
            next.className = "btn btn-sm btn-outline-primary px-2 py-1 ms-1";
next.innerText = "»";
next.style.fontSize = "12px";
            next.innerText = "Next";

            next.onclick = () => {
                currentPage++;
                loadParty(currentType, currentPage);
            };

            pagination.appendChild(next);
        }

        partyList.appendChild(pagination);
    }

    // =========================
    // SWITCH TYPE
document.getElementById("customerBtn").onclick=function(){

currentType="customer";

this.classList.add("btn-primary");
this.classList.remove("btn-outline-secondary");

document.getElementById("supplierBtn")
.classList.remove("btn-primary");

document.getElementById("supplierBtn")
.classList.add("btn-outline-secondary");



loadParty("customer");

};


document.getElementById("supplierBtn").onclick=function(){

currentType="supplier";

this.classList.add("btn-primary");
this.classList.remove("btn-outline-secondary");

document.getElementById("customerBtn")
.classList.remove("btn-primary");

document.getElementById("customerBtn")
.classList.add("btn-outline-secondary");

loadParty("supplier");

};



// ADD GIVEN BUTTON
document.getElementById("btnAddGiven").addEventListener("click", function(){

if(!selectedCustomer){
    errorToast("Please select a customer first");
    return;
}

openGivenModal(selectedCustomer);

});


// ADD RECEIVED BUTTON
document.getElementById("btnAddReceived").addEventListener("click", function(){

if(!selectedCustomer){
    errorToast("Please select a customer first");
    return;
}

openReceivedModal(selectedCustomer);

});

    // INIT
    loadParty("customer");

});
function resetFilters() {
    // clear search
    phoneInput.value = "";
    searchValue = "";

    // clear date range
    if (dateInput._flatpickr) {
        dateInput._flatpickr.clear();
    }
    startDate = null;
    endDate = null;

    // hide icons
    clearPhone.style.display = "none";
    clearDate.style.display = "none";

    // reset pagination
    currentPage = 1;

    // IMPORTANT: reload default data
    loadParty(currentType, 1);
}



</script>
@endsection
