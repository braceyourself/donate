<?php namespace App\Http\Controllers;

use App\Notifications\PaymentErrorNotification;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\SimpleMessage;
use Illuminate\Notifications\Notification;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Laravel\Cashier\Exceptions\PaymentFailure;
use Stripe\PaymentMethod;

class DonateController extends Controller
{
    public function index(Request $request)
    {
        return view('donate', [
            'amount' => $request->amount * 100,
            'donor_name' => $request->donor_name,
            'description' => $request->description
        ]);
    }

    public function submit(Request $request)
    {
        try {
            $payment = (new User)->charge(100, $request->payment_method);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Notification::send([
                new User([
                    'name' => 'ethan',
                    'email' => 'ethanabrace@gmail.com'
                ])
            ], new PaymentErrorNotification($e));
            return response([
                'message' => $e->getMessage()
            ]);
        }

        return response([
            'message' => "Thank you for your donation!"
        ]);
    }

    protected function success()
    {
        return view('success');
    }

    protected function error()
    {
        return view('error');
    }

}