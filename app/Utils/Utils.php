<?
namespace App\Utils;

class Utils {
    public static function formatPrice($amount) {
        $formattedAmmount =  number_format($amount, 0, ',', '.');
        return $formattedAmmount;
    }

    public static function formatTimeToHoursAndMinitues($time) {
       return date( "H:i:s", strtotime( $time ) );
    }
}
