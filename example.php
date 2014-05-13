<?php

require 'vendor/autoload.php';

$trade_type = $_GET['trade_type'];
$amount = $_GET['amount'];

$data = $_GET;
$data['out_order_no'] = $data['timestamp'] = time();

$data['subject'] = '充值'.$amount.'元';
$data['notify_url'] = Vdopool\Config::$notify_url;
$data['mer_key'] = Vdopool\Config::$mer_key;

try {
  $response = Vdopool\Service::trade($data);
  Vdopool\Helper::logResult('request.log', $response);
  echo $response;
} catch (\Exception $e) {
  var_dump($e->getMessage());
}



