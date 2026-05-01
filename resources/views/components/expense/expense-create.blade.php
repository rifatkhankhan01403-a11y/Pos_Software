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
// ==============================
// SAFE LOCAL DATE FUNCTION
// ==============================
function getLocalDate() {
    let d = new Date();

    let year = d.getFullYear();
    let month = String(d.getMonth() + 1).padStart(2, '0');
    let day = String(d.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}


// ==============================
// SET DEFAULT DATE ON OPEN
// ==============================
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('expenseDate').value = getLocalDate();
});


// ==============================
// SAVE EXPENSE
// ==============================
async function SaveExpense() {

    let date = document.getElementById('expenseDate').value;
    let category = document.getElementById('expenseCategory').value;
    let amount = document.getElementById('expenseAmount').value;
    let note = document.getElementById('expenseNote').value;

    // ================= VALIDATION =================
    if (!date) {
        errorToast("Date Required");
        return;
    }

    if (!category) {
        errorToast("Category Required");
        return;
    }

    if (!amount || amount <= 0) {
        errorToast("Valid Amount Required");
        return;
    }

    // ================= REQUEST =================
    document.getElementById('expense-modal-close').click();
    showLoader();

    try {

        let res = await axios.post("/create-expense", {
            date: date,
            category: category,
            amount: amount,
            note: note
        });

        hideLoader();

        if (res.status === 201) {

            successToast("Expense Created Successfully");

            // reset form
            document.getElementById("expense-save-form").reset();

            // reset date correctly
            document.getElementById('expenseDate').value = getLocalDate();

            await getExpenseList();

        } else {
            errorToast("Request failed");
        }

    } catch (error) {
        hideLoader();
        errorToast("Server Error");
        console.log(error);
    }
}

</script>
