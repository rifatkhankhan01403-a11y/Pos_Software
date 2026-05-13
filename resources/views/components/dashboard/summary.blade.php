@extends('layout.sidenav-layout')

@section('content')

<style>

/* Background */
.dashboard-card,
.action-card {
    cursor: pointer;
}

.dashboard-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}
body{
background:#fff4f7;
}

/* Header Filter */


.pdf-btn{
border:none;
background:#198754;
color:white;
padding:6px 14px;
border-radius:6px;
font-size:13px;
font-weight:600;
transition:.2s;
}

.pdf-btn:hover{
background:#157347;
}
.filter-btn{
border:none;
background:#ffd6e2;
color:#a78294;
padding:6px 14px;
border-radius:6px;
font-size:13px;
font-weight:600;
}

.filter-btn.active{
background:#d63384;
color:white;
}


/* Balance */

.balance-box{
background:#ffd6e2;
color:#d63384;
padding:8px 18px;
border-radius:8px;
font-weight:600;
}


/* Cards */

.dashboard-card{
background:white;
border-radius:12px;
padding:18px;
box-shadow:0 2px 8px rgba(0,0,0,0.06);
display:flex;
justify-content:space-between;
align-items:center;
}

.card-title{
font-size:13px;
color:#777;
}

.card-value{
font-size:22px;
font-weight:700;
}

.icon-box{
width:40px;
height:40px;
border-radius:8px;
background:#ffd6e2;
display:flex;
align-items:center;
justify-content:center;
font-size:20px;
color:#d63384;
}


/* Action Cards */

.action-card{
background:white;
border-radius:12px;
padding:35px;
text-align:center;
box-shadow:0 2px 8px rgba(0,0,0,0.05);
cursor:pointer;
transition:.2s;
}

.action-card:hover{
background:#ffd6e2;
}

.action-icon{
font-size:40px;
color:#d63384;
margin-bottom:10px;
}

</style>



<div class="container-fluid">


<!-- HEADER -->

<div class="d-flex justify-content-between align-items-center mb-4">

<h4 class="fw-bold">Dashboard</h4>

<div class="d-flex gap-2">

<button class="filter-btn active" data-filter="today">Today</button>
<button class="filter-btn" data-filter="weekly">Weekly</button>
<button class="filter-btn" data-filter="monthly">Monthly</button>
<button class="filter-btn" data-filter="yearly">Yearly</button>
<button class="filter-btn" data-filter="all">All Time</button>

<button class="pdf-btn"
        onclick="downloadDashboardPdf()">
    📄 Download PDF
</button>

</div>


</div>



<div class="mb-4">
<span class="balance-box" id="balanceBox">
💰 Balance: ৳ 0
</span>
</div>



<!-- TOP CARDS -->

<!-- TOP CARDS -->
<div class="row g-3 mb-4">

    <!-- SELL -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/salePage')">
            <div>
                <div class="card-title" id="sellTitle">Today Sale</div>
                <div class="card-value text-success" id="sellAmount">৳ 0</div>
            </div>
            <div class="icon-box">📈</div>
        </div>
    </div>

    <!-- CONDITION SELL -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/salePage')">
            <div>
                <div class="card-title" id="conditionTitle">Today Condition Sale</div>
                <div class="card-value text-warning" id="conditionAmount">৳ 0</div>
            </div>
            <div class="icon-box">🚚</div>
        </div>
    </div>

    <!-- PURCHASE -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/purchase-book')">
            <div>
                <div class="card-title" id="purchaseTitle">Today Purchase</div>
                <div class="card-value text-primary" id="purchaseAmount">৳ 0</div>
            </div>
            <div class="icon-box">🛒</div>
        </div>
    </div>

    <!-- EXPENSE -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/expensePage')">
            <div>
                <div class="card-title" id="expenseTitle">Today Expense</div>
                <div class="card-value text-danger" id="expenseAmount">৳ 0</div>
            </div>
            <div class="icon-box">💸</div>
        </div>
    </div>

</div>



<!-- SECOND ROW -->

