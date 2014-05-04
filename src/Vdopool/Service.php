<?php
namespace Vdopool;

class Service {

  /**
   * 充值
   *
   * @param array $data 交易需要的数据
   *
   * @return string
   * @author Me
   **/
  public static function trade($data)
  {
    $sign = Helper::hmacSign('POST', $data, Config::$secret_key);
    $data['signature'] = $sign;

    $client = new \GuzzleHttp\Client;

    $request = $client->createRequest('POST', Config::$trade_url);
    $postBody = $request->getBody();

    // $postBody is an instance of GuzzleHttp\Post\PostBodyInterface
    foreach ($data as $k => $v) {
      $postBody->setField($k, $v);
    }
    // Send the POST request
    $response = $client->send($request);
    return $response->getBody();
  }

  /**
   * 查询订单
   *
   * @param array $data 查询需要的数据
   *
   * @return string
   * @author Me
   **/
  public static function query($data)
  {
    $sign = Helper::hmacSign('GET', $data, Config::$secret_key);
    $data['signature'] = $sign;

    $url = Config::$query_url . '?' . Helper::createLinkstring($data);

    $client = new \GuzzleHttp\Client;

    $request = $client->get($url);

    // Send the GET request
    $response = $request->send();
    return $response->getBody();
  }
}

