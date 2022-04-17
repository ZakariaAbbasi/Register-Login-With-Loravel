<?php

namespace App\Services\Notifications\Constants;

use App\Mail\TopicCreated;
use App\Mail\ForgetPassword;
use App\Mail\UserRegistered;

class EmailTypes
{
    const USER_REGISTERED = 1;
    const TOPIC_CREATED = 2;
    const FORGET_PASSWORD = 3;

    public static function toString()
    {
        return [
            self::USER_REGISTERED => 'ثبت نام کاربر',
            self::TOPIC_CREATED => 'تثب مقاله جدید',
            self::FORGET_PASSWORD => 'فراموشی رمز عبور',
        ];
    }

    public static function toMail($type)
    {
        return [
            self::USER_REGISTERED => UserRegistered::class,
            self::TOPIC_CREATED =>  TopicCreated::class,
            self::FORGET_PASSWORD => ForgetPassword::class,
            
        ][$type];
    }
}
