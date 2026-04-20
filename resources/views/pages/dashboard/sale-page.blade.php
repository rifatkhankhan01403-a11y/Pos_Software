{{-- @extends('layout.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email:  <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Total</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                       <div class="col-12">
                           <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                           <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                           <span class="text-xxs">Discount(%):</span>
                           <input onkeydown="return false" value="0" min="0" type="number" step="0.25" onchange="DiscountChange()" class="form-control w-40 " id="discountP"/>
                           <p>
                              <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                           </p>
                       </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Customer</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>




    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" id="PId">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="PName">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="PQty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success" >Add</button>
                </div>
            </div>
        </div>
    </div>


    <script>


        (async ()=>{
          showLoader();
          await  CustomerList();
          await ProductList();
          hideLoader();
        })()


        let InvoiceItemList=[];


        function ShowInvoiceItem() {

            let invoiceList=$('#invoiceList');

            invoiceList.empty();

            InvoiceItemList.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td>${item['product_name']}</td>
                        <td>${item['qty']}</td>
                        <td>${item['sale_price']}</td>
                        <td><a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a></td>
                     </tr>`
                invoiceList.append(row)
            })

            CalculateGrandTotal();

            $('.remove').on('click', async function () {
                let index= $(this).data('index');
                removeItem(index);
            })

        }


        function removeItem(index) {
            InvoiceItemList.splice(index,1);
            ShowInvoiceItem()
        }

        function DiscountChange() {
            CalculateGrandTotal();
        }

        function CalculateGrandTotal(){
            let Total=0;
            let Vat=0;
            let Payable=0;
            let Discount=0;
            let discountPercentage=(parseFloat(document.getElementById('discountP').value));

            InvoiceItemList.forEach((item,index)=>{
                Total=Total+parseFloat(item['sale_price'])
            })

             if(discountPercentage===0){
                 Vat= ((Total*5)/100).toFixed(2);
             }
             else {
                 Discount=((Total*discountPercentage)/100).toFixed(2);
                 Total=(Total-((Total*discountPercentage)/100)).toFixed(2);
                 Vat= ((Total*5)/100).toFixed(2);
             }

             Payable=(parseFloat(Total)+parseFloat(Vat)).toFixed(2);


            document.getElementById('total').innerText=Total;
            document.getElementById('payable').innerText=Payable;
            document.getElementById('vat').innerText=Vat;
            document.getElementById('discount').innerText=Discount;
        }


        function add() {
           let PId= document.getElementById('PId').value;
           let PName= document.getElementById('PName').value;
           let PPrice=document.getElementById('PPrice').value;
           let PQty= document.getElementById('PQty').value;
           let PTotalPrice=(parseFloat(PPrice)*parseFloat(PQty)).toFixed(2);
           if(PId.length===0){
               errorToast("Product ID Required");
           }
           else if(PName.length===0){
               errorToast("Product Name Required");
           }
           else if(PPrice.length===0){
               errorToast("Product Price Required");
           }
           else if(PQty.length===0){
               errorToast("Product Quantity Required");
           }
           else{
               let item={product_name:PName,product_id:PId,qty:PQty,sale_price:PTotalPrice};
               InvoiceItemList.push(item);
               console.log(InvoiceItemList);
               $('#create-modal').modal('hide')
               ShowInvoiceItem();
           }
        }




        function addModal(id,name,price) {
            document.getElementById('PId').value=id
            document.getElementById('PName').value=name
            document.getElementById('PPrice').value=price
            $('#create-modal').modal('show')
        }


        async function CustomerList(){
            let res=await axios.get("/list-customer");
            let customerList=$("#customerList");
            let customerTable=$("#customerTable");
            customerTable.DataTable().destroy();
            customerList.empty();

            res.data.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td><i class="bi bi-person"></i> ${item['name']}</td>
                        <td><a data-name="${item['name']}" data-email="${item['email']}" data-id="${item['id']}" class="btn btn-outline-dark addCustomer  text-xxs px-2 py-1  btn-sm m-0">Add</a></td>
                     </tr>`
                customerList.append(row)
            })


            $('.addCustomer').on('click', async function () {

                let CName= $(this).data('name');
                let CEmail= $(this).data('email');
                let CId= $(this).data('id');

                $("#CName").text(CName)
                $("#CEmail").text(CEmail)
                $("#CId").text(CId)

            })

            new DataTable('#customerTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }


        async function ProductList(){
            let res=await axios.get("/list-product");
            let productList=$("#productList");
            let productTable=$("#productTable");
            productTable.DataTable().destroy();
            productList.empty();

            res.data.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td> <img class="w-10" src="${item['img_url']}"/> ${item['name']} ($ ${item['price']})</td>
                        <td><a data-name="${item['name']}" data-price="${item['price']}" data-id="${item['id']}" class="btn btn-outline-dark text-xxs px-2 py-1 addProduct  btn-sm m-0">Add</a></td>
                     </tr>`
                productList.append(row)
            })


            $('.addProduct').on('click', async function () {
                let PName= $(this).data('name');
                let PPrice= $(this).data('price');
                let PId= $(this).data('id');
                addModal(PId,PName,PPrice)
            })


            new DataTable('#productTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }



      async  function createInvoice() {
            let total=document.getElementById('total').innerText;
            let discount=document.getElementById('discount').innerText
            let vat=document.getElementById('vat').innerText
            let payable=document.getElementById('payable').innerText
            let CId=document.getElementById('CId').innerText;


            let Data={
                "total":total,
                "discount":discount,
                "vat":vat,
                "payable":payable,
                "customer_id":CId,
                "products":InvoiceItemList
            }


            if(CId.length===0){
                errorToast("Customer Required !")
            }
            else if(InvoiceItemList.length===0){
                errorToast("Product Required !")
            }
            else{

                showLoader();
                let res=await axios.post("/invoice-create",Data)
                hideLoader();
                if(res.data===1){
                    window.location.href='/invoicePage'
                    successToast("Invoice Created");
                }
                else{
                    errorToast("Something Went Wrong")
                }
            }

        }

    </script>




@endsection --}}


@extends('layout.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Invoice Section -->
       <div class="col-md-5 col-lg-5 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                        <p class="text-xs mx-0 my-1">Mobile: <span id="CMobile"></span></p>
                            <p class="text-xs mx-0 my-1">User ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Total</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                 <div class="row">
    <div class="col-12">

    <!-- SUMMARY BOX -->
    <div class="border rounded p-2">

        <div class="d-flex justify-content-between text-xs my-1">
            <span class="text-dark fw-bold">TOTAL</span>
            <span>$ <span id="total"></span></span>
        </div>

        <div class="d-flex justify-content-between text-xs my-1">
            <span class="text-dark fw-bold">VAT (5%)</span>
            <span>$ <span id="vat"></span></span>
        </div>

        <div class="d-flex justify-content-between text-xs my-1">
            <span class="text-dark fw-bold">DISCOUNT</span>
            <span>$ <span id="discount"></span></span>
        </div>

        <div class="d-flex justify-content-between text-xs my-2 border-top pt-1">
            <span class="text-dark fw-bold">PAYABLE</span>
            <span>$ <span id="payable"></span></span>
        </div>

    </div>s

    <div class="row mt-2">
    <div class="col-6">

    <!-- Discount -->
    <div class="mb-2">
        <label class="text-xxs mb-1">Discount (%)</label>

       <input
       value="0"
       min="0"
       type="number"
       step="0.25"
       oninput="DiscountChange()"
       class="form-control form-control-sm"
       id="discountP"
       style="height: 38px;">
    </div>

    <!-- ADD DUE (now BELOW discount) -->
    <div class="mb-2">
       <button type="button"
        class="btn btn-outline-primary btn-sm w-100"
        style="height: 38px;"
        onclick="openDueModal()">
    ADD DUE
</button>
    </div>

</div>

</div>
    <!-- CONFIRM BUTTON -->
    <button onclick="createInvoice()"
            class="btn bg-gradient-primary w-100 mt-3">
        Confirm
    </button>

</div>
</div>
                </div>
            </div>

            <!-- Product Section -->
          <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customer Section with Add Button -->
          <div class="col-md-3 col-lg-3 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-xs text-bold">Customer</h6>
                        <button class="btn btn-sm btn-primary" onclick="OpenCustomerModal()">+ Add Customer</button>
                    </div>
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Customer</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" id="PId">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="PName">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="PQty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success" >Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal animated zoomIn" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCustomerLabel">Create Customer</h5>
                </div>
                <div class="modal-body">
                    <form id="createCustomerForm">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" id="customerName">
                                    <label class="form-label mt-2">Customer Email *</label>
                                    <input type="text" class="form-control" id="customerEmail">
                                    <label class="form-label mt-2">Customer Mobile *</label>
                                    <input type="text" class="form-control" id="customerMobile">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close-customer" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="SaveCustomer()" id="save-customer-btn" class="btn bg-gradient-success">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="dueModal" tabindex="-1">
<div class="modal-dialog modal-md modal-dialog-centered">        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">Due Payment</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
            </div>

            <div class="modal-body">

                <!-- TOTAL (READ ONLY) -->
                <label class="text-xxs">Total Payable</label>
                <input type="text" class="form-control form-control-sm mb-2"
                       id="dueTotal" readonly>

                <!-- PAID -->
                <label class="text-xxs">Paid Amount</label>
                <input type="number" class="form-control form-control-sm mb-2"
                       id="paidAmount" oninput="calculateDue()">

                <!-- DUE (AUTO) -->
                <label class="text-xxs">Due Amount</label>
                <input type="text" class="form-control form-control-sm mb-2"
                       id="dueAmount" readonly>

                <!-- DUE DATE -->
                <label class="text-xxs">Due Date</label>
                <input type="date" class="form-control form-control-sm"
                       id="dueDate">

            </div>

            <div class="modal-footer">
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal"
                        >
                    Close
                </button>

                <button class="btn btn-sm btn-primary"
                        data-bs-dismiss="modal"
                        onclick="saveDue()">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>
    <script>
        (async ()=>{
          showLoader();
          await CustomerList();
          await ProductList();
          hideLoader();
        })()

        let InvoiceItemList=[];

        function ShowInvoiceItem() {
            let invoiceList=$('#invoiceList');
            invoiceList.empty();

            InvoiceItemList.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td>${item['product_name']}</td>
                        <td>${item['qty']}</td>
                        <td>${item['sale_price']}</td>
                        <td><a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a></td>
                     </tr>`
                invoiceList.append(row)
            })

            CalculateGrandTotal();

            $('.remove').on('click', async function () {
                let index= $(this).data('index');
                removeItem(index);
            })
        }

        function removeItem(index) {
            InvoiceItemList.splice(index,1);
            ShowInvoiceItem()
        }

        function DiscountChange() {
            CalculateGrandTotal();
        }

        function CalculateGrandTotal(){
            let Total=0;
            let Vat=0;
            let Payable=0;
            let Discount=0;
            let discountPercentage=(parseFloat(document.getElementById('discountP').value));

            InvoiceItemList.forEach((item,index)=>{
                Total=Total+parseFloat(item['sale_price'])
            })

             if(discountPercentage===0){
                 Vat= ((Total*5)/100).toFixed(2);
             }
             else {
                 Discount=((Total*discountPercentage)/100).toFixed(2);
                 Total=(Total-((Total*discountPercentage)/100)).toFixed(2);
                 Vat= ((Total*5)/100).toFixed(2);
             }

             Payable=(parseFloat(Total)+parseFloat(Vat)).toFixed(2);

            document.getElementById('total').innerText=Total;
            document.getElementById('payable').innerText=Payable;
            document.getElementById('vat').innerText=Vat;
            document.getElementById('discount').innerText=Discount;
        }

        function add() {
           let PId= document.getElementById('PId').value;
           let PName= document.getElementById('PName').value;
           let PPrice=document.getElementById('PPrice').value;
           let PQty= document.getElementById('PQty').value;
           let PTotalPrice=(parseFloat(PPrice)*parseFloat(PQty)).toFixed(2);
           if(PId.length===0){
               errorToast("Product ID Required");
           }
           else if(PName.length===0){
               errorToast("Product Name Required");
           }
           else if(PPrice.length===0){
               errorToast("Product Price Required");
           }
           else if(PQty.length===0){
               errorToast("Product Quantity Required");
           }
           else{
               let item={product_name:PName,product_id:PId,qty:PQty,sale_price:PTotalPrice};
               InvoiceItemList.push(item);
               $('#create-modal').modal('hide')
               ShowInvoiceItem();
           }
        }

        function addModal(id,name,price) {
            document.getElementById('PId').value=id
            document.getElementById('PName').value=name
            document.getElementById('PPrice').value=price
            $('#create-modal').modal('show')
        }

    async function CustomerList(){
    let res = await axios.get("/list-customer");
    let customerList = $("#customerList");
    let customerTable = $("#customerTable");
    customerTable.DataTable().destroy();
    customerList.empty();

    // Sort so latest customers are first
    res.data.sort((a,b)=> b.id - a.id); // Assuming higher ID = latest

    res.data.forEach(function(item, index){
        let row = `<tr class="text-xs">
                <td><i class="bi bi-person"></i> ${item['name']}</td>
                <td><a data-name="${item['name']}" data-mobile="${item['mobile']}" data-id="${item['id']}" class="btn btn-outline-dark addCustomer text-xxs px-2 py-1 btn-sm m-0">Add</a></td>
             </tr>`;
        customerList.append(row);
    });
$('.addCustomer').on('click', function () {

    let CName = $(this).data('name');
    let CMobile = $(this).data('mobile');
    let CId = $(this).data('id');

    $("#CName").text(CName);
    $("#CMobile").text(CMobile);
    $("#CId").text(CId);
});

    new DataTable('#customerTable',{
         ordering: false,
        scrollCollapse: false,
        info: false,
        lengthChange: false
    });
}
async function SaveCustomer() {
    let name = document.getElementById('customerName').value;
    let email = document.getElementById('customerEmail').value;
    let mobile = document.getElementById('customerMobile').value;

    if (!name || !email || !mobile) return errorToast("All fields required!");

    document.getElementById('modal-close-customer').click();
    showLoader();

    try {
        let res = await axios.post("/create-customer", {name, email, mobile});
        hideLoader();

        if(res.status === 201){
            successToast("Customer Created!");
            document.getElementById("createCustomerForm").reset();

            // Prepend instead of append
            let row = `<tr class="text-xs">
                <td><i class="bi bi-person"></i> ${res.data.name}</td>
                <td><a data-name="${res.data.name}" data-email="${res.data.email}" data-id="${res.data.id}" class="btn btn-outline-dark addCustomer text-xxs px-2 py-1 btn-sm m-0">Add</a></td>
            </tr>`;

            $('#customerList').prepend(row);

            // Rebind click event
            $('.addCustomer').off('click').on('click', function(){
                let CName = $(this).data('name');
              let CMobile = $(this).data('mobile');
                let CId = $(this).data('id');

                $("#CName").text(CName);
               $("#CMobile").text(CMobile);
                $("#CId").text(CId);
            });

        } else {
            errorToast("Request failed!");
        }
    } catch (err) {
        hideLoader();
        errorToast("Something went wrong!");
    }
}
        async function ProductList(){
            let res=await axios.get("/list-product");
            let productList=$("#productList");
            let productTable=$("#productTable");
            productTable.DataTable().destroy();
            productList.empty();

            res.data.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td> <img class="w-10" src="${item['img_url']}"/> ${item['name']} ($ ${item['sell_price']})</td>
                        <td><a data-name="${item['name']}" data-price="${item['sell_price']}" data-id="${item['id']}" class="btn btn-outline-dark text-xxs px-2 py-1 addProduct  btn-sm m-0">Add</a></td>
                     </tr>`
                productList.append(row)
            })

            $('.addProduct').on('click', async function () {
                let PName= $(this).data('name');
                let PPrice= $(this).data('price');
                let PId= $(this).data('id');
                addModal(PId,PName,PPrice)
            })

            new DataTable('#productTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }

        async function createInvoice() {

               const confirm = await Swal.fire({
        title: "Save Invoice?",
        text: "Are you sure you want to save this invoice?",
        icon: "question",
        width: "350px",
        showCancelButton: true,
        confirmButtonText: "Yes, Save",
        cancelButtonText: "No",
        confirmButtonColor: "#0d6efd"
    });

    if(!confirm.isConfirmed){
        return; // stop if user clicks No
    }


            let total=document.getElementById('total').innerText;
            let discount=document.getElementById('discount').innerText
            let vat=document.getElementById('vat').innerText
            let payable=document.getElementById('payable').innerText
            let CId=document.getElementById('CId').innerText;
let CName = document.getElementById('CName').innerText;
let CMobile = document.getElementById('CMobile').innerText;

let finalPaid, finalDue;

if (!window.invoiceDue) {
    // 👉 No due used → FULL PAID
    finalPaid = payable;
    finalDue = 0;
} else {
    finalPaid = window.invoiceDue.paid;
    finalDue = window.invoiceDue.due;
}

let Data = {
    total: total,
    discount: discount,
    vat: vat,
    payable: payable,
    customer_id: CId,
    customer_name: CName,
    customer_mobile: CMobile,
    paid: finalPaid,
    due: finalDue,
    due_date: window.invoiceDue?.dueDate || null,
    items: InvoiceItemList
};

            if(CId.length===0){
                errorToast("Customer Required !")
            }
            else if(InvoiceItemList.length===0){
                errorToast("Product Required !")
            }
            else{
                showLoader();
                let res=await axios.post("/invoice-create",Data)
                hideLoader();
               if(res.data.status === true){
    successToast("Invoice Created");
       // 1. Clear invoice items
    InvoiceItemList = [];
    ShowInvoiceItem();

    // 2. Clear customer info
    $("#CName").text("");
    $("#CMobile").text("");
    $("#CId").text("");

    // 3. Clear totals
    $("#total").text("");
    $("#vat").text("");
    $("#discount").text("");
    $("#payable").text("");

    // 4. Reset discount input
    $("#discountP").val(0);
$("#PQty").val("");

    // 5. Reset due system
    window.invoiceDue = null;

    // 6. Reset due modal fields (optional safety)
    $("#dueTotal").val("");
    $("#paidAmount").val("");
    $("#dueAmount").val("");
    $("#dueDate").val("");

    // 7. Optional UX: scroll back to top
    window.scrollTo({ top: 0, behavior: "smooth" });
} else {
    errorToast(res.data.error || "Something Went Wrong");
}
            }
        }

        // New Customer Modal Functions
        function OpenCustomerModal(){
            $('#createCustomerModal').modal('show');
        }



      function openDueModal() {
    let total = document.getElementById("payable").innerText;

    document.getElementById("dueTotal").value = total;

    // 👉 Restore old values if exist
    if (window.invoiceDue) {
        document.getElementById("paidAmount").value = window.invoiceDue.paid ?? "";
        document.getElementById("dueAmount").value = window.invoiceDue.due ?? "";
        document.getElementById("dueDate").value = window.invoiceDue.dueDate ?? "";
    } else {
        document.getElementById("paidAmount").value = "";
        document.getElementById("dueAmount").value = "";
        document.getElementById("dueDate").value = "";
    }

    $('#dueModal').modal('show');
}

function calculateDue() {
    let total = parseFloat(document.getElementById("dueTotal").value || 0);
    let paid = parseFloat(document.getElementById("paidAmount").value || 0);

    let due = total - paid;

    if (due < 0) due = 0;

    document.getElementById("dueAmount").value = due.toFixed(2);
}

function resetDue() {
    document.getElementById("paidAmount").value = "";
    document.getElementById("dueAmount").value = "";
    document.getElementById("dueDate").value = "";
}

function saveDue() {
    let paidInput = document.getElementById("paidAmount").value;

    // 👉 IF EMPTY = FULL PAID
    if (paidInput === "") {
        window.invoiceDue = {
            paid: parseFloat(document.getElementById("payable").innerText),
            due: 0,
            dueDate: null
        };
        return;
    }

    let paid = parseFloat(paidInput);
    let total = parseFloat(document.getElementById("dueTotal").value);
    let due = total - paid;

    if (due < 0) due = 0;

    window.invoiceDue = {
        paid: paid,
        due: due,
        dueDate: document.getElementById("dueDate").value
    };
}




    </script>

@endsection
