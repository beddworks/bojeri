<?php

namespace Workdo\Stripe\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Workdo\Stripe\Events\StripePaymentStatus;
use Workdo\Bookings\Models\BookingAppointment;
use Workdo\Bookings\Models\BookingPackage;
use Workdo\Bookings\Models\BookingCustomer;
use Workdo\LaundryManagement\Models\LaundryRequest;
use Workdo\Holidayz\Models\HolidayzCart;
use Workdo\Holidayz\Models\HolidayzRoomBooking;
use Workdo\Holidayz\Models\HolidayzRoomBookingItem;
use Workdo\Holidayz\Models\HolidayzCoupon;
use Workdo\Holidayz\Models\HolidayzCouponUsage;
use Workdo\Holidayz\Helpers\HolidayzAvailabilityHelper;

use Workdo\LMS\Models\LMSCart;
use Workdo\LMS\Models\LMSOrder;
use Workdo\LMS\Models\LMSOrderItem;
use Workdo\LMS\Models\LMSCoupon;
use Inertia\Inertia;
use Workdo\BeautySpaManagement\Models\BeautyBooking;
use Workdo\BeautySpaManagement\Models\BeautyService;
use Workdo\BeautySpaManagement\Models\BeautyBookingReceipt;
use Stripe\StripeClient;
use Workdo\BeautySpaManagement\Events\BeautyBookingPayments;
use Workdo\BeautySpaManagement\Models\BeautyServiceOffer;
use Workdo\Bookings\Events\BookingAppointmentPayments;
use Workdo\CoworkingSpaceManagement\Events\CoworkingBookingPayments;
use Workdo\CoworkingSpaceManagement\Events\CoworkingMembershipPayments;
use Workdo\CoworkingSpaceManagement\Http\Controllers\CoworkingMembershipController;
use Workdo\CoworkingSpaceManagement\Models\CoworkingBooking;
use Workdo\CoworkingSpaceManagement\Models\CoworkingMembership;
use Workdo\CoworkingSpaceManagement\Models\CoworkingMembershipPlan;
use Workdo\EventsManagement\Events\EventBookingPayments;
use Workdo\ParkingManagement\Models\ParkingBooking;
use Workdo\LaundryManagement\Events\LaundryBookingPayments;
use Workdo\EventsManagement\Models\Event;
use Workdo\EventsManagement\Models\EventBooking;
use Workdo\EventsManagement\Models\EventBookingPayment;

use Workdo\Facilities\Services\FacilitiesBookingService;
use Workdo\Holidayz\Events\HolidayzBookingPayments;
use Workdo\LMS\Events\LMSOrderPayments;
use Workdo\NGOManagment\Events\CreateNgoDonation;
use Workdo\NGOManagment\Http\Controllers\DonationController;
use Workdo\NGOManagment\Models\NgoCampaign;
use Workdo\NGOManagment\Models\NgoDonation;
use Workdo\NGOManagment\Models\NgoDonor;
use Workdo\MovieShowBookingSystem\Events\MovieBookingPayments;
use Workdo\MovieShowBookingSystem\Models\MovieBooking;
use Workdo\ParkingManagement\Events\ParkingBookingPayments;
use Workdo\SportsClubAndAcademyManagement\Events\SportsClubBookingPayments;
use Workdo\SportsClubAndAcademyManagement\Events\SportsClubPlanPayments;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubAndGroundOrder;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubAssignedMembership;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubBookingFacility;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubFacility;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubGround;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubMember;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubMembershipPlan;
use Workdo\SportsClubAndAcademyManagement\Models\SportsClubMembershipPlanPayment;
use Workdo\Stripe\Events\FacilityBookingPaymentStripe;

use Workdo\VehicleBookingManagement\Events\VehicleBookingPayments;
use Workdo\VehicleBookingManagement\Models\VehicleBooking;

class StripeController extends Controller
{
    public function planPayWithStripe(Request $request)
    {
        $plan = Plan::find($request->plan_id);
        $user = User::find($request->user_id);
        $admin_settings = getAdminAllSetting();
        $admin_currancy = !empty($admin_settings['defaultCurrency']) ? $admin_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($admin_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }
        $user_counter = !empty($request->user_counter_input) ? $request->user_counter_input : 0;
        $user_module = !empty($request->user_module_input) ? $request->user_module_input : '';
        $storage_limit = !empty($request->storage_limit_input) ? $request->storage_limit_input : 0;
        $duration = !empty($request->time_period) ? $request->time_period : 'Month';
        $user_module_price = 0;

        if (!empty($user_module) && $plan->custom_plan == 1) {
            $user_module_array = explode(',', $user_module);
            foreach ($user_module_array as $key => $value) {
                $temp = ($duration == 'Year') ? ModulePriceByName($value)['yearly_price'] : ModulePriceByName($value)['monthly_price'];
                $user_module_price = $user_module_price + $temp;
            }
        }
        $user_price = 0;
        if ($user_counter > 0) {
            $temp = ($duration == 'Year') ? $plan->price_per_user_yearly : $plan->price_per_user_monthly;
            $user_price = $user_counter * $temp;
        }

        $storage_price = 0;
        if ($storage_limit > 0 && $plan->custom_plan == 1) {
            $temp = ($duration == 'Year') ? $plan->price_per_storage_yearly : $plan->price_per_storage_monthly;
            $storage_price = $storage_limit * $temp;
        }

        $plan_price = ($duration == 'Year') ? $plan->package_price_yearly : $plan->package_price_monthly;
        $counter = [
            'user_counter' => $user_counter,
            'storage_limit' => $storage_limit,
        ];

        $stripe_session = '';
        $orderID = strtoupper(substr(uniqid(), -12));

        if ($plan) {
            /* Check for code usage */
            $plan->discounted_price = false;
            $payment_frequency = $plan->duration;
            $price = $plan_price + $user_module_price + $user_price + $storage_price;

            if ($request->coupon_code) {
                $validation = applyCouponDiscount($request->coupon_code, $price, auth()->id());
                if ($validation['valid']) {
                    $price = $validation['final_amount'];
                }
            }
            if ($price <= 0) {
                $assignPlan = assignPlan($plan->id, $duration, $user_module, $counter, $request->user_id);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __('Something went wrong, Please try again,'));
                }
            }

