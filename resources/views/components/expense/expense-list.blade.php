<div class="container-fluid">
<div class="row">
<div class="col-md-12 col-sm-12 col-lg-12">

<div class="card px-5 py-5">

<div class="row align-items-center mb-2">

    <!-- LEFT -->
    <div class="col-md-6">
        <h4 class="mb-0">Expense Report</h4>
    </div>

    <!-- RIGHT -->
    <div class="col-md-6 d-flex justify-content-end gap-2">

        <!-- DOWNLOAD PDF (CARD STYLE like Cashbox) -->
        <a href="#"
           onclick="downloadExpensePdf()"
           class="card border-0 shadow-sm px-3 py-2 text-decoration-none">

            <div class="d-flex align-items-center gap-2">
                <div class="icon-circle bg-danger-subtle">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                </div>

                <div>
                    <div class="summary-title text-dark">
                        Download PDF
                    </div>
                </div>
            </div>
        </a>


        <!-- KEEP YOUR ORIGINAL BUTTON (UNCHANGED) -->
        <button data-bs-toggle="modal"
                data-bs-target="#create-expense-modal"
                class="btn m-0 bg-gradient-primary">
            + New Expense
        </button>

    </div>

</div>

<!-- ================= FILTER ================= -->
<div class="row mt-3 mb-3">
    <div class="col-md-4">
    <div class="position-relative w-100">

        <input type="text"
               id="expenseDateFilter"
               class="form-control pe-5"
               placeholder="Select date range">

        <span id="clearExpenseFilter"
              style="
                  position:absolute;
                  right:12px;
                  top:50%;
                  transform:translateY(-50%);
                  cursor:pointer;
                  font-size:20px;
                  color:#999;
                  display:none;
                  line-height:1;
                  user-select:none;
              ">
            ×
        </span>

    </div>
</div>

    <div class="col-md-2">
      <button class="btn btn-sm btn-primary" onclick="applyExpenseFilter()">
    Filter
</button>



</div>

<hr class="bg-dark"/>

<table class="table" id="expenseTable">
    <thead>
    <tr class="bg-light">
        <th>No</th>
        <th>Category</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Notes</th>
        <th>Action</th>
    </tr>
    </thead>

    <tbody id="expenseTableList"></tbody>
</table>

</div>
</div>
</div>
</div>

<!-- ================= LIBRARIES ================= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
function downloadExpensePdf() {

    let url = `/expense/pdf?start_date=${expenseStartDate || ''}&end_date=${expenseEndDate || ''}`;

    window.open(url, '_blank');
}


    $('#expenseDateFilter').daterangepicker({
    autoUpdateInput: false,
    locale: { cancelLabel: 'Clear' }
});

let expenseStartDate = "";
let expenseEndDate = "";
let expenseTable = null;

$('#expenseDateFilter').daterangepicker({
    autoUpdateInput: false,
    locale: { cancelLabel: 'Clear' }
});

$('#expenseDateFilter').on('apply.daterangepicker', function (ev, picker) {

    expenseStartDate = picker.startDate.format('YYYY-MM-DD');
    expenseEndDate = picker.endDate.format('YYYY-MM-DD');

    $(this).val(expenseStartDate + ' to ' + expenseEndDate);

    // show cross button
    $('#clearExpenseFilter').show();
});

$('#expenseDateFilter').on('cancel.daterangepicker', function () {

    clearExpenseFilter();
});


$('#clearExpenseFilter').on('click', function () {

    clearExpenseFilter();
});

function clearExpenseFilter() {

    expenseStartDate = "";
    expenseEndDate = "";

    $('#expenseDateFilter').val('');
    $('#clearExpenseFilter').hide();

    getExpenseList(); // reload default data
}

/* ================= LOAD EXPENSE ================= */
async function getExpenseList() {

    showLoader();

    try {

        let res = await axios.get("/expense-list", {
            params: {
                start_date: expenseStartDate || null,
                end_date: expenseEndDate || null
            }
        });

        hideLoader();

        let data = res.data || [];

        let tableList = $("#expenseTableList");

        // destroy safely
        if (expenseTable !== null) {
            expenseTable.destroy();
        }

        tableList.empty();

        if (data.length === 0) {
            tableList.html(`
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No expense found
                    </td>
                </tr>
            `);
        } else {

            data.forEach((item, index) => {

                tableList.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.category}</td>
                        <td>৳ ${item.amount}</td>
                        <td>${item.date}</td>
                        <td>${item.note ?? ''}</td>
                        <td>
                            <button data-id="${item.id}" class="btn editBtn btn-sm btn-outline-success">
                                Edit
                            </button>

                            <button data-id="${item.id}" class="btn deleteBtn btn-sm btn-outline-danger">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);
            });
        }

        // rebind events
        $(document).off('click', '.editBtn').on('click', '.editBtn', async function () {
            let id = $(this).data('id');
            await FillUpExpenseUpdateForm(id);
            $("#update-expense-modal").modal('show');
        });

        $(document).off('click', '.deleteBtn').on('click', '.deleteBtn', function () {
            let id = $(this).data('id');
            $("#delete-expense-modal").modal('show');
            $("#deleteExpenseID").val(id);
        });

        // init datatable AFTER DOM update
        expenseTable = $('#expenseTable').DataTable({
            order: [[0, 'desc']],
            lengthMenu: [5, 10, 15, 20, 30]
        });

    } catch (error) {
        hideLoader();
        console.log(error);
    }
}


/* ================= DEFAULT LOAD ================= */
$(document).ready(function () {
    getExpenseList();
});

function applyExpenseFilter(){
    getExpenseList();
}
</script>
