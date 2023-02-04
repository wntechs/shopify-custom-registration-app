<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRegistration;
use App\Models\Registration;
use Illuminate\Http\Request;
use Shopify\Utils;

class WebhookController
{
    public function handleJotForm(Request $request)
    {
       // Log::debug('webhook', $request->all());
        $data = json_decode($request->rawRequest, true);
        $shop = $data['q12_shop'];

        //we might omit shop validation checking in case we still want to store registration in our db
        $session = Utils::loadOfflineSession('genx-institute.myshopify.com');
        //print_r([$session->shop, $shop]);die;
        if ($session->shop == $shop) {
            $address = $data['q11_address'];
            $formData = [
                'shop' => $shop,
                'first_name' => $data['q3_name']['first'],
                'last_name' => $data['q3_name']['last'],
                'email' => $data['q9_email'],
                'phone' => $data['q10_phoneNumber']['full'],
                'company' => $data['q7_company'],
                'address_1' => $address['addr_line1'],
                'address_2' => $address['addr_line2'],
                'city' => $address['city'],
                'state' => $address['state'],
                'zip' => $address['postal'],
                'country' => $address['country'],
            ];

            if (!empty($formData['email'])) {
                $registration = Registration::where('email', $formData['email'])->first();
                if ($registration == null) {
                    $registration = Registration::create($formData);
                    ProcessRegistration::dispatch($registration);
                } else {
                    unset($formData['email']);
                    $registration->fill($formData);
                    $registration->save();
                }
            }
        }

        return response('success');
    }
}