            try {

                $payment_plan = $duration;
                $payment_type = 'onetime';
                /* Payment details */
                $code = '';

                /* Final price */
                $stripe_formatted_price = in_array(
                    $admin_currancy,
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                        'BRL'
                    ]
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;
                $return_url_parameters = function ($return_type) use ($payment_frequency, $payment_type) {
                    return '&return_type=' . $return_type . '&payment_processor=stripe&payment_frequency=' . $payment_frequency . '&payment_type=' . $payment_type;
                };
                /* Initiate Stripe */
                $stripe_session = $this->createStripeSession([
                    'api_key' => $admin_settings['stripe_secret'] ?? '',
                    'currency' => $admin_currancy,
                    'amount' => $stripe_formatted_price,
                    'product_name' => $plan->name ?? 'Basic Package',
                    'description' => $payment_plan,
                    'metadata' => [
                        'user_id' => $user->id,
                        'package_id' => $plan->id,
                        'payment_frequency' => $payment_frequency,
                        'code' => $code,
                    ],
                    'success_url' => route('payment.stripe.status', [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        'user_module' => $user_module,
                        'duration' => $duration,
                        'counter' => $counter,
                        'coupon_code' => $request->coupon_code,
                        'user_id' => $user->id,
                        $return_url_parameters('success'),
                    ]),
                    'cancel_url' => route('payment.stripe.status', [
                        'plan_id' => $orderID,
                        'order_id' => $plan->id,
                        $return_url_parameters('cancel'),
                    ]),
                ]);

                $order = new Order();
                $order->order_id = $orderID;
                $order->name = $user->name ?? '';
                $order->email = $user->email ?? '';
                $order->card_number = null;
                $order->card_exp_month = null;
                $order->card_exp_year = null;
                $order->plan_name = !empty($plan->name) ? $plan->name : 'Basic Package';
                $order->plan_id = $plan->id;
                $order->price = !empty($price) ? $price : 0;
                $order->currency = $admin_currancy;
                $order->txn_id = '';
                $order->payment_type = 'Stripe';
                $order->payment_status = 'pending';
                $order->receipt = null;
                $order->created_by = $user->id;
                $order->save();

                Session::put('stripe_session', $stripe_session);
                $stripe_session = $stripe_session ?? false;
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', $e->getMessage());
            }
            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $admin_settings['stripe_key'] ?? ''
            ]);
        } else {
            return redirect()->route('plans.index')->with('error', __('The Plan has been deleted.'));
        }
    }

    public function planGetStripeStatus(Request $request)
    {
        $admin_settings = getAdminAllSetting();
        try {
            $stripe = new StripeClient(!empty($admin_settings['stripe_secret']) ? $admin_settings['stripe_secret'] : '');
            $stripe_session = Session::get('stripe_session');
            if ($stripe_session && isset($stripe_session->payment_intent)) {
                $paymentIntents = $stripe->paymentIntents->retrieve(
                    $stripe_session->payment_intent,
                    []
                );
                $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
            } else {
                $receipt_url = "";
            }
        } catch (\Exception $exception) {
            $receipt_url = "";
        }
        Session::forget('stripe_session');
        try {
            if ($request->return_type == 'success') {
                $Order = Order::where('order_id', $request->order_id)->first();
                $Order->payment_status = 'succeeded';
                $Order->receipt = $receipt_url;
                $Order->save();

                $plan = Plan::find($request->plan_id);
                $counter = [
                    'user_counter' => $request->counter['user_counter'] ?? 0,
                    'storage_counter' => $request->counter['storage_limit'] ?? 0,
                ];
                $assignPlan = assignPlan($plan->id, $request->duration, $request->user_module, $counter, $request->user_id);
                if ($assignPlan['is_success']) {
                    if ($request->coupon_code) {
                        $coupon = Coupon::where('code', $request->coupon_code)->first();
                        if ($coupon) {
                            recordCouponUsage($coupon->id, $request->user_id, $request->order_id);
                        }
                    }
                    $type = 'Subscription';

                    try {
                        StripePaymentStatus::dispatch($plan, $type, $Order);
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', $e->getMessage());
                    }

                    $value = Session::get('user-module-selection');
                    if (!empty($value)) {
                        Session::forget('user-module-selection');
                    }
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __('Something went wrong, Please try again,'));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('plans.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Create Stripe checkout session - Dynamic for both plans and invoices
     */
    private function createStripeSession($params)
    {
        $api_key = $params['api_key'] ??
            $params['admin_settings']['stripe_secret'] ??
            ($params['company_settings'] ? company_setting('stripe_secret', $params['user_id'] ?? null) : null) ??
            '';
        \Stripe\Stripe::setApiKey($api_key);

        // Build session data
        $session_data = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $params['currency'],
                    'unit_amount' => (int) $params['amount'],
                    'product_data' => [
                        'name' => $params['product_name'],
                        'description' => $params['description'] ?? '',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => $params['metadata'],
            'success_url' => $params['success_url'],
            'cancel_url' => $params['cancel_url'],
        ];

        return \Stripe\Checkout\Session::create($session_data);
    }

    public function bookingPayWithStripe(Request $request)
    {
        // Get booking data from request (same structure as booking.store)
        $selectedTimeSlot = [
            'start_time' => $request->input('selectedTimeSlot.start_time'),
            'end_time' => $request->input('selectedTimeSlot.end_time'),
            'label' => $request->input('selectedTimeSlot.label')
        ];

        $bookingData = [
            'selectedDate' => $request->selectedDate,
            'selectedItem' => $request->selectedItem,
            'selectedPackageItem' => $request->selectedPackageItem,
            'selectedTimeSlot' => $selectedTimeSlot,
            'formData' => [
                'firstName' => $request->input('formData.firstName'),
                'lastName' => $request->input('formData.lastName'),
                'email' => $request->input('formData.email'),
                'phone' => $request->input('formData.phone'),
                'description' => $request->input('formData.description'),
                'paymentOption' => $request->input('formData.paymentOption')
            ]
        ];

        // Store booking data and userSlug in session for after payment
        Session::put('booking_data', $bookingData);
        Session::put('booking_user_slug', $request->route('userSlug'));

        $package = BookingPackage::find($request->selectedPackageItem);
        if (!$package) {
            return redirect()->back()->with('error', __('Package not found.'));
        }

        $company_settings = getCompanyAllSetting($package->created_by);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = $package->price ?? 0;
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => $package->name ?? 'Booking Service',
                'description' => 'Booking Service Payment',
                'metadata' => [
                    'package_id' => $package->id,
                    'customer_name' => $bookingData['formData']['firstName'] . ' ' . $bookingData['formData']['lastName'],
                    'customer_email' => $bookingData['formData']['email'],
                ],
                'success_url' => route('booking.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $request->route('userSlug')
                ]),
                'cancel_url' => route('booking.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $request->route('userSlug')
                ]),
            ]);

            Session::put('booking_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function bookingGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('booking_data');
        $stripe_session = Session::get('booking_stripe_session');
        $userSlug = Session::get('booking_user_slug');
        if (!$bookingData) {
            return redirect()->route('booking.home', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $package = BookingPackage::find($bookingData['selectedPackageItem']);
        $company_settings = getCompanyAllSetting($package->created_by ?? 1);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');
            $payment_intent = null;
            $receipt_url = "";
            if ($stripe_session && isset($stripe_session['id'])) {
                // Retrieve fresh session from Stripe API
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                        $receipt_url = $charge->receipt_url ?? '';
                    }
                }
            }
        } catch (\Exception $exception) {
            $receipt_url = "";
        }

        Session::forget('booking_stripe_session');
        Session::forget('booking_data');

        try {
            if ($request->return_type == 'success') {
                // Create appointment after successful payment
                $timeSlot = $bookingData['selectedTimeSlot'];
                $userId = $package->created_by ?? 1;

                // Find or create customer (same as BookingController)
                $customer = BookingCustomer::where('email', $bookingData['formData']['email'])
                    ->where('created_by', $userId)
                    ->first();

                if (!$customer) {
                    $customer = new BookingCustomer();
                    $customer->first_name = $bookingData['formData']['firstName'];
                    $customer->last_name = $bookingData['formData']['lastName'];
                    $customer->email = $bookingData['formData']['email'];
                    $customer->mobile_number = $bookingData['formData']['phone'];
                    $customer->description = $bookingData['formData']['description'] ?? null;
                    $customer->created_by = $userId;
                    $customer->creator_id = $userId;
                    $customer->save();
                }

                // Generate appointment number (same as BookingController)
                $currentYear = date('Y');
                $lastAppointment = BookingAppointment::where('created_by', $userId)
                    ->where('appointment_number', 'like', 'APT-' . $currentYear . '-' . $userId . '-%')
                    ->orderBy('appointment_number', 'desc')
                    ->first();

                if ($lastAppointment) {
                    $lastNumber = (int) substr($lastAppointment->appointment_number, -4);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                $appointmentNumber = 'APT-' . $currentYear . '-' . $userId . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                // Create appointment (same structure as BookingController)
                $appointment = new BookingAppointment();
                $appointment->appointment_number = $appointmentNumber;
                $appointment->date = $bookingData['selectedDate'];
                $appointment->item_id = $bookingData['selectedItem'];
                $appointment->package_id = $bookingData['selectedPackageItem'];
                $appointment->customer_id = $customer->id;
                $appointment->start_time = $timeSlot['start_time'];
                $appointment->end_time = $timeSlot['end_time'];
                $appointment->payment = 'Stripe';
                $appointment->status = 'confirmed';
                $appointment->payment_status = 'paid';
                $appointment->payment_receipt = $receipt_url;
                $appointment->online_payment_id = $payment_intent ?? null;
                $appointment->created_by = $userId;
                $appointment->creator_id = $userId;
                $appointment->save();

                try {
                    BookingAppointmentPayments::dispatch($appointment);
                } catch (\Throwable $th) {
                    return redirect()->route('booking.home', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                }

                // Get userSlug from session
                Session::forget('booking_user_slug');

                return redirect()->route('booking.home', ['userSlug' => $userSlug])->with('success', __('Payment completed and appointment created successfully!'));
            } else {
                Session::forget('booking_user_slug');
                return redirect()->route('booking.home', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('booking.home', ['userSlug' => $userSlug])->with('error', $exception->getMessage());
        }
    }


    public function beautySpaPayWithStripe(Request $request)
    {
        // Store booking data in session
        $bookingData = [
            'service' => $request->service,
            'date' => $request->date,
            'time_slot' => $request->time_slot,
            'person' => $request->person,
            'gender' => $request->gender,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'reference' => $request->reference,
            'additional_notes' => $request->additional_notes,
            'payment_option' => $request->payment_option
        ];

        Session::put('beauty_booking_data', $bookingData);
        Session::put('beauty_booking_user_slug', $request->route('userSlug'));

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $service = BeautyService::where('id', $request->service)
            ->where('created_by', $userId)
            ->firstOrFail();

        $company_settings = getCompanyAllSetting($userId);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        // Check for active offers
        $offers = BeautyServiceOffer::where('beauty_service_id', $service->id)
            ->where('start_date', '<=', $request->date)
            ->where('end_date', '>=', $request->date)
            ->where('created_by', $userId)
            ->get();
        $totalOfferPrice = $offers->sum('offer_price');
        $price = $totalOfferPrice * $request->person;
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => $service->name ?? 'Beauty Service',
                'description' => 'Beauty Service Payment',
                'metadata' => [
                    'service_id' => $service->id,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                ],
                'success_url' => route('beauty-spa.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('beauty-spa.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('beauty_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function beautySpaGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('beauty_booking_data');
        $stripe_session = Session::get('beauty_stripe_session');
        $userSlug = Session::get('beauty_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('beauty-spa.booking', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $service = BeautyService::where('id', $bookingData['service'])
            ->where('created_by', $userId)
            ->first();

        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('beauty_stripe_session');
        Session::forget('beauty_booking_data');
        Session::forget('beauty_booking_user_slug');

        try {
            if ($request->return_type == 'success') {
                // Check for active offers to get correct price
                $offers = BeautyServiceOffer::where('beauty_service_id', $service->id)
                    ->where('start_date', '<=', $bookingData['date'])
                    ->where('end_date', '>=', $bookingData['date'])
                    ->where('created_by', $userId)
                    ->get();

                $totalOfferPrice = $offers->sum('offer_price');
                $servicePrice = $totalOfferPrice * $bookingData['person'];
                $times = explode('-', $bookingData['time_slot']);

                $booking = new BeautyBooking();
                $booking->name = $bookingData['name'];
                $booking->email = $bookingData['email'];
                $booking->phone_number = $bookingData['phone_number'];
                $booking->service = $bookingData['service'];
                $booking->date = $bookingData['date'];
                $booking->start_time = $times[0];
                $booking->end_time = $times[1];
                $booking->person = $bookingData['person'];
                $booking->price = $servicePrice;
                $booking->gender = $bookingData['gender'];
                $booking->reference = $bookingData['reference'];
                $booking->notes = $bookingData['additional_notes'];
                $booking->payment_option = 'Stripe';
                $booking->payment_status = 'paid';
                $booking->stage_id = 0;
                $booking->creator_id = null;
                $booking->created_by = $userId;
                $booking->save();

                $beautyreceipt                  = new BeautyBookingReceipt();
                $beautyreceipt->beauty_booking_id      = $booking->id;
                $beautyreceipt->name            = $booking->name;
                $beautyreceipt->service         = $booking->service;
                $beautyreceipt->number          = $booking->number;
                $beautyreceipt->gender          = $booking->gender;
                $beautyreceipt->start_time      = $booking->start_time;
                $beautyreceipt->end_time        = $booking->end_time;
                $beautyreceipt->price           = $booking->price;
                $beautyreceipt->payment_type    = 'Stripe';
                $beautyreceipt->created_by      = $booking->created_by;
                $beautyreceipt->save();

                try {
                    BeautyBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }

                return redirect()->route('beauty-spa.booking-success', ['userSlug' => $userSlug, 'id' => \Illuminate\Support\Facades\Crypt::encrypt($booking->id)])
                    ->with('success', __('Payment completed and booking confirmed successfully!'));
            } else {
                return redirect()->route('beauty-spa.booking', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('beauty-spa.booking', ['userSlug' => $userSlug])->with('error', $exception->getMessage());
        }
    }

    public function lmsPayWithStripe(Request $request)
    {
        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        if (!$user) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        $student = auth('lms_student')->user();
        if (!$student) {
            return redirect()->route('lms.frontend.login', ['userSlug' => $userSlug]);
        }

        // Get cart items
        $cartItems = LMSCart::where('created_by', $user->id)
            ->where('student_id', $student->id)
            ->with('course')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('lms.frontend.cart', ['userSlug' => $userSlug])
                ->with('error', __('Your cart is empty'));
        }

        // Calculate totals (same logic as placeOrder)
        $originalTotal = $cartItems->sum('original_price');
        $subtotal = $cartItems->sum('price');
        $courseDiscount = $originalTotal - $subtotal;
        $couponDiscount = 0;
        $appliedCoupon = session('applied_coupon');

        if ($appliedCoupon) {
            $coupon = LMSCoupon::where('id', $appliedCoupon['id'])
                ->where('created_by', $user->id)
                ->first();

            if ($coupon && $coupon->isValid()) {
                if (!$coupon->minimum_amount || $subtotal >= $coupon->minimum_amount) {
                    if ($coupon->type === 'percentage') {
                        $couponDiscount = ($subtotal * $coupon->value) / 100;
                    } else {
                        $couponDiscount = $coupon->value;
                    }
                    $couponDiscount = min($couponDiscount, $subtotal);
                }
            }
        }

        $total = $subtotal - $couponDiscount;

        if ($total <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        // Store order data in session
        Session::put('lms_order_data', [
            'original_total' => $originalTotal,
            'payment_method' => $request->payment_method,
            'payment_note' => $request->payment_note,
            'subtotal' => $subtotal,
            'course_discount' => $courseDiscount,
            'coupon_discount' => $couponDiscount,
            'total' => $total,
            'applied_coupon' => $appliedCoupon
        ]);
        Session::put('lms_user_slug', $userSlug);

        $company_settings = getCompanyAllSetting($user->id);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($total, 2, '.', '') : number_format($total, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => 'LMS Course Purchase',
                'description' => 'Online Course Payment',
                'metadata' => [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'student_email' => $student->email,
                    'course_count' => $cartItems->count()
                ],
                'success_url' => route('lms.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('lms.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('lms_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function lmsGetStripeStatus(Request $request)
    {
        $orderData = Session::get('lms_order_data');
        $stripe_session = Session::get('lms_stripe_session');
        $userSlug = Session::get('lms_user_slug');

        if (!$orderData) {
            return redirect()->route('lms.frontend.home', ['userSlug' => $userSlug])->with('error', __('Order data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $student = auth('lms_student')->user();

        if (!$user || !$student) {
            return redirect()->route('lms.frontend.home', ['userSlug' => $userSlug])->with('error', __('Invalid session.'));
        }

        $company_settings = getCompanyAllSetting($user->id);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');
            $payment_intent = null;
            $receipt_url = "";

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                        $receipt_url = $charge->receipt_url ?? '';
                    }
                }
            }
        } catch (\Exception $exception) {
            $receipt_url = "";
        }

        Session::forget('lms_stripe_session');
        Session::forget('lms_order_data');
        Session::forget('lms_user_slug');

        try {
            if ($request->return_type == 'success') {
                // Get cart items
                $cartItems = LMSCart::where('created_by', $user->id)
                    ->where('student_id', $student->id)
                    ->with('course')
                    ->get();

                if ($cartItems->isEmpty()) {
                    return redirect()->route('lms.frontend.cart', ['userSlug' => $userSlug])
                        ->with('error', __('Your cart is empty'));
                }

                // Create order
                $order = new LMSOrder();
                $order->order_number = LMSOrder::generateOrderNumber($user->id);
                $order->student_id = $student->id;
                $order->payment_method = 'Stripe';
                $order->payment_status = 'paid';
                $order->original_total = $orderData['original_total'];
                $order->subtotal = $orderData['subtotal'];
                $order->discount_amount = $orderData['course_discount'];
                $order->coupon_discount = $orderData['coupon_discount'];
                $order->total_discount = $orderData['course_discount'] + $orderData['coupon_discount'];
                $order->total_amount = $orderData['total'];
                $order->coupon_id = $orderData['applied_coupon'] ? $orderData['applied_coupon']['id'] : null;
                $order->coupon_code = $orderData['applied_coupon'] ? $orderData['applied_coupon']['code'] : null;
                $order->status = 'confirmed';
                $order->receipt = $receipt_url;
                $order->notes = $orderData['payment_note'];
                $order->order_date = now();
                $order->payment_id = $payment_intent;
                $order->creator_id = $user->id;
                $order->created_by = $user->id;
                $order->save();

                // Create order items
                foreach ($cartItems as $cartItem) {
                    $orderItem = new LMSOrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->course_id = $cartItem->course_id;
                    $orderItem->quantity = $cartItem->quantity;
                    $orderItem->unit_price = $cartItem->price;
                    $orderItem->total_price = $cartItem->price * $cartItem->quantity;
                    $orderItem->save();
                }

                // Clear cart and coupon
                $cartItems->each->delete();
                session()->forget('applied_coupon');

                try {
                    LMSOrderPayments::dispatch($order);
                } catch (\Throwable $th) {
                    return redirect()->route('lms.frontend.home', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                }

                return redirect()->route('lms.frontend.home', ['userSlug' => $userSlug])
                    ->with('success', __('Payment completed successfully! Order #:number', ['number' => $order->order_number]));
            } else {
                return redirect()->route('lms.frontend.checkout', ['userSlug' => $userSlug])
                    ->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('lms.frontend.checkout', ['userSlug' => $userSlug])
                ->with('error', $exception->getMessage());
        }
    }
    public function laundryPayWithStripe(Request $request)
    {
        $bookingData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'location' => $request->location,
            'numberOfItems' => $request->cloth_no,
            'specialInstructions' => $request->instructions,
            'pickupDate' => $request->pickup_date,
            'pickupTime' => $request->pickupTime,
            'deliveryDate' => $request->delivery_date,
            'deliveryTime' => $request->deliveryTime,
            'services' => json_decode($request->services, true) ?? [],
            'total' => $request->total
        ];

        Session::put('laundry_booking_data', $bookingData);
        Session::put('laundry_booking_user_slug', $request->route('userSlug'));

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $company_settings = getCompanyAllSetting($userId);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = floatval($request->total ?? 0);
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => 'Laundry Service',
                'description' => 'Laundry Service Payment',
                'metadata' => [
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                ],
                'success_url' => route('laundry.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('laundry.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('laundry_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function laundryGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('laundry_booking_data');
        $stripe_session = Session::get('laundry_stripe_session');
        $userSlug = Session::get('laundry_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('laundry-management.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;
        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');
            $payment_intent = null;

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('laundry_stripe_session');
        Session::forget('laundry_booking_data');
        Session::forget('laundry_booking_user_slug');

        try {
            if ($request->return_type == 'success') {
                $booking = new LaundryRequest();
                $booking->name = $bookingData['name'];
                $booking->email = $bookingData['email'];
                $booking->phone = $bookingData['phone'];
                $booking->address = $bookingData['address'];
                $booking->location = $bookingData['location'];
                $booking->cloth_no = $bookingData['numberOfItems'];
                $booking->instructions = $bookingData['specialInstructions'];
                $booking->pickup_date = $bookingData['pickupDate'] . ' ' . $bookingData['pickupTime'];
                $booking->delivery_date = $bookingData['deliveryDate'] . ' ' . $bookingData['deliveryTime'];
                $booking->services = $bookingData['services'];
                $booking->payment_method = 'Stripe';
                $booking->payment_id = $payment_intent;
                $booking->status = 2;
                $booking->total = $bookingData['total'];
                $booking->created_by = $userId;
                $booking->creator_id = $userId;
                $booking->save();

                try {
                    LaundryBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }

                return redirect()->route('laundry-management.frontend.booking-success', [
                    'userSlug' => $userSlug,
                    'requestId' => encrypt($booking->id)
                ]);
            } else {
                return redirect()->route('laundry-management.frontend.booking', ['userSlug' => $userSlug])
                    ->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('laundry-management.frontend.booking', ['userSlug' => $userSlug])
                ->with('error', $exception->getMessage());
        }
    }
    public function parkingPayWithStripe(Request $request)
    {
        $bookingData = [
            'slot_name'      => $request->slot_name,
            'slot_type_id'   => $request->slot_type_id,
            'date'           => $request->date,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'vehicle_name'   => $request->vehicle_name,
            'vehicle_number' => $request->vehicle_number,
            'payment_option' => $request->payment_option,
            'total_amount'   => $request->total_amount
        ];

        Session::put('parking_booking_data', $bookingData);
        Session::put('parking_booking_user_slug', $request->route('userSlug'));

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $company_settings = getCompanyAllSetting($userId);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = floatval($request->total_amount);
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => 'Parking Slot - ' . $request->slot_name,
                'description' => 'Parking Management Payment',
                'metadata' => [
                    'slot_name' => $request->slot_name,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                ],
                'success_url' => route('parking.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('parking.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('parking_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function parkingGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('parking_booking_data');
        $stripe_session = Session::get('parking_stripe_session');
        $userSlug = Session::get('parking_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('parking-management.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;
        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('parking_stripe_session');
        Session::forget('parking_booking_data');
        Session::forget('parking_booking_user_slug');

        try {
            if ($request->return_type == 'success') {
                $booking = new ParkingBooking();
                $booking->slot_name = $bookingData['slot_name'];
                $booking->slot_type_id = $bookingData['slot_type_id'];
                $booking->booking_date = $bookingData['date'];
                $booking->start_time = $bookingData['start_time'];
                $booking->end_time = $bookingData['end_time'];
                $booking->customer_name = $bookingData['customer_name'];
                $booking->customer_email = $bookingData['customer_email'];
                $booking->customer_phone = $bookingData['customer_phone'];
                $booking->vehicle_name = $bookingData['vehicle_name'];
                $booking->vehicle_number = $bookingData['vehicle_number'];
                $booking->total_amount = $bookingData['total_amount'];
                $booking->payment_method = 'Stripe';
                $booking->payment_status = 'paid';
                $booking->booking_status = 'confirmed';
                $booking->creator_id = $userId;
                $booking->created_by = $userId;
                $booking->save();

                try {
                    ParkingBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return redirect()->route('parking-management.frontend.booking', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                }

                return redirect()->route('parking-management.frontend.booking-success', ['userSlug' => $userSlug, 'id' => \Illuminate\Support\Facades\Crypt::encrypt($booking->id)])
                    ->with('success', __('Payment completed and booking confirmed successfully!'));
            } else {
                return redirect()->route('parking-management.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('parking-management.frontend.booking', ['userSlug' => $userSlug])->with('error', $exception->getMessage());
        }
    }

    public function eventsPayWithStripe(Request $request)
    {
        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        if (!$user) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        $eventId = $request->event_id;
        $event = Event::where('id', $eventId)
            ->where('created_by', $user->id)
            ->firstOrFail();

        // Store booking data in session
        $bookingData = [
            'event_id' => $eventId,
            'fullName' => $request->fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'persons' => $request->persons,
            'total' => $request->total,
            'ticket_type_id' => $request->ticket_type_id,
            'time_slot' => $request->time_slot,
            'selected_date' => $request->selected_date
        ];

        Session::put('events_booking_data', $bookingData);
        Session::put('events_user_slug', $userSlug);

        $company_settings = getCompanyAllSetting($user->id);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = floatval($request->total);
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => $event->title ?? 'Event Booking',
                'description' => 'Event Booking Payment',
                'metadata' => [
                    'event_id' => $eventId,
                    'customer_name' => $request->fullName,
                    'customer_email' => $request->email,
                ],
                'success_url' => route('events-management.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('events-management.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('events_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function eventsGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('events_booking_data');
        $stripe_session = Session::get('events_stripe_session');
        $userSlug = Session::get('events_user_slug');
        if (!$bookingData) {
            return redirect()->route('events-management.frontend.index', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $event = Event::where('id', $bookingData['event_id'])
            ->where('created_by', $user->id)
            ->first();

        $company_settings = getCompanyAllSetting($user->id);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('events_stripe_session');
        Session::forget('events_booking_data');
        Session::forget('events_user_slug');
        try {
            if ($request->return_type == 'success') {
                // Create event booking
                $eventbooking = new EventBooking();
                $eventbooking->event_id = $bookingData['event_id'];
                $eventbooking->ticket_type_id = $bookingData['ticket_type_id'];
                $eventbooking->time_slot = $bookingData['time_slot'];
                $eventbooking->name = $bookingData['fullName'];
                $eventbooking->email = $bookingData['email'];
                $eventbooking->mobile = $bookingData['phone'];
                $eventbooking->person = $bookingData['persons'];
                $eventbooking->date = $bookingData['selected_date'];
                $eventbooking->total_price = $bookingData['total'];
                $eventbooking->price = $bookingData['total'] / $bookingData['persons'];
                $eventbooking->status = 'confirmed';
                $eventbooking->created_by = $user->id;
                $eventbooking->creator_id = $user->id;
                $eventbooking->save();

                // Create payment record
                $eventBookingPayment = new EventBookingPayment();
                $eventBookingPayment->event_booking_id = $eventbooking->id;
                $eventBookingPayment->booking_number = $eventbooking->booking_number;
                $eventBookingPayment->event_name = $event->title;
                $eventBookingPayment->customer_name = $bookingData['fullName'];
                $eventBookingPayment->payment_date = now();
                $eventBookingPayment->amount = $bookingData['total'];
                $eventBookingPayment->payment_status = 'cleared';
                $eventBookingPayment->payment_type = 'Stripe';
                $eventBookingPayment->description = 'Payment via Stripe';
                $eventBookingPayment->created_by = $user->id;
                $eventBookingPayment->creator_id = $user->id;
                $eventBookingPayment->save();

                try {
                    EventBookingPayments::dispatch($eventbooking, $eventBookingPayment);
                } catch (\Throwable $th) {
                    return redirect()->route('events-management.frontend.ticket', ['userSlug' => $userSlug, 'id' => $bookingData['event_id']])->with('error', $th->getMessage());
                }

                return redirect()->route('events-management.frontend.ticket', ['userSlug' => $userSlug, 'id' => $eventbooking->id, 'paymentId' => $eventBookingPayment->id])
                    ->with('success', __('Payment completed and booking confirmed successfully!'));
            } else {
                return redirect()->route('events-management.frontend.payment', ['userSlug' => $userSlug, 'id' => $bookingData['event_id']])
                    ->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('events-management.frontend.payment', ['userSlug' => $userSlug, 'id' => $bookingData['event_id']])->with('error', $exception->getMessage());
        }
    }

    // Room Booking Stripe Payment
    public function holidayzPayWithStripe(Request $request)
    {
        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        if (!$user) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        $customer = auth('holidayz_customer')->user();
        if (!$customer) {
            return redirect()->route('hotel.frontend.login', ['userSlug' => $userSlug]);
        }

        // Get cart items with relationships
        $cart = HolidayzCart::where('created_by', $user->id)
            ->where('customer_id', $customer->id)
            ->with(['items.room', 'items.facilities', 'items.taxes'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('hotel.frontend.cart', ['userSlug' => $userSlug])
                ->with('error', __('Your cart is empty'));
        }

        // Check room availability for all cart items before payment
        foreach ($cart->items as $cartItem) {
            $availableRooms = HolidayzAvailabilityHelper::getAvailableRoomCount(
                $cartItem->room_id,
                $cartItem->check_in_date->format('Y-m-d'),
                $cartItem->check_out_date->format('Y-m-d'),
                null,
                $user->id
            );

            if ($cartItem->quantity > $availableRooms) {
                return redirect()->route('hotel.frontend.cart', ['userSlug' => $userSlug])
                    ->with('error', __('Room ":room" is no longer available for the selected dates. Only :available rooms available.', [
                        'room' => $cartItem->room->room_type,
                        'available' => $availableRooms
                    ]));
            }
        }

        // Calculate totals with proper coupon handling
        $subtotal = $cart->items->sum(function ($item) {
            return $item->rent_per_night * $item->nights * $item->quantity;
        });

        $tax_amount = $cart->items->sum(function ($item) {
            return $item->taxes->sum('pivot.tax_amount');
        });

        $facilities_amount = $cart->items->sum(function ($item) {
            return $item->facilities->sum('pivot.total_amount');
        });

        $coupon_discount = 0;
        $applied_coupon = session('applied_coupon');

        if ($applied_coupon && isset($applied_coupon['id'])) {
            $coupon = HolidayzCoupon::find($applied_coupon['id']);

            if ($coupon && $coupon->created_by == $user->id && $coupon->isValid()) {
                $coupon_discount = $applied_coupon['discount'] ?? 0;
                $coupon_discount = min($coupon_discount, $subtotal);
            } else {
                session()->forget('applied_coupon');
                $applied_coupon = null;
            }
        }

        $total = $subtotal + $tax_amount + $facilities_amount - $coupon_discount;

        if ($total <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        // Store order data in session
        Session::put('holidayz_order_data', [
            'payment_method' => 'Stripe',
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'facilities_amount' => $facilities_amount,
            'coupon_discount' => $coupon_discount,
            'total' => $total,
            'applied_coupon' => $applied_coupon,
            'special_requests' => $request->special_requests
        ]);
        Session::put('holidayz_user_slug', $userSlug);

        $company_settings = getCompanyAllSetting($user->id);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($total, 2, '.', '') : number_format($total, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => 'Hotel Booking - ' . $cart->items->count() . ' room(s)',
                'description' => 'Hotel Room Reservation Payment for ' . $cart->items->sum('nights') . ' night(s)',
                'metadata' => [
                    'customer_id' => $customer->id,
                    'customer_name' => ($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''),
                    'customer_email' => $customer->email ?? '',
                    'room_count' => $cart->items->sum('quantity'),
                    'total_nights' => $cart->items->sum('nights'),
                    'check_in' => $cart->items->first()->check_in_date ?? '',
                    'check_out' => $cart->items->first()->check_out_date ?? ''
                ],
                'success_url' => route('holidayz.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('holidayz.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('holidayz_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function holidayzGetStripeStatus(Request $request)
    {
        $orderData = Session::get('holidayz_order_data');
        $stripe_session = Session::get('holidayz_stripe_session');
        $userSlug = Session::get('holidayz_user_slug');

        if (!$orderData) {
            return redirect()->route('hotel.frontend.index', ['userSlug' => $userSlug])->with('error', __('Order data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $customer = auth('holidayz_customer')->user();
        if (!$user || !$customer) {
            return redirect()->route('hotel.frontend.index', ['userSlug' => $userSlug])->with('error', __('Invalid session.'));
        }

        $company_settings = getCompanyAllSetting($user->id);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('holidayz_stripe_session');
        Session::forget('holidayz_order_data');
        Session::forget('holidayz_user_slug');

        try {
            if ($request->return_type == 'success') {
                // Create booking after successful payment
                $cart = HolidayzCart::where('created_by', $user->id)
                    ->where('customer_id', $customer->id)
                    ->with(['items.room', 'items.facilities', 'items.taxes'])
                    ->first();

                $booking = new HolidayzRoomBooking();
                $booking->booking_date = now();
                $booking->customer_id = $customer->id;
                $booking->adults = $cart->items->sum('adults');
                $booking->children = $cart->items->sum('children');
                $booking->total_guests = $cart->items->sum('adults') + $cart->items->sum('children');
                $booking->subtotal = $orderData['subtotal'];
                $booking->tax_amount = $orderData['tax_amount'];
                $booking->coupon_id = $orderData['applied_coupon']['id'] ?? null;
                $booking->discount_amount = $orderData['coupon_discount'];
                $booking->total_amount = $orderData['total'];
                $booking->paid_amount = $orderData['total'];
                $booking->balance_amount = 0;
                $booking->payment_method = 'Stripe';
                $booking->status = 'paid';
                $booking->special_requests = $orderData['special_requests'];
                $booking->creator_id = $user->id;
                $booking->created_by = $user->id;
                $booking->save();

                foreach ($cart->items as $cartItem) {
                    $bookingItem = new HolidayzRoomBookingItem();
                    $bookingItem->booking_id = $booking->id;
                    $bookingItem->room_id = $cartItem->room_id;
                    $bookingItem->check_in_date = $cartItem->check_in_date;
                    $bookingItem->check_out_date = $cartItem->check_out_date;
                    $bookingItem->quantity = $cartItem->quantity;
                    $bookingItem->adults = $cartItem->adults;
                    $bookingItem->children = $cartItem->children;
                    $bookingItem->rent_per_night = $cartItem->rent_per_night;
                    $bookingItem->nights = $cartItem->nights;
                    $bookingItem->discount_percentage = 0;
                    $bookingItem->discount_amount = 0;
                    $bookingItem->total_amount = $cartItem->rent_per_night * $cartItem->nights * $cartItem->quantity;
                    $bookingItem->save();

                    foreach ($cartItem->facilities as $facility) {
                        $bookingItem->facilities()->attach($facility->id, [
                            'price' => $facility->pivot->price,
                            'quantity' => $facility->pivot->quantity,
                            'total_amount' => $facility->pivot->total_amount
                        ]);
                    }

                    foreach ($cartItem->taxes as $tax) {
                        $bookingItem->taxes()->attach($tax->id, [
                            'tax_name' => $tax->pivot->tax_name ?? $tax->name,
                            'tax_rate' => $tax->pivot->tax_rate ?? $tax->rate,
                            'tax_amount' => $tax->pivot->tax_amount
                        ]);
                    }
                }

                // Record coupon usage if applicable
                if ($orderData['applied_coupon']) {
                    $couponId = $orderData['applied_coupon']['id'];
                    $coupon = HolidayzCoupon::find($couponId);
                    if ($coupon) {
                        // Check if already recorded (prevent duplicates)
                        $existingUsage = HolidayzCouponUsage::where('coupon_id', $couponId)
                            ->where('customer_id', $customer->id)
                            ->exists();

                        if (!$existingUsage) {
                            $couponUsage = new HolidayzCouponUsage();
                            $couponUsage->coupon_id = $couponId;
                            $couponUsage->customer_id = $customer->id;
                            $couponUsage->used_at = now();
                            $couponUsage->creator_id = $coupon->creator_id;
                            $couponUsage->created_by = $coupon->created_by;
                            $couponUsage->save();

                            $coupon->increment('used_count');
                        }
                    }
                }

                // Clear cart and sessions
                HolidayzCart::where('created_by', $user->id)
                    ->where('customer_id', $customer->id)
                    ->delete();

                session()->forget('applied_coupon');

                try {
                    HolidayzBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return redirect()->route('hotel.frontend.index', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                }

                return redirect()->route('hotel.frontend.booking-confirm', [
                    'userSlug' => $userSlug,
                    'encryptedBooking' => encrypt($booking->id)
                ])->with('success', __('Payment completed successfully! Booking #:number', ['number' => $booking->booking_number]));
            } else {
                return redirect()->route('hotel.frontend.checkout', ['userSlug' => $userSlug])
                    ->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('hotel.frontend.checkout', ['userSlug' => $userSlug])
                ->with('error', $exception->getMessage());
        }
    }

    public function facilitiesPaymentWithStripe(Request $request)
    {
        try {
            $userSlug = $request->route('userSlug');

            $user     = User::where('slug', $userSlug)->first();
            if (!$user) {
                return redirect()->back()->with('error', __('User not found.'));
            }

            $company_settings = getCompanyAllSetting($user->id);
            $currency         = $company_settings['defult_currancy'] ?? 'USD';

            // Get booking data from service
            $bookingData = FacilitiesBookingService::prepareBookingData($request, $user->id);

            if (!$bookingData) {
                return redirect()->back()->with('error', __('Invalid booking data.'));
            }

            $totalAmount = $bookingData['total_amount'];

            if ($totalAmount <= 0) {
                return redirect()->back()->with('error', __('Invalid booking amount.'));
            }

            $orderID = 'FB-' . strtoupper(substr(uniqid(), -8));

            // Format price for Stripe
            $stripe_formatted_price = in_array($currency, ['JPY', 'BIF', 'CLP', 'DJF', 'GNF', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'])
                ? number_format($totalAmount, 2, '.', '')
                : number_format($totalAmount, 2, '.', '') * 100;

            // Create Stripe session
            $stripe_session = $this->createStripeSession([
                'api_key'      => $company_settings['stripe_secret'] ?? '',
                'currency'     => $currency,
                'amount'       => $stripe_formatted_price,
                'product_name' => 'Facility Booking',
                'description'  => 'Facility booking payment',
                'metadata'     => [
                    'booking_type' => 'facility',
                    'user_id'      => $user->id,
                    'order_id'     => $orderID,
                ],
                'success_url'  => route('facilities.payment.stripe.status', [
                    'userSlug'    => $userSlug,
                    'order_id'    => $orderID,
                    'return_type' => 'success'
                ]),
                'cancel_url'   => route('facilities.payment.stripe.status', [
                    'userSlug'    => $userSlug,
                    'order_id'    => $orderID,
                    'return_type' => 'cancel'
                ]),
            ]);

            Session::put('facility_booking_' . $orderID, $bookingData);
            Session::put('facilities_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key'     => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Payment processing failed. Please try again.'));
        }
    }

    public function facilitiesGetStripeStatus(Request $request)
    {
        try {
            $userSlug    = $request->route('userSlug');
            $orderID     = $request->get('order_id');
            $return_type = $request->get('return_type');

            if ($return_type === 'cancel') {
                return redirect()->route('facilities.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }

            $user = User::where('slug', $userSlug)->first();
            if (!$user) {
                return redirect()->back()->with('error', __('User not found.'));
            }

            $company_settings = getCompanyAllSetting($user->id);
            $stripe           = new StripeClient($company_settings['stripe_secret'] ?? '');
            $stripe_session   = Session::get('facilities_stripe_session');

            if ($stripe_session) {
                $session = $stripe->checkout->sessions->retrieve($stripe_session->id);

                if ($session->payment_status === 'paid') {
                    // Get booking data
                    $bookingData = Session::get('facility_booking_' . $orderID);

                    if ($bookingData) {
                        // Create facility booking using Service
                        $booking = FacilitiesBookingService::createBooking($bookingData, $user->id, 'Stripe');

                        // Create payment entry
                        FacilitiesBookingService::createPaymentEntry($booking, $user->id, [
                            'method' => 'Stripe',
                            'transaction_id' => $session->payment_intent ?? null,
                            'currency' => $company_settings['defult_currancy'] ?? 'USD',
                            'receipt_url' => $session->url ?? null,
                        ]);

                        try {
                            FacilityBookingPaymentStripe::dispatch($booking);
                        } catch (\Throwable $th) {
                            return back()->with('error', $th->getMessage());
                        }

                        // Clean up session
                        Session::forget('facility_booking_' . $orderID);
                        Session::forget('facilities_stripe_session');

                        return redirect()->route('facilities.frontend.booking-success', [
                            'userSlug'       => $userSlug,
                            'booking_number' => $booking->booking_number
                        ])->with('success', __('Payment successful! Booking confirmed: ') . $booking->booking_number);
                    }
                }
            }

            return redirect()->route('facilities.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Payment verification failed.'));
        } catch (\Exception $e) {

            return redirect()->route('facilities.frontend.booking', ['userSlug' => $request->route('userSlug')])->with('error', __('Payment verification failed.'));
        }
    }

    // vehicle Booking Stripe Payment
    public function vehicleBookingPayWithStripe(Request $request)
    {
        $bookingData = [
            'email' => $request->email,
            'selected_seats' => $request->selectedSeats,
            'passengers' => $request->passengers,
            'route_id' => $request->route_id,
            'vehicle_id' => $request->vehicle_id,
            'booking_date' => $request->booking_date,
            'total_amount' => $request->total_amount,
            'special_requests' => $request->special_requests,
            'payment_method' => 'Stripe'
        ];

        Session::put('vehicle_booking_data', $bookingData);
        Session::put('vehicle_booking_user_slug', $request->route('userSlug'));

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $company_settings = getCompanyAllSetting($userId);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : 'USD';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = floatval($request->total_amount);
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => 'Vehicle Booking',
                'description' => 'Vehicle Booking Payment',
                'metadata' => [
                    'customer_email' => $request->email,
                    'route_id' => $request->route_id,
                ],
                'success_url' => route('vehicle-booking.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('vehicle-booking.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('vehicle_booking_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function vehicleBookingGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('vehicle_booking_data');
        $stripe_session = Session::get('vehicle_booking_stripe_session');
        $userSlug = Session::get('vehicle_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('vehicle-booking.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;
        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');
            $payment_intent = null;

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                        $receipt_url = $charge->receipt_url ?? '';
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('vehicle_booking_stripe_session');
        Session::forget('vehicle_booking_data');
        Session::forget('vehicle_booking_user_slug');

        try {
            if ($request->return_type == 'success') {
                $booking = new VehicleBooking();
                $booking->booking_number = VehicleBooking::generateBookingNumber($userId);
                $booking->email = $bookingData['email'];
                $booking->selected_seats = $bookingData['selected_seats'];
                $booking->passengers = $bookingData['passengers'];
                $booking->route_id = $bookingData['route_id'];
                $booking->vehicle_id = $bookingData['vehicle_id'];
                $booking->booking_date = $bookingData['booking_date'];
                $booking->total_amount = $bookingData['total_amount'];
                $booking->payment_method = 'Stripe';
                $booking->payment_status = 'paid';
                $booking->booking_status = 'confirmed';
                $booking->special_requests = $bookingData['special_requests'];
                $booking->transaction_id = $payment_intent;
                $booking->creator_id = $userId;
                $booking->created_by = $userId;
                $booking->save();

                try {
                    VehicleBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }

                return redirect()->route('vehicle-booking.frontend.success', ['userSlug' => $userSlug, 'id' => \Illuminate\Support\Facades\Crypt::encrypt($booking->id)])
                    ->with('success', __('Payment completed and booking confirmed successfully!'));
            } else {
                return redirect()->route('vehicle-booking.frontend.booking', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('vehicle-booking.frontend.booking', ['userSlug' => $userSlug])->with('error', $exception->getMessage());
        }
    }

    public function movieBookingPayWithStripe(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->back()->with('error', __('Booking data not found.'));
        }

        $bookingData['customer'] = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ];

        Session::put('movie_booking_data', $bookingData);
        Session::put('movie_booking_user_slug', $request->route('userSlug'));

        $userSlug = $request->route('userSlug');
        $user     = User::where('slug', $userSlug)->first();
        $userId   = $user ? $user->id : '';

        $company_settings     = getCompanyAllSetting($userId);
        $company_currancy     = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : 'USD';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = floatval($request->amount ?? 0);
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key'      => $company_settings['stripe_secret'] ?? '',
                'currency'     => $company_currancy,
                'amount'       => $stripe_formatted_price,
                'product_name' => 'Movie Ticket Booking',
                'description'  => 'Movie Show Booking Stripe Payment',
                'metadata'     => [
                    'customer_name'  => $request->name,
                    'customer_email' => $request->email,
                ],
                'success_url' => route('movie-booking.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug'    => $userSlug
                ]),
                'cancel_url' => route('movie-booking.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug'    => $userSlug
                ]),
            ]);

            Session::put('movie_booking_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key'     => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function movieBookingGetStripeStatus(Request $request)
    {
        $bookingData    = Session::get('movie_booking_data');
        $stripe_session = Session::get('movie_booking_stripe_session');
        $userSlug       = Session::get('movie_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('movie-booking.home', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user             = User::where('slug', $userSlug)->first();
        $userId           = $user ? $user->id : 1;
        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('movie_booking_stripe_session');
        Session::forget('movie_booking_data');
        Session::forget('movie_booking_user_slug');

        try {
            if ($request->return_type == 'success') {
                $bookedSeats = array_map(function ($seat) {
                    return [
                        'seat'  => $seat['seat'],
                        'price' => $seat['price']
                    ];
                }, $bookingData['seats'] ?? []);

                $bookedFoods = array_map(function ($food) {
                    return [
                        'id' => $food['id'],
                        'price' => $food['price'],
                        'quantity' => $food['quantity']
                    ];
                }, $bookingData['foods'] ?? []);

                $booking                 = new MovieBooking();
                $booking->booking_id     = strtoupper(uniqid());
                $booking->movie_id       = $bookingData['movie_id'];
                $booking->movie_show_id  = $bookingData['show_id'];
                $booking->screen_id      = $bookingData['screen_id'];
                $booking->customer_name  = $bookingData['customer']['name'] ?? '';
                $booking->customer_email = $bookingData['customer']['email'] ?? '';
                $booking->customer_phone = $bookingData['customer']['phone'] ?? '';
                $booking->booking_date   = $bookingData['date'] ?? '';
                $booking->show_time      = $bookingData['time'];
                $booking->total_seats    = $bookingData['pricing']['tickets'] ?? 0;
                $booking->booked_seats   = $bookedSeats;
                $booking->booked_foods   = $bookedFoods;
                $booking->subtotal       = $bookingData['pricing']['subtotal'] ?? 0;
                $booking->taxes          = $bookingData['pricing']['taxes'] ?? [];
                $booking->tax_amount     = $bookingData['pricing']['taxAmount'] ?? 0;
                $booking->total_amount   = $bookingData['pricing']['total'] ?? 0;
                $booking->payment_method = 'Stripe';
                $booking->payment_status = 'paid';
                $booking->booking_status = 'confirmed';
                $booking->creator_id     = $userId;
                $booking->created_by     = $userId;
                $booking->save();
                try {
                    MovieBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }
                return redirect()->route('movie-booking.confirmation', ['userSlug' => $userSlug, 'id' => $booking->booking_id])
                    ->with('success', __('Payment completed and booking confirmed successfully!'));
            } else {
                return redirect()->route('movie-booking.home', ['userSlug' => $userSlug])
                    ->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('movie-booking.home', ['userSlug' => $userSlug])
                ->with('error', $exception->getMessage());
        }
    }

    public function ngoDonationPayWithStripe(Request $request)
    {
        // Store donation data in session
        $donationData = [
            'amount' => $request->amount,
            'campaign_id' => $request->campaign_id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_message' => $request->donor_message,
            'payment_method' => 'Stripe'
        ];

        Session::put('ngo_donation_data', $donationData);
        Session::put('ngo_donation_user_slug', $request->route('userSlug'));

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        if (!$user) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        $company_settings = getCompanyAllSetting($user->id);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $price = floatval($request->amount ?? 0);
        if ($price <= 0) {
            return redirect()->back()->with('error', __('Invalid donation amount.'));
        }

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => 'NGO Donation',
                'description' => 'Donation Payment',
                'metadata' => [
                    'donor_name' => $request->donor_name,
                    'donor_email' => $request->donor_email,
                    'campaign_id' => $request->campaign_id,
                ],
                'success_url' => route('ngo.donation.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('ngo.donation.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('ngo_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ngoDonationGetStripeStatus(Request $request)
    {
        $donationData = Session::get('ngo_donation_data');
        $stripe_session = Session::get('ngo_stripe_session');
        $userSlug = Session::get('ngo_donation_user_slug');

        if (!$donationData) {
            return redirect()->route('ngo.frontend.index', ['userSlug' => $userSlug])->with('error', __('Donation data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        if (!$user) {
            return redirect()->route('ngo.frontend.index', ['userSlug' => $userSlug])->with('error', __('User not found.'));
        }

        $company_settings = getCompanyAllSetting($user->id);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                        $receipt_url = $charge->receipt_url ?? '';
                    }
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        Session::forget('ngo_stripe_session');
        Session::forget('ngo_donation_data');
        Session::forget('ngo_donation_user_slug');

        try {
            if ($request->return_type == 'success') {
                // Find or create donor
                $donor = NgoDonor::where('email', $donationData['donor_email'])
                    ->where('created_by', $user->id)
                    ->first();

                if (!$donor) {
                    $donor = new NgoDonor();
                    $donor->name = $donationData['donor_name'];
                    $donor->email = $donationData['donor_email'];
                    $donor->created_by = $user->id;
                    $donor->creator_id = $user->id;
                    $donor->save();
                }

                // Create donation record
                $donation = new NgoDonation();
                $donation->donor_id = $donor->id;
                $donation->campaign_id = ($donationData['campaign_id'] === 'general' || !$donationData['campaign_id']) ? null : $donationData['campaign_id'];
                $donation->amount = $donationData['amount'];
                $donation->payment_method = 'Stripe';
                $donation->status = 'paid';
                $donation->transaction_id = $checkoutSession->payment_intent ?? null;
                $donation->donation_date = now();
                $donation->notes = $donationData['donor_message'];
                $donation->created_by = $user->id;
                $donation->creator_id = $user->id;
                $donation->save();

                // Update donor total donations
                $donor->increment('total_donations', $donationData['amount']);

                // Update campaign current amount if specific campaign
                if ($donation->campaign_id) {
                    $campaign = NgoCampaign::find($donation->campaign_id);
                    if ($campaign) {
                        $campaign->increment('current_amount', $donationData['amount']);
                    }
                }

                try {
                    CreateNgoDonation::dispatch(new Request($donationData), $donation);
                } catch (\Throwable $th) {
                    return redirect()->route('ngo.frontend.index', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                }

                return redirect()->route('ngo.frontend.index', ['userSlug' => $userSlug])
                    ->with('success', __('Thank you for your donation! Your payment has been processed successfully.'));
            } else {
                return redirect()->route('ngo.frontend.index', ['userSlug' => $userSlug])
                    ->with('error', __('Donation was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('ngo.frontend.index', ['userSlug' => $userSlug])
                ->with('error', $exception->getMessage());
        }
    }

    public function coworkingSpacePayWithStripe(Request $request)
    {
        $paymentType = $request->input('type', 'membership');

        if ($paymentType === 'booking') {
            $bookingData = [
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'specialRequests' => $request->specialRequests,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'selectedAmenities' => json_decode($request->selectedAmenities, true) ?? [],
                'totalAmount' => $request->totalAmount,
                'duration' => $request->duration,
                'payment_method' => 'Stripe',
                'type' => 'booking'
            ];

            Session::put('coworking_booking_data', $bookingData);
            Session::put('coworking_booking_user_slug', $request->route('userSlug'));

            $userSlug = $request->route('userSlug');
            $user = User::where('slug', $userSlug)->first();
            $userId = $user ? $user->id : '';

            $company_settings = getCompanyAllSetting($userId);
            $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : 'USD';
            $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

            if (!in_array($company_currancy, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }

            $price = floatval($request->totalAmount);
            if ($price <= 0) {
                return redirect()->back()->with('error', __('Invalid payment amount.'));
            }

            try {
                $stripe_formatted_price = in_array(
                    $company_currancy,
                    ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                $stripe_session = $this->createStripeSession([
                    'api_key' => $company_settings['stripe_secret'] ?? '',
                    'currency' => $company_currancy,
                    'amount' => $stripe_formatted_price,
                    'product_name' => 'Coworking Space Booking',
                    'description' => 'Coworking Space Booking Payment',
                    'metadata' => [
                        'customer_name' => $request->firstName . ' ' . $request->lastName,
                        'customer_email' => $request->email,
                        'type' => 'booking'
                    ],
                    'success_url' => route('coworking-space.payment.stripe.status', [
                        'return_type' => 'success',
                        'userSlug' => $userSlug
                    ]),
                    'cancel_url' => route('coworking-space.payment.stripe.status', [
                        'return_type' => 'cancel',
                        'userSlug' => $userSlug
                    ]),
                ]);

                Session::put('coworking_stripe_session', $stripe_session);

                return Inertia::render('Stripe/StripePayment', [
                    'stripe_session' => $stripe_session,
                    'stripe_key' => $company_settings['stripe_key'] ?? ''
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $bookingData = [
                'member_name' => $request->member_name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'plan_id' => $request->plan_id,
                'payment_method' => 'Stripe',
                'type' => 'membership'

            ];

            if (!$bookingData) {
                return redirect()->back()->with('error', __('Booking data not found.'));
            }

            Session::put('coworking_booking_data', $bookingData);
            Session::put('coworking_booking_user_slug', $request->route('userSlug'));

            $userSlug = $request->route('userSlug');
            $user = User::where('slug', $userSlug)->first();
            $userId = $user ? $user->id : '';

            // Get plan details
            $plan = CoworkingMembershipPlan::find($request->plan_id);
            if (!$plan) {
                return redirect()->back()->with('error', __('Plan not found.'));
            }

            $company_settings = getCompanyAllSetting($userId);
            $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : 'USD';
            $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

            if (!in_array($company_currancy, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }

            $price = floatval($plan->plan_price);
            if ($price <= 0) {
                return redirect()->back()->with('error', __('Invalid payment amount.'));
            }

            try {
                $stripe_formatted_price = in_array(
                    $company_currancy,
                    ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                $stripe_session = $this->createStripeSession([
                    'api_key' => $company_settings['stripe_secret'] ?? '',
                    'currency' => $company_currancy,
                    'amount' => $stripe_formatted_price,
                    'product_name' => $plan->plan_name ?? 'Coworking Membership',
                    'description' => 'Coworking Space Membership Payment',
                    'metadata' => [
                        'plan_id' => $plan->id,
                        'member_name' => $request->member_name,
                        'member_email' => $request->email,
                        'type' => 'membership'
                    ],
                    'success_url' => route('coworking-space.payment.stripe.status', [
                        'return_type' => 'success',
                        'userSlug' => $userSlug
                    ]),
                    'cancel_url' => route('coworking-space.payment.stripe.status', [
                        'return_type' => 'cancel',
                        'userSlug' => $userSlug
                    ]),
                ]);

                Session::put('coworking_stripe_session', $stripe_session);

                return Inertia::render('Stripe/StripePayment', [
                    'stripe_session' => $stripe_session,
                    'stripe_key' => $company_settings['stripe_key'] ?? ''
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    public function coworkingSpaceGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('coworking_booking_data');
        $stripe_session = Session::get('coworking_stripe_session');
        $userSlug = Session::get('coworking_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('coworking-space.purchase', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $paymentType = $bookingData['type'] ?? 'membership';

        if ($paymentType === 'booking') {
            $user = User::where('slug', $userSlug)->first();
            $userId = $user ? $user->id : 1;
            $company_settings = getCompanyAllSetting($userId);

            try {
                $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

                if ($stripe_session && isset($stripe_session['id'])) {
                    $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                    if (isset($checkoutSession->payment_intent)) {
                        $payment_intent = $checkoutSession->payment_intent;
                    }
                }
            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }

            Session::forget('coworking_stripe_session');
            Session::forget('coworking_booking_data');
            Session::forget('coworking_booking_user_slug');

            try {
                if ($request->return_type == 'success') {
                    // Create coworking booking after successful payment
                    $booking = new CoworkingBooking();
                    $booking->first_name = $bookingData['firstName'];
                    $booking->last_name = $bookingData['lastName'];
                    $booking->email = $bookingData['email'];
                    $booking->phone_no = $bookingData['phone'];
                    $booking->amenities = $bookingData['selectedAmenities'];
                    $booking->start_date_time = $bookingData['startDate'];
                    $booking->end_date_time = $bookingData['endDate'];
                    $booking->amount = $bookingData['totalAmount'];
                    $booking->booking_duration = $bookingData['duration'];
                    $booking->payment_status = 'paid';
                    $booking->payment_method = 'Stripe';
                    $booking->special_requests = $bookingData['specialRequests'];
                    $booking->creator_id = $userId;
                    $booking->created_by = $userId;
                    $booking->save();

                    try {
                        CoworkingBookingPayments::dispatch($booking);
                    } catch (\Throwable $th) {
                        return redirect()->route('coworking-space.booking', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                    }

                    return redirect()->route('coworking-space.home', ['userSlug' => $userSlug])
                        ->with('success', __('Payment completed and booking confirmed successfully! Booking #:number', ['number' => $booking->booking_number]));
                } else {
                    return redirect()->route('coworking-space.booking', ['userSlug' => $userSlug])
                        ->with('error', __('Payment was cancelled.'));
                }
            } catch (\Exception $exception) {
                return redirect()->route('coworking-space.booking', ['userSlug' => $userSlug])
                    ->with('error', $exception->getMessage());
            }
        } else {
            $user = User::where('slug', $userSlug)->first();
            $userId = $user ? $user->id : 1;
            $company_settings = getCompanyAllSetting($userId);

            try {
                $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');

                if ($stripe_session && isset($stripe_session['id'])) {
                    $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                    if (isset($checkoutSession->payment_intent)) {
                        $payment_intent = $checkoutSession->payment_intent;
                    }
                }
            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }

            Session::forget('coworking_stripe_session');
            Session::forget('coworking_booking_data');
            Session::forget('coworking_booking_user_slug');

            try {
                if ($request->return_type == 'success') {
                    $plan = CoworkingMembershipPlan::find($bookingData['plan_id']);
                    if (!$plan) {
                        return redirect()->route('coworking-space.purchase', ['userSlug' => $userSlug])
                            ->with('error', __('Plan not found.'));
                    }

                    // Create coworking membership after successful payment
                    $membership = new CoworkingMembership();
                    $membership->member_name = $bookingData['member_name'];
                    $membership->email = $bookingData['email'];
                    $membership->phone_no = $bookingData['phone_no'];
                    $membership->membership_plan_id = $bookingData['plan_id'];
                    $membership->duration = $plan->duration;
                    $membership->price = $plan->plan_price;
                    $membershipController = new CoworkingMembershipController();
                    $membership->plan_expiry_date  = $membershipController->calculateExpiryDate($plan->duration);
                    $membership->plan_status = 'Active';
                    $membership->payment_method = 'Stripe';
                    $membership->payment_status = 'paid';
                    $membership->creator_id = $userId;
                    $membership->created_by = $userId;
                    $membership->save();

                    try {
                        CoworkingMembershipPayments::dispatch($membership);
                    } catch (\Throwable $th) {
                        return redirect()->route('coworking-space.purchase', ['userSlug' => $userSlug])->with('error', $th->getMessage());
                    }

                    return redirect()->route('coworking-space.purchase', ['userSlug' => $userSlug])
                        ->with('success', __('Payment completed and membership activated successfully!'));
                } else {
                    return redirect()->route('coworking-space.purchase', ['userSlug' => $userSlug])
                        ->with('error', __('Payment was cancelled.'));
                }
            } catch (\Exception $exception) {
                return redirect()->route('coworking-space.purchase', ['userSlug' => $userSlug])
                    ->with('error', $exception->getMessage());
            }
        }
    }

    public function sportsClubPayWithStripe(Request $request)
    {
        $bookingData = [
            'ground_id' => $request->ground_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'booked_by' => $request->booked_by,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'facilities' => $request->facilities ?? [],
            'special_requirements' => $request->special_requirements,
            'purpose' => $request->purpose,
            'total_amount' => $request->total_amount
        ];

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $ground = SportsClubGround::findOrFail($request->ground_id);
        $company_settings = getCompanyAllSetting($userId);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        // Calculate total amount
        $totalAmount = floatval($request->total_amount);

        if ($totalAmount <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        Session::put('sports_club_booking_data', $bookingData);
        Session::put('sports_club_booking_user_slug', $request->route('userSlug'));

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($totalAmount, 2, '.', '') : number_format($totalAmount, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => $ground->name ?? 'Sports Ground Booking',
                'description' => 'Sports Club Ground Booking Payment',
                'metadata' => [
                    'ground_id' => $ground->id,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                ],
                'success_url' => route('sports-club.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('sports-club.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('sports_club_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sportsClubGetStripeStatus(Request $request)
    {
        $bookingData = Session::get('sports_club_booking_data');
        $stripe_session = Session::get('sports_club_stripe_session');
        $userSlug = Session::get('sports_club_booking_user_slug');

        if (!$bookingData) {
            return redirect()->route('sports-academy.booking', ['userSlug' => $userSlug])->with('error', __('Booking data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;
        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');
            $payment_intent = null;
            $receipt_url = "";

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                        $receipt_url = $charge->receipt_url ?? '';
                    }
                }
            }
        } catch (\Exception $exception) {
            $receipt_url = "";
        }

        Session::forget('sports_club_stripe_session');
        Session::forget('sports_club_booking_data');
        Session::forget('sports_club_booking_user_slug');

        try {
            if ($request->return_type == 'success') {
                // Create booking
                $booking = new SportsClubAndGroundOrder();
                $booking->name = $bookingData['name'];
                $booking->email = $bookingData['email'];
                $booking->mobile_no = $bookingData['mobile_number'];
                $booking->booked_by = $bookingData['booked_by'];
                $booking->sports_club_id = $bookingData['ground_id'];
                $booking->date = $bookingData['booking_date'];
                $booking->start_date = $bookingData['start_date'] ?? null;
                $booking->end_date = $bookingData['end_date'] ?? null;
                $booking->start_time = $bookingData['start_time'] ?? null;
                $booking->end_time = $bookingData['end_time'] ?? null;
                $booking->total_amount = $bookingData['total_amount'];
                $booking->notes = $bookingData['special_requirements'];
                $booking->purpose = $bookingData['purpose'];
                $booking->transaction_id = $payment_intent;
                $booking->payment_type = 'Stripe';
                $booking->payment_status = 'paid';
                $booking->creator_id = $userId;
                $booking->created_by = $userId;
                $booking->save();

                // Store selected facilities
                if (!empty($bookingData['facilities']) && is_array($bookingData['facilities'])) {
                    foreach ($bookingData['facilities'] as $facilityId) {
                        $facility = SportsClubFacility::find($facilityId);
                        if ($facility) {
                            $bookingFacility = new SportsClubBookingFacility();
                            $bookingFacility->booking_id = $booking->id;
                            $bookingFacility->facility_id = $facilityId;
                            $bookingFacility->facility_name = $facility->name;
                            $bookingFacility->facility_amount = $facility->amount;
                            $bookingFacility->creator_id = $userId;
                            $bookingFacility->created_by = $userId;
                            $bookingFacility->save();
                        }
                    }
                }

                try {
                    SportsClubBookingPayments::dispatch($booking);
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }

                // Redirect back to booking page with success and booking data
                $encryptedBookingId = encrypt($booking->id);
                $redirectUrl = route('sports-academy.booking', ['userSlug' => $userSlug]) . '?step=4&booking_id=' . $encryptedBookingId;
                return redirect($redirectUrl)->with('success', __('Payment completed and booking confirmed successfully!'));
            } else {
                return redirect()->route('sports-academy.booking', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('sports-academy.booking', ['userSlug' => $userSlug])->with('error', $exception->getMessage());
        }
    }

    public function sportsClubPlanPayWithStripe(Request $request)
    {
        $planData = [
            'plan_id' => $request->plan_id,
            'user_email' => $request->user_email,
        ];

        $userSlug = $request->route('userSlug');
        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;

        $plan = SportsClubMembershipPlan::findOrFail($request->plan_id);
        $company_settings = getCompanyAllSetting($userId);
        $company_currancy = !empty($company_settings['defaultCurrency']) ? $company_settings['defaultCurrency'] : '';
        $supported_currencies = ['EUR', 'GBP', 'USD', 'CAD', 'AUD', 'JPY', 'INR', 'CNY', 'SGD', 'HKD', 'BRL'];

        if (!in_array($company_currancy, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }

        $totalAmount = floatval($plan->price);

        if ($totalAmount <= 0) {
            return redirect()->back()->with('error', __('Invalid payment amount.'));
        }

        Session::put('sports_club_plan_data', $planData);
        Session::put('sports_club_plan_user_slug', $request->route('userSlug'));

        try {
            $stripe_formatted_price = in_array(
                $company_currancy,
                ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF', 'BRL']
            ) ? number_format($totalAmount, 2, '.', '') : number_format($totalAmount, 2, '.', '') * 100;

            $stripe_session = $this->createStripeSession([
                'api_key' => $company_settings['stripe_secret'] ?? '',
                'currency' => $company_currancy,
                'amount' => $stripe_formatted_price,
                'product_name' => $plan->name ?? 'Sports Club Membership Plan',
                'description' => 'Sports Club Membership Plan Payment',
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_email' => $request->user_email,
                ],
                'success_url' => route('sports-club-plan.payment.stripe.status', [
                    'return_type' => 'success',
                    'userSlug' => $userSlug
                ]),
                'cancel_url' => route('sports-club-plan.payment.stripe.status', [
                    'return_type' => 'cancel',
                    'userSlug' => $userSlug
                ]),
            ]);

            Session::put('sports_club_plan_stripe_session', $stripe_session);

            return Inertia::render('Stripe/StripePayment', [
                'stripe_session' => $stripe_session,
                'stripe_key' => $company_settings['stripe_key'] ?? ''
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sportsClubPlanGetStripeStatus(Request $request)
    {
        $planData = Session::get('sports_club_plan_data');
        $stripe_session = Session::get('sports_club_plan_stripe_session');
        $userSlug = Session::get('sports_club_plan_user_slug');

        if (!$planData) {
            return redirect()->route('sports-academy.plans', ['userSlug' => $userSlug])->with('error', __('Plan data not found.'));
        }

        $user = User::where('slug', $userSlug)->first();
        $userId = $user ? $user->id : 1;
        $company_settings = getCompanyAllSetting($userId);

        try {
            $stripe = new StripeClient(!empty($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '');
            $payment_intent = null;
            $receipt_url = "";

            if ($stripe_session && isset($stripe_session['id'])) {
                $checkoutSession = $stripe->checkout->sessions->retrieve($stripe_session['id'], []);

                if (isset($checkoutSession->payment_intent)) {
                    $payment_intent = $checkoutSession->payment_intent;
                    $paymentIntents = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent, []);
                    if (!empty($paymentIntents->latest_charge)) {
                        $charge = $stripe->charges->retrieve($paymentIntents->latest_charge, []);
                        $receipt_url = $charge->receipt_url ?? '';
                    }
                }
            }
        } catch (\Exception $exception) {
            $receipt_url = "";
        }

        Session::forget('sports_club_plan_stripe_session');
        Session::forget('sports_club_plan_data');
        Session::forget('sports_club_plan_user_slug');

        try {
            if ($request->return_type == 'success') {
                $plan = SportsClubMembershipPlan::findOrFail($planData['plan_id']);
                $member = SportsClubMember::where('created_by', $userId)
                    ->where('email', $planData['user_email'])
                    ->first();

                if (!$member) {
                    return redirect()->route('sports-academy.plans', ['userSlug' => $userSlug])->with('error', __('Member not found.'));
                }

                // Create plan payment
                $planPayment = new SportsClubMembershipPlanPayment();
                $planPayment->member_id = $member->id;
                $planPayment->membershipplan_id = $plan->id;
                $planPayment->fee = $plan->price;
                $planPayment->duration = $plan->duration;
                $planPayment->date = now()->toDateString();
                $planPayment->start_date = now()->toDateString();
                $planPayment->end_date = $plan->calculateEndDate()->toDateString();
                $planPayment->reference_number = $payment_intent;
                $planPayment->status = 'accepted';
                $planPayment->creator_id = $userId;
                $planPayment->created_by = $userId;
                $planPayment->save();

                // Create assignment record
                $assignment = new SportsClubAssignedMembership();
                $assignment->member_id = $member->id;
                $assignment->membershipplan_id = $plan->id;
                $assignment->start_date = now()->toDateString();
                $assignment->end_date = $plan->calculateEndDate()->toDateString();
                $assignment->status = 'accepted';
                $assignment->duration = $plan->duration;
                $assignment->fee = $plan->price;
                $assignment->payment_type = 'Stripe';
                $assignment->creator_id = $userId;
                $assignment->created_by = $userId;
                $assignment->save();

                try {
                    SportsClubPlanPayments::dispatch($request, $assignment);
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }

                return redirect()->route('sports-academy.plans', ['userSlug' => $userSlug])->with('success', __('Payment completed and plan subscription confirmed successfully!'));
            } else {
                return redirect()->route('sports-academy.plans', ['userSlug' => $userSlug])->with('error', __('Payment was cancelled.'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('sports-academy.plans', ['userSlug' => $userSlug])->with('error', $exception->getMessage());
        }
    }
}
