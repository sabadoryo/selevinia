<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\SubscribeRequest;
use App\Mail\NewSubscription;
use App\Models\Subscribtion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionController extends Controller
{
    public function index() {
        $mails = QueryBuilder::for(Subscribtion::class)
            ->paginate(request('itemsPerPage'));

        return $this->apiResponse($mails);
    }

    public function subscribe(SubscribeRequest $request) {
        $email = Subscribtion::create([
            'email' => $request->email
        ]);

        Mail::to(env('ADMIN_EMAIL'))->send(new NewSubscription($request->email));

        return $this->apiResponse($email);
    }
}
