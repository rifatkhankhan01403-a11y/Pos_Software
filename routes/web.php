<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleHistoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockAddController;
use App\Http\Controllers\InvoiceBillingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CashboxController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\PurchaseBookController;

use App\Http\Middleware\TokenVerificationMiddleware;

use Illuminate\Support\Facades\Route;


// Web API Routes
Route::post('/user-registration',[UserController::class,'UserRegistration']);
Route::post('/user-login',[UserController::class,'UserLogin']);
Route::post('/send-otp',[UserController::class,'SendOTPCode']);
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);
Route::post('/reset-password',[UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/user-profile',[UserController::class,'UserProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/user-update',[UserController::class,'UpdateProfile'])->middleware([TokenVerificationMiddleware::class]);


// User Logout
Route::get('/logout',[UserController::class,'UserLogout']);

// Page Routes
Route::get('/',[HomeController::class,'HomePage']);
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);
 Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/userProfile',[UserController::class,'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/categoryPage',[CategoryController::class,'CategoryPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/customerPage',[CustomerController::class,'CustomerPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/productPage',[ProductController::class,'ProductPage'])->middleware([TokenVerificationMiddleware::class]);
// Route::get('/invoicePage',[InvoiceController::class,'InvoicePage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/salePage',[InvoiceBillingController::class,'SalePage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/reportPage',[ReportController::class,'ReportPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/saleHistory',[SaleHistoryController::class,'saleHistory'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/supplierPage',[SupplierController::class,'SupplierPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/due-book', [SaleHistoryController::class,'dueBook'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/stock-add', [StockAddController::class, 'StockPurchasePage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/expensePage',[ExpenseController::class,'ExpensePage'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/cashbox',[CashboxController::class,'cashBox'])->middleware([TokenVerificationMiddleware::class]);

//stock add
Route::post('/stock-store', [StockAddController::class, 'CreateStockPurchase'])->middleware([TokenVerificationMiddleware::class]);


//dashboard
Route::get('/dashboard-data', [App\Http\Controllers\DashboardController::class,'getDashboardData']);
Route::get('/dashboard-summary', [DashboardController::class, 'getDashboardSummary']);


//purchase book
Route::get('/purchase-book', [PurchaseBookController::class, 'purchaseBook']);
Route::delete('/purchase-book/delete/{id}', [PurchaseBookController::class, 'deletePurchase']);


//quick sell

// Category API
Route::post("/create-category",[CategoryController::class,'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-category",[CategoryController::class,'CategoryList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-category",[CategoryController::class,'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-category",[CategoryController::class,'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/category-by-id",[CategoryController::class,'CategoryByID'])->middleware([TokenVerificationMiddleware::class]);


// Customer API
Route::post("/create-customer",[CustomerController::class,'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-customer",[CustomerController::class,'CustomerList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-customer",[CustomerController::class,'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-customer",[CustomerController::class,'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/customer-by-id",[CustomerController::class,'CustomerByID'])->middleware([TokenVerificationMiddleware::class]);


// Product API
Route::post("/create-product",[ProductController::class,'CreateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-product",[ProductController::class,'DeleteProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-product",[ProductController::class,'UpdateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-product",[ProductController::class,'ProductList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/product-by-id",[ProductController::class,'ProductByID'])->middleware([TokenVerificationMiddleware::class]);


//expense book

Route::get('/expense-list',[ExpenseController::class,'ExpenseList']);

Route::post('/create-expense',[ExpenseController::class,'CreateExpense']);

Route::post('/expense-by-id',[ExpenseController::class,'ExpenseByID']);

Route::post('/update-expense',[ExpenseController::class,'UpdateExpense']);

Route::post('/delete-expense',[ExpenseController::class,'DeleteExpense']);




// sale book  page
Route::get('/sale-history',[SaleHistoryController::class,'saleHistory']);
Route::delete('/sale-history/delete/{id}',[SaleHistoryController::class,'deleteSale']);




//due book
Route::post('/due-store', [DueController::class, 'store'])->name('due.store');
Route::get('/due-book', [DueController::class, 'dueBookPage']);
//Route::get('/due/parties', [DueController::class, 'partyList'])->name('due.parties');
Route::get('/party-list', [DueController::class, 'partyList']);
Route::get('/party-ledger', [DueController::class, 'partyLedger']);



// Supplier API
Route::post('/create-supplier',[SupplierController::class,'CreateSupplier'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/update-supplier',[SupplierController::class,'UpdateSupplier'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/delete-supplier',[SupplierController::class,'DeleteSupplier'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/list-supplier',[SupplierController::class,'ListSupplier'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/supplier-by-id',[SupplierController::class,'SupplierByID'])->middleware([TokenVerificationMiddleware::class]);


// Invoice
//Route::post("/invoice-create",[InvoiceController::class,'invoiceCreate'])->middleware([TokenVerificationMiddleware::class]);
// Route::get("/invoice-select",[InvoiceController::class,'invoiceSelect'])->middleware([TokenVerificationMiddleware::class]);
// Route::post("/invoice-details",[InvoiceController::class,'InvoiceDetails'])->middleware([TokenVerificationMiddleware::class]);
// Route::post("/invoice-delete",[InvoiceController::class,'invoiceDelete'])->middleware([TokenVerificationMiddleware::class]);


//invoice billing


Route::post('/invoice-create', [InvoiceBillingController::class, 'createInvoice'])
    ->middleware(TokenVerificationMiddleware::class);
//quick sell
Route::post('/quick-sell-store', [InvoiceBillingController::class, 'QuickSellStore']);


//cashbox
Route::post('/cash-in', [CashboxController::class, 'cashIn']);
Route::post('/cash-out', [CashboxController::class, 'cashOut']);

// SUMMARY & Report
Route::get("/summary",[DashboardController::class,'Summary'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/sales-report/{FormDate}/{ToDate}",[ReportController::class,'SalesReport'])->middleware([TokenVerificationMiddleware::class]);




