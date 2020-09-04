<h1 align="center">IP-Locating</h1>

<p align="center">基于高德开放平台的 PHP IP 定位组件。</p>

## 安装

```shell script
$ composer require lanxr/ip-locating -vvv
```

## 配置

在使用本扩展之前，你需要去 [高德开放平台](https://lbs.amap.com/dev/id/newuser) 注册账号，然后创建应用，获取应用的 API Key。

## 使用

```php
use Lanxr\Locating\IPLocating;

$key = 'xxxxxxxxxx';

$ipLocating = new IPLocating($key);
```

### 获取 IP 定位信息

```php
$response = $ipLocating->getIPLocating('114.247.50.2');
```

返回示例：

```json
{
    "status": "1",
    "info": "OK",
    "infocode": "10000",
    "province": "北京市",
    "city": "北京市",
    "adcode": "110000",
    "rectangle": "116.0119343,39.66127144;116.7829835,40.2164962"
}
```

### 获取 XML 格式返回值

第二个参数为返回值类型，可选 `json` 与 `xml` ，默认 `json` ：

```php
$response = $ipLocating->getIPLocating('114.247.50.2', 'xml');
```

```xml
<response>
    <status>1</status>
    <info>OK</info>
    <infocode>10000</infocode>
    <province>北京市</province>
    <city>北京市</city>
    <adcode>110000</adcode>
    <rectangle>116.0119343,39.66127144;116.7829835,40.2164962</rectangle>
</response>
```

### 参数说明

```
array | string getIPLocating(string $ip, string $format = 'json')
```

> - `$ip` - IP 字符串，例如（'114.247.50.2'）
> - `$format` - 输出数据格式，默认为 `json` 格式，当设置为 `xml` 时，输出为 XML 格式的数据

### 在 Laravel 中使用

在 Laravel 中使用也是同样的安装方式，配置写在 `config/services.php` 中：

```php
    .
    .
    .
    'locating' => [
        'key' => env('IP_LOCATING_API_KEY'),
    ],
    .
    .
    .
```

然后在 `.env` 中配置 `IP_LOCATING_API_KEY` ：

```env
IP_LOCATING_API_KEY=xxxxxxxxxxx
```

可以用两种方法来获取 `Lanxr\Locating\IPLocating` 实例：

#### 方法参数注入

```php
    .
    .
    .
    public function show(IPLocating $ipLocating)
    {
        $response = $ipLocating->getIPLocating('114.247.50.2');
    }
    .
    .
    .
```

#### 服务名访问

```php
    .
    .
    .
    public function show()
    {
        $response = app('ipLocating')->getIPLocating('114.247.50.2');
    }
    .
    .
    .
```

## 参考

- [高德开放平台 IP 定位接口](https://lbs.amap.com/api/webservice/guide/api/ipconfig)

## License

MIT
