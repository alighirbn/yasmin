<?php

namespace App\Helpers;

class Number
{
    // Custom method to convert numbers to Arabic ordinals
    public static function convert($number)
    {
        $ordinalWords = [
            1 => 'الأولى',
            2 => 'الثانية',
            3 => 'الثالثة',
            4 => 'الرابعة',
            5 => 'الخامسة',
            6 => 'السادسة',
            7 => 'السابعة',
            8 => 'الثامنة',
            9 => 'التاسعة',
            10 => 'العاشرة',
            11 => 'الحادية عشر',
            12 => 'الثانية عشر',
            13 => 'الثالثة عشر',
            14 => 'الرابعة عشر',
            15 => 'الخامسة عشر',
            16 => 'السادسة عشر',
            17 => 'السابعة عشر',
            18 => 'الثامنة عشر',
            19 => 'التاسعة عشر',
            // Continue for other numbers as needed...
        ];

        // Return the ordinal word for the number, or the number itself if not in the list
        return $ordinalWords[$number] ?? $number;
    }
}
