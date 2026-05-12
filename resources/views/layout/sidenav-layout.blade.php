<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title></title>

    <link rel="icon" type="image/x-icon" href="{{asset('/favicon.ico')}}" />
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/fontawesome.css')}}" rel="stylesheet" />
    <link href="{{asset('css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('css/toastify.min.css')}}" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet" />
    <script src="{{asset('js/jquery-3.7.0.min.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{asset('js/toastify-js.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/config.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<style>
/* blur background */

.pagination {

    gap: 5px;

}

.clear-date-btn{
    border: none;
    background: transparent !important;
    box-shadow: none !important;
    font-size: 20px;
    line-height: 1;
    padding: 0;
    cursor: pointer;
}

.clear-date-btn:hover,
.clear-date-btn:focus,
.clear-date-btn:active{
    background: transparent !important;
    box-shadow: none !important;
    outline: none;
}


.pagination li {
    margin: 0 2px;
}

.pagination li a,
.pagination li span {
    padding: 6px 10px;
    border-radius: 6px !important;
    font-size: 14px;
}

.modal-backdrop.show{
    backdrop-filter: blur(6px);
    background: rgba(0,0,0,0.45);
}

/* modal style */
.quick-modal-box{
    border-radius:12px;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);
    padding:10px;
}

/* move modal slightly higher */
.quick-modal-position{
    margin-top:-70px;
}

/* smooth animation */
.quick-modal .modal-dialog{
    animation: zoomIn .25s ease;
}

.quick-close{
    position:absolute;
    top:12px;
    right:15px;
    font-size:22px;
    cursor:pointer;
    color:#333;
}

.quick-close:hover{
    color:#000;
}

.side-bar-item {
    display: flex;
    align-items: center;
    gap: 8px;              /* 🔽 reduced gap */
    padding: 8px 12px;     /* 🔽 less padding */
    border-radius: 6px;
    margin: 2px 6px;       /* 🔽 tighter spacing */
    font-size: 14px;
    transition: all 0.2s ease;
}

.side-bar-item i {
    font-size: 16px;       /* slightly smaller icon */
}

.nav-section-title {
    font-size: 10px;
    padding: 8px 12px 4px; /* tighter section spacing */
    margin-top: 5px;
}

