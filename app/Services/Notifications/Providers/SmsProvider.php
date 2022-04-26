<?php

namespace App\Services\Notifications\Providers;

use App\Models\User;
use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use App\Services\Notifications\Contracts\ProviderInterface;
use App\Services\Notifications\Exceptions\UserDoesNotHavePhoneNumber;

class SmsProvider implements ProviderInterface
{

    public function __construct(
        private User $user,
        private string $text
    ) {
        # code...
    }

    public function send()
    {
        $this->userHavePhoneNumber();

        try {
            $this->prepareDateSms();
        } catch (ApiException $e) {
            return $e->errorMessage();
        } catch (HttpException $e) {
            return $e->errorMessage();
        }
    }

    private function prepareDateSms()
    {
        $message = $this->text;
        $lineNumber = config('services.sms.auth.sms_line_number');
        $receptor = $this->user->phone_number;
        $api = new \Ghasedak\GhasedakApi(config('services.sms.api_key'));
        $api->SendSimple($receptor,$message,$lineNumber);  
    }

    private function userHavePhoneNumber()
    {
        if (is_null($this->user->phone_number)) {
            throw new UserDoesNotHavePhoneNumber();
        }
    }
}
