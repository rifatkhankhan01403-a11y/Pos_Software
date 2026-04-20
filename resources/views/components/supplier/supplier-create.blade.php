<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h5 class="modal-title">Add Supplier</h5>
            <form id="createSupplierForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Enter supplier name" required>
                </div>
                <div class="mb-2">
                    <label>Mobile <span class="text-danger">*</span></label>
                    <input type="text" name="mobile" class="form-control" placeholder="Enter mobile" required>
                </div>
                <div class="mb-2">
    <label>Email <span class="text-danger"></span></label>
    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
</div>
                <div class="mb-2">
                    <label>Address</label>
                    <textarea name="address" class="form-control" placeholder="Optional"></textarea>
                </div>
                <div class="mb-2">
                    <label>Note</label>
                    <textarea name="note" class="form-control" placeholder="Optional"></textarea>
                </div>
                <div class="mb-2">
                    <label>Image</label>
                    <input type="file" name="img" class="form-control">
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="saveSupplier()" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function saveSupplier() {
    const form = document.getElementById('createSupplierForm');
    const formData = new FormData(form);

    // Client-side validation
    const name = formData.get('name').trim();
    const mobile = formData.get('mobile').trim();

    if(!name) {
        alert('Name is required!');
        return;
    }
    if(!mobile) {
        alert('Mobile is required!');
        return;
    }

    axios.post('/create-supplier', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'id': '1' // replace with dynamic user header if needed
        }
    })
    .then(res => {
        alert('Supplier added successfully!');
        form.reset();
        const modalEl = document.getElementById('createSupplierModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        // Optionally reload supplier list
        loadSupplierList();
    })
    .catch(err => {
        if(err.response && err.response.data) {
            const errors = err.response.data.errors || {};
            let msg = '';
            for (let key in errors) {
                msg += errors[key].join(', ') + '\n';
            }
            alert(msg || 'Error adding supplier!');
        } else {
            alert('Error adding supplier!');
        }
        console.error(err);
    });
}
</script>
