<div class="modal animated zoomIn" id="delete-expense-modal">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-body text-center">

<h3 class="mt-3 text-warning">Delete !</h3>

<p class="mb-3">
Once delete, you can't get it back.
</p>

<input class="d-none" id="deleteExpenseID"/>

</div>

<div class="modal-footer justify-content-end">

<button
id="delete-expense-close"
class="btn mx-2 bg-gradient-primary"
data-bs-dismiss="modal">
Cancel
</button>

<button
onclick="deleteExpense()"
class="btn bg-gradient-danger">
Delete
</button>

</div>

</div>
</div>
</div>


<script>

async function deleteExpense(){

let id=document.getElementById('deleteExpenseID').value

document.getElementById('delete-expense-close').click()

showLoader()

let res=await axios.post("/delete-expense",{id:id})

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
