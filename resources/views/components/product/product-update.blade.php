<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
            </div>

            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">

                            <!-- CATEGORY -->
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>
                                <select class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                {{-- <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate"> --}}

                                <label class="form-label mt-2">Quantity</label>
                                <input type="number" class="form-control" id="productQuantityUpdate">

                                <label class="form-label mt-2">Buy Price</label>
                                <input type="text" class="form-control" id="productBuyPriceUpdate">

                                <label class="form-label mt-2">Sell Price</label>
                                <input type="text" class="form-control" id="productSellPriceUpdate">

                                <label class="form-label mt-2">Note</label>
                                <input type="text" class="form-control" id="productNoteUpdate">

                                <br/>

                                <!-- IMAGE -->
                                <img class="w-25" id="oldImg" src="{{ asset('images/default.jpg') }}"
                                     onerror="this.src='{{ asset('images/default.jpg') }}'">
                                <br/>

                                <label class="form-label mt-2">Image</label>
                                <input type="file" class="form-control" id="productImgUpdate"
                                       oninput="oldImg.src = window.URL.createObjectURL(this.files[0])">

                                <input type="hidden" id="updateID">
                                <input type="hidden" id="filePath">

                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn bg-gradient-primary" data-bs-dismiss="modal">Close</button>
                <button onclick="update()" class="btn bg-gradient-success">Update</button>
            </div>

        </div>
    </div>
</div>

<script>
async function UpdateFillCategoryDropDown() {
    $("#productCategoryUpdate").html(`<option value="">Select Category</option>`);
    let res = await axios.get("/list-category", { headers: { id: localStorage.getItem('user_id') } });
    res.data.forEach(item => {
        $("#productCategoryUpdate").append(`<option value="${item.id}">${item.name}</option>`);
    });
}

async function FillUpUpdateForm(id, filePath) {
    document.getElementById('updateID').value = id;
    document.getElementById('filePath').value = filePath;
    document.getElementById('oldImg').src = filePath ?? "{{ asset('images/default.jpg') }}";

    showLoader();
    await UpdateFillCategoryDropDown();

    let res = await axios.post("/product-by-id", { id: id }, { headers: { id: localStorage.getItem('user_id') } });
    hideLoader();

    let data = res.data;
    document.getElementById('productNameUpdate').value = data.name ?? '';
    // document.getElementById('productUnitUpdate').value = data.unit ?? '';
    document.getElementById('productQuantityUpdate').value = data.quantity ?? '';
    document.getElementById('productBuyPriceUpdate').value = data.buy_price ?? '';
    document.getElementById('productSellPriceUpdate').value = data.sell_price ?? '';
    document.getElementById('productNoteUpdate').value = data.note ?? '';
    document.getElementById('productCategoryUpdate').value = data.category_id ?? '';
}

async function update() {

    let userId = localStorage.getItem('user_id');

    let productCategoryUpdate = document.getElementById('productCategoryUpdate').value;
    let productNameUpdate = document.getElementById('productNameUpdate').value;
    let productQuantityUpdate = document.getElementById('productQuantityUpdate').value;
    let productBuyPriceUpdate = document.getElementById('productBuyPriceUpdate').value;
    let productSellPriceUpdate = document.getElementById('productSellPriceUpdate').value;
    let productNoteUpdate = document.getElementById('productNoteUpdate').value;
    let updateID = document.getElementById('updateID').value;
    let productImgUpdate = document.getElementById('productImgUpdate').files[0];

    if (!productCategoryUpdate) return errorToast("Category required!");
    if (!productNameUpdate) return errorToast("Name required!");

    let formData = new FormData();
    formData.append('id', updateID);
    formData.append('name', productNameUpdate);
    formData.append('quantity', productQuantityUpdate);
    formData.append('buy_price', productBuyPriceUpdate);
    formData.append('sell_price', productSellPriceUpdate);
    formData.append('note', productNoteUpdate);
    formData.append('category_id', productCategoryUpdate);

    if (productImgUpdate) {
        formData.append('img', productImgUpdate);
    }

    showLoader();

    try {
        let res = await axios.post("/update-product", formData, {
            headers: {
                'content-type': 'multipart/form-data',
                id: userId
            }
        });

        hideLoader();

        if (res.data == 1) {
            successToast("Product updated successfully");
            document.getElementById("update-form").reset();
            await getList();
        } else {
            errorToast("Update failed!");
        }

    } catch (error) {
        hideLoader();
        console.log(error);
        errorToast("Server error!");
    }
}
</script>
