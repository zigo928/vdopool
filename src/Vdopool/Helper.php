<?php
namespace Vdopool;
/**
 * 星空支付辅助函数类
 *
 * @package default
 * @author Me
**/
class Helper {

  /**
   * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
   * @param $para 需要拼接的数组
   * return 拼接完成以后的字符串
   */
  public static function createLinkstring($para) {
    $arg  = "";
    foreach ($para as $key => $val) {
      $arg.=$key."=".$val."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,count($arg)-2);

    //如果存在转义字符，那么去掉转义
    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

    return $arg;
  }
  /**
   * 除去数组中的空值和签名参数
   * @param $para 签名参数组
   * return 去掉空值与签名参数后的新签名参数组
   */
  public static function paraFilter($para)
  {
    $para_filter = array();
    foreach ($para as $key => $val) {
      if($key == "signature" || $key == "sign_type" || $val == "") {
        continue;
      } else {
        $para_filter[$key] = $para[$key];
      }
    }
    return $para_filter;
  }

  /**
   * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
   * 注意：服务器需要开通fopen配置
   * @param $word 要写入日志里的文本内容 默认值：空值
   */
  public static function logResult($filePath, $word='')
  {
    $fp = fopen($filePath, "a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
  }

  /**
   * 签名字符串
   * @param $prestr 需要签名的字符串
   * @param $key 私钥
   * return 签名结果
   */
  public static function md5Sign($prestr, $key) {
    $prestr = $prestr . $key;
    return md5($prestr);
  }

  /**
   * 验证签名
   * @param $prestr 需要签名的字符串
   * @param $sign 签名结果
   * @param $key 私钥
   * return 签名结果
   */
  public static function md5Verify($prestr, $sign, $key) {
    $prestr = $prestr . $key;
    $mysgin = md5($prestr);

    if($mysgin == $sign) {
      return true;
    }
    else {
      return false;
    }
  }

  /**
   * 文本编码(就是urlencode的功能)
   *
   * @param string $text 文本
   *
   * @return string
   * @author Me
   **/
  public static function charEncode($text)
  {
    return urlencode($text);
  }

  /**
   * 请求参数签名
   *
   * @param string $method 请求方法 post|get|put|delete
   * @param string $queryVars 请求参数
   * @param string $secretAccessKey 私钥
   *
   * @return string
   * @author Me
   **/
  public static function hmacSign($method, $queryVars, $secretAccessKey)
  {
    $queryVars = self::paraFilter($queryVars);
    // 1a. Sort the UTF-8 query string components by parameter name
    ksort($queryVars);
    reset($queryVars);
    // 1b. URL encode the parameter name and values
    $encodedVars = array();
    foreach($queryVars as $key => $value) {
      $encodedVars[self::charEncode($key)] = self::charEncode($value);
    }
    // 1c. 1d. Reconstruct encoded query
    $encodedQueryVars = array();
    foreach($encodedVars as $key => $value) {
      $encodedQueryVars[] = $key."=".$value;
    }
    $encodedQuery = implode("&",$encodedQueryVars);
    // 2. Create the string to sign
    $stringToSign = strtoupper($method);
    $stringToSign .= "n".$encodedQuery;
    // 3. Calculate an RFC 2104-compliant HMAC with the string you just created,
    //    your Secret Access Key as the key, and SHA256 as the hash algorithm.
    if (function_exists("hash_hmac")) {
      // 二进制
      /* $hmac = hash_hmac("sha256",$stringToSign,$secretAccessKey,TRUE); */
      // 十六进制
      $hmac = hash_hmac("sha256",$stringToSign,$secretAccessKey);
    } elseif(function_exists("mhash")) {
      $hmac = mhash(MHASH_SHA256,$stringToSign,$secretAccessKey);
    } else {
      die("No hash function available!");
    }
    // 4. Convert the resulting value to base64
    $hmacBase64 = base64_encode($hmac);
    // 5. Use the resulting value as the value of the Signature request parameter
    // (URL encoded as per step 1b)
    return $hmacBase64;
  }

  /**
   * 验证hmac加密
   *
   * @param string $method 请求方法 post|get|put|delete
   * @param string $queryVars 请求参数
   * @param string $signature 签名
   * @param string $secretAccessKey 私钥
   *
   * @return boolean true | false
   * @author Me
   **/
  public static function hmacVerify($method, $queryVars, $signature, $secretAccessKey)
  {
    $sign = self::hmacSign($method, $queryVars, $secretAccessKey);

    return ($sign === $signature);
  }
}
