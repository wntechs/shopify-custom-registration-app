<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

class WebhookController
{
    public function handleJotForm(Request $request){
        dd($request->toArray());
        $data = $request->validate([
            ''
        ]);
    }
}
