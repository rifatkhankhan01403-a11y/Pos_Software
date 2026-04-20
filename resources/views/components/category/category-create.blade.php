{{-- <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Create Category</h6>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryName">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>


<script>
    async function Save() {
        let categoryName = document.getElementById('categoryName').value;
        if (categoryName.length === 0) {
            errorToast("Category Required !")
        }
        else {
            document.getElementById('modal-close').click();
            showLoader();
            let res = await axios.post("/create-category",{name:categoryName})
            hideLoader();
            if(res.status===201){
                successToast('Request completed');
                document.getElementById("save-form").reset();
                await getList();
            }
            else{
                errorToast("Request fail !")
            }
        }
    }
</script> --}}


<div class="modal animated zoomIn" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">Create Category</h6>
            </div>

            <div class="modal-body">
                <form id="save-form">

                    <!-- CATEGORY -->
                    <div class="col-12 p-1">
                        <label class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="categoryName">
                    </div>

                    <hr>

                    <!-- SUB CATEGORY SECTION -->
                    <div class="col-12 p-1">
                        <label class="form-label">Sub Categories (Optional)</label>

                        <div id="subCategoryWrapper">
                            <input type="text" class="form-control subCategoryInput mb-2"
                                   placeholder="Sub category name">
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-secondary mt-2"
                                onclick="addMoreSubCategory()">
                            + Add More
                        </button>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary"
                        data-bs-dismiss="modal">
                    Close
                </button>

                <button onclick="Save()" id="save-btn"
                        class="btn bg-gradient-success">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>


<script>

// ➕ Add more sub category inputs
function addMoreSubCategory() {
    let html = `
        <input type="text"
               class="form-control subCategoryInput mb-2"
               placeholder="Sub category name">
    `;
    document.getElementById('subCategoryWrapper')
        .insertAdjacentHTML('beforeend', html);
}

// 💾 SAVE CATEGORY + SUBCATEGORIES
async function Save() {

    let categoryName = document.getElementById('categoryName').value;

    // collect sub categories
    let subInputs = document.querySelectorAll('.subCategoryInput');

    let subCategories = [];

    subInputs.forEach(input => {
        if (input.value.trim() !== '') {
            subCategories.push(input.value);
        }
    });

    // validation
    if (categoryName.length === 0) {
        errorToast("Category Required !");
        return;
    }

    document.getElementById('modal-close').click();
    showLoader();

    // send everything to backend
    let res = await axios.post("/create-category", {
        name: categoryName,
        sub_categories: subCategories
    });

    hideLoader();

    if (res.status === 201 || res.status === 200) {
        successToast('Category Created Successfully');

        document.getElementById("save-form").reset();

        // reset sub category area
        document.getElementById("subCategoryWrapper").innerHTML = `
            <input type="text"
                   class="form-control subCategoryInput mb-2"
                   placeholder="Sub category name">
        `;

        await getList();
    }
    else {
        errorToast("Request failed !");
    }
}

</script>