.side-bar-item.active {
    background: linear-gradient(45deg, #4e73df, #ed85bb);
    color: #fff !important;
}

.side-bar-item.active i {
    color: #c35ece;
}
.side-bar-item:hover {
    background: #f3f6ff;
    transform: translateX(3px);
}

.side-bar-item.active {
    background: linear-gradient(45deg, #f464b3, #ee7cb7);
    color: #fff;
}
</style>
<body>

<div id="loader" class="LoadingOverlay d-none">
    <div class="Line-Progress">
        <div class="indeterminate"></div>
    </div>
</div>

<nav class="navbar fixed-top px-0 shadow-sm bg-white">
    <div class="container-fluid">

        <a class="navbar-brand" href="#">
            <span class="icon-nav m-0 h5" onclick="MenuBarClickHandler()">
                <img class="nav-logo-sm mx-2"  src="{{asset('images/menu.svg')}}" alt="logo"/>
            </span>
            <img class="nav-logo  mx-2"  src="{{asset('images/logo.png')}}" alt="logo"/>
        </a>

        <div class="float-right h-auto d-flex">
            <div class="user-dropdown">
                <img class="icon-nav-img" src="{{asset('images/user.webp')}}" alt=""/>
                <div class="user-dropdown-content ">
                    <div class="mt-4 text-center">
                        <img class="icon-nav-img" src="{{asset('images/user.webp')}}" alt=""/>
                        <h6>User Name</h6>
                        <hr class="user-dropdown-divider  p-0"/>
                    </div>
                    <a href="{{url('/userProfile')}}" class="side-bar-item">
                        <span class="side-bar-item-caption">Profile</span>
                    </a>
                    <a href="{{url("/logout")}}" class="side-bar-item">
                        <span class="side-bar-item-caption">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>


<div id="sideNavRef" class="side-nav-open">

    <!-- DASHBOARD -->
    <div class="nav-section-title">Overview</div>

    <a href="{{url('/dashboard')}}" class="side-bar-item">
        <i class="bi bi-speedometer2"></i>
        <span class="side-bar-item-caption">Dashboard</span>
    </a>


    <!-- PEOPLE -->




    <!-- SALES -->
    <div class="nav-section-title">Sales</div>

    <a href="{{url('/salePage')}}" class="side-bar-item">
        <i class="bi bi-cart-check"></i>
        <span class="side-bar-item-caption">Add Sale</span>
    </a>

    <a href="#" class="side-bar-item" data-bs-toggle="modal" data-bs-target="#quickSellModal">
        <i class="bi bi-lightning-charge"></i>
        <span class="side-bar-item-caption">Quick Sale</span>
    </a>
  <a href="{{url('/conditionSell')}}" class="side-bar-item">
        <i class="bi bi-cart-check"></i>
        <span class="side-bar-item-caption">Condition Sale</span>
    </a>


    <!-- PRODUCTS -->
    <div class="nav-section-title">Inventory</div>

    <a href="{{url('/categoryPage')}}" class="side-bar-item">
        <i class="bi bi-tags"></i>
        <span class="side-bar-item-caption">Categories</span>
    </a>

    <a href="{{url('/productPage')}}" class="side-bar-item">
        <i class="bi bi-box-seam"></i>
        <span class="side-bar-item-caption">Products</span>
    </a>

    <a href="{{url('/stock-add')}}" class="side-bar-item">
        <i class="bi bi-plus-square"></i>
        <span class="side-bar-item-caption">Add Purchase</span>
    </a>



    <!-- ACCOUNTS -->
    <div class="nav-section-title">Accounts</div>

    <a href="{{url('/cashbox')}}" class="side-bar-item">
        <i class="bi bi-wallet2"></i>
        <span class="side-bar-item-caption">Cashbox</span>
    </a>

    <a href="{{url('/expensePage')}}" class="side-bar-item">
        <i class="bi bi-receipt"></i>
        <span class="side-bar-item-caption">Expense Book</span>
    </a>

    <a href="{{url('/purchase-book')}}" class="side-bar-item">
        <i class="bi bi-journal-check"></i>
        <span class="side-bar-item-caption">Purchase Book</span>
    </a>

   <a href="{{url('/saleHistory')}}" class="side-bar-item">
        <i class="bi bi-clock-history"></i>
        <span class="side-bar-item-caption">Sale Book</span>
    </a>

    <a href="{{url('/due-book')}}" class="side-bar-item">
        <i class="bi bi-journal-text"></i>
        <span class="side-bar-item-caption">Due Book</span>
    </a>

  <div class="nav-section-title">People</div>

    <a href="{{url('/supplierPage')}}" class="side-bar-item">
        <i class="bi bi-truck"></i>
        <span class="side-bar-item-caption">Suppliers</span>
    </a>

    <a href="{{url('/customerPage')}}" class="side-bar-item">
        <i class="bi bi-person"></i>
        <span class="side-bar-item-caption">Customers</span>
    </a>



    <!-- REPORT -->
    <div class="nav-section-title">Analytics</div>

    <a href="{{url('/reportPage')}}" class="side-bar-item">
        <i class="bi bi-bar-chart-line"></i>
        <span class="side-bar-item-caption">Reports</span>
    </a>

</div>


<div id="contentRef" class="content">
    @yield('content')
</div>




<!-- Quick Sale Global Modal -->
<!-- QUICK SELL MODAL -->
<div class="modal fade quick-modal" id="quickSellModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered quick-modal-position">
    <div class="modal-content quick-modal-box">

      <!-- Header -->
     <div class="modal-header border-0 pb-2 position-relative">
    <h4 class="modal-title fw-semibold">Quick Sell</h4>

    <!-- custom cross close -->
    <span class="quick-close" data-bs-dismiss="modal">
        <i class="bi bi-x-sm"></i>
    </span>
</div>

      <!-- Body -->
      <div class="modal-body pt-2">

        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label fw-semibold">Date</label>
            <input id="sellDate" type="date" class="form-control form-control-lg">
          </div>

          <div class="col-md-6">
    <label class="form-label fw-semibold">Payment Type</label>
    <input type="text" class="form-control form-control-lg" value="Cash" readonly>
</div>




<!-- PRODUCT -->
<div class="col-md-6">
    <label class="form-label fw-semibold">Product</label>

    <div class="input-group input-group-sm">

        <select id="qs_product_id" class="form-select">
            <option value="">Select Product</option>
        </select>

        <!-- ADD PRODUCT BUTTON -->
 {{-- <button class="btn btn-primary"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#create-modal">
            <i class="bi bi-plus-lg"></i>
        </button> --}}

    </div>
</div>

<!-- CURRENT STOCK -->

<!-- QTY -->
<div class="col-md-6">
    <label class="form-label fw-semibold">Qty</label>

    <input type="number"
           id="qs_qty"
           class="form-control form-control-sm"
           min="1"
           value="0">
</div>






          <div class="col-md-6">
            <label class="form-label fw-semibold">Amount*</label>
           <input id="qs_amount" type="text" class="form-control form-control-lg" placeholder="Enter Amount">

          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Profit</label>
           <input id="qs_profit" type="text" class="form-control form-control-lg" placeholder="Profit Amount">

          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Customer Name</label>
          <input id="qs_name" type="text" class="form-control form-control-lg" placeholder="Customer Name">

          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Mobile</label>
           <input id="qs_mobile" type="text" class="form-control form-control-lg" placeholder="+880">
          </div>

        </div>

      </div>

      <!-- Footer -->
       <div class="modal-footer bg-light border-0 pt-3">
      <button onclick="submitQuickSell()" class="btn bg-gradient-primary text-white w-100 btn-lg fw-semibold shadow-sm">
  Amount Received
</button>
      </div>

    </div>
  </div>
</div>


{{--
create porduct modal
--}}

<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Product</h5>
            </div>

            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <!-- Category -->
                                <label class="form-label">Category</label>
                                <select class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <!-- Subcategory -->
                                {{-- <label class="form-label mt-2">Sub Category</label>
                                <select class="form-control form-select" id="productSubCategory">
                                    <option value="">Select Sub Category</option>
                                </select> --}}

                                <!-- Name -->
                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                {{-- <!-- Unit -->
                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit"> --}}

                                <!-- Quantity -->
                                <label class="form-label mt-2">Quantity</label>
                                <input type="number" class="form-control" id="productQuantity">

                                <!-- Buy Price -->
                                <label class="form-label mt-2">Buy Price</label>
                                <input type="text" class="form-control" id="productBuyPrice">

                                <!-- Sell Price -->
                                <label class="form-label mt-2">Sell Price</label>
                                <input type="text" class="form-control" id="productSellPrice">

                                <!-- Note -->
                                <label class="form-label mt-2">Note</label>
                                <input type="text" class="form-control" id="productNote">

                                <br/>

                                <!-- Image Preview -->
                                <img class="w-15" id="newImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>

                                <!-- Image -->
                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])"
                                       type="file"
                                       class="form-control"
                                       id="productImg">

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal">Close</button>
                <button onclick="Save()" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
async function Save() {

        let productCategory = document.getElementById('productCategory').value;
        let productSubCategory = document.getElementById('productSubCategory').value;
        let productName = document.getElementById('productName').value;
      //  let productUnit = document.getElementById('productUnit').value;
        let productQuantity = document.getElementById('productQuantity').value;
        let productBuyPrice = document.getElementById('productBuyPrice').value;
        let productSellPrice = document.getElementById('productSellPrice').value;
        let productNote = document.getElementById('productNote').value;
        let productImg = document.getElementById('productImg').files[0]; // ✅ FIXED


        if (productCategory.length === 0) {
            errorToast("Product Category Required !");
        }
        else if(productName.length === 0){
            errorToast("Product Name Required !");
        }
        // else if(productUnit.length === 0){
        //     errorToast("Product Unit Required !");
        // }
        // else if(productQuantity.length === 0){
        //     errorToast("Quantity Required !");
        // }
        else if(productBuyPrice.length === 0){
            errorToast("Buy Price Required !");
        }

        else {

            document.getElementById('modal-close').click();

            let formData = new FormData();

            if(productImg){
                formData.append('img', productImg)
            }

            formData.append('name', productName)
            formData.append('quantity', productQuantity)
            formData.append('buy_price', productBuyPrice)
            formData.append('sell_price', productSellPrice)
            formData.append('note', productNote)
            formData.append('category_id', productCategory)
            formData.append('subcategory_id', productSubCategory)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/create-product", formData, config)
            hideLoader();

            if(res.status === 201){
                successToast('Product Added Successfully');
                document.getElementById("save-form").reset();
               await loadQuickSellProducts();
            }
            else{
                errorToast("Request fail !");
            }
        }
    }














function MenuBarClickHandler() {
    let sideNav = document.getElementById('sideNavRef');
    let content = document.getElementById('contentRef');

    if (sideNav.classList.contains("side-nav-open")) {
        sideNav.classList.add("side-nav-close");
        sideNav.classList.remove("side-nav-open");
        content.classList.add("content-expand");
        content.classList.remove("content");
    } else {
        sideNav.classList.remove("side-nav-close");
        sideNav.classList.add("side-nav-open");
        content.classList.remove("content-expand");
        content.classList.add("content");
    }
}
document.addEventListener("DOMContentLoaded", function () {

    let today = new Date().toISOString().split('T')[0];

    document.getElementById("sellDate").value = today;

    loadQuickSellProducts();

});

function submitQuickSell() {

    let productId =
        document.getElementById('qs_product_id').value;

    let qty =
        parseFloat(document.getElementById('qs_qty').value);

let amount =
    parseFloat(document.getElementById('qs_amount').value);

// VALIDATE QTY AFTER PRODUCT SELECT
if (productId && qty < 1) {

    Toastify({
        text: "Quantity must be at least 1",
        duration: 3000,
        backgroundColor: "red"
    }).showToast();

    return;
}
    let data = {

        sell_date:
            document.getElementById('sellDate').value,

        product_id: productId,

        qty: qty,
 amount: amount,
        customer_name:
            document.getElementById('qs_name').value,

        customer_mobile:
            document.getElementById('qs_mobile').value,
    };

    axios.post('/quick-sell-store', data)

    .then(function (response) {

        if (response.data.status === true) {

            Toastify({
                text: "Quick Sell Saved Successfully",
                duration: 3000,
                gravity: "top",
                position: "center",
                backgroundColor: "green",
            }).showToast();

            let modal = bootstrap.Modal.getInstance(
                document.getElementById('quickSellModal')
            );

            modal.hide();

            document.getElementById('qs_product_id').value = "";
    document.getElementById('qs_qty').value = 0;
    document.getElementById('qs_amount').value = "";
    document.getElementById('qs_profit').value = "";
    document.getElementById('qs_name').value = "";
    document.getElementById('qs_mobile').value = "";

            loadQuickSellProducts();
        }

        else {

            Toastify({
                text: response.data.message,
                duration: 3000,
                backgroundColor: "red",
            }).showToast();
        }

    })

    .catch(function (error) {

        Toastify({
            text: "Something went wrong",
            duration: 3000,
            backgroundColor: "red",
        }).showToast();

        console.log(error);
    });
}

let cashInBtn = document.querySelector('#cashInModal button.btn-success');
if(cashInBtn){
cashInBtn.addEventListener('click', function () {

    let amount = document.querySelector('#cashInModal input').value;
    let note = document.querySelector('#cashInModal textarea').value;

    fetch('/cash-in', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            amount: amount,
            note: note
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
});
}
let cashOutBtn = document.querySelector('#cashOutModal button.btn-danger');

if(cashOutBtn){

cashOutBtn.addEventListener('click', function () {

    let amount = document.querySelector('#cashOutModal input').value;
    let note = document.querySelector('#cashOutModal textarea').value;

    fetch('/cash-out', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            amount: amount,
            note: note
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });

});

}




document.addEventListener("DOMContentLoaded", function () {

    let currentPath = window.location.pathname;

    document.querySelectorAll('.side-bar-item').forEach(l => l.classList.remove('active'));

    document.querySelectorAll('.side-bar-item').forEach(link => {

        if (!link.href || link.getAttribute('href') === '#' || link.hasAttribute('data-bs-toggle')) {
            return;
        }

        let linkPath = new URL(link.href).pathname;

        if (linkPath === currentPath) {
            link.classList.add('active');
        }

    });

});


async function loadQuickSellProducts() {

    try {

        let res = await axios.get('/list-product');

        let productDropdown =
            document.getElementById('qs_product_id');

        productDropdown.innerHTML =
            `<option value="">Select Product</option>`;

        res.data.forEach(product => {

            productDropdown.innerHTML += `
                <option
                    value="${product.id}"
                    data-stock="${product.quantity}"
                    data-price="${product.sell_price}"
                >
                    ${product.name} (Stock: ${product.quantity})
                </option>
            `;
        });

    } catch (e) {

        console.log(e);

    }
}

</script>

</body>
</html>
