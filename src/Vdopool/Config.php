<?php
namespace Vdopool;

/**
 * 配置文件
 **/
class Config
{
  // 商户的ID
  public static $mer_key    = 'D8TkcPiHkoYv0VPA';

  // 商户的私有密钥
  public static $secret_key = 'XT8lwS0jJv5ju0EmVoDgCMiTkKIiOWdSHXuMBe3l';

  // 商户设置的回调地址
  public static $notify_url = 'http://payment.211.100.56.140.xip.io/test/notify';

  // 充值地址
  public static $trade_url  = 'http://payment.211.100.56.140.xip.io/api/v1/trades';

  // 查询地址
  public static $query_url  = 'http://payment.211.100.56.140.xip.io/api/v1/trades/';
}

