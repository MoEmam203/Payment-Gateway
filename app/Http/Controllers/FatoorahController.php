<?php

namespace App\Http\Controllers;

use App\Http\services\FatoorahService;
use Illuminate\Http\Request;

class FatoorahController extends Controller
{
    private $fatoorahService;
    
    // Inject Fatoorah Service
    public function __construct(FatoorahService $fatoorahService)
    {
        $this->fatoorahService = $fatoorahService;
    }

    public function payOrder(){
        // Required Data
        $data = [
            'CustomerName'       => 'Mustafa Emam', // auth()->user()->name
            'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
            'InvoiceValue'       => '50', // product value
            "CustomerEmail"      => "mostafa@test.com", // auth()->user()->email
            'CallBackUrl'        => env("fatoorah_callback_url"),
            'ErrorUrl'           => env("fatoorah_error_url"), //or 'https://example.com/error.php'
            'Language'           => 'en', //or 'ar'
            'DisplayCurrencyIso' => 'EGP'
        ];

        return $this->fatoorahService->sendPayment($data);

        // make transaction table => save InvoiceId , auth()->user , status =>false
    }


    // If payment success
    public function callback(Request $request){
        $data =[
            'key' => $request->paymentId,
            'KeyType' => "paymentId"
        ];
        $res = $this->fatoorahService->getPaymentStatus($data);
        
        $invoiceId =  $res['Data']['InvoiceId'];

        // get record from transaction table where invoiceId = $invoiceId
        // change status of the record to true
        // return message to user 
        return "success payment";
    }

    // If payment fail
    public function error(){
        return "error";
    }
}
