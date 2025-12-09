<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class EmailController extends Controller
{
    //
    public function sendEmail(Request $request){
        $to = $request->to;
        $msg = $request->msg;
        $subject = $request->subject;
        Mail::to($to)->send(new WelcomeEmail($msg,$subject));
        return back()->with('success', 'Mail sent successfully.');
    }
}
