<?php
namespace ImiApp\Base;

use Imi\Model\Model;
use Imi\Model\Annotation\DDL;
use Imi\Model\Annotation\Table;
use Imi\Model\Annotation\Column;
use Imi\Model\Annotation\Entity;

/**
 * tb_user 基类
 * @Entity
 * @Table(name="tb_user", id={"id"})
 * @DDL("CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `register_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4")
 * @property int $id 
 * @property string $username 
 * @property string $password 
 * @property string $registerTime 
 * @property string $lastLoginTime 
 */
abstract class UserBase extends Model
{
    /**
     * id
     * @Column(name="id", type="int", length=11, accuracy=0, nullable=false, default="", isPrimaryKey=true, primaryKeyIndex=0, isAutoIncrement=true)
     * @var int
     */
    protected $id;

    /**
     * 获取 id
     *
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * 赋值 id
     * @param int $id id
     * @return static
     */ 
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * username
     * @Column(name="username", type="varchar", length=200, accuracy=0, nullable=false, default="", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false)
     * @var string
     */
    protected $username;

    /**
     * 获取 username
     *
     * @return string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * 赋值 username
     * @param string $username username
     * @return static
     */ 
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * password
     * @Column(name="password", type="varchar", length=255, accuracy=0, nullable=false, default="", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false)
     * @var string
     */
    protected $password;

    /**
     * 获取 password
     *
     * @return string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * 赋值 password
     * @param string $password password
     * @return static
     */ 
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * register_time
     * @Column(name="register_time", type="timestamp", length=0, accuracy=0, nullable=false, default="", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false)
     * @var string
     */
    protected $registerTime;

    /**
     * 获取 registerTime
     *
     * @return string
     */ 
    public function getRegisterTime()
    {
        return $this->registerTime;
    }

    /**
     * 赋值 registerTime
     * @param string $registerTime register_time
     * @return static
     */ 
    public function setRegisterTime($registerTime)
    {
        $this->registerTime = $registerTime;
        return $this;
    }

    /**
     * last_login_time
     * @Column(name="last_login_time", type="datetime", length=0, accuracy=0, nullable=false, default="", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false)
     * @var string
     */
    protected $lastLoginTime;

    /**
     * 获取 lastLoginTime
     *
     * @return string
     */ 
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * 赋值 lastLoginTime
     * @param string $lastLoginTime last_login_time
     * @return static
     */ 
    public function setLastLoginTime($lastLoginTime)
    {
        $this->lastLoginTime = $lastLoginTime;
        return $this;
    }

}