<!-- SECOND ROW -->
<div class="row g-3 mb-4">

    <!-- TOTAL STOCK -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/productPage')">
            <div>
                <div class="card-title">Total Stock</div>
                <div class="card-value text-success" id="stockValue">0</div>
            </div>
            <div class="icon-box">📦</div>
        </div>
    </div>

    <!-- STOCK SOLD -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/salePage')">
            <div>
                <div class="card-title" id="stockSellTitle">Today Stock Sold</div>
                <div class="card-value text-info" id="stockSellValue">0</div>
            </div>
            <div class="icon-box">📤</div>
        </div>
    </div>

    <!-- RECEIVABLE -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/due-book')">
            <div>
                <div class="card-title">Receivable</div>
                <div class="card-value text-danger" id="receivableValue">৳ 0</div>
            </div>
            <div class="icon-box">💳</div>
        </div>
    </div>

    <!-- PAYABLE -->
    <div class="col-lg-3">
        <div class="dashboard-card" onclick="goTo('/due-book')">
            <div>
                <div class="card-title">Payable</div>
                <div class="card-value text-success" id="payableValue">৳ 0</div>
            </div>
            <div class="icon-box">🧾</div>
        </div>
    </div>

</div>


<!-- ACTION CARDS -->

<div class="row g-3">

<div class="col-lg-4">

<div class="action-card" onclick="goTo('/stock-add')">
<div class="action-icon">📦</div>

<h6>Purchase</h6>

</div>

</div>


<div class="col-lg-4">

<div class="action-card" onclick="goTo('/salePage')">

<div class="action-icon">🛍️</div>

<h6>Sell</h6>

</div>

</div>


<div class="col-lg-4">

<div class="action-card" data-bs-toggle="modal" data-bs-target="#quickSellModal">

<div class="action-icon">⚡</div>

<h6>Quick Sell</h6>

</div>

</div>

</div>


</div>



<script>

let currentFilter = 'today';
/* FILTER BUTTON CLICK */

document.querySelectorAll(".filter-btn").forEach(button=>{

button.addEventListener("click",function(){

document.querySelectorAll(".filter-btn").forEach(btn=>{
btn.classList.remove("active")
})

this.classList.add("active")

let filter=this.dataset.filter

currentFilter = filter;

loadDashboardData(filter)

})

})



function goTo(url){
    window.location.href = url;
}



/* LOAD DATA FROM LARAVEL */

function loadDashboardData(filter){

fetch('/dashboard-data?filter='+filter)

.then(response=>response.json())

.then(data=>{

document.getElementById("sellAmount").innerText =
        "৳ " + data.sell;

    document.getElementById("purchaseAmount").innerText =
        "৳ " + data.purchase;

    document.getElementById("expenseAmount").innerText =
        "৳ " + data.expense;

    document.getElementById("conditionAmount").innerText =
        "৳ " + data.condition_sell;

    document.getElementById("stockSellValue").innerText =
        data.stock_sold_qty;

    let labels = {
    today: "Today's",
    weekly: "Weekly",
    monthly: "Monthly",
    yearly: "Yearly",
    all: "All Time"
};

let title = labels[filter];

document.getElementById("sellTitle").innerText =
        title + " Sale";

document.getElementById("purchaseTitle").innerText =
        title + " Purchase";

document.getElementById("expenseTitle").innerText =
        title + " Expense";

document.getElementById("conditionTitle").innerText =
        title + " Condition Sale";

document.getElementById("stockSellTitle").innerText =
        title + " Stock Sold";

})

}

function loadDashboardSummary(){

fetch('/dashboard-summary')
.then(res => res.json())
.then(data => {

    document.getElementById("receivableValue").innerText = "৳ " + data.receivable;
    document.getElementById("payableValue").innerText = "৳ " + data.payable;
    document.getElementById("stockValue").innerText = data.stock;
    document.getElementById("balanceBox").innerText =
    "💰 Balance: ৳ " + data.net;

});
}

loadDashboardSummary();


/* DEFAULT LOAD */

loadDashboardData('today')


function downloadDashboardPdf(){

    window.open('/dashboard-pdf?filter=' + currentFilter, '_blank');

}
</script>


@endsection
