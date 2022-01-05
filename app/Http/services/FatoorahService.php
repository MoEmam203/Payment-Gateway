<?php 

namespace App\Http\services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class FatoorahService {

    private $base_url;
    private $headers;
    private $request_client;


    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url = env("fatoorah_base_url");
        $this->headers = [
            "content_type" => "application/json",
            "authorization" => "Bearer ". env('fatoorah_api_token')
        ];
    }


    public function buildRequest($uri,$method,$data=[]){

        $request = new Request($method,$this->base_url.$uri,$this->headers);

        if(!$data){
            return false;
        }

        $response = $this->request_client->send($request,[
            'json'=>$data
        ]);

        if($response->getStatusCode() !=200){
            return false;
        }

        $res = json_decode($response->getBody(),true);

        return $res;
    }


    public function sendPayment($data){
        return $this->buildRequest("v2/sendPayment","POST",$data);
    }

    public function getPaymentStatus($data){
        return $this->buildRequest("v2/getPaymentStatus","POST",$data);
    }

}

?>