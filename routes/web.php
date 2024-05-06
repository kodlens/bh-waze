<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false,
]);

Route::middleware('guest')->get('/gate', function () {
    //this route will check and redirect user
});




Route::get('/sample',[App\Http\Controllers\SampleController::class,'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/register', App\Http\Controllers\RegisterPageController::class);
Route::post('/register-landowner', [App\Http\Controllers\RegisterPageController::class, 'saveLandOwner']);
Route::post('/register-boarder', [App\Http\Controllers\RegisterPageController::class, 'saveBoarder']);



//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);
//get, post, put, delete



//ADMINSITRATOR
Route::resource('/dashboard', App\Http\Controllers\Administrator\AdminDashboardController::class);

Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
Route::post('/user-reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);


Route::resource('/request-accounts', App\Http\Controllers\Administrator\RequestAccountController::class);
Route::get('/get-request-accounts', [App\Http\Controllers\Administrator\RequestAccountController::class, 'getRequestAccount']);
Route::post('/request-account-approved/{id}', [App\Http\Controllers\Administrator\RequestAccountController::class, 'approveAccount']);
Route::post('/request-account-disapproved/{id}', [App\Http\Controllers\Administrator\RequestAccountController::class, 'disapproveAccount']);



//ROUTE FOR REQUEST BHOUSE // KANANG MAG PA APPROVE SA BHOUSE E DISPLAY
// ANG BUSINESS PERMIT
Route::resource('/bh-request', App\Http\Controllers\Administrator\BHRequestController::class);
Route::get('/get-bh-requests', [App\Http\Controllers\Administrator\BHRequestController::class, 'getBHRequests']);
Route::post('/bh-request-approved/{id}', [App\Http\Controllers\Administrator\BHRequestController::class, 'approveBh']);
Route::post('bh-request-deactivate/{id}', [App\Http\Controllers\Administrator\BHRequestController::class, 'deactivateBh']);
//---------------------------//
//---------------------------//





//LANDOWNER
//-------------------------///
Route::resource('/landowner-dashboard', App\Http\Controllers\LandOwner\LandownerDashboardController::class);
Route::get('/get-landowner-bhouses', [App\Http\Controllers\LandOwner\LandownerDashboardController::class, 'getLandOwnerBhouses']);


//BOARDING HOUSE
Route::resource('/boarding-house', App\Http\Controllers\LandOwner\LandownerBoardingHouseController::class);
Route::post('/boarding-house-update/{id}', [App\Http\Controllers\LandOwner\LandownerBoardingHouseController::class, 'update']);

Route::get('/get-bhouses', [App\Http\Controllers\LandOwner\LandownerBoardingHouseController::class, 'getBhouses']);

Route::post('/boarder-reservation-approved/{book_bedspace_id}', [App\Http\Controllers\LandOwner\BoarderReservationController::class, 'approvedReservation']);
Route::post('/boarder-reservation-cancel/{book_bedspace_id}', [App\Http\Controllers\LandOwner\BoarderReservationController::class, 'cancelReservation']);

//LADN OWNER ->>>BOADER LIST
Route::resource('/boarder-list', App\Http\Controllers\LandOwner\BoarderListController::class);
Route::get('/get-boarder-list', [App\Http\Controllers\LandOwner\BoarderListController::class, 'getBoarderList']);
Route::post('/removeboarder-boarder-list/{boarder_id}', [App\Http\Controllers\LandOwner\BoarderListController::class, 'removeBoarder']);


Route::get('/get-boarder-bill/{boarder_id}', [App\Http\Controllers\LandOwner\BoarderListController::class, 'showPaymentDetails']);

//BOARDER PAYMENT LANDOWNER SIDE
Route::resource('/boarder-payment', App\Http\Controllers\LandOwner\BoarderPaymentController::class);
Route::get('/get-boarder-payment', [App\Http\Controllers\LandOwner\BoarderPaymentController::class, 'getBoarderPayment']);
Route::post('/boarder-payment-mark-paid/{payment_detail_id}', [App\Http\Controllers\LandOwner\BoarderPaymentController::class, 'markPaid']);
Route::get('/boarder-payment-receipt/{payment_detail_id}', [App\Http\Controllers\LandOwner\BoarderPaymentController::class, 'getReceiptInfo']);


//Payment details
Route::post('/boarder-submit-bill', [App\Http\Controllers\LandOwner\PaymentController::class, 'store']);


//BOARDING HOUSE ROOMS LAND OWNER
Route::get('/boarding-house-rooms/{id}', [App\Http\Controllers\LandOwner\LandOwnerRoomController::class, 'index']);
Route::post('/boarding-house-rooms/{id}', [App\Http\Controllers\LandOwner\LandOwnerRoomController::class, 'store']);
Route::post('/boarding-house-rooms-update/{id}', [App\Http\Controllers\LandOwner\LandOwnerRoomController::class, 'update']);


Route::get('/get-boarding-house-rooms/{id}', [App\Http\Controllers\LandOwner\LandOwnerRoomController::class, 'getRooms']);
Route::delete('/boarding-house-room-delete/{id}', [App\Http\Controllers\LandOwner\LandOwnerRoomController::class, 'destroy']);

Route::get('/get-boarding-house-room-edit/{id}', [App\Http\Controllers\LandOwner\LandOwnerRoomController::class, 'getBhouseRoomEdit']);

Route::get('/boarder-reservation', [App\Http\Controllers\LandOwner\BoarderReservationController::class, 'index']);
Route::get('/get-boarder-reservation', [App\Http\Controllers\LandOwner\BoarderReservationController::class, 'getBoarderReservation']);



//-------------LANDOWNER------------///
//----------------------------------///



//BOARDING HOUSE - BEDSPACE
Route::get('/boarding-house-bedspace/{bhouse_id}/{bh_room_id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'index']);
Route::get('/get-boarding-house-bedspaces/{id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'showBedSpace']);

Route::post('/boarding-house-bedspace/{bhouse_id}/{room_id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'store']);
//Route::get('/get-boarding-house-bedspaces-imgs/{id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'getBedspaceImgs']);
Route::put('/boarding-house-bedspace-update/{id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'update']);

Route::get('/get-bhouse-bedspaces/{id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'getBhBedspaces']);
Route::delete('/boarding-house-bedspace-delete/{id}', [App\Http\Controllers\LandOwner\LandownerBedspaceController::class, 'destroy']);

//bedspace images
Route::get('/boarding-house-bedspace/{bhid}/{roomid}/{bedspaceid}', [App\Http\Controllers\LandOwner\BedspaceImageController::class, 'index']);
Route::post('/boarding-house-bedspace-store/{bedspaceid}', [App\Http\Controllers\LandOwner\BedspaceImageController::class, 'store']);

Route::get('/get-bedspace-imgs/{bedspaceid}', [App\Http\Controllers\LandOwner\BedspaceImageController::class, 'getImages']);
Route::delete('/bedspace-imgs-delete/{bedspaceid}', [App\Http\Controllers\LandOwner\BedspaceImageController::class, 'destroy']);




//-------------------------///

//BOARDER DASHBOARD // CLIENT SIDE (BOARDER)
Route::get('/boarder-dashboard', [App\Http\Controllers\Boarder\BoarderDashboardController::class, 'index']);

Route::get('/get-bhouse-user', [App\Http\Controllers\Boarder\BoarderDashboardController::class, 'getUser']);



//My Reservation
Route::get('/my-reservation', [App\Http\Controllers\Boarder\MyReservationController::class, 'index']);
Route::get('/get-my-reservation', [App\Http\Controllers\Boarder\MyReservationController::class, 'getMyReservation']);
//upload proof of transaction
Route::post('/upload-proof-transaction/{book_bedspace_id}', [App\Http\Controllers\Boarder\MyReservationController::class, 'uploadProofTransaction']);
Route::post('/my-reservation-cancel/{bedspace_id}', [App\Http\Controllers\Boarder\MyReservationController::class, 'cancelReservation']);


Route::get('/my-billing', [App\Http\Controllers\Boarder\MyPaymentController::class, 'index']);
Route::get('/my-payment-receipt/{payment_detail_id}', [App\Http\Controllers\Boarder\MyPaymentController::class, 'getReceiptInfo']);

Route::get('/get-my-payment', [App\Http\Controllers\Boarder\MyPaymentController::class, 'getMyPayment']);
Route::get('/get-my-payment-details/{payment_id}', [App\Http\Controllers\Boarder\MyPaymentController::class, 'getMyPaymentDetail']);

Route::post('/submit-pay-bill/{payment_detail_id}', [App\Http\Controllers\Boarder\MyPaymentController::class, 'submitPaymentBill']);



//BOARDER DASHBOARD






//CLIENT BOARDING HOUSES //
Route::get('/get-client-bhouses', [App\Http\Controllers\ClientBhouseController::class, 'getBhouses']);
Route::get('/client-bhouse-detail/{id}', [App\Http\Controllers\ClientBhouseController::class, 'showBhouseDetail']);
Route::get('/get-bhouse-detail/{id}', [App\Http\Controllers\ClientBhouseController::class, 'getBhouseDetail']);



//CLIENT BHROOM
Route::get('/client-bh-rooms/{id}', [App\Http\Controllers\ClientBhouseRoomController::class, 'index']);
Route::get('/get-client-bh-rooms/{id}', [App\Http\Controllers\ClientBhouseRoomController::class, 'getBhRoomsClient']);
Route::get('/get-client-bhroom-bedspaces/{id}', [App\Http\Controllers\ClientBhouseRoomController::class, 'getBedSpaces']);
Route::post('/client-bhroom-bedspace-reserve/{bedspace_id}', [App\Http\Controllers\ClientReserveBedSpaceController::class, 'reserveBedspace']);





Route::get('/session', function(){
    return Session::all();
});
