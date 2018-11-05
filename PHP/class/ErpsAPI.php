<?php
/**
 * Created by PhpStorm.
 * User: Vanand Mkrtchyan
 * Date: 11/5/2018
 * Time: 7:00 PM
 */

class ErpsAPI{

    private $token  = null;
    private $secret = null;
    private $fields = [];

    private $uri = "http://sandbox.erpscloud.vm/api/order";

    private $success_uri = null;
    private $cancel_uri  = null;

    public function __construct($token,$secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function getApiToken()
    {
        return $this->token;
    }

    public function getApiSecret()
    {
        return $this->secret;
    }

    public function setRawFields( array $fields)
    {
        $this->fields = $fields;
    }

    public function setMetaFields()
    {
        $this->fields['detected_client_ip'] = '142.119.30.60'; // should be replaced with $_SERVER['REMOTE_ADDR'];
        $this->fields['date'] = date('d/m/Y H:i:s');
    }

    public function setAmount($amount)
    {
        $this->fields['amount'] = $amount;
    }

    public function setSuccessUri($uri)
    {
        $this->success_uri = $uri;
    }

    public function setCancelUri($uri)
    {
        $this->cancel_uri = $uri;
    }
    
    protected function calculateSignature()
    {
        ksort($this->fields);
        return hash_hmac('sha256', http_build_query($this->fields), $this->secret);
    }

    public function pay()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'X-Access-Token: ' . $this->token
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //Post Fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($this->fields, [
            'signature' => $this->calculateSignature(),
            'success_url' => $this->success_uri,
            'cancel_url' => $this->cancel_uri
        ]));

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;

    }
}