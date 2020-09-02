### 1，安装扩展
> composer require zz/pay

### 2，请求支付平台接口，加密方式
```php
use Zz\Pay;

$data=['a'=>1,'b'=>2];//要发送的数据
$ulr='http://testpayapi.9617777.com/xxx';//需要调用的接口地址
$key='123456';//加签key
$deskey='a31f4e78161be73177185f16'; //3des加密key

$post=ZzPay::signEncrypt($data,$key,$deskey); //生成加密数据
$result=Curl::post($ulr,$post); //请求接口 
```