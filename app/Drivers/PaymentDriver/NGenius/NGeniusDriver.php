<?php

namespace App\Drivers\PaymentDriver\NGenius;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Shetabit\Multipay\Abstracts\Driver;
use Shetabit\Multipay\Contracts\ReceiptInterface;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Receipt;
use Shetabit\Multipay\RedirectionForm;

class NGeniusDriver extends Driver
{
    protected $invoice; // Invoice.

    protected $settings; // Driver settings.

    protected Client $client;

    public function __construct(Invoice $invoice, $settings)
    {
        $this->invoice($invoice); // Set the invoice.
        $this->settings = (object) $settings; // Set settings.
        $this->client = new Client();
    }

    public function purchase()
    {
        $details = $this->invoice->getDetails();
        $accessToken = $this->getAccessToken();
        $data = [
            'action' => 'SALE',
            'amount' => [
                'currencyCode' => 'USD',
                'value' => $this->invoice->getAmount() * 100,
            ],
            'merchantAttributes' => [
                'redirectUrl' => $this->settings->callbackUrl.'?local_transaction='
                    .$this->invoice->getDetail('transaction_id'),
                'skipConfirmationPage' => true,
            ],
            'emailAddress' => $this->invoice->getDetail('email'),
            'billingAddress' => [
                'firstName' => $this->invoice->getDetail('firstName'),
                'lastName' => $this->invoice->getDetail('lastName'),
                'address1' => $this->invoice->getDetail('address'),
                'city' => $this->invoice->getDetail('city'),
                'countryCode' => $this->invoice->getDetail('countryCode'),
            ],
        ];

        $headers = [
            'Authorization' => 'Bearer '.$accessToken,
            'Content-Type' => 'application/vnd.ni-payment.v2+json',
        ];
        $uri = str_replace(':outletId', $this->settings->outletId, $this->settings->apiPayStart);

        $res = $this->client->post($uri, [
            'json' => $data,
            'headers' => $headers,
        ]);

        if ($res->getStatusCode() == 201) {
            $body = json_decode($res->getBody()->getContents(), true);
            $this->invoice->detail('response', $body);
            $this->invoice->detail('paymentHref', $body['_links']['payment']['href']);
            $parsedUrl = parse_url($this->invoice->getDetail('paymentHref'));
            parse_str($parsedUrl['query'], $query);
            $this->invoice->detail('paymentCode', $query['code']);
            $this->invoice->transactionId($body['_embedded']['payment'][0]['orderReference']);

            // return the transaction's id
            return $this->invoice->getTransactionId();
        } else {
            throw new \Exception('error in create order');
        }
    }

    protected function getAccessToken()
    {
        $headers = [
            'Authorization' => 'Basic '.$this->settings->apiKey,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $options = [
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ];

        $request = new Request('POST', $this->settings->accessTokenUrl, $headers);
        $res = $this->client->sendAsync($request, $options)->wait();
        if ($res->getStatusCode() == 200) {
            $body = json_decode($res->getBody()->getContents(), true);

            return $body['access_token'];
        } else {
            throw new \Exception('error in get access token');
        }
    }

    // Redirect into bank using transactionId, to complete the payment.
    public function pay(): RedirectionForm
    {
        return $this->redirectWithForm($this->settings->payUrl, ['code' => $this->invoice->getDetail('paymentCode')], 'GET');
    }

    public function verify(): ReceiptInterface
    {
        //todo: implement this method
        return new Receipt('n-genius', 1234);
    }
}
