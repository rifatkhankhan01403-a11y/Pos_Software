<div class="modal animated zoomIn" id="update-expense-modal">

<div class="modal-dialog modal-md modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Update Expense</h5>
</div>

<div class="modal-body">

<form id="update-expense-form">

<label class="form-label">Date</label>
<input type="date" class="form-control" id="expenseDateUpdate">

<label class="form-label mt-3">Category</label>

<select class="form-control" id="expenseCategoryUpdate">

<option value="Salary">Salary</option>
<option value="Rent">Rent</option>
<option value="Purchase">Purchase</option>
<option value="Bill">Bill</option>

</select>

<label class="form-label mt-3">Amount</label>
<input type="number" class="form-control" id="expenseAmountUpdate">

<label class="form-label mt-3">Note</label>
<textarea class="form-control" id="expenseNoteUpdate"></textarea>

<input type="hidden" id="updateExpenseID">

</form>

</div>

<div class="modal-footer">

<button id="update-expense-close"
class="btn bg-gradient-primary"
data-bs-dismiss="modal">
Close
</button>

<button onclick="UpdateExpense()"
class="btn bg-gradient-success">
Update
</button>

</div>

</div>
</div>
</div>



<script>

async function FillUpExpenseUpdateForm(id){

document.getElementById('updateExpenseID').value=id

showLoader()

let res=await axios.post("/expense-by-id",{id:id})

hideLoader()

document.getElementById('expenseDateUpdate').value=res.data['date']
document.getElementById('expenseCategoryUpdate').value=res.data['category']
document.getElementById('expenseAmountUpdate').value=res.data['amount']
document.getElementById('expenseNoteUpdate').value=res.data['note']

}



async function UpdateExpense(){

let id=document.getElementById('updateExpenseID').value
let date=document.getElementById('expenseDateUpdate').value
let category=document.getElementById('expenseCategoryUpdate').value
let amount=document.getElementById('expenseAmountUpdate').value
let note=document.getElementById('expenseNoteUpdate').value


document.getElementById('update-expense-close').click()

showLoader()

let res=await axios.post("/update-expense",{
id:id,
date:date,
category:category,
amount:amount,
note:note
})

hideLoader()

if(res.data===1){

successToast("Request completed")

await getExpenseList()

}
else{
errorToast("Request fail")
}

}

</script>
