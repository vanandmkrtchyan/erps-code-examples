<?php
/**
 * Created by PhpStorm.
 * User: Vanand Mkrtchyan
 * Date: 11/5/2018
 * Time: 6:59 PM
 */

require_once('class/ErpsAPI.php');

$token = 'Your Token';
$secret = 'Your Secret';

$erps = new ErpsAPI($token,$secret);

$fields = array(
    'first_name'            => 'John',
    'last_name'             => 'Doe',
    'country'               => 'ES',
    'state'                 => 'BA',
    'product_description'   => 'This is description',

//    uncomment if these fields are required for your user
//    'city'                  => 'Barcelona',
//    'address'               => 'Some address 123',
//    'telephone'             => '+123456789',
//    'zip'                   => '1234',
);
$erps->setRawFields($fields);
$erps->setMetaFields();

$erps->setSuccessUri("http://example.com/success");
$erps->setCancelUri("http://example.com/cancel");


$erps->setAmount("100");

$erps->pay();