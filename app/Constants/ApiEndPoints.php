<?php

namespace App\Constants;

class ApiEndpoints
{
    // Định nghĩa hằng số cơ bản
    public const BASE_URL = 'http://localhost:8080/api/';

    // Định nghĩa các hằng số khác bằng cách nối chuỗi
    public const API_DEPARTURE_LIST = self::BASE_URL . 'trip/departure';
    public const API_DESTINATION_LIST = self::BASE_URL . 'trip/destination';

    // Trip
    public const API_TRIP_LIST = self::BASE_URL . 'trip';
    public const API_TRIP_ADD = self::BASE_URL . 'trip';
    public const API_TRIP_UPDATE = self::BASE_URL . 'trip/';
    public const API_TRIP_DELETE = self::BASE_URL . 'trip/';
    public const API_TRIP_SEARCH_BY_ID = self::BASE_URL . 'trip/';

    // Trip Detail
    public const API_TRIP_DETAIL_SEARCH = self::BASE_URL . 'trip-detail/search?';
    public const API_TRIP_DETAIL_SEARCH_ADVANCED = self::BASE_URL . 'trip-detail/search/advanced?';
    public const API_TRIP_DETAIL_SEARCH_BY_ID = self::BASE_URL . 'trip-detail/';

    public const API_TRIP_DETAIL_LIST = self::BASE_URL . 'trip-detail';
    public const API_TRIP_DETAIL_ADD = self::BASE_URL . 'trip-detail';
    public const API_TRIP_DETAIL_UPDATE = self::BASE_URL . 'trip-detail/';
    public const API_TRIP_DETAIL_DELETE = self::BASE_URL . 'trip-detail/';

    public const API_SEAT_MAP_GET = self::BASE_URL . 'seat-availability/';
    public const API_BOOKING_SEAT_POST = self::BASE_URL . 'booking-seat';
    public const API_BOOKING_SEAT_PROMOTION_POST = self::BASE_URL . 'booking-seat/';

    // Car Type
    public const API_CAR_TYPE_LIST = self::BASE_URL . 'car-type';
    public const API_CAR_TYPE_SEARCH_BY_ID = self::BASE_URL . 'car-type/';
    public const API_CAR_TYPE_SEARCH_BY_NAME = self::BASE_URL . 'car-type/search/';
    public const API_CAR_TYPE_ADD = self::BASE_URL . 'car-type';
    public const API_CAR_TYPE_UPDATE = self::BASE_URL . 'car-type/';
    public const API_CAR_TYPE_DELETE = self::BASE_URL . 'car-type/';

    // Car
    public const API_CAR_LIST = self::BASE_URL . 'car';
    public const API_CAR_SEARCH_BY_ID = self::BASE_URL . 'car/';
    public const API_CAR_ADD = self::BASE_URL . 'car';
    public const API_CAR_UPDATE = self::BASE_URL . 'car/';
    public const API_CAR_DELETE = self::BASE_URL . 'car/';

    // Seat
    public const API_SEATS_MAP_ADD = self::BASE_URL . 'seat/';
    public const API_SEATS_MAP_BY_CAR_GET = self::BASE_URL . 'seat/seats-map/';
    public const API_SEATS_MAP_GET = self::BASE_URL . 'seat/seats-map';

    // Report
    public const API_REPORT_SEARCH = self::BASE_URL . 'report/search';
}
