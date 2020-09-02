<?php

namespace Zz\Pay;

use Exception;

/**
 * 至尊支付平台
 * Class ZzPay
 * @package Nyg\Tool
 */
class ZzPay
{

    /**
     * 支付平台加密接口用
     * @param $data array <p>支付平台传过来的
     * @param $key string <p>支付平台分发的密钥key</p>
     * @param $deskey string <p> <b style='color:red'>3des</b>加密所需key</p>
     * @return string <p>最终上送数据</p>
     * @throws Exception
     */
    static public function prepare($data, $key, $deskey)
    {
        $my_sign = Aes::sign_encrypt($data, $key);
        if ($my_sign != strtoupper($data['sign'])) {
            throw new Exception('签名错误！');
        }
        return self::encrypt($data, $deskey);
    }

    /**
     * 3des加密
     * @param $data array <p>支付平台传过来的
     * @param $deskey string <p> <b style='color:red'>3des</b>加密所需key</p>
     * @return string <p>最终上送数据</p>
     */
    static private function encrypt($data, $deskey)
    {
        $data = array_filter($data, 'strlen');
        ksort($data);
        $json = json_encode($data);
        return base64_encode(openssl_encrypt(Aes::pkcs5_pad($json, 8), "DES-EDE3", $deskey, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING));
    }

    /**
     * 3des解密
     * @param string $data <p>需要解密的字符串 $post['data']</p>
     * @param string $deskey <p> <b style='color:red'>3des</b>加密所需key</p>
     * @return array
     */
    static public function decrypt($data, $deskey)
    {
        $result = openssl_decrypt(base64_decode($data), 'DES-EDE3', $deskey, OPENSSL_RAW_DATA);
        return json_decode($result, true);
    }

    /**
     * 调用支付结算中心里，数据签名加密接口
     * @param array $data 待加密的数据
     * @param string $key 加签key
     * @param string $deskey 3des加密key
     * @return array
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/19 9:15
     */
    static public function signEncrypt($data, $key, $deskey)
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = json_encode($v);
            }
        }
        $data['sign'] = strtolower(Aes::sign_encrypt($data, $key));
        $post['data'] = self::encrypt($data, $deskey);
        return $post;
    }
}