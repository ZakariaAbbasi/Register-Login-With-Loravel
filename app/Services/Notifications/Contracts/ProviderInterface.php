<?php
namespace App\Services\Notifications\Contracts;

interface ProviderInterface
{
    public function send();
}