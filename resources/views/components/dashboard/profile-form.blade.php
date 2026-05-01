<div class="container-fluid">

<div class="card shadow-sm p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Profile Settings</h4>
</div>

<hr>

<!-- SHOP INFO -->
<div class="row">

<div class="col-md-4 p-2">
    <label>Shop Name</label>
    <input  id="shopName" class="form-control">
</div>

<div class="col-md-4 p-2">
    <label>Shop ID</label>
    <input readonly id="shopId" class="form-control">
</div>

<div class="col-md-4 p-2">
    <label>Customer Name</label>
    <input id="firstName" class="form-control">
</div>

<div class="col-md-4 p-2">
    <label>Email</label>
    <input id="email" class="form-control" readonly>
</div>

<div class="col-md-4 p-2">
    <label>Mobile</label>
    <input id="mobile" class="form-control">
</div>

<div class="col-md-4 p-2">
    <label>Password</label>
    <input id="password" type="password" class="form-control" placeholder="New password (optional)">
</div>

</div>

<!-- BUTTON -->
<div class="mt-3">
    <button onclick="onUpdate()" class="btn btn-primary w-25">
        Update Profile
    </button>
</div>

</div>
</div>


<script>
getProfile();

async function getProfile(){
    showLoader();

    let res = await axios.get("/user-profile");

    hideLoader();

    if(res.status === 200 && res.data.status === 'success'){

        let data = res.data.data;

        document.getElementById('email').value = data.email ?? '';
        document.getElementById('firstName').value = data.firstName ?? '';
        document.getElementById('mobile').value = data.mobile ?? '';

        document.getElementById('shopName').value = data.shop_name ?? '';
        document.getElementById('shopId').value = data.shop_id ?? '';

    } else {
        errorToast(res.data.message);
    }
}


/* UPDATE PROFILE */
async function onUpdate(){

    let firstName = document.getElementById('firstName').value;
    let mobile = document.getElementById('mobile').value;
    let shopName = document.getElementById('shopName').value;
    let password = document.getElementById('password').value;

    if(firstName.length === 0){
        return errorToast("Name required");
    }

    if(mobile.length === 0){
        return errorToast("Mobile required");
    }

    if(shopName.length === 0){
        return errorToast("Shop Name required");
    }

    let res = await axios.post("/user-update", {
        firstName: firstName,
        mobile: mobile,
        shopName: shopName,
        password: password
    });

    if(res.status === 200 && res.data.status === 'success'){
        successToast(res.data.message);
        getProfile();
    } else {
        errorToast(res.data.message);
    }
}
</script>
