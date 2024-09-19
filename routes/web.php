<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripDetailController;
use Faker\Provider\ar_EG\Payment;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [TripController::class, 'home'])->name('home');


Route::post('/booking/search', [TripDetailController::class, 'storeSearch'])->name('booking.search');
Route::post('/booking/search/advanced', [TripDetailController::class, 'storeSearchAdvanced'])->name('booking.search.advanced');
Route::post('/validate-booking', [BookingController::class, 'validateBooking'])->name('validate.booking');

// Route::get('/booking/{tripDetailId}/{departureDate}', [BookingController::class, 'showBookingForm'])->name('booking.form')->where('tripDetailId', '[0-9]+');

Route::get(
    '/booking/{tripDetailId}/{departureDate}',
    [BookingController::class, 'processBooking']
)->name('booking.process')->where('tripDetailId', '[0-9]+');


Route::post('/payment',
[PaymentController::class, 'payment'])->name('booking.payment');

Route::get('/callback',
[PaymentController::class, 'callBack'])->name('booking.callback');


Route::get('/payment-status',
[PaymentController::class, 'paymentStatus'])->name('booking.paymentstatus');

// Route::post('/booking/payment', function () {
//     // Your previous code for payment processing here
//     return view('Pages.booking-confirm');
// })->name('booking.payment'); // Change 'payement' to 'payment'

Route::post('/booking/confirm/{tripDetailId}',  
[BookingController::class, 'confirm'])->name('booking.confirm')->where('tripDetailId', '[0-9]+');

Route::get('/notifycation',  
[BookingController::class, 'confirm'])->name('notifycation')->where('tripDetailId', '[0-9]+');

Route::get('/booking-seat}',  
[PaymentController::class, 'paymentBookingSeat'])->name('payment.booking-seat');

// Admin
Route::get('/admin', function () {
    return view('Admin.Layouts.admin');
});

// Dashboard
Route::get('/admin/dashboard', function () {
    return view('Admin.Pages.dashboard');
});

// Car type
Route::get('/admin/car-type/page={page}',
[CarTypeController::class, 'carTypeManagement'])->name('car-type');

Route::get('/car-type/search/page={page}', [CarTypeController::class, 'search'])->name('car-type.search');

Route::post('/admin/car-type/create',
[CarTypeController::class, 'addCarType'])->name('car-type.create');

Route::put('/admin/car-type/update/{carTypeId}',
[CarTypeController::class, 'updateCarType'])->name('car-type.update')->where('carTypeId', '[0-9]+');

Route::get('/admin/car-type/delete/{carTypeId}',
[CarTypeController::class, 'deleteCarType'])->name('car-type.delete')->where('carTypeId', '[0-9]+');

// Car
Route::get('/admin/car/page={page}',
[CarController::class, 'carManagement'])->name('car');

Route::post('/admin/car/create',
[CarController::class, 'addCar'])->name('car.create');

Route::put('/admin/car/update/{carId}',
[CarController::class, 'updateCar'])->name('car.update')->where('carId', '[0-9]+');

Route::get('/admin/car/delete/{carId}',
[CarController::class, 'deleteCar'])->name('car.delete')->where('carId', '[0-9]+');

// Trip
Route::get('/admin/trip/page={page}',
[TripController::class, 'tripManagement'])->name('trip');

Route::post('/admin/trip/create',
[TripController::class, 'addTrip'])->name('trip.create');

Route::put('/admin/trip/update/{tripId}',
[TripController::class, 'updateTrip'])->name('trip.update')->where('tripId', '[0-9]+');

Route::get('/admin/trip/delete/{tripId}',
[TripController::class, 'deleteTrip'])->name('trip.delete')->where('tripId', '[0-9]+');

// Trip Detail
Route::get('/admin/trip-detail/page={page}',
[TripDetailController::class, 'tripDetailManagement'])->name('trip-detail');

Route::post('/admin/trip-detail/create',
[TripDetailController::class, 'addTripDetail'])->name('trip-detail.create');

Route::put('/admin/trip-detail/update/{tripDetailId}',
[TripDetailController::class, 'updateTripDetail'])->name('trip-detail.update')->where('tripDetailId', '[0-9]+');

Route::get('/admin/trip-detail/delete/{tripDetailId}',
[TripDetailController::class, 'deleteTripDetail'])->name('trip-detail.delete')->where('tripDetailId', '[0-9]+');

//Seat
Route::get('/admin/seats', [SeatController::class, 'seatManagement'])->name('seats');

Route::get('/admin/seats-map/{carId}', [SeatController::class, 'seatsMap'])->name('seats-map')->where('carId', '[0-9]+');

Route::post('/admin/seats/create', 
[SeatController::class, 'addSeatsMap'])->name('seats.create');

Route::get('/seat', [SeatController::class, 'seatStatus'])->name('seat.status');

//History
Route::get('/history-booking', [BookingController::class, 'historyBooking'])->name('history-booking');

Route::post('/history-booking/search', [BookingController::class, 'searchHistoryBooking'])->name('history-booking.search');

Route::get('/confirm-cancel-ticket/{bookingId}', [BookingController::class, 'confirmCancelTicket'])->name('confirm-cancel');

Route::post('/cancel-ticket/{bookingId}', [BookingController::class, 'cancelTicket'])->name('cancel-ticket');

// Promotion
Route::get('/admin/promotion', [PromotionController::class, 'promotionManagement'])->name('promotion');
Route::post('/admin/promotion/create', [PromotionController::class, 'addPromotion'])->name('promotion.create');
Route::put('/admin/promotion/update', [PromotionController::class, 'updatePromotion'])->name('promotion.update');
Route::get('/admin/promotion/update/{id}',  [PromotionController::class, 'deletePromotion'])->name('promotion.delete');