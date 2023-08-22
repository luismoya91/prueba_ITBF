<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    //Types
    const TYPE_STANDARD = 'ESTANDAR';
    const TYPE_JUNIOR = 'JUNIOR';
    const TYPE_SUITE = 'SUITE';

    //Accommodation

    const ACCOMMODATION_SIMPLE = 'SENCILLA';
    const ACCOMMODATION_DOUBLE = 'DOBLE';
    const ACCOMMODATION_TRIPLE = 'TRIPLE';
    const ACCOMMODATION_QUADRUPLE = 'CUADRUPLE';

    public static function validateAccommodationByType($type, $accommodation){
        //Valida si la acomodacion se encuentra en el tipo
        $rules = [
            self::TYPE_STANDARD => [
                self::ACCOMMODATION_SIMPLE,
                self::ACCOMMODATION_DOUBLE,
            ],
            self::TYPE_JUNIOR => [
                self::ACCOMMODATION_TRIPLE,
                self::ACCOMMODATION_QUADRUPLE,
            ],
            self::TYPE_SUITE => [
                self::ACCOMMODATION_SIMPLE,
                self::ACCOMMODATION_DOUBLE,
                self::ACCOMMODATION_TRIPLE
            ],
        ];

        if(!in_array($accommodation,$rules[$type])){
            return false;
        }else{
            return true;
        }
    }

    public static function validateHotel(hotel $hotel, $body) {
        //Valida si el hotel ya tiene habitaciones con tipo y acomodacion igual
        foreach ($hotel->rooms as $key => $room) {
            foreach ($body as $key2 => $roomtmp) {
                if($room->type == $roomtmp['type'] && $room->accommodation == $roomtmp['accommodation']){
                    return false;
                }
            }
        }

        return true;
    }
}
