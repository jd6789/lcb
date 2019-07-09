<?php
//配置文件
return [
    'cache'   => [
           // 缓存类型为File
    'type'  =>  'File',
    // 缓存有效期为永久有效
    'expire'=>  0,
    //缓存前缀
    'prefix'=>  'think',
    // 指定缓存目录
    'path'  =>  APP_PATH.'runtime/cache/jilu/',
    ],
    //分页配置
    'paginate'      => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 9,
        'newstyle'  => true,
    ],
];