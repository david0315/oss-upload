<?php
declare(strict_types=1);

return [
    'aliyun' => [
        'access_id'  => env('OSS_ACCESS_ID',''),
        'access_key' => env('OSS_ACCESS_KEY',''),
        'bucket'     => env('OSS_BUCKET',''),
        'endpoint'   => env('OSS_ENDPOINT',''),//your endpoint 地域节点
        'socket_timeout' => '5184000', // 设置Socket层传输数据的超时时间
        'connection_timeout' => '10', //建立链接的超时时间
        'save_path' => 'upload/',  //存储目录
        'url'       =>  env('OSS_URL',''),	 //修改成自己的地址
        'upload_file_size' => env('UPLOAD_FILE_SIZE', 5 * 1024 * 1024),
    ],
];