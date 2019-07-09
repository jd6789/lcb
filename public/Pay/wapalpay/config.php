<?php
$config = array (
    //应用ID,您的APPID。
    'app_id' => "2017121300690545",

    //商户私钥
    'merchant_private_key' => "MIIEowIBAAKCAQEAx5q0aN5TyjVgRTd/Eqj1+AHW/G/NENpodjAr04fPP0Gsh1Y16ZmCtNhrhml19NrZacySAgqOIkUUp++M27x7fVa5AYHBtXaEIBLLiFRhnNbZd1mIs6ApQM4ZZXL8fhbr/aap4u1n+ArTVm8pHCEPVLnhOj+hxj3PhhmLwEXm9t0pe1Xx12tQCZpwvMRW6aU8INP1tljeAfbBEAxgO1jSl2M4CVJWEjDbYgtRZdluhMwW5IpP5Im2Luck0d86PliZmrEV9574g5XLp7j+VsgOv9F3q/5KJVqpD8hWqMMp26BQqKz+GO4ynZ24en5qka2Tmaubb594fC1K0Wq/5TWgEQIDAQABAoIBACWbpHpNyYPD4sun1S1y4EVGM3eEqSKPxDbghKFqaUYF3bfyKl5FKjdgv4GnxS3JPkWM4cW4r2cNlQK/Q87zCGFpQZpjFFhYaDSS1f5tx6aBZJ/0IqmVd7BaJLbUn3YyY2x7Rm8XRAucHo2ejCRcpkLUcvdCnfx4EO23SnmDsiy40Z4CVVvB2heB+EyipjZuoWn2JpryNwaiWgUuC9F13lv+xiYyDnwzsuKOgFnHZyIzAaaPXTTWD8iDMT492xnmJB9MDv7HIsCB0I/tArgKNJp69Lh9juPEDyo2NNzsvzrfeG4RQ/tSAfkFbWU1zH2bM7tJZDz9jmUGmdhO2J6K9jECgYEA7Lq7FCEtS6YLP2fE1FAfMc06nwWKdKQB18eqUrJ4a28LEUFgfrBHMS+7JQCwXZchfhHMjIGdyLnZKdjBlwug3IR32qCuZ5DRri0iKpuADAQNVA6ydDgB1m4xG3rsLMRB8UvRwHdV8U6lonJQhRNEAdtKSGn74x+GxKN89uTpxOMCgYEA19pQU5e9sEV0fmbEPX93cridY++7YU4r9k/tmcbfPuYPoKpq/FJcH/21M++SsrBBR+9/Ev5YOB9T73/PaH1pFKEf+VpLrD05Wo+e0PkgcMPm/FFelx91H5/nW/Qr0jmlH/k5Z6nt5LKUG3NW6Ses9l3+e8X+5n7L3YaQoygxjXsCgYAjgeapoEhQ/njVa9UkG1hyedv/Gi+oSylTmkk72jUhuCgq0GmO1xX2lLg3VqEdiJunczgBIzftcaLXGgH/i+j26o+npU5Djn9E5tQnG+fa5YiyQPoXYIc2CJUpEAj4tf9GGB9ABSZI6YkCkq7tJvSeaqv7rJnxeTsXp4YI6lD+DQKBgDZSRbbT0DQFNqz81dQYOQpQ9aMJ2OFDGAMz2DrD9rjQkww+9w+q9m5CnhCE8Skw/CzLU/KGudLd53S1eI/2R7SeW/qXw8WD3uQwkqpKl+RpGL9VBvYHP6WGy2WzzDdqtGiYgt7Yv/q3CljiLPQePQP/YaTqjhyZulp0m//DhLzzAoGBAK8BPgllmMlUffLoWNhdp4e/lGA0aMVM2zsK7r5iHhe038MLRteLP49wtjwhVj0hB237gEahQqjEr4sr3glddwPXk3Nny0Ks2s9idwcb177y7Gqhjx3M/FV3hHMI8em1FBzemvfz8kGWWFBjrHpn3gPJ0IrEBmqjPDzHh5HE/yFt",


    //异步通知地址
    'notify_url' => "http://localhosta/alpay/notify_url.php",

    //同步跳转
    'return_url' => "http://web.jieyu.com/Home/Order/returnUrl.html",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    //在使用公钥时需要指定为支付宝的公钥目前是测试账户
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3rYjiteDcGYdXhTJ3r146h/nYJUXXlxdzYd86eX/FZ7qmOQXBoAfRbG/xp70ZUA7X8nrqBIxKZ+YI8H92+j4Aa9310p22lxj2ul0YF2hW2SbOOMJnI7vYEebvDJoPHqOjh4bk+SGcinLwx3wcgE8PPNngGoE+k4XDcls34lsHMSAAKv8dff5Ggh7VN/GV6SlNxchvTzqKEkofIOxLLEWjt3H8s2UHRxIdb7rxZ6f6A9gkSVcJz/qTBRnED+oGkTh7fZGclvIpYlI6zcy2GpSy587n0yq3sGk9C6ZzNSdO5SUzHmrM6Cv3VBW7s15yGEpETa3ORvu1jlb0uLL6m2dlwIDAQAB",
);