<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $promotions = ApiController::putData("http://localhost:8080/api/promotions/" . $promotionCode , $promotionRequest);
        return redirect()->route('promotion');
    }

    public static function deletePromotion($id)
    {
        $promotions = ApiController::deleteData("http://localhost:8080/api/promotions/" . $id);
        return redirect()->route('promotion');
    }
}
