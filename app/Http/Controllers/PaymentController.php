<?php

namespace App\Http\Controllers;

use Carbon\Exceptions\Exception as ExceptionsException;
use FFI\Exception as FFIException;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Constants\ApiEndpoints;

class PaymentController extends Controller
{
    private $name;
    private $phone;
    private $email;
    private $startDestination;
    private $endDestination;
    private $departureDate;
    private $selectedSeats;
    private $selectedSeatIds;
    private $tripDetailId;
    private $tripDetail;
    private $departure;
    private $destination;
    private $price;
    private $departureTime;
    private $destinationTime;
    private $carType;
    private $totalPrice;
    private $promotionCode;

    // private $appId = 553;
    // private $key1 = "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q";
    // private $key2 = "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3";
    // private $endpoint = "https://sandbox.zalopay.com.vn/v001/tpe/createorder";
    private $config = [
        "appid" => 553,
        "key1" => "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q",
        "key2" => "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3",
        "endpoint" => "https://sandbox.zalopay.com.vn/v001/tpe/createorder"
    ];

    private $apptransid;

    public function payment(Request $request)
    {
        // Gán giá trị cho thuộc tính private từ request
        $this->name = $request->input('name');
        $this->phone = $request->input('phone');
        $this->email = $request->input('email');
        $this->startDestination = $request->input('pickup_location');
        $this->endDestination = $request->input('dropoff_location');
        $this->departureDate = $request->input('departureDate');
        $this->selectedSeats = $request->input('selectedSeats');
        $this->selectedSeatIds = $request->input('selectedSeatIds');
        $this->tripDetailId = $request->input('trip-detail-id');
        $this->promotionCode = $request->input('promotionCode');

        // Lấy thông tin chuyến đi
        $this->tripDetail = TripDetailController::getTripDetailById($this->tripDetailId);
        $this->departure = $this->tripDetail['trip']['departure'];
        $this->destination = $this->tripDetail['trip']['destination'];
        $this->price = $this->tripDetail['price'];
        $this->departureTime = $this->tripDetail['departureTime'];
        $this->destinationTime = $this->tripDetail['destinationTime'];
        $this->carType = $this->tripDetail['car']['carType']['name'];

        //Lay ra danh sach ghe da chon
        $this->totalPrice = $request->input('totalPrice');

        // Lưu dữ liệu vào session
        session([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'startDestination' => $this->startDestination,
            'endDestination' => $this->endDestination,
            'departureDate' => $this->departureDate,
            'selectedSeatIds' => $this->selectedSeatIds,
            'tripDetail' => $this->tripDetail,
            'promotionCode' => $this->promotionCode
        ]);

        $embeddata = [
            "merchantinfo" => "embeddata123",
            "redirecturl" =>    route('payment.booking-seat')
        ];
        $items = [
            ["itemid" => "knb", "itemname" => "kim nguyen bao", "itemprice" => 198400, "itemquantity" => 1]
        ];
        $order = [
            "appid" => $this->config["appid"],
            "apptime" => round(microtime(true) * 1000), // miliseconds
            "apptransid" => date("ymd") . "_" . uniqid(), // mã giao dich có định dạng yyMMdd_xxxx
            "appuser" => "demo",
            "item" => json_encode($items, JSON_UNESCAPED_UNICODE),
            "embeddata" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
            "amount" => $request->input('totalPrice'),
            "description" => "ZaloPay Intergration Demo",
            "bankcode" => ""
        ];

       // để yên t request api

        // appid|apptransid|appuser|amount|apptime|embeddata|item
        $data = $order["appid"] . "|" . $order["apptransid"] . "|" . $order["appuser"] . "|" . $order["amount"]
            . "|" . $order["apptime"] . "|" . $order["embeddata"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $this->config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($this->config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        $this->apptransid = $order['apptransid'];

        // $this->paymentStatus();

        return redirect($result['orderurl']);
    }

    public function paymentBookingSeat()
    {

        // Lấy dữ liệu từ session
        $name = session('name');
        $phone = session('phone');
        $email = session('email');
        $startDestination = session('startDestination');
        $endDestination = session('endDestination');
        $departureDate = session('departureDate');
        $selectedSeatIds = session('selectedSeatIds');
        $tripDetail = session('tripDetail');
        $promotionCode = session('promotionCode');

        // Chuyển đổi chuỗi thành mảng số nguyên dương
        $selectedSeatIdsArray = array_map('intval', explode(',', $selectedSeatIds));

        $customer = [
            "name" => $name,
            "phone" => $phone,
            "email" => $email
        ];

        $bookingRequest = [
            "customer" => $customer,
            "tripDetail" => $tripDetail,
            "startDestination" => $startDestination,
            "endDestination" => $endDestination,
            "seatsIdSelected" => $selectedSeatIdsArray,
            "departureDate" => $departureDate
        ];

        // $bookingSeatRequest = [
        //     "booking" => $bookingRequest
        // ];
        if (!empty($promotionCode)) {
            $bookingSeatResponse = ApiController::postData(ApiEndpoints::API_BOOKING_SEAT_PROMOTION_POST . $promotionCode, $bookingRequest);
        }
        else {
            $bookingSeatResponse = ApiController::postData(ApiEndpoints::API_BOOKING_SEAT_POST, $bookingRequest);
        }
        //Trả về một phản hồi hoặc chuyển hướng
        return view('Pages.notifycation');
    }

    public function paymentReturn(Request $request)
    {
        $orderId = $request->input('orderId');
        $amount = $request->input('amount');
        $status = $request->input('status');
        $zaloPayTranId = $request->input('zaloPayTranId');
        $zpPayTransId = $request->input('zpPayTransId');
        $payType = $request->input('payType');
        $message = $request->input('message');
        $transStatus = $request->input('transStatus');
        $returnCode = $request->input('returnCode');

        // Xử lý thông tin trả về từ ZaloPay
        // Có thể xác thực thanh toán ở đây

        return view('payment-return', compact('orderId', 'amount', 'status', 'zaloPayTranId', 'zpPayTransId', 'payType', 'message', 'transStatus', 'returnCode'));
    }

    public function callBack()
    {
        $result = [];

        try {
            $postdata = file_get_contents('php://input');
            $postdatajson = json_decode($postdata, true);
            $mac = hash_hmac("sha256", $postdatajson["data"], $this->config['key2']);

            $requestmac = $postdatajson["mac"];

            // kiểm tra callback hợp lệ (đến từ ZaloPay server)
            if (strcmp($mac, $requestmac) != 0) {
                // callback không hợp lệ
                $result["returncode"] = -1;
                $result["returnmessage"] = "mac not equal";
            } else {
                // thanh toán thành công
                // merchant cập nhật trạng thái cho đơn hàng
                $datajson = json_decode($postdatajson["data"], true);
                // echo "update order's status = success where apptransid = ". $dataJson["apptransid"];

                $result["returncode"] = 1;
                $result["returnmessage"] = "success";
            }
        } catch (Exception $e) {
            $result["returncode"] = 0; // ZaloPay server sẽ callback lại (tối đa 3 lần)
            $result["returnmessage"] = $e->message();
        }

        // thông báo kết quả cho ZaloPay server
        echo json_encode($result);

        //return redirect('https://www.youtube.com/watch?v=Bo5wSwq7ajg');
    }

    public function paymentStatus()
    {
        $config = [
            "appid" => 553,
            "key1" => "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q",
            "key2" => "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3",
            "endpoint" => "https://sandbox.zalopay.com.vn/v001/tpe/getstatusbyapptransid"
        ];

        $apptransid = "190308_123456";
        $data = $config["appid"] . "|" . $apptransid . "|" . $config["key1"]; // appid|apptransid|key1
        $params = [
            "appid" => $config["appid"],
            "apptransid" => $apptransid,
            "mac" => hash_hmac("sha256", $data, $config["key1"])
        ];

        $resp = file_get_contents($config["endpoint"] . "?" . http_build_query($params));
        $result = json_decode($resp, true);

        return $result;

        foreach ($result as $key => $value) {
            echo $key . ": ";
            print_r($value);
            echo "<br>";
        }
    }

    public function paymentNotify(Request $request)
    {
        $orderId = $request->input('orderId');
        $amount = $request->input('amount');
        $status = $request->input('status');
        $zaloPayTranId = $request->input('zaloPayTranId');
        $zpPayTransId = $request->input('zpPayTransId');
        $payType = $request->input('payType');
        $message = $request->input('message');
        $transStatus = $request->input('transStatus');
        $returnCode = $request->input('returnCode');

        // Xử lý thông báo từ ZaloPay (cập nhật trạng thái đơn hàng, gửi email, v.v.)

        return response()->json(['status' => 'success']);
    }
}
