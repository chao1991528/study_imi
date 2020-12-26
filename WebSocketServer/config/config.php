<?php

use Imi\Log\LogLevel;
return [
    'configs'    =>    [
    ],
    // bean扫描目录
    'beanScan'    =>    [
        'ImiApp\WebSocketServer\Controller',
        'ImiApp\WebSocketServer\HttpController',
        'ImiApp\WebSocketServer\WebsocketController',
        'ImiApp\Enum',
        'ImiApp\WebSocketServer\App',
        'ImiApp\Module',
        'ImiApp\WebSocketServer\Middleware'
    ],
    'beans'    =>    [
        'SessionManager'    =>    [
            'handlerClass'    =>    \Imi\Server\Session\Handler\File::class,
        ],
        'SessionFile'    =>    [
            'savePath'    =>    dirname(__DIR__, 2) . '/.runtime/.session/',
        ],
        'SessionConfig'    =>    [

        ],
        'SessionCookie'    =>    [
            'enable'    =>  false,
            'lifetime'    =>    86400 * 30,
        ],
        'HttpDispatcher'    =>    [
            'middlewares'    =>    [
                \ImiApp\WebSocketServer\Middleware\PoweredBy::class,
                \Imi\Server\Session\Middleware\HttpSessionMiddleware::class,
                \Imi\Server\WebSocket\Middleware\HandShakeMiddleware::class,
                \Imi\Server\Http\Middleware\RouteMiddleware::class,
            ],
        ],
        'HtmlView'    =>    [
            'templatePath'    =>    dirname(__DIR__) . '/template/',
            // 支持的模版文件扩展名，优先级按先后顺序
            'fileSuffixs'        =>    [
                'tpl',
                'html',
                'php'
            ],
        ],
        'WebSocketDispatcher'    =>    [
            'middlewares'    =>    [
                \ImiApp\WebSocketServer\Middleware\ReturnMessageMiddleware::class,
                \Imi\Server\WebSocket\Middleware\RouteMiddleware::class,
            ],
        ],
        'ServerGroup' => [
            'status' => true , // 启用
            'groupHandler' => \Imi\Server\Group\Handler\Redis::class, // 分组处理器，目前仅支持 Redis
        ],
        'GroupRedis'    =>    [
            'redisPool'    =>    'redis',
        ],
        'ConnectContextStore'   =>  [
            'handlerClass'  =>  \Imi\Server\ConnectContext\StoreHandler\Redis::class,
        ],
        'ConnectContextRedis'    =>    [
            'redisPool'    => 'redis', // Redis 连接池名称
            'redisDb'      => 0, // redis中第几个库
            'key'          => 'imi:connect_context', // 键
            'heartbeatTimespan' => 5, // 心跳时间，单位：秒
            'heartbeatTtl' => 8, // 心跳数据过期时间，单位：秒
            'dataEncode'=>  'serialize', // 数据写入前编码回调
            'dataDecode'=>  'unserialize', // 数据读出后处理回调
            'lockId'    =>  "redis", // 必设，需要用锁来防止数据错乱问题
        ],
//        'ConnectContextLocal'    =>    [
//            'lockId'    =>  'redis',
//        ],
        'ConnectionBinder'  =>  [
            // Redis 连接池名称
            'redisPool' =>  'redis',
            // redis中第几个库
            'redisDb'   =>  0,
            // 键，多个服务共用 redis 请设为不同的，不然会冲突
            'key'       =>  'imi:wsTest:connectionBinder:map',
        ],

        'OptionsMiddleware' =>  [
            // 设置允许的 Origin，为 null 时允许所有，为数组时允许多个
            'allowOrigin'       =>  null,
            // 允许的请求头
            'allowHeaders'      =>  'Authorization, Content-Type, Accept, Origin, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, X-Id, X-Token, Cookie, x-session-id',
            // 允许的跨域请求头
            'exposeHeaders'     =>  'Authorization, Content-Type, Accept, Origin, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, X-Id, X-Token, Cookie, x-session-id',
            // 允许的请求方法
            'allowMethods'      =>  'GET, POST, PATCH, PUT, DELETE',
            // 是否允许跨域 Cookie
            'allowCredentials'  =>  'true',
            // 当请求为 OPTIONS 时，是否中止后续中间件和路由逻辑，一般建议设为 true
            'optionsBreak'      =>  true,
        ],

        'HttpSessionMiddleware' =>  [
            'sessionIdHandler'    =>    function(\Imi\Server\Http\Message\Request $request){
                $sessionId = $request->getHeaderLine('X-Session-Id');
                if(!$sessionId)
                {
                    $sessionId = $request->get('_sessionId');
                }
                return $sessionId;
            },
        ],

        'HttpErrorHandler'    =>    [
            // 指定默认处理器
            'handler'    =>    \ImiApp\WebSocketServer\ErrorHandler\HttpErrorHandler::class,
        ],
    ],
    [
        'tools'  =>  [
            'generate/model'    =>  [
                'relation'  =>  [
                    'tb_user'   =>  [
                        'namespace' =>  'ImiApp\Module\User\Model',
                    ],
                ],
            ],
        ],
    ]
];