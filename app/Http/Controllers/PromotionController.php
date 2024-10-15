<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Thư viện HTTP client của Laravel
use App\Constants\ApiEndpoints;

class PromotionController extends Controller
{
    private $search = false;
    private const PAGE_SIZE = 5;

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

    // public function search(Request $request, $page = 1)
    // {
    //     // Lấy tên loại xe từ yêu cầu
    //     $searchCarTypeName = $request->input('searchCarTypeName', '');
    //     if ($searchCarTypeName == '')
    //         return redirect()->route('car-type', $page = 1);

    //     // Tạo đường dẫn API với tham số tìm kiếm, kích thước trang, và số trang
    //     $apiUrl = ApiEndpoints::API_CAR_TYPE_SEARCH_BY_NAME . urlencode($searchCarTypeName) .
    //         '?pageSize=' . self::PAGE_SIZE . '&pageNo=' . $page - 1;

    //     // Gọi API để lấy dữ liệu loại xe theo tên với phân trang
    //     $apiResponse = ApiController::getData($apiUrl);

    //     $carTypes = $apiResponse['carTypes'];
    //     $totalPages = $apiResponse['totalPages'];

    //     // Lấy thông báo từ session và xóa nó
    //     $message = session()->get('message');
    //     session()->forget('message'); // Xóa thông báo khỏi session

    //     $search = true;
    //     return view('Admin.Pages.car-type', [
    //         'carTypes' => $carTypes,
    //         'totalPages' => $totalPages,
    //         'currentPage' => $page,
    //         'pageSize' => self::PAGE_SIZE,
    //         'message' => $message,
    //         'searchCarTypeName' => $searchCarTypeName,
    //         'search' => $search
    //     ]);
    // }

    public function search(Request $request)
    {
        // Lấy thông tin từ request
        $searchPromotionCode = $request->input('searchPromotionCode');
        $searchStartDate = $request->input('searchStartDate');
        $searchEndDate = $request->input('searchEndDate');

        if ($searchPromotionCode == '' && $searchStartDate == '' && $searchEndDate == '')
            return redirect()->route('promotion');

        // Tạo đường dẫn API với các tham số
        $apiUrl = 'http://localhost:8080/api/promotions/search?';

        // Thêm các tham số vào URL nếu có
        if ($searchPromotionCode != '') {
            $apiUrl .= 'code=' . urlencode($searchPromotionCode) . '&';
        }
        if ($searchStartDate != '') {
            $apiUrl .= 'startDate=' . urlencode($searchStartDate) . '&';
        }
        if ($searchEndDate != '') {
            $apiUrl .= 'endDate=' . urlencode($searchEndDate) . '&';
        }

        // Bỏ dấu '&' cuối cùng (nếu có)
        $apiUrl = rtrim($apiUrl, '&');

        // Gọi API và lấy kết quả
        $apiResponse = ApiController::getData($apiUrl);
      
        // Xử lý kết quả từ API
        $promotions = $apiResponse;
    

        return view('Admin.Pages.promotion', compact(
            "promotions",
            "searchPromotionCode",
            "searchStartDate",
            "searchEndDate"
        ));
    }
}
