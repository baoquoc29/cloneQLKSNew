<?php

namespace App\Http\Controllers;

use App\Constants\ApiEndpoints;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function seatManagement()
    {
        $cars = CarController::getAllCar();
        $seatsMap = ApiController::getData(ApiEndpoints::API_SEATS_MAP_GET);
        return view('Admin.Pages.seat-management', compact('cars', 'seatsMap'));
    }

    public static function seatsMap($carId)
    {
        $car = CarController::findCarById($carId);
        $seatMap =  ApiController::getData(ApiEndpoints::API_SEATS_MAP_BY_CAR_GET . $carId);
        return view('Admin.Pages.seats-map', compact('car', 'seatMap'));
    }

    public static function addSeatsMap(Request $request)
    {
        // Lấy ma trận từ request
        $seatsMap = $request->input('seats');

        // Chuyển đổi chuỗi JSON thành mảng PHP
        $seatsMapRequest = json_decode($seatsMap, true);
       
        // Lấy ra id car
        $carId = $request->input('carId');

        // Lưu dữ liệu vào session (hoặc cơ sở dữ liệu)
        session(['seatsMapRequest' => $seatsMapRequest]);
        session(['carId' => $carId]);

        // Gửi dữ liệu JSON đến API
        $seatsMapResponse = ApiController::postData(ApiEndpoints::API_SEATS_MAP_ADD . $carId, $seatsMapRequest);
       return $seatsMapResponse;
        // Kiểm tra phản hồi từ API
        if ($seatsMapResponse !== null) {
            return redirect()->route('seats');
        } else {
            // Trả về JSON lỗi nếu API không trả về phản hồi hợp lệ
            return response()->json(['success' => false, 'message' => 'Failed to add seats.'], 500);
        }
    }

    public static function seatStatus() {
        return ApiController::getData('http://localhost:8080/api/seat/status');
    }
}
