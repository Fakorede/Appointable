<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'FrontendController@index');

Route::get('/new-appointment/{doctor}/{date}', 'FrontendController@showAppointment')->name('show.appointment');

Route::get('my-bookings', 'FrontendController@myBookings')->name('my.bookings')->middleware('auth');

Route::post('/book/appointment', 'FrontendController@storeAppointment')->name('store.appointment')->middleware('auth');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('doctor', 'DoctorController');
});

Route::group(['middleware' => ['auth', 'doctor']], function () {
    Route::resource('appointment', 'AppointmentController');
    Route::post('appointment/check', 'AppointmentController@check')->name('appointment.check');
    Route::post('appointment/update', 'AppointmentController@updateTime')->name('appointment.update');
});
