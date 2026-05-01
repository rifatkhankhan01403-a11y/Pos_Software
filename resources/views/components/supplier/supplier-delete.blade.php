<!-- Delete Supplier Modal -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h5 class="modal-title">Delete Supplier</h5>
            <form id="deleteSupplierForm">
                @csrf
                <input type="hidden" id="deleteID">
                <input type="hidden" id="deleteFilePath">
                <p>Are you sure you want to delete this supplier?</p>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" onclick="deleteSupplier()" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function deleteSupplier() {
    const id = $("#deleteID").val();
    const file_path = $("#deleteFilePath").val();

    try {
        showLoader(); // Your loader function
        let res = await axios.post('/delete-supplier', {
            id: id,
            file_path: file_path,
        }, {
            headers: { 'id': localStorage.getItem('user_id') } // Your user ID header
        });
        hideLoader();

      if(res.status === 200) {

    const modalEl = document.getElementById('deleteSupplierModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();

    successToast('Supplier deleted successfully');

    await loadSuppliers(); // IMPORTANT: wait for reload
}
    } catch (err) {
        hideLoader();
        console.error(err);
        showMessage('Failed to delete supplier', 'danger');
    }
}

// Optional: clear modal hidden inputs on close
$('#deleteSupplierModal').on('hidden.bs.modal', function () {
    $("#deleteID").val('');
    $("#deleteFilePath").val('');
});
</script>
