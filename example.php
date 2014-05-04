<?php

require 'vendor/autoload.php';

$trade_type = $_GET['trade_type'];
$amount = $_GET['amount'];

$data = [];
$data['out_order_no'] = $data['timestamp'] = time();

$data['subject'] = '充值'.$amount.'元';
$data['amount'] = $amount;
$data['trade_type'] = $trade_type;
$data['notify_url'] = Vdopool\Config::$notify_url;
$data['mer_key'] = Vdopool\Config::$mer_key;

$response = Vdopool\Service::trade($data);

echo $response;exit;


