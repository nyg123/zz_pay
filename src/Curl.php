<?php

namespace Zz\Pay;

use Exception;

class Curl
{

    /**
     * 普通curl请求
     * @param string $url 接口地址
     * @param array $data POST数据
     * @param array $headers headers头
     * @param int $timeout 超时时间，秒
     * @return bool|string
     * @throws Exception
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/12 16:36
     */
    static function post($url, $data = null, $headers = [], $timeout = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if ($timeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //设置超时秒数
        }
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);//返回response头部信息
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception("Curl error: " . curl_errno($ch));
        }
        curl_close($ch);
        return $info;
    }
}