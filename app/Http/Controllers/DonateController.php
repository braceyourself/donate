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
        /** @var  User $user */
        $user = User::firstOrCreate([
            'name' => $request->name,
        ], [
            'email' => $request->email,
            'password' => bcrypt(config('app.key'))
        ]);

        try {
            $payment = $user->charge(100, $request->payment_method, [
                'receipt_email' => 'jenny.rosen@example.com',
            ]);
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

}