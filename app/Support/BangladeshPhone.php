<?php

namespace App\Support;

class BangladeshPhone
{
    public static function normalize(string $phone): string
    {
        $phone = strtr($phone, ['০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4', '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9']);
        $phone = preg_replace('/[\s().-]+/', '', trim($phone));
        if (preg_match('/^01[0-9]{9}$/', $phone)) return '+88' . $phone;
        if (preg_match('/^8801[0-9]{9}$/', $phone)) return '+' . $phone;
        return $phone;
    }

    public static function valid(string $phone): bool
    {
        return (bool) preg_match('/^\+8801[0-9]{9}$/', $phone);
    }

    public static function variants(string $phone): array
    {
        $normalized = self::normalize($phone);
        $variants = [$normalized, trim($phone)];
        if (self::valid($normalized)) {
            $variants[] = substr($normalized, 3);
            $variants[] = substr($normalized, 1);
        }
        return array_values(array_unique($variants));
    }
}
