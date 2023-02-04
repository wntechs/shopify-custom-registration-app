<?php

namespace App\Lib;

use Illuminate\Support\Str;
use Shopify\Auth\OAuth;
use Shopify\Rest\Admin2023_01\Customer;

class CustomerCreator
{
    public static function create($data)
    {
        $session = ( new DbSessionStorage())->loadSession(OAuth::getOfflineSessionId($data['shop']));
        // Log::debug("session not registered", [$session->shop, $data]);

        if (!$session) {
            return;
        }
        $customer = new Customer($session);
        $customer->first_name = $data['first_name'];
        $customer->last_name = $data['last_name'];
        $customer->email = $data['email'];
       // $customer->phone = $phone; // TODO: need validation stuffs to insert this properly
        $customer->verified_email = false;
        $customer->addresses = [
            [
                "address1" => $data['address_1'],
                "city" => $data['city'],
                "province" => $data['state'],
                "phone" => $data['phone'],
                "zip" => $data['zip'],
                "last_name" => $data['last_name'],
                "first_name" => $data['first_name'],
                "country" => $data['country']
            ]
        ];
        $pass = Str::random(8);
        $customer->password = $pass;
        $customer->password_confirmation = $pass;
        $customer->send_email_welcome = false;
        $customer->save(
            true, // Update Object
        );
    }
}
