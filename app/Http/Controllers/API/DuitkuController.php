<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Setting;
use App\Model\Transaction;

use DB;

class DuitkuController extends Controller
{
    public static function get_apikey()
    {
        return Setting::where('module', 'merchant_apikey')->first()->value;
    }

    public static function get_merchantcode()
    {
        return Setting::where('module', 'merchant_code')->first()->value;
    }

    public static function get_urlduitku()
    {
        return Setting::where('module', 'duitku_url')->first()->value;
    }

    public static function order($params_string)
    {
        $getUrl = DuitkuController::get_urlduitku();
        $url = $getUrl.'/webapi/api/merchant/v2/inquiry';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',  
            'Content-Length: ' . strlen($params_string))
        );   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        \Log::debug("[REQUEST ORDER] : " . $params_string);
        $ress = curl_exec($ch);
        \Log::debug("[RESPONSE ORDER] : " . $ress);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($httpCode == 200){
            $ress = array(
                'status' => true,
                'data' => $ress,
                'message' => 'Success transaction'
            );
        }else{
            $ress = array(
                'status' => false,
                'data' => [],
                'message' => 'Failed to create transaction, '. json_decode($ress, true)['Message']
            );
        }

        return $ress;
    }

    public static function get_payment_method()
    {
        $json = file_get_contents('php://input');
        date_default_timezone_set('Asia/Jakarta');
        $result = json_decode($json);

        $merchantCode = DuitkuController::get_merchantcode();
        $paymentAmount = 0;
        $dateTime = date('Y-m-d H:i:s'); 
        $merchantKey = DuitkuController::get_apikey();

        $signature = hash('sha256', $merchantCode . $paymentAmount . $dateTime . $merchantKey);
        $itemsParam = array(
            'merchantcode' => $merchantCode,
            'amount' => $paymentAmount,
            'datetime' => $dateTime,
            'signature' => $signature
        );

        $params = array_merge((array)$result,$itemsParam);
        $params_string = json_encode($params);

        $getUrl = DuitkuController::get_urlduitku();
        $url = $getUrl.'/webapi/api/merchant/paymentmethod/getpaymentmethod';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',  
            'Content-Length: ' . strlen($params_string))
        );   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        \Log::debug("[REQUEST GET METHOD] : " . $params_string);
        $ress = curl_exec($ch);
        \Log::debug("[RESPONSE GET METHOD] : " . $ress);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $ress;
    }

    public static function get_transaction_status($orderid = false)
    {
        $merchantCode = DuitkuController::get_merchantcode();
        $paymentAmount = 0;
        $dateTime = date('Y-m-d H:i:s'); 
        $merchantKey = DuitkuController::get_apikey();

        if($orderid){
            $getTransaction = Transaction::where('status', 'Pending')->where('reff_number_to_duitku', $orderid)->get();
        }else{
            $getTransaction = Transaction::where('status', 'Pending')->get();
        }

        foreach($getTransaction as $val){
            $merchantOrderId = $val->reff_number_to_duitku;
            $signature = md5($merchantCode . $merchantOrderId . $merchantKey);
            $params = array(
                'merchantCode' => $merchantCode,
                'merchantOrderId' => $merchantOrderId,
                'signature' => $signature
            );

            $params_string = json_encode($params);

            $getUrl = DuitkuController::get_urlduitku();
            $url = $getUrl.'/webapi/api/merchant/transactionStatus';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',  
                'Content-Length: ' . strlen($params_string))
            );   
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            \Log::debug("[REQUEST GET STATUS] : " . $params_string);
            $ress = curl_exec($ch);
            \Log::debug("[RESPONSE GET STATUS] : " . $ress);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            $result = json_decode($ress, true);

            if(isset($result['statusCode'])){
                if($result['statusCode'] == "02"){
                    $status = "Failed";
                }else if($result['statusCode'] == "00"){
                    $status = "Sukses";
                }else{
                    $status = "Pending";
                }

                $updateTransaksi = Transaction::where('id', $val->id)->first();
                $updateTransaksi->status = $status;
                $updateTransaksi->save();
            }
        }
    }
}
