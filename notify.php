<?php
require 'vendor/autoload.php';

$data = $_POST;

$signature = $data['signature'];

$verifyResult = Vdopool\Helper::hmacVerify('POST', $data, $signature, Vdopool\Config::$secret_key);

if ($verifyResult) {
  // 商家代码逻辑处理之处
  if ($data['trade_status'] == 'success') {
    // code
  } else {
    // code
  }
  return 'success';
} else {
  return 'fail';
}

