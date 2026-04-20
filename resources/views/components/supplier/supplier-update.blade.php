<!-- Update Supplier Modal -->
<div class="modal animated zoomIn" id="updateSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">

            <div class="modal-header">
                <h5 class="modal-title">Update Supplier</h5>
            </div>

            <div class="modal-body">
                <form id="updateSupplierForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="updateSupplierId">

                    <div class="mb-2">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" id="updateSupplierName" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" id="updateSupplierEmail" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Mobile <span class="text-danger">*</span></label>
                        <input type="text" id="updateSupplierMobile" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Address</label>
                        <textarea id="updateSupplierAddress" class="form-control"></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Note</label>
                        <textarea id="updateSupplierNote" class="form-control"></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Image</label>
                        <br/>
                        <img id="updateSupplierOldImg" src="{{ asset('images/default.jpg') }}" class="w-15 mb-2"/>
                        <input type="file" id="updateSupplierImg" class="form-control"
                               oninput="updateSupplierOldImg.src=window.URL.createObjectURL(this.files[0])">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="updateSupplier()" class="btn btn-success">Update</button>
            </div>

        </div>
    </div>
</div>

<script>
let currentFilePath = null;

// ================= FILL UPDATE FORM =================
async function FillUpUpdateForm(id, file_path = null) {
    console.log("Edit clicked");
    try {
        showLoader();

        let res = await axios.post('/supplier-by-id', { id }, {
            headers: { 'id': localStorage.getItem('user_id') }
        });

        hideLoader();

        if(res.data){

            const supplier = res.data;

            $("#updateSupplierId").val(supplier.id);
            $("#updateSupplierName").val(supplier.name);
            $("#updateSupplierEmail").val(supplier.email || '');
            $("#updateSupplierMobile").val(supplier.mobile);
            $("#updateSupplierAddress").val(supplier.address || '');
            $("#updateSupplierNote").val(supplier.note || '');

            currentFilePath = supplier.img_url || '';
            $("#updateSupplierOldImg").attr('src', currentFilePath || "{{ asset('images/default.jpg') }}");

            // ✅ OPEN MODAL (IMPORTANT FIX)
           let modal = new bootstrap.Modal(document.getElementById('updateSupplierModal'));
modal.show();
        }

    } catch (err) {
        hideLoader();
        console.error(err);
        showMessage('Failed to load supplier data', 'danger');
    }
}

// ================= UPDATE SUPPLIER =================
async function updateSupplier() {
    const id = $("#updateSupplierId").val();
    const name = $("#updateSupplierName").val();
    const email = $("#updateSupplierEmail").val() || '';
    const mobile = $("#updateSupplierMobile").val();
    const address = $("#updateSupplierAddress").val();
    const note = $("#updateSupplierNote").val();
    const file = $("#updateSupplierImg")[0].files[0];

    if(!name || !mobile){
        errorToast("Name and Mobile are required!");
        return;
    }

    const formData = new FormData();
    formData.append('id', id);
    formData.append('name', name);
    formData.append('email', email);
    formData.append('mobile', mobile);
    formData.append('address', address);
    formData.append('note', note);
    formData.append('file_path', currentFilePath || '');
    if(file) formData.append('img', file);

    try {
        showLoader();

        let res = await axios.post('/update-supplier', formData, {
            headers: {
                'id': localStorage.getItem('user_id'),
                'Content-Type': 'multipart/form-data'
            }
        });

        hideLoader();

        if(res.status === 200){
            let modalEl = document.getElementById('updateSupplierModal');
let modal = bootstrap.Modal.getInstance(modalEl);
if (modal) modal.hide();
            showMessage('Supplier updated successfully');
            loadSuppliers(); // reload table
        } else {
            errorToast("Update failed!");
        }

    } catch(err) {
        hideLoader();
        console.error(err.response?.data);
        showMessage(err.response?.data?.message || 'Failed to update supplier', 'danger');
    }
}

// ================= RESET MODAL =================
$('#updateSupplierModal').on('hidden.bs.modal', function(){
    $("#updateSupplierForm")[0].reset();
    $("#updateSupplierId").val('');
    currentFilePath = null;
    $("#updateSupplierOldImg").attr('src', "{{ asset('images/default.jpg') }}");
});
</script>
