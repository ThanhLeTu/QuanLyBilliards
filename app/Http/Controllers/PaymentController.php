<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Invoice; //

class PaymentController extends Controller
{
    public function payWithMomo(Request $request)
    {
        session([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'reservation_id' => $request->reservation_id ?? null
        ]);
        
        $amount = $request->amount ?? 10000;

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = "MOMO";
        $accessKey = "F8BBA842ECF85";
        $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";
        $orderId = uniqid();
        $orderInfo = "Thanh toán hóa đơn Billiards";
        $redirectUrl = route('payment.momo.callback');
        $ipnUrl = route('payment.momo.callback');
        $requestId = time() . "";
        $requestType = "captureWallet";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => '',
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'vi'
        ];

        $response = Http::post($endpoint, $data)->json();

        return redirect($response['payUrl']);
    }

    //Hàm xử lý callback từ Momo
    public function handleMomoCallback(Request $request)
    {
        if ($request->resultCode == 0) {
            // Giao dịch thành công → lưu hóa đơn
            Invoice::create([
                'reservation_id'   => session('reservation_id'),
                'customer_name'    => session('customer_name'),
                'customer_phone'   => session('customer_phone'),
                'payment_method'   => 'momo',
                'amount'           => $request->amount,
                'momo_order_id'    => $request->orderId,
                'momo_trans_id'    => $request->transId,
                'status'           => 'success',
            ]);
            
        }

        return view('payment.success', ['data' => $request->all()]);
    }
}
