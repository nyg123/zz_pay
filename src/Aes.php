<?php

namespace Jyzz\Pay;

class Aes
{
    /**
     * 可逆解密
     * @param string $data 通过Aes::encrypt加密的加密字符串
     * @param string $key 加密密钥
     * @return array|null
     * @author 牛永光 nyg1991@aliyun.com
     * @date   2020/1/9 11:59
     */
    static public function decrypt($data, $key)
    {
        return json_decode(openssl_decrypt(base64_decode($data), 'aes-128-ecb', $key), true);
    }

    /**
     * 可逆加密
     * @param array $data 要加密的数组
     * @param string $key 加密密钥
     * @return string
     * @author 牛永光 nyg1991@aliyun.com
     * @date   2020/1/9 12:00
     */
    static public function encrypt($data, $key)
    {
        $data = json_encode($data);
        return base64_encode(openssl_encrypt($data, 'aes-128-ecb', $key));
    }

    /**
     * 验证签名
     * @param array $data 要验证的原始数组数据
     * @param string $sign 签名数据
     * @param string $key key
     * @return bool
     * @author 牛永光 nyg1991@aliyun.com
     * @date   2019/11/6 16:47
     */
    static public function sign_decrypt($data, $sign, $key)
    {
        return $sign == self::sign_encrypt($data, $key);
    }

    /**
     * 生成sign签名
     * @param array $data 要签名的数据,必须是一维数组
     * @param string $key
     * @return string
     * @author 牛永光 nyg1991@aliyun.com
     * @date   2019/11/6 16:47
     */
    static public function sign_encrypt($data, $key)
    {
        unset($data['sign']);
        $data = array_filter($data, 'strlen');
        ksort($data);
        $secret = urldecode(http_build_query($data, '', '&'));
        $secret .= "&key={$key}";
        return strtoupper(md5($secret));
    }

    static public function pkcs5_pad($text, $block_size)
    {
        $pad = $block_size - (strlen($text) % $block_size);
        return $text . str_repeat(chr($pad), $pad);
    }
}