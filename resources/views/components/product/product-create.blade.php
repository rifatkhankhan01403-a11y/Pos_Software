<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Product</h5>
            </div>

            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <!-- Category -->
                                <label class="form-label">Category</label>
                                <select class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <!-- Subcategory -->
                                <label class="form-label mt-2">Sub Category</label>
                                <select class="form-control form-select" id="productSubCategory">
                                    <option value="">Select Sub Category</option>
                                </select>

                                <!-- Name -->
                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                {{-- <!-- Unit -->
                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit"> --}}

                                <!-- Quantity -->
                                <label class="form-label mt-2">Quantity</label>
                                <input type="number" class="form-control" id="productQuantity">

                                <!-- Buy Price -->
                                <label class="form-label mt-2">Buy Price</label>
                                <input type="text" class="form-control" id="productBuyPrice">

                                <!-- Sell Price -->
                                <label class="form-label mt-2">Sell Price</label>
                                <input type="text" class="form-control" id="productSellPrice">

                                <!-- Note -->
                                <label class="form-label mt-2">Note</label>
                                <input type="text" class="form-control" id="productNote">

                                <br/>

                                <!-- Image Preview -->
                                <img class="w-15" id="newImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>

                                <!-- Image -->
                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])"
                                       type="file"
                                       class="form-control"
                                       id="productImg">

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal">Close</button>
                <button onclick="Save()" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>


<script>

    FillCategoryDropDown();

    async function FillCategoryDropDown(){
        let res = await axios.get("/list-category");

        let categorySelect = document.getElementById('productCategory');
        categorySelect.innerHTML = `<option value="">Select Category</option>`;

        res.data.forEach(function (item) {
            let option = `<option value="${item.id}">${item.name}</option>`;
            categorySelect.innerHTML += option;
        });

        // store full data globally
        window.categoryData = res.data;
    }


    // 🔥 Load Subcategory when category changes
    document.getElementById('productCategory').addEventListener('change', function () {

        let categoryId = this.value;
        let subSelect = document.getElementById('productSubCategory');

        subSelect.innerHTML = `<option value="">Select Sub Category</option>`;

        let selectedCategory = window.categoryData.find(c => c.id == categoryId);

        if (selectedCategory && selectedCategory.sub_categories.length > 0) {

            selectedCategory.sub_categories.forEach(function (sub) {
                let option = `<option value="${sub.id}">${sub.name}</option>`;
                subSelect.innerHTML += option;
            });

        }
    });


    async function Save() {

        let productCategory = document.getElementById('productCategory').value;
        let productSubCategory = document.getElementById('productSubCategory').value;
        let productName = document.getElementById('productName').value;
      //  let productUnit = document.getElementById('productUnit').value;
        let productQuantity = document.getElementById('productQuantity').value;
        let productBuyPrice = document.getElementById('productBuyPrice').value;
        let productSellPrice = document.getElementById('productSellPrice').value;
        let productNote = document.getElementById('productNote').value;
        let productImg = document.getElementById('productImg').files[0]; // ✅ FIXED


        if (productCategory.length === 0) {
            errorToast("Product Category Required !");
        }
        else if(productName.length === 0){
            errorToast("Product Name Required !");
        }
        // else if(productUnit.length === 0){
        //     errorToast("Product Unit Required !");
        // }
        else if(productQuantity.length === 0){
            errorToast("Quantity Required !");
        }
        else if(productBuyPrice.length === 0){
            errorToast("Buy Price Required !");
        }

        else {

            document.getElementById('modal-close').click();

            let formData = new FormData();

            if(productImg){
                formData.append('img', productImg)
            }

            formData.append('name', productName)
            formData.append('quantity', productQuantity)
            formData.append('buy_price', productBuyPrice)
            formData.append('sell_price', productSellPrice)
            formData.append('note', productNote)
            formData.append('category_id', productCategory)
            formData.append('subcategory_id', productSubCategory)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/create-product", formData, config)
            hideLoader();

            if(res.status === 201){
                successToast('Product Added Successfully');
                document.getElementById("save-form").reset();
                await getList();
            }
            else{
                errorToast("Request fail !");
            }
        }
    }

</script>
