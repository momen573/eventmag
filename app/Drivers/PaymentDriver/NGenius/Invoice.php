<?php

namespace App\Drivers\PaymentDriver\NGenius;

class Invoice extends \Shetabit\Multipay\Invoice
{
    public function __construct($firstName, $lastName, $address, $city, $email, $countryCode = 'IR')
    {
        parent::__construct();
        $this->details = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'address1' => $address,
            'city' => $city,
            'email' => $email,
            'countryCode' => $countryCode,
        ];
    }
}
