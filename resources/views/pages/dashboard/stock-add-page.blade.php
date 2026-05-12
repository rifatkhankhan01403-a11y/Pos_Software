
@extends('layout.sidenav-layout')

@section('content')
<style>
    .product-suggest-box{
        position:absolute;
        top:100%;
        left:0;
        right:0;
        z-index:1050;
        max-height:220px;
        overflow-y:auto;
        display:none;
    }
    .product-suggest-item{
        cursor:pointer;
    }
</style>

<div class="container-fluid py-2">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0 fw-semibold">Stock Purchase</h5>
    </div>

    <form id="purchaseForm">
        @csrf

        {{-- SUPPLIER INFO --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-3 col-md-4 col-12">
                        <label class="form-label small text-muted mb-0">Supplier <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <select id="supplier_id" name="supplier_id" class="form-select">
                                <option value="">Select Supplier</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSupplierModal">+</button>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small text-muted mb-0">Phone</label>
                        <input type="text" id="supplier_phone" name="supplier_phone" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-lg-3 col-md-4 col-6">
                        <label class="form-label small text-muted mb-0">Address</label>
                        <input type="text" id="supplier_address" name="supplier_address" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small text-muted mb-0">Invoice No</label>
                        <input type="text" id="invoice_no" name="invoice_no" class="form-control form-control-sm" readonly value="">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small text-muted mb-0">Purchase Date</label>
                        <input type="date" id="purchase_date" name="purchase_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2">
            {{-- PRODUCT INFO --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body py-2">
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small text-muted mb-0">Product Name <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm position-relative">
                                    <div class="position-relative flex-grow-1">
                                        <input type="text" id="product_name_search" class="form-control form-control-sm" placeholder="Search / type product name" autocomplete="off">
                                        <div id="productSuggestions" class="list-group product-suggest-box shadow-sm"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#create-modal">+</button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0">Category</label>
                                <select class="form-select form-select-sm" id="product_category" name="product_category">
                                    <option value="">Select Category</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Stationery">Stationery</option>
                                </select>
                            </div>
                           <div class="col-md-6">
    <label class="form-label small text-muted mb-0">Current Stock</label>
    <input type="number" id="current_stock" class="form-control form-control-sm" readonly value="0">
</div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0">Quantity <span class="text-danger">*</span></label>
                                <input type="number" min="1" step="1" id="product_qty" class="form-control form-control-sm" placeholder="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0">Buy Price <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="1" id="buy_price" class="form-control form-control-sm" placeholder="">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0">Sell Price <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="1" id="sell_price" class="form-control form-control-sm" placeholder="">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0">Note</label>
                                <input type="text" id="product_note" class="form-control form-control-sm" placeholder="Optional note...">
                            </div>

                            <div class="col-12 pt-1">
                                <button type="button" class="btn btn-primary w-100 btn-sm fw-semibold" id="add_product_btn">
                                    Add Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           {{-- PURCHASE ITEMS + SUMMARY --}}
<div class="col-lg-7">
    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body py-2">

            {{-- PURCHASE ITEMS TABLE --}}
            <div class="table-responsive rounded-3 border">
                <table class="table table-sm align-middle mb-0 text-center">
                    <thead class="table-light">
                       <tr>
    <th style="width:10px;">SL</th>
    <th style="width:25%;">Name</th>
    <th style="width:20%;">Qty</th>
    <th style="width:25%;">Buy</th>
    <th style="width:25%;">Sell</th>
    <th style="width:3px;"></th>
</tr>
                    </thead>
               <tbody id="purchaseList">
    <tr class="purchase-row" id="defaultRow" style="background-color:#f8f9fa">
        <td class="serial">1</td>
        <td>
            <input type="hidden" name="items[0][product_id]" value="">
            <input type="text" class="form-control form-control-sm row-product-name" name="items[0][product_name]" value="">
            <input type="hidden" name="items[0][category]" value="">
        </td>
     <td><input type="number" name="items[0][qty]" class="form-control form-control-sm row-qty" value=""></td>

<td><input type="number" name="items[0][buy_price]" class="form-control form-control-sm row-buy" value=""></td>

<td><input type="number" name="items[0][sell_price]" class="form-control form-control-sm row-sell" value=""></td>
        <td class="text-end">
            <button type="button" class="btn btn-outline-danger btn-sm p-0 remove-row-btn"
                style="width:24px; height:24px;">×</button>
        </td>
    </tr>
</tbody>
                </table>
            </div>

        {{-- final summary  box --}}

<div class="card border rounded-4 bg-light-subtle p-3 mt-3 shadow-sm">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0 fw-semibold text-primary">Final Summary</h6>
        <div class="w-45 ms-2">
            <label class="form-label small text-muted mb-1">Supplier Name</label>
            <input readonly class="form-control form-control-sm" id="summary_supplier_name" value="ABC Supplier">
        </div>
    </div>


    <div class="row g-1 align-items-center text-center">
        <div class="col-md-2 col-6">
            <label class="form-label small text-muted mb-1">Total Qty</label>
            <input readonly class="form-control form-control-sm text-center fw-semibold" id="summary_total_qty" value="0">
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label small text-muted mb-1">Total Cost</label>
            <input readonly class="form-control form-control-sm text-center fw-semibold" id="summary_total_cost" value="0">
        </div>
        <div class="col-md-4 col-6">
            <label class="form-label small text-muted mb-1">Paid</label>
    <input type="text"

           class="form-control form-control-sm text-center fw-semibold"
           id="paid_amount"
           value="">
        </div>
        <div class="col-md-3 col-6">
           <label class="form-label small text-muted mb-1">Due</label>
    <input readonly
           class="form-control form-control-sm text-center fw-semibold"
           id="summary_total_due"
           value="0">
        </div>
    </div>
</div>
         {{-- DUE PAYMENT PLAN --}}
{{-- DUE PAYMENT PLAN --}}
<div class="card border-0 shadow-sm rounded-4 mt-3">
    <div class="card-body py-2">


    <button type="button"
        class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
        id="toggle_due_btn">

        Add Due Schedule
        <span id="due_arrow">▾</span>
    </button>
</div>

        <div id="dueDrawer" style="display:none; margin-top:10px;">

            <div class="table-responsive border rounded-3">
                <table class="table table-sm mb-0 align-middle">
                   <thead class="table-light">
<tr class="small">
    <th>Date</th>
    <th>Paid</th>
    <th>Due</th>
    <th>Note</th>
    <th></th>
</tr>
</thead>
                    <tbody id="dueScheduleBody"></tbody>
                </table>
            </div>

            {{-- <button type="button"
                class="btn btn-outline-secondary btn-sm mt-2"
                id="add_due_row_btn">
                + Add Row
            </button> --}}
        </div>

    </div>
</div>
            <button type="submit" class="btn btn-success w-100 mt-2 py-2 btn-sm fw-semibold" id="save_purchase_btn">
                Save Purchase
            </button>

        </div>
    </div>
</div>
        </div>
    </form>
</div>

<script>
    let itemIndex = 1, dueIndex = 0;
    let paidManuallyChanged = false
    const purchaseList = document.getElementById('purchaseList');
  function generateInvoiceNo() {
    const now = new Date();

    // 4-digit time (HHMM)
    const time =
        String(now.getHours()).padStart(2, '0') +
        String(now.getMinutes()).padStart(2, '0');

    // 4-digit random
    const random = Math.floor(1000 + Math.random() * 9000);

    return `INV${time}${random}`;
}
    const dueScheduleBody = document.getElementById('dueScheduleBody');
    const productSuggestions = document.getElementById('productSuggestions');

    let productData = [];
    let selectedProduct = null;

    function toNumber(val){ return parseFloat(val)||0; }
   function formatMoney(val){
    return Number(val || 0);
}
  function recalcTotals(){

    let totalQty = 0, totalCost = 0;

    document.querySelectorAll('.purchase-row').forEach((row,i)=>{

        const qty = toNumber(row.querySelector('.row-qty')?.value);
        const buy = toNumber(row.querySelector('.row-buy')?.value);

        totalQty += qty;
        totalCost += qty * buy;

        const serialCell = row.querySelector('.serial');
        if (serialCell) serialCell.textContent = i+1;

    });

    let paidInput = document.getElementById('paid_amount');
    let paid = toNumber(paidInput.value);

    // ⭐ NEW LOGIC
    // if paid empty → assume full payment
 if(!paidManuallyChanged){
    paid = totalCost;
    paidInput.value = paid;
}

    const due = totalCost - paid;

    document.getElementById('summary_total_qty').value = totalQty;
    document.getElementById('summary_total_cost').value = formatMoney(totalCost);
    document.getElementById('summary_total_due').value = formatMoney(due < 0 ? 0 : due);

    const supplierName = document.getElementById('supplier_id').selectedOptions[0]?.text || '';
    document.getElementById('summary_supplier_name').value =
        supplierName !== 'Select Supplier' ? supplierName : '';

    // ⭐ Sync Due Table
    const row = document.querySelector('#dueScheduleBody tr');

    if(row){

        const paidInputRow = row.querySelector('.due-paid');
        const dueInputRow = row.querySelector('.due-amount');

        if(paidInputRow) paidInputRow.value = paid;
        if(dueInputRow) dueInputRow.value = due > 0 ? due : 0;

    }

}
   function createPurchaseRow(data){

    const defaultRow = document.getElementById('defaultRow');

    // 👉 If default row exists → replace it
    if(defaultRow){
        defaultRow.querySelector('[name*="[product_id]"]').value = data.product_id || '';
        defaultRow.querySelector('.row-product-name').value = data.product_name || '';
        defaultRow.querySelector('[name*="[category]"]').value = data.category || '';
        defaultRow.querySelector('.row-qty').value = data.qty || 1;
        defaultRow.querySelector('.row-buy').value = data.buy_price || 0;
        defaultRow.querySelector('.row-sell').value = data.sell_price || 0;

        defaultRow.removeAttribute('id');

        defaultRow.querySelectorAll('.row-qty,.row-buy').forEach(input => {
            input.addEventListener('input', recalcTotals);
        });

        defaultRow.querySelector('.remove-row-btn').addEventListener('click', ()=>{
            defaultRow.remove();
            recalcTotals();
        });

        recalcTotals();
        return;
    }

    // 👉 Normal add after first row used
    const tr = document.createElement('tr');
    tr.className = 'purchase-row';

    tr.innerHTML = `
        <td class="serial"></td>
        <td>
            <input type="hidden" name="items[${itemIndex}][product_id]" value="${data.product_id || ''}">
            <input type="text" class="form-control form-control-sm row-product-name" name="items[${itemIndex}][product_name]" value="${data.product_name || ''}">
            <input type="hidden" name="items[${itemIndex}][category]" value="${data.category || ''}">
        </td>
     <td><input type="number" name="items[${itemIndex}][qty]" class="form-control form-control-sm row-qty" value="${data.qty || 1}"></td>

<td><input type="number" name="items[${itemIndex}][buy_price]" class="form-control form-control-sm row-buy" value="${data.buy_price || 0}"></td>

<td><input type="number" name="items[${itemIndex}][sell_price]" class="form-control form-control-sm row-sell" value="${data.sell_price || 0}"></td>
        <td class="text-end">
            <button type="button" class="btn btn-outline-danger btn-sm p-0 remove-row-btn"
                style="width:24px; height:24px;">×</button>
        </td>
    `;

    tr.querySelectorAll('.row-qty,.row-buy').forEach(input => {
        input.addEventListener('input', recalcTotals);
    });

    tr.querySelector('.remove-row-btn').addEventListener('click', ()=>{
        tr.remove();
        recalcTotals();
    });

    purchaseList.appendChild(tr);
    itemIndex++;
    recalcTotals();
}

 function addDueRow(dueDate = '', paid = '', note = '') {

    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td>
            <input type="date"
                class="form-control form-control-sm"
                name="due_plan[${dueIndex}][due_date]"
                value="${dueDate}">
        </td>

        <td>
            <input type="number"
                min="0"
                step="1"
                class="form-control form-control-sm due-paid"
                value="${paid}">
        </td>

        <td>
            <input type="number"
                class="form-control form-control-sm due-amount"
                name="due_plan[${dueIndex}][amount]"
                readonly
                value="">
        </td>

        <td>
            <input type="text"
                class="form-control form-control-sm"
                name="due_plan[${dueIndex}][note]"
                value="${note}"
                placeholder="Optional">
        </td>

        <td class="text-center">
            <button type="button"
                class="btn btn-outline-danger btn-sm p-0 remove-due-btn"
                style="width:24px;height:24px;">
                ×
            </button>
        </td>
    `;

    const paidInput = tr.querySelector('.due-paid');
    const dueInput = tr.querySelector('.due-amount');

    paidInput.addEventListener('input', () => {

        const totalCost = toNumber(document.getElementById('summary_total_cost').value);
        const paidVal = toNumber(paidInput.value);

        const dueVal = totalCost - paidVal;

        dueInput.value = dueVal > 0 ? dueVal : 0;

        document.getElementById('paid_amount').value = paidVal;
        recalcTotals();
    });

    tr.querySelector('.remove-due-btn').addEventListener('click', () => {
        tr.remove();
    });

    dueScheduleBody.appendChild(tr);
    dueIndex++;
}
let supplierData = [];

async function loadSupplierList(selectedId = null){
    try{
        const res = await axios.get('/list-supplier', {
            headers: {
                'id': localStorage.getItem('user_id')
            }
        });

        supplierData = Array.isArray(res.data) ? res.data : [];

        // 🔥 latest first (if backend not already sorted)
        supplierData = supplierData.reverse();

        const select = document.getElementById('supplier_id');
        select.innerHTML = `<option value="">Select Supplier</option>`;

        supplierData.forEach(item=>{
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            select.appendChild(opt);
        });

        // ✅ auto select newly created supplier
        if(selectedId){
            select.value = selectedId;
            select.dispatchEvent(new Event('change'));
        }

    }catch(err){
        console.error("Supplier Load Error:", err);
    }
}
    async function loadProductList(){
        try{
           const res = await axios.get('/list-product');

productData = Array.isArray(res.data)
    ? res.data
    : (res.data?.data ?? [])
        }catch(err){
            console.error(err);
            productData = [];
        }
    }

    function hideProductSuggestions(){
        productSuggestions.style.display = 'none';
        productSuggestions.innerHTML = '';
    }

   function fillProductFields(product){
    selectedProduct = product || null;

    document.getElementById('product_name_search').value = product?.name || '';

    const buy = product?.buy_price ?? '';
    const sell = product?.sell_price ?? '';
    const categoryName = product?.category?.name || '';
    const stock = product?.quantity ?? 0;
    const note = product?.note ?? '';

    document.getElementById('buy_price').value = buy;
    document.getElementById('sell_price').value = sell;
    document.getElementById('current_stock').value = stock;
    document.getElementById('product_note').value = note;

    const categorySelect = document.getElementById('product_category');
if(categoryName){
    setTimeout(()=>{
        categorySelect.value = categoryName;
    },100);
}

    hideProductSuggestions();
}


let categoryData = [];

async function loadCategoryList(){
    try{
        const res = await axios.get('/list-category');

        categoryData = res.data || [];

        const select = document.getElementById('product_category');

        select.innerHTML = `<option value="">Select Category</option>`;

        categoryData.forEach(item=>{
            select.innerHTML += `<option value="${item.name}">${item.name}</option>`;
        });

    }catch(err){
        console.error("Category Load Error:", err);
    }
}


    function renderProductSuggestions(query=''){
        const q = query.trim().toLowerCase();
        if(!q){
            hideProductSuggestions();
            return;
        }

        const matches = productData.filter(item =>
            (item.name || '').toLowerCase().includes(q)
        ).slice(0, 8);

        if(matches.length === 0){
            productSuggestions.innerHTML = `<div class="list-group-item small text-muted">No product found</div>`;
            productSuggestions.style.display = 'block';
            return;
        }

        productSuggestions.innerHTML = '';
        matches.forEach(item=>{
            const div = document.createElement('button');
            div.type = 'button';
            div.className = 'list-group-item list-group-item-action product-suggest-item';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span>${item.name || ''}</span>
                    <small class="text-muted">${item.buy_price ?? ''}</small>
                </div>
            `;
            div.addEventListener('click', ()=>fillProductFields(item));
            productSuggestions.appendChild(div);
        });

        productSuggestions.style.display = 'block';
    }

    document.getElementById('product_name_search').addEventListener('input', function(){
        selectedProduct = null;
        renderProductSuggestions(this.value);
    });

    document.getElementById('product_name_search').addEventListener('focus', function(){
        renderProductSuggestions(this.value);
    });

    document.addEventListener('click', function(e){
        const wrap = document.getElementById('product_name_search')?.closest('.position-relative');
        if(wrap && !wrap.contains(e.target)){
            hideProductSuggestions();
        }
    });

    document.getElementById('add_product_btn').addEventListener('click', ()=>{
        const product_name = document.getElementById('product_name_search').value.trim();
        const category = document.getElementById('product_category').value.trim();
        const qty = document.getElementById('product_qty').value;
        const buy_price = document.getElementById('buy_price').value;
        const sell_price = document.getElementById('sell_price').value;

        if(!product_name || !qty || !buy_price || !sell_price){
            alert('Fill all required product fields');
            return;
        }

        const matchedProduct = selectedProduct || productData.find(p => (p.name || '').toLowerCase() === product_name.toLowerCase());

        createPurchaseRow({
            product_id: matchedProduct?.id || '',
            product_name,
            category: category || matchedProduct?.category?.name || '',
            qty,
            buy_price,
            sell_price
        });

        ['product_name_search','product_category','product_qty','buy_price','sell_price','product_note', 'current_stock'].forEach(id=>{
            const el = document.getElementById(id);
            if(el) el.value = '';
        });

        selectedProduct = null;
        hideProductSuggestions();
    });

document.getElementById('paid_amount').addEventListener('input', function(){

    paidManuallyChanged = true; // user manually edited paid

    recalcTotals();

    const paid = toNumber(this.value);
    const totalCost = toNumber(document.getElementById('summary_total_cost').value);
    const due = totalCost - paid;

    const row = document.querySelector('#dueScheduleBody tr');

    if(row){

        const paidInput = row.querySelector('.due-paid');
        const dueInput = row.querySelector('.due-amount');

        if(paidInput) paidInput.value = paid;
        if(dueInput) dueInput.value = due > 0 ? due : 0;
    }

});

    document.getElementById('supplier_id').addEventListener('change', async function(){
        recalcTotals();

        const id = this.value;
        if(!id){
            document.getElementById('supplier_phone').value = '';
            document.getElementById('supplier_address').value = '';
            return;
        }

        try{
           const res = await axios.post('/supplier-by-id',
    { id: id },
    {
        headers: {
            'id': localStorage.getItem('user_id')
        }
    }
);
            document.getElementById('supplier_phone').value = res.data?.mobile || '';
            document.getElementById('supplier_address').value = res.data?.address || '';
        }catch(err){
            console.error(err);
        }
    });

  // document.getElementById('add_due_row_btn')
  //  .addEventListener('click', () => addDueRow());

   document.getElementById('purchaseForm').addEventListener('submit', async function(e){
    e.preventDefault();
  const confirm = await Swal.fire({
        title: "Save Purchase?",
        text: "Do you want to save this purchase record?",
        icon: "question",
        width: "400px",

        showCancelButton: true,
        confirmButtonText: "Yes, Save",
        cancelButtonText: "No",
        confirmButtonColor: "#28a745"
    });

    if(!confirm.isConfirmed){
        return; // user clicked N
    }
 const paid = parseFloat(document.getElementById('paid_amount').value) || 0;

    // ❌ VALIDATION (ADD HERE)
    if (paid < 0) {
        Toastify({
            text: "Paid amount is required (must be greater than 0)",
            duration: 3000,
            gravity: "top",
            position: "center",
            backgroundColor: "red",
        }).showToast();

        document.getElementById('paid_amount').focus();
        return; // 🚫 STOP FORM SUBMIT
    }
        try{
            const items = [];
            document.querySelectorAll('#purchaseList .purchase-row').forEach(row=>{
                const product_id = row.querySelector('[name*="[product_id]"]')?.value || '';
                const product_name = row.querySelector('[name*="[product_name]"]')?.value || '';
                const category = row.querySelector('[name*="[category]"]')?.value || '';
              const qty = row.querySelector('.row-qty')?.value || 0;
const buy_price = row.querySelector('.row-buy')?.value || 0;
const sell_price = row.querySelector('.row-sell')?.value || 0;

                if(product_name || qty || buy_price || sell_price){
                    items.push({
                        product_id,
                        product_name,
                        category,
                        qty,
                        buy_price,
                        sell_price
                    });
                }
            });

           const due_plan = [];
document.querySelectorAll('#dueScheduleBody tr').forEach(row=>{

    const amount = row.querySelector('.due-amount')?.value || 0;

    due_plan.push({
        due_date: row.querySelector('[name*="[due_date]"]')?.value || '',
        amount: amount,
        note: row.querySelector('[name*="[note]"]')?.value || ''
    });

});

            const formData = new FormData();
            formData.append('supplier_id', document.getElementById('supplier_id').value);
            formData.append('invoice_no', document.getElementById('invoice_no').value);
            formData.append('purchase_date', document.getElementById('purchase_date').value);
            formData.append('paid_amount', document.getElementById('paid_amount').value);
            formData.append('items', JSON.stringify(items));
            formData.append('due_plan', JSON.stringify(due_plan));

            const res = await axios.post('/stock-store', formData, {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

          if(res.data && res.data.status === 'success'){
   Toastify({
    text: "Purchase Saved Successfully",
    duration: 3000,
    gravity: "top",
    position: "right",
    backgroundColor: "#28a745"
}).showToast();
    document.getElementById('invoice_no').value = generateInvoiceNo();

                document.getElementById('purchaseForm').reset();
                document.getElementById('purchaseList').innerHTML = '';
                document.getElementById('dueScheduleBody').innerHTML = '';

                itemIndex = 1;
                dueIndex = 0;
                addDueRow();
                recalcTotals();
            }else{
               Toastify({
    text: "Request Failed",
    duration: 3000,
    gravity: "top",
    position: "right",
    backgroundColor: "red"
}).showToast();
            }
        }catch(err){
            console.error(err);
           Toastify({
    text: "Error Saving Purchase",
    duration: 3000,
    gravity: "top",
    position: "right",
    backgroundColor: "red"
}).showToast();
        }
    });

(async function init(){

    // 🔥 set instantly (no waiting)
    const today = new Date();
    const localDate = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
        .toISOString()
        .split('T')[0];

    document.getElementById('purchase_date').value = localDate;

    // invoice also instant
    document.getElementById('invoice_no').value = generateInvoiceNo();

    // then API calls
    await loadSupplierList();
    await loadProductList();
    await loadCategoryList();

    addDueRow();
    recalcTotals();

})();

document.getElementById('toggle_due_btn').addEventListener('click', function () {
    const drawer = document.getElementById('dueDrawer');
    const arrow = document.getElementById('due_arrow');

    if (drawer.style.display === 'none') {
        drawer.style.display = 'block';
        arrow.innerHTML = '▴'; // up arrow
    } else {
        drawer.style.display = 'none';
        arrow.innerHTML = '▾'; // down arrow
    }
});



async function loadSupplierList(selectedId = null){
    try{
        const res = await axios.get('/list-supplier', {
            headers: {
                'id': localStorage.getItem('user_id')
            }
        });

        supplierData = Array.isArray(res.data) ? res.data : [];

        // 🔥 latest first (if backend not already sorted)
        supplierData = supplierData.reverse();

        const select = document.getElementById('supplier_id');
        select.innerHTML = `<option value="">Select Supplier</option>`;

        supplierData.forEach(item=>{
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            select.appendChild(opt);
        });

        // ✅ auto select newly created supplier
        if(selectedId){
            select.value = selectedId;
            select.dispatchEvent(new Event('change'));
        }

    }catch(err){
        console.error("Supplier Load Error:", err);
    }
}

</script>
@endsection
