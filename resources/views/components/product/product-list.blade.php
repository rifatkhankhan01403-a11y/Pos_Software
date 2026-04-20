<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="card px-5 py-5">

                <div class="row justify-content-between">
                    <div class="col">
                        <h4>Product</h4>
                    </div>

                    <div class="col">
                        <button data-bs-toggle="modal"
                                data-bs-target="#create-modal"
                                class="float-end btn bg-gradient-primary">
                            Create
                        </button>
                    </div>
                </div>

                <hr class="bg-dark"/>

                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Buy Price</th>
                            <th>Sell Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableList"></tbody>
                </table>

            </div>

        </div>
    </div>
</div>

<script>

getList();

async function getList() {

    showLoader();

    let res = await axios.get("/list-product");

    hideLoader();

    let tableList = $("#tableList");

    if ($.fn.DataTable.isDataTable("#tableData")) {
        $('#tableData').DataTable().destroy();
    }

    tableList.empty();

    res.data.forEach(function (item) {

        let imgSrc = item.img_url
            ? item.img_url
            : "{{asset('images/default.jpg')}}";

        let categoryName = item.category?.name ?? "No Category";

        let row = `
            <tr>

                <td>
                    <img src="${imgSrc}"
                         onerror="this.src='{{asset('images/default.jpg')}}'"
                         style="width:50px;height:50px;object-fit:cover;border-radius:5px;">
                </td>

                <td>${item.name ?? ''}</td>

                <td>

                        ${categoryName}

                </td>

                <td>${item.quantity ?? 0}</td>

                <td>${item.buy_price ?? 0}</td>

                <td>${item.sell_price ?? 0}</td>

                <td>
                    <button data-path="${item.img_url ?? ''}"
                            data-id="${item.id}"
                            class="btn editBtn btn-sm btn-outline-success">
                        Edit
                    </button>

                    <button data-path="${item.img_url ?? ''}"
                            data-id="${item.id}"
                            class="btn deleteBtn btn-sm btn-outline-danger">
                        Delete
                    </button>
                </td>

            </tr>
        `;

        tableList.append(row);
    });

    // EDIT
    $('.editBtn').on('click', async function () {
        let id = $(this).data('id');
        let filePath = $(this).data('path');
        await FillUpUpdateForm(id, filePath);
        $("#update-modal").modal('show');
    });

    // DELETE
    $('.deleteBtn').on('click', function () {
        let id = $(this).data('id');
        let path = $(this).data('path');

        $("#delete-modal").modal('show');
        $("#deleteID").val(id);
        $("#deleteFilePath").val(path);
    });

    new DataTable('#tableData', {
        order: [[0, 'desc']],
        lengthMenu: [5, 10, 15, 20, 30]
    });
}

</script>
