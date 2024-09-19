<?php

namespace App\Http\Controllers;

use App\Constants\ApiEndpoints;
use Illuminate\Http\Request;
use App\Http\Controllers\TripDetailController;
use App\Http\Controllers\DateTime;
use App\Utils\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    // public function showBookingForm($tripId)
    // {
    //     $trip = Trip::find($tripId);

    //     if (!$trip) {
    //         abort(404); // Trip not found
    //     }

    //     return view('booking', ['trip' => $trip]);
    // }

    public function processBooking($tripDetailId, $departureDate)
    {
        $tripDetail = TripDetailController::getTripDetailById($tripDetailId);
        $seatMaps = TripDetailController::getSeatMaps($tripDetailId, $departureDate);
       // return $seatsMap;

        // Nếu tripDetail không tồn tại, có thể xử lý lỗi hoặc quay lại trang trước đó
        if (!$tripDetail) {
            abort(404, 'Chuyến đi không tồn tại.');
        }

        // Lấy thông tin chuyến đi
        $departure = $tripDetail['trip']['departure'];
        $destination =  $tripDetail['trip']['destination'];
        $price = $tripDetail['price'];
        $departureTime = $tripDetail['departureTime'];
        $destinationTime = $tripDetail['destinationTime'];
        $carType = $tripDetail['car']['carType']['name'];


        // Truyền tất cả giá trị vào view
        return view('Pages.booking-seat', [
            'tripDetail' => $tripDetail,
            'seatMaps' => $seatMaps,
            'departure' => $departure,
            'destination' => $destination,
            'price' => $price,
            'departureTime' => $departureTime,
            'destinationTime' => $destinationTime,
            'carType' => $carType,
            'departureDate' => $departureDate,
        ]);
    }

    public static function confirm(Request $request, $tripDetailId)
    {
        // Lấy tất cả dữ liệu từ form
        $data = $request->all();

        // Hoặc lấy từng trường cụ thể như cách truyền thống $_POST['data']
        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $promotionCode = $request->input('promotion_code');
        $startDestination = $request->input('pickup_location');
        $endDestination = $request->input('dropoff_location');
        $departureDate = $request->input('departureDate');
        $selectedSeats = $request->input('selectedSeats');
        $selectedSeatIds = $request->input('selectedSeatIds');

        $tripDetail = TripDetailController::getTripDetailById($tripDetailId);
        // Lấy thông tin chuyến đi
        $departure = $tripDetail['trip']['departure'];
        $destination =  $tripDetail['trip']['destination'];
        $price = $tripDetail['price'];
        $departureTime = $tripDetail['departureTime'];
        $destinationTime = $tripDetail['destinationTime'];
        $carType = $tripDetail['car']['carType']['name'];

        //Lay ra danh sach ghe da chon
        $selectedSeats = $request->input('selectedSeats');
        $totalPrice = $price * count(explode(',', $selectedSeats));

        // $customer = [
        //     "name" => $name,
        //     "phone" => $phone,
        //     "email" => $email 
        // ];

        // $bookingSeatRequest = [
        //     "customer" => $customer,
        //     "tripDetail" => $tripDetail,
        //     "startDestination" => $startDestiantion,
        //     "endDestination" => $endDestination ,
        //     "departureDate" => $departureDate
        // ];

        //$bookingSeatResponse = ApiController::postData(ApiEndpoints::API_BOOKING_SEAT_POST . $selectedSeats, $bookingSeatRequest);

        //Trả về một phản hồi hoặc chuyển hướng
        return view('Pages.booking-confirm', compact(
            "departure",
            "destination",
            "departureDate",
            "tripDetail",
            "selectedSeats",
            "selectedSeatIds",
            "name",
            "phone",
            "email",
            "startDestination",
            "endDestination",
            "departureTime",
            "destinationTime",
            "totalPrice",
            "promotionCode"
        ));
    }

    public static function validateBooking(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'email' => 'required|email|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    }

    public static function historyBooking()
    {
        $bookedTickets = null;
        $name = "";
        $phone = "";
        $email = "";

        return view('Pages.history-booking', compact(
            'bookedTickets',
            'name',
            'phone',
            'email'
        ));
    }

    public static function searchHistoryBooking(Request $request)
    {
        $name = $request->input('fullName');
        $email = $request->input('email');
        $phone = $request->input('phone');

        // Tạo mảng customer với các thông tin cần thiết
        $customer = [
            "name" => $name,
            "phone" => $phone,
            "email" => $email
        ];

        // Tạo query string từ mảng customer
        $queryString = http_build_query($customer);

        // Gọi API với URL đã có query string
        $bookedTickets = ApiController::getData("http://localhost:8080/api/booking/history-booked?" . $queryString);

        return view('Pages.history-booking', compact(
            'bookedTickets',
            'name',
            'phone',
            'email'
        ));
    }

    public static function confirmCancelTicket($bookingId)
    {
        $bookedTicket = ApiController::getData("http://localhost:8080/api/booking/history-booked/" . $bookingId);
        $cancelTicket = ApiController::getData("http://localhost:8080/api/verification-code/" . $bookingId);
        // $cancelTicketCode = $cancelTicket['code'];
        // session()->put('cancelTicketCode', $cancelTicketCode);
        return view('Pages.cancel-ticket', compact('bookedTicket'));
    }

    public static function cancelTicket(Request $request, $bookingId)
    {
        // $cancelTicketCode = session()->get('cancelTicketCode');
        $confirmationCode = $request->input('confirmationCode');
        $response = ApiController::putData("http://localhost:8080/api/verification-code/" . $bookingId . "/" . $confirmationCode, []);

        // Lấy dữ liệu từ JsonResponse
        $responseData = $response->getData(true);
    
        // Kiểm tra giá trị của 'success'
        if (isset($responseData['success']) && $responseData['success'] == 'true') {
            // Nếu mã xác nhận hợp lệ
            return view("Pages.cancel-success");
        } else {
            // Nếu mã xác nhận không hợp lệ hoặc đã hết hạn
            return redirect()->back()->withErrors(['confirmationCode' => 'Mã xác nhận không chính xác hoặc đã hết hạn.']);
        }
    }

    // public static function bookingSeat(Request $request)
    // {
    //     // Lấy tất cả dữ liệu từ form
    //     $data = $request->all();

    //     // Hoặc lấy từng trường cụ thể như cách truyền thống $_POST['data']
    //     $name = $request->input('name');
    //     $phone = $request->input('phone');
    //     $email = $request->input('email');
    //     $startDestination = $request->input('pickup_location');
    //     $endDestination = $request->input('dropoff_location');
    //     $departureDate = $request->input('departureDate');
    //     $selectedSeats = $request->input('selectedSeats');
    //     $selectedSeatIds = $request->input('selectedSeatIds');

    //     $tripDetailId = $request->input('trip-detail-id');
    //     $tripDetail = TripDetailController::getTripDetailById($tripDetailId);
    //     // Lấy thông tin chuyến đi
    //     $departure = $tripDetail['trip']['departure'];
    //     $destination =  $tripDetail['trip']['destination'];
    //     $price = $tripDetail['price'];
    //     $departureTime = $tripDetail['departureTime'];
    //     $destinationTime = $tripDetail['destinationTime'];
    //     $carType = $tripDetail['car']['carType']['name'];

    //     //Lay ra danh sach ghe da chon
    //     $selectedSeats = $request->input('selectedSeats');
    //     $totalPrice = $price * count(explode(',', $selectedSeats));

    //     $customer = [
    //         "name" => $name,
    //         "phone" => $phone,
    //         "email" => $email 
    //     ];

    //     $bookingSeatRequest = [
    //         "customer" => $customer,
    //         "tripDetail" => $tripDetail,
    //         "startDestination" => $startDestination,
    //         "endDestination" => $endDestination ,
    //         "departureDate" => $departureDate
    //     ];

    //     $bookingSeatResponse = ApiController::postData(ApiEndpoints::API_BOOKING_SEAT_POST . $selectedSeats, $bookingSeatRequest);

    //     //Trả về một phản hồi hoặc chuyển hướng
    //     return view('Pages.notifycation');
    // }
}
