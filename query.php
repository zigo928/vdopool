<?php

require 'vendor/autoload.php';


$data['order_no'] = $data['timestamp'] = time();
$data['mer_key'] = Vdopool\Config::$mer_key;
$data['trade_type'] = 'alipay';
$data['order_time'] = date('Y-m-d H:i:s');

try {
  $response = Vdopool\Service::query($data);
  /* Vdopool\Helper::logResult('request.log', $response); */
  echo $response;
} catch (\Exception $e) {
  var_dump($e->getMessage());
}
