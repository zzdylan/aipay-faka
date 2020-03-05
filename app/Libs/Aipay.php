<?php

namespace App\Libs;

class Aipay {


    private $mchid = '1554208111';
    private $key   = 'nwcpHlrh7LIq4KAE';

    private $native_url   = 'https://www.aipay6.com/api/native';
    private $jsapi_url   = 'https://www.aipay6.com/api/jsapi';
    private $micropay_url   = 'https://www.aipay6.com/api/micropay';
    private $openid_url   = 'https://www.aipay6.com/api/openid';
    private $check_url   = 'https://www.aipay6.com/api/check';
    private $refund_url   = 'https://www.aipay6.com/api/refund';


    public function __construct(){
    	$this->apiUrl = config('aipay.api_url');
    	$this->mchid = config('aipay.mchid');
    	$this->key = config('aipay.key');
    	$this->native_url   = $this->apiUrl.'/api/native';
        $this->jsapi_url   = $this->apiUrl.'/api/jsapi';
        $this->micropay_url   = $this->apiUrl.'/api/micropay';
        $this->openid_url   = $this->apiUrl.'/api/openid';
        $this->check_url   = $this->apiUrl.'/api/check';
        $this->refund_url   = $this->apiUrl.'/api/refund';
    	
    }

    public function setMchId($mchid){
        $this->mchid = $mchid;
    }

    public function setKey($key){
        $this->key = $key;
    }

    public function getOpenidUrl(){
        return $this->openid_url;
    }

    //扫码支付
    public function native(array $data){
        return $this->request($this->native_url,$data);
    }

    //jsapi支付
    public function jsapi(array $data){
        return $this->request($this->jsapi_url,$data);
    }

    //付款码支付
    public function micropay(array $data){
        return $this->request($this->micropay_url,$data);
    }

    //查订单状态
    public function check(array $data){
        return $this->request($this->check_url,$data);
    }

    //退款
    public function refund(array $data){
        return $this->request($this->refund_url,$data);
    }

    public function request($url,array $data){
        $data['mchid'] = $this->mchid;
        $data['sign'] = $this->sign($data);
        return json_decode($this->post($data, $url),true);
    }

    public function post($data, $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $rst = curl_exec($ch);
        curl_close($ch);
        return $rst;
    }

    //签名
    public function sign(array $attributes) {
        foreach($attributes as $key=>$attribute){
            if($key == 'sign'){
                unset($attributes[$key]);
            }
            if(!$attribute){
                unset($attributes[$key]);
            }
        }
        ksort($attributes);
        $sign = strtoupper(md5(urldecode(http_build_query($attributes)) . '&key=' . $this->key));
        return $sign;
    }
}


//扫码支付
//$order = [
//    'out_trade_no' => time(),
//    'body' => '测试订单',
//    'total_fee' => 1,
//];
//$aipay = new Aipay();
//$result = $aipay->native($order);
//print_r($result);


//jsapi支付
//$order = [
//    'out_trade_no' => time(),
//    'body' => '测试订单',
//    'total_fee' => 1,
//    'openid' => 'oyOzqwjmAPNRrAS7Zd_FN-4Z_tns'
//];
//$aipay = new Aipay();
//$result = $aipay->jsapi($order);
//print_r($result);


//付款码支付
//$order = [
//    'out_trade_no' => time(),
//    'body' => '测试订单',
//    'total_fee' => 1,
//    'auth_code' => '134663126181200520',
//];
//$aipay = new Aipay();
//$result = $aipay->micropay($order);
//print_r($result);


//退款
//$order = [
//    'trade_no' => '156741144329144010'
//];
//$aipay = new Aipay();
//$result = $aipay->refund($order);
//print_r($result);
//exit();


?>