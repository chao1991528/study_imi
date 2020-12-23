<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2020/12/23
 * Time: 14:27
 */


namespace ImiApp\Module\User\ApiController;


use Imi\Aop\Annotation\Inject;
use Imi\Controller\SingletonHttpController;
use Imi\HttpValidate\Annotation\HttpValidation;
use Imi\Server\Http\Annotation\ExtractData;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Controller;
use Imi\Validate\Annotation\Required;
use Imi\Validate\Annotation\Text;
use ImiApp\Module\User\Service\UserService;

/**
 * @Controller("/user/")
 */
class UserController extends SingletonHttpController
{

    /**
     * @Inject("UserService")
     * @var UserService
     */
    protected $userService;

    /**
     * 注册
     * @Action
     *
     * @HttpValidation
     *
     * @Required(name="$post.username", message="用户名为必传参数")
     * @Text(name="$post.username", min="1", message="用户名不符合规则")
     * @Required(name="$post.password", message="密码为必传参数")
     * @Text(name="$post.password", min="2", message="密码长度不得少于2位")
     *
     * @ExtractData(name="username", to="username")
     * @ExtractData(name="password", to="password")
     *
     * @param  $username
     * @param  $password
     *
     * return void
     */
    public function register($username, $password){
//        var_dump($username, $password);die;
        $this->userService->register($username, $password);
    }
}