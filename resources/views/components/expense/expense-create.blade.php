<div class="modal animated zoomIn" id="create-expense-modal">

<div class="modal-dialog modal-md modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Add Expense</h5>
</div>


<div class="modal-body">

<form id="expense-save-form">

<div class="container">
<div class="row">

<div class="col-12 p-1">

<label class="form-label">Expense Date *</label>
<input type="date" class="form-control" id="expenseDate">

<label class="form-label mt-3">Category *</label>

<select class="form-control" id="expenseCategory">
<option value="">Select Category</option>
<option value="Salary">Salary</option>
<option value="Rent">Rent</option>
<option value="Purchase">Purchase</option>
<option value="Bill">Bill</option>
</select>

<label class="form-label mt-3">Amount *</label>
<input type="number" step="1" min="1" class="form-control" id="expenseAmount">

<label class="form-label mt-3">Expense Reason</label>
<textarea class="form-control" id="expenseNote"></textarea>

</div>

</div>
</div>

</form>

</div>


<div class="modal-footer">

<button id="expense-modal-close"
class="btn bg-gradient-primary"
data-bs-dismiss="modal">
Close
</button>

<button onclick="SaveExpense()"
class="btn bg-gradient-success">
Save
</button>

</div>

</div>
</div>
</div>



<script>

document.getElementById('expenseDate').valueAsDate=new Date();


async function SaveExpense(){

let date=document.getElementById('expenseDate').value
let category=document.getElementById('expenseCategory').value
let amount=document.getElementById('expenseAmount').value
let note=document.getElementById('expenseNote').value


if(date.length===0){
errorToast("Date Required")
}
else if(category.length===0){
errorToast("Category Required")
}
else if(amount.length===0 || amount<=0){
errorToast("Valid Amount Required")
}

else{

document.getElementById('expense-modal-close').click()

showLoader()

let res=await axios.post("/create-expense",{
date:date,
category:category,
amount:amount,
note:note
})

hideLoader()

if(res.status===201){

successToast("Request completed")

document.getElementById("expense-save-form").reset()
document.getElementById('expenseDate').valueAsDate=new Date();
await getExpenseList()

}
else{
errorToast("Request fail")
}

}

}

</script>
