<?php

use Illuminate\Support\Facades\Route;
use Workdo\Stripe\Http\Controllers\DashboardController;
use Workdo\Stripe\Http\Controllers\StripeItemController;
use Workdo\Stripe\Http\Controllers\StripeSettingsController;
use Workdo\Stripe\Http\Controllers\StripeController;

Route::middleware(['web', 'auth', 'verified', 'PlanModuleCheck:Stripe'])->group(function () {
    Route::post('/stripe/settings', [StripeSettingsController::class, 'update'])->name('stripe.settings.update');
});

Route::middleware(['web'])->group(function () {
    Route::prefix('stripe')->group(function () {
        Route::post('/plan/company/payment', [StripeController::class, 'planPayWithStripe'])->name('payment.stripe.store')->middleware(['auth']);
        Route::get('/plan/company/status', [StripeController::class, 'planGetStripeStatus'])->name('payment.stripe.status')->middleware(['auth']);

        Route::post('{userSlug?}/booking/payment', [StripeController::class, 'bookingPayWithStripe'])->name('booking.payment.stripe.store');
        Route::get('{userSlug?}/booking/status', [StripeController::class, 'bookingGetStripeStatus'])->name('booking.payment.stripe.status');



        // BeautySpa payment routes
        Route::post('{userSlug?}/beauty-spa/payment', [StripeController::class, 'beautySpaPayWithStripe'])->name('beauty-spa.payment.stripe.store');
        Route::get('{userSlug?}/beauty-spa/status', [StripeController::class, 'beautySpaGetStripeStatus'])->name('beauty-spa.payment.stripe.status');

        // LMS payment routes
        Route::post('{userSlug?}/lms/payment', [StripeController::class, 'lmsPayWithStripe'])->name('lms.payment.stripe.store');
        Route::get('{userSlug?}/lms/status', [StripeController::class, 'lmsGetStripeStatus'])->name('lms.payment.stripe.status');

        // Laundry payment routes
        Route::post('{userSlug?}/laundry/payment', [StripeController::class, 'laundryPayWithStripe'])->name('laundry.payment.stripe.store');
        Route::get('{userSlug?}/laundry/status', [StripeController::class, 'laundryGetStripeStatus'])->name('laundry.payment.stripe.status');

        // Parking payment routes
        Route::post('{userSlug}/parking/payment', [StripeController::class, 'parkingPayWithStripe'])->name('parking.payment.stripe.store');
        Route::get('{userSlug}/parking/status', [StripeController::class, 'parkingGetStripeStatus'])->name('parking.payment.stripe.status');

        // EventsManagement payment routes
        Route::post('{userSlug?}/events/payment', [StripeController::class, 'eventsPayWithStripe'])->name('events-management.payment.stripe.store');
        Route::get('{userSlug?}/events/status', [StripeController::class, 'eventsGetStripeStatus'])->name('events-management.payment.stripe.status');

        // Holidayz payment routes
        Route::post('{userSlug?}/holidayz/payment', [StripeController::class, 'holidayzPayWithStripe'])->name('holidayz.payment.stripe.store');
        Route::get('{userSlug?}/holidayz/status', [StripeController::class, 'holidayzGetStripeStatus'])->name('holidayz.payment.stripe.status');

        // Facilities payment routes
        Route::post('{userSlug}/facilities/payment', [StripeController::class, 'facilitiesPaymentWithStripe'])->name('facilities.payment.stripe.store');
        Route::get('{userSlug}/facilities/status', [StripeController::class, 'facilitiesGetStripeStatus'])->name('facilities.payment.stripe.status');

        // Vehicle Booking payment routes
        Route::post('{userSlug?}/vehicle-booking/payment', [StripeController::class, 'vehicleBookingPayWithStripe'])->name('vehicle-booking.payment.stripe.store');
        Route::get('{userSlug?}/vehicle-booking/status', [StripeController::class, 'vehicleBookingGetStripeStatus'])->name('vehicle-booking.payment.stripe.status');

        // Movie Booking payment routes
        Route::post('{userSlug?}/movie-booking/payment', [StripeController::class, 'movieBookingPayWithStripe'])->name('movie-booking.payment.stripe.store');
        Route::get('{userSlug?}/movie-booking/status', [StripeController::class, 'movieBookingGetStripeStatus'])->name('movie-booking.payment.stripe.status');

        // NGO donation payment routes
        Route::post('{userSlug?}/ngo/donation/payment', [StripeController::class, 'ngoDonationPayWithStripe'])->name('ngo.donation.payment.stripe.store');
        Route::get('{userSlug?}/ngo/donation/status', [StripeController::class, 'ngoDonationGetStripeStatus'])->name('ngo.donation.payment.stripe.status');

        // Coworking Space payment routes
        Route::post('{userSlug?}/coworking-space/payment', [StripeController::class, 'coworkingSpacePayWithStripe'])->name('coworking-space.payment.stripe.store');
        Route::get('{userSlug?}/coworking-space/status', [StripeController::class, 'coworkingSpaceGetStripeStatus'])->name('coworking-space.payment.stripe.status');

        // Sports Club payment routes
        Route::post('{userSlug?}/sports-club/payment', [StripeController::class, 'sportsClubPayWithStripe'])->name('sports-club.payment.stripe.store');
        Route::get('{userSlug?}/sports-club/status', [StripeController::class, 'sportsClubGetStripeStatus'])->name('sports-club.payment.stripe.status');

        // Sports Club Plan payment routes
        Route::post('{userSlug?}/sports-club-plan/payment', [StripeController::class, 'sportsClubPlanPayWithStripe'])->name('sports-club-plan.payment.stripe.store');
        Route::get('{userSlug?}/sports-club-plan/status', [StripeController::class, 'sportsClubPlanGetStripeStatus'])->name('sports-club-plan.payment.stripe.status');
    });
});
