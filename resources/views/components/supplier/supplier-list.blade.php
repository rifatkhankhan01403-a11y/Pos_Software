<div class="card p-3">
    <div class="d-flex justify-content-between mb-3">
        <h5>Suppliers</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSupplierModal">Add Supplier</button>
    </div>
    <table class="table table-sm" id="supplierTable">
        <thead>
            <tr class="bg-light">
                <th>ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Address</th>
                <th>Note</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="supplierTableBody">
            <!-- Dynamic Rows -->
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
async function loadSuppliers() {
    showLoader(); // Your loader function
    let res = await axios.get('/list-supplier', {
        headers: { 'id': localStorage.getItem('user_id') } // Or how you store user_id
    });
    hideLoader();

    const tableBody = $("#supplierTableBody");
    const table = $("#supplierTable");

    // Destroy previous DataTable if exists
    if ($.fn.DataTable.isDataTable('#supplierTable')) {
        table.DataTable().destroy();
    }
    tableBody.empty();

    res.data.forEach((s, index) => {
        const row = `
        <tr>
            <td>${s.id}</td>
            <td>${s.name}</td>
            <td>${s.mobile || ''}</td>
            <td>${s.email || ''}</td>
            <td>${s.address || ''}</td>
            <td>${s.note || ''}</td>
            <td>${s.img_url ? `<img src="${s.img_url}" width="50">` : ''}</td>
            <td>
                <button data-id="${s.id}" data-path="${s.img_url || ''}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                <button data-id="${s.id}" data-path="${s.img_url || ''}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
            </td>
        </tr>`;
        tableBody.append(row);
    });

    // Edit button click
    $('.editBtn').on('click', async function() {
        const id = $(this).data('id');
        const path = $(this).data('path');
        await fillSupplierUpdateForm(id, path); // Define this function to fill modal
        $("#updateSupplierModal").modal('show');
    });

    // Delete button click
    $('.deleteBtn').on('click', function() {
        const id = $(this).data('id');
        const path = $(this).data('path');
        $("#deleteSupplierModal").modal('show');
        $("#deleteID").val(id);
        $("#deleteFilePath").val(path);
    });

    // Initialize DataTable
    new DataTable('#supplierTable', {
        order: [[0, 'desc']],
        lengthMenu: [5, 10, 15, 20, 30]
    });
}

// Call on page load
$(document).ready(function() {
    loadSuppliers();
});
</script>
