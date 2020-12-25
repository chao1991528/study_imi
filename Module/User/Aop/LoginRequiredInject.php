<?php


namespace ImiApp\Module\User\Aop;

use Imi\Aop\Annotation\Aspect;
use Imi\Aop\Annotation\Before;
use Imi\Aop\Annotation\PointCut;
use Imi\Aop\JoinPoint;
use Imi\Aop\PointCutType;
use Imi\RequestContext;
use ImiApp\Module\User\Exception\UserNotLoginException;

/**
 * @Aspect
 * Class LoginRequiredInject
 * @package ImiApp\Module\User\Aop
 */
class LoginRequiredInject
{

    /**
     * 自动事务支持
     * @PointCut(
     *         type=PointCutType::ANNOTATION,
     *         allow={
     *             \ImiApp\Module\User\Annotation\LoginRequired::class
     *         }
     * )
     * @Before
     * @return mixed
     */
    public function inject(JoinPoint $joinPoint)
    {
        $userSession = RequestContext::getBean('UserSessionService');
        if(!$userSession->isLogin())
        {
            throw new UserNotLoginException;
        }
    }


}