<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2020/12/23
 * Time: 9:59
 */


namespace ImiApp\WebSocketServer\ErrorHandler;


use Imi\App;
use Imi\RequestContext;
use Imi\Server\Http\Error\IErrorHandler;
use Imi\Util\Http\Consts\MediaType;
use Imi\Util\Http\Consts\RequestHeader;
use ImiApp\Enum\MessageCode;
use ImiApp\Exception\BusinessException;

class HttpErrorHandler implements IErrorHandler
{
    public function handle(\Throwable $throwable): bool
    {
        // TODO: Implement handle() method.
        $cancelThrow = false;
        if($throwable instanceof BusinessException) {
            $code = $throwable->getCode();
        } else {
            $code = MessageCode::ERROR;
        }
        $data = [
            'code' => $code ? $code : $throwable->getCode(),
            'message' => $throwable->getMessage(),
        ];
        if(App::isDebug()) {
            $data['exception'] = [
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => explode(PHP_EOL, $throwable->getTraceAsString())
            ];
        }
        RequestContext::get('response')->withAddedHeader(RequestHeader::CONTENT_TYPE, MediaType::APPLICATION_JSON)
        ->write(json_encode($data))
        ->send();

        return $cancelThrow;
    }
}