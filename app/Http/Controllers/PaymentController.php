<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Invoice;

class PaymentController extends Controller
{
    public function createMomoPayment(Request $request)
    {
        // Lưu dữ liệu khách + hóa đơn vào session để dùng sau khi redirect từ Momo
        session([
            'momo_invoice_data' => $request->all()
        ]);
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $orderId = time() . "";
        $requestId = time() . "";
        $amount = $request->amount;
        $orderInfo = "Thanh toán đơn hàng tại Billiards";
        $redirectUrl = route('payment.momoReturn');
        $ipnUrl = route('payment.momoReturn'); // hoặc route khác để xử lý callback
        $partnerCode = 'MOMO';
        $accessKey = "F8BBA842ECF85";
        $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

        $requestData = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => "",
            'requestType' => "captureWallet"
        ];

        // Tạo chữ ký
        ksort($requestData);
        $rawHash = "";
        foreach ($requestData as $key => $value) {
            $rawHash .= "$key=$value&";
        }
        $rawHash = rtrim($rawHash, "&");

        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $requestData["signature"] = $signature;

        $response = Http::post($endpoint, $requestData);
        $res = $response->json();

        return redirect($res['payUrl']);
    }

    public function momoReturn(Request $request)
    {
        if ($request->resultCode == 0) {
            // ✅ Lấy dữ liệu đã lưu từ session
            $data = session('momo_invoice_data');

            // ✅ Tạo hóa đơn từ dữ liệu đã có
            $invoice = Invoice::create([
                'reservation_id'     => $data['reservation_id'],
                'customer_name'      => $data['customer_name'],
                'customer_phone'     => $data['customer_phone'],
                'customer_note'      => $data['customer_note'] ?? null,
                'table_name'         => $data['table_name'],
                'start_time'         => now(),
                'end_time'           => now()->addMinutes(60),
                'play_time_minutes'  => 60,
                'table_price'        => $data['table_price'] ?? 50000,
                'play_cost'          => $data['play_cost'] ?? 50000,
                'services_cost'      => $data['service_cost'] ?? 0,
                'total_payment'      => $request->amount,
            ]);

            // ✅ Lưu dịch vụ nếu có
            if (isset($data['services']) && is_array($data['services'])) {
                foreach ($data['services'] as $item) {
                    $invoice->usedServices()->create([
                        'service_id' => $item['service_id'],
                        'name'       => $item['name'],
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);
                }
            }

            // ✅ Cập nhật trạng thái bàn về "available"
            $reservation = \App\Models\Reservation::find($data['reservation_id']);
            if ($reservation && $reservation->table_id) {
                \App\Models\Table::where('id', $reservation->table_id)->update(['status' => 'available']);
            }

            return redirect()->route('invoices.show', $invoice->id);
        }

        return view('payment.fail');
    }

}
