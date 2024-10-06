<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Thư viện HTTP client của Laravel

class PromotionController extends Controller
{
    public static function promotionManagement()
    {
        $promotions = ApiController::getData("http://localhost:8080/api/promotions");
        return view('Admin.Pages.promotion', compact(
            "promotions"
        ));
    }

    public static function addPromotion(Request $request)
    {
        $promotionCode = $request->input('promotionCode');
        $promotionDescription = $request->input('promotionDescription');
        $promotionDiscount = $request->input('promotionDiscount');
        $promotionStartDate = $request->input('promotionStartDate');
        $promotionEndDate = $request->input('promotionEndDate');

        $promotionRequest = [
            "code" => $promotionCode,
            "description" => $promotionDescription,
            "discountPercentage" => $promotionDiscount,
            "startDate" => $promotionStartDate,
            "endDate" => $promotionEndDate
        ];

        $promotions = ApiController::postData("http://localhost:8080/api/promotions", $promotionRequest);
        return redirect()->route('promotion');
    }

    public static function updatePromotion(Request $request)
    {
        $promotionCode = $request->input('promotionCode');
        $promotionDescription = $request->input('promotionDescription');
        $promotionDiscount = $request->input('promotionDiscount');
        $promotionStartDate = $request->input('promotionStartDate');
        $promotionEndDate = $request->input('promotionEndDate');

        $promotionRequest = [
            "code" => $promotionCode,
            "description" => $promotionDescription,
            "discountPercentage" => $promotionDiscount,
            "startDate" => $promotionStartDate,
            "endDate" => $promotionEndDate
        ];

        $promotions = ApiController::putData("http://localhost:8080/api/promotions/" . $promotionCode, $promotionRequest);
        return redirect()->route('promotion');
    }

    public static function deletePromotion($id)
    {
        $promotions = ApiController::deleteData("http://localhost:8080/api/promotions/" . $id);
        return redirect()->route('promotion');
    }

    public static function apply(Request $request)
    {
        $promoCode = $request->input('promotion_code');
        $originalPrice = session()->get('original_price', 100000); // Giả sử bạn lưu giá gốc trong session

        // Gửi yêu cầu tới API để kiểm tra mã khuyến mại
        $response = Http::post("http://localhost:8080/api/promotions/checkPromoCode/{$promoCode}/{$originalPrice}");

        if ($response->ok()) {
            $totalPrice = $response->json('data');

            // Gửi yêu cầu để lấy thông tin giảm giá
            $discount = Http::get("http://localhost:8080/api/promotions/{$promoCode}")['data']['discountPercentage'];

            // Lưu thông tin giảm giá vào session
            session()->put('discounted_price', $totalPrice);

            // Trả về hai giá trị riêng biệt
            return redirect()->back()->with([
                'totalPrice' => $totalPrice,
                'discount' => $discount
            ]);
        }

        return redirect()->back()->with('error', 'Mã khuyến mại không hợp lệ hoặc đã hết hạn.');
    }
}
