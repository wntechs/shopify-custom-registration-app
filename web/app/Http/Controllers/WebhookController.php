<?php

namespace App\Http\Controllers;



use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController
{
    public function handleJotForm(Request $request){

        $form_id = $request->formID;
        if($form_id == '230332414469048'){
            $data = json_decode($request->rawRequest, true);
            $address = $data['q11_address'];
            $formData = [
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

            if(!empty($formData['email'])){
                $registration = Registration::where('email', $formData['email'])->first();
                if($registration == null){
                    Registration::create($formData);
                }else{
                    unset($formData['email']);
                    $registration->fill($formData);
                    $registration->save();
                }
            }
        }
        return response('success');

    }
}
