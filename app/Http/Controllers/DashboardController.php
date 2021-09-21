<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Model\Transaction;
use App\Http\Controllers\API\DuitkuController;


use DB;
use Auth;
use Validator;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        $payment_method = json_decode(DuitkuController::get_payment_method());

        return view('user.order', compact('payment_method'));
    }

    public function getStatus(Request $request)
    {
        $trxid = $request->get('trxid');
        return DuitkuController::get_transaction_status($trxid);
    }

    public function create(Request $request)
    {
        // return $request->all();

        $merchant_code = DuitkuController::get_merchantcode();
        $merchant_api = DuitkuController::get_apikey();

        $orderReff = "TRX" . date('y') . rand(1111,9999);
        $signature = MD5($merchant_code . $orderReff . $request->order_product_price . $merchant_api);
        $itemDetails = array(
            'name' => $request->order_product,
            'price' => $request->order_product_price,
            'quantity' => 1
        );
        $method = explode('-', $request->order_payment_method);
        $paymentMethod = $method[0];
        $paymentMethodName = $method[1];

        $params = array(
            'merchantCode' => $merchant_code,
            'paymentAmount' => $request->order_product_price,
            'paymentMethod' => $paymentMethod,
            'merchantOrderId' => $orderReff,
            'productDetails' => "Order " . $request->order_product,
            'additionalParam' => '',
            'merchantUserInfo' => '',
            'customerVaName' => $request->cust_name ,
            'email' => $request->cust_email,
            'phoneNumber' => $request->cust_phone,
            'itemDetails' => array($itemDetails),
            'customerDetail' => array(),
            'callbackUrl' => env('APP_URL') . 'callback_transaksi',
            'returnUrl' => env('APP_URL') . 'redirect-order',
            'signature' => $signature,
            'expiryPeriod' => 10
        );

        $params_string = json_encode($params);

        $ress = DuitkuController::order($params_string);
        $resultRess = $ress['status'];

        if($resultRess){
            $result = json_decode($ress['data'], true);
            $transaksi = new Transaction();
            $transaksi->amount          = $request->order_product_price;
            $transaksi->customer_email  = $request->cust_email;
            $transaksi->customer_name   = $request->cust_name;
            $transaksi->customer_phone  = $request->cust_phone;
            $transaksi->payment_method  = $paymentMethod;
            $transaksi->payment_method_name = $paymentMethodName;
            $transaksi->product         = $request->order_product;
            $transaksi->product_description = $request->order_product_desc;
            $transaksi->reff_number_to_duitku = $orderReff;
            $transaksi->duitku_reff_number = $result['reference'];
            $transaksi->virtual_account_number = (isset($result['vaNumber'])) ? $result['vaNumber'] : NULL;
            $transaksi->duitku_payment_url = $result['paymentUrl'];
            $transaksi->status = "Pending";
            $transaksi->user_id = Auth::user()->id;
            $transaksi->save();

            return redirect($result['paymentUrl']);
            return redirect('my_transaction?openWeb=true&trxid=' . $orderReff);
        }else{
            Session::flash('message', $ress['message']);
            return redirect('/');
        }
    }

    public function getCallback(Request $request)
    {
        if(isset($request->merchantCode)){
            $reffOrder = $request->reference;
            $resultStatus = $request->resultCode;
            $isCallback = true;
        }else{
            $reffOrder = $request->get('reference');
            $resultStatus = $request->get('resultCode');
            $isCallback = false;
        }

        $transaksi = Transaction::where('duitku_reff_number', $reffOrder)->where('status', 'Pending')->first();
        if($transaksi){
            if($resultStatus == 00){
                $status = "Sukses";
            }else if($resultStatus == 02){
                $status = "Failed";
            }else{
                $status = "Pending";
            }

            $transaksi->status = $status;
            $transaksi->save();
            if(!$isCallback){
                $message = "Transaksi ". $transaksi->reff_number_to_duitku ." dirubah menjadi status " . $status;
                Session::flash('message', $message);
                return redirect('/my_transaction?trxid='. $transaksi->reff_number_to_duitku);
            }
        }
    }

    public function getMyTransaction(Request $request)
    {
        $trxid = $request->get('trxid');
        if(isset($trxid)){
            $openDuitku = $request->get('openWeb');
            if(isset($openDuitku)){
                return redirect('my_transaction?trxid='. $trxid);
            }
            $orders = Transaction::where('user_id', Auth::user()->id)->where('reff_number_to_duitku', $trxid)->first();
            return view('user.my_transaction_detail', compact('orders'));
        }else{
            $orders = Transaction::where('user_id', Auth::user()->id)->get();
            return view('user.my_transaction', compact('orders'));
        }
    }

    public function check_pembayaran(Request $request)
    {
        $trxid = $request->get('trxid');
        $check = DuitkuController::get_transaction_status($trxid);
        $checkData = Transaction::where('reff_number_to_duitku', $trxid)->first();
        if($checkData){
            if($checkData->status == "Sukses" || $checkData->status == "Failed"){
                $message = "Transaksi ". $checkData->reff_number_to_duitku ." dirubah menjadi status " . $checkData->status;
                Session::flash('message', $message);
            }
            return redirect('/my_transaction?trxid='. $checkData->reff_number_to_duitku);
        }else{
            return redirect('/my_transaction?trxid='. $reffOrder); 
        }
    }

    public function getAllTransaction(Request $request)
    {
        $trxid = $request->get('trxid');
        if(isset($trxid)){
            $orders = Transaction::select('transactions.*', 'users.name as user_buy')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->where('transactions.reff_number_to_duitku', $trxid)
                    ->first();
            return view('admin.detail_transaction', compact('orders'));
        }else{
            $orders = Transaction::select('transactions.*', 'users.name as user_buy')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->get();
            return view('admin.list_transaction', compact('orders'));
        }
    }
}
