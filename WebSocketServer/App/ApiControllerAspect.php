<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2020/12/23
 * Time: 9:30
 */


namespace ImiApp\WebSocketServer\App;

use Imi\Aop\AfterReturningJoinPoint;
use Imi\Aop\Annotation\AfterReturning;
use Imi\Aop\Annotation\Aspect;
use Imi\Aop\Annotation\PointCut;
use Imi\Aop\PointCutType;
use Imi\Server\Route\Annotation\Action;

/**
 * @Aspect
 */
class ApiControllerAspect
{
    /**
     * @PointCut(
     *     type=PointCutType::ANNOTATION,
     *     allow={
     *          Action::class
     *     }
     * )
     * @AfterReturning
     * @return mixed
     */
    public function parse(AfterReturningJoinPoint $afterReturningJoinPoint)
    {
        $returnValue = $afterReturningJoinPoint->getReturnValue();
        if($returnValue === null || (is_array($returnValue) && !isset($returnValue['code']))) {
            $returnValue['code'] = 0;
            $returnValue['message'] = '';
        } elseif (is_object($returnValue) && !isset($returnValue['code'])) {
            $returnValue['code'] = 0;
            $returnValue['message'] = '';
        } else {
            return ;
        }
        $afterReturningJoinPoint->setReturnValue($returnValue);
    }
}