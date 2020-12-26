<?php


namespace ImiApp\WebSocketServer\Middleware;


use Imi\Aop\Annotation\Inject;
use Imi\Bean\Annotation\Bean;
use Imi\Log\ErrorLog;
use Imi\Server\WebSocket\IMessageHandler;
use Imi\Server\WebSocket\Message\IFrame;
use Imi\Server\WebSocket\Middleware\IMiddleware;
use ImiApp\Enum\MessageCode;
use ImiApp\Exception\BusinessException;

/**
 * 返回数据处理中间件
 * @Bean("ReturnMessageMiddleware")
 */
class ReturnMessageMiddleware implements IMiddleware
{
    /**
     * @Inject("ErrorLog")
     * @var ErrorLog
     */
    protected $errorLog;

    public function process(IFrame $frame, IMessageHandler $handler)
    {
        // TODO: Implement process() method.
        try{
            $result = $handler->handle($frame);
        } catch (BusinessException $businessException){
            $code = $businessException->getCode() ?? MessageCode::ERROR;
            $message = $businessException->getMessage() ?? "";
            $result = [];
            $this->errorLog->onException($businessException);
        } catch (\Throwable $throwable){
            $code = MessageCode::ERROR;
            $message = "系统错误";
            $result = [];
            $this->errorLog->onException($throwable);
        }
        if(null !== $result) {
            if(!isset($result['code'])) {
                $result['code'] = $code ?? MessageCode::SUCCESS;
            }
            if(!isset($result['message'])) {
                $result['message'] = $message ?? '';
            }
            if(!isset($result['messageId'])) {
                $result['messageId'] = $frame->getFormatData()['messageId'] ?? null;
            }
            return $result;
        }
    }
}