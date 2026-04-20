<div class="container-fluid">
<div class="row">
<div class="col-md-12 col-sm-12 col-lg-12">
<div class="card px-5 py-5">

<div class="row justify-content-between">
<div class="align-items-center col">
<h4>Expense Report</h4>
</div>

<div class="align-items-center col">
<button data-bs-toggle="modal" data-bs-target="#create-expense-modal"
class="float-end btn m-0 bg-gradient-primary">
+ New Expense
</button>
</div>
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


<script>

getExpenseList();

async function getExpenseList(){

showLoader();

let res=await axios.get("/expense-list");

hideLoader();

let tableList=$("#expenseTableList");
let tableData=$("#expenseTable");

tableData.DataTable().destroy();
tableList.empty();

res.data.forEach(function(item,index){

let row=`<tr>
<td>${index+1}</td>
<td>${item['category']}</td>
<td>৳ ${item['amount']}</td>
<td>${item['date']}</td>
<td>${item['note']??''}</td>

<td>

<button data-id="${item['id']}"
class="btn editBtn btn-sm btn-outline-success">
Edit
</button>

<button data-id="${item['id']}"
class="btn deleteBtn btn-sm btn-outline-danger">
Delete
</button>

</td>
</tr>`

tableList.append(row)

})

$('.editBtn').on('click',async function(){

let id=$(this).data('id');

await FillUpExpenseUpdateForm(id);

$("#update-expense-modal").modal('show');

})


$('.deleteBtn').on('click',function(){

let id=$(this).data('id');

$("#delete-expense-modal").modal('show');

$("#deleteExpenseID").val(id);

})


new DataTable('#expenseTable',{
order:[[0,'desc']],
lengthMenu:[5,10,15,20,30]
})

}

</script>
