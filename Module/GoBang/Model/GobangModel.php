<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2021/1/5
 * Time: 10:17
 */


namespace ImiApp\Module\GoBang\Model;

use Imi\Model\RedisModel;
use Imi\Model\Annotation\Column;
use Imi\Model\Annotation\Entity;
use Imi\Model\Annotation\RedisEntity;
use ImiApp\Module\GoBang\Enum\GobangCell;

/**
 * Gobang
 * @Entity
 * @RedisEntity(storage="hash", key="imi:gobang:games", member="{roomId}")
 */
class GobangModel extends RedisModel
{
    /**
     * roomId
     * @Column(name="roomId")
     * @var int
     */
    protected $roomId;
    /**
     * size
     * @Column(name="size")
     * @var int
     */
    protected $size;
    /**
     * currentColor
     * @Column(name="currentColor")
     * @var int
     */
    protected $currentColor;
    /**
     * map
     * @Column(name="map")
     * @var array
     */
    protected $gobangMap;
    /**
     * player1Color
     * @Column(name="player1Color")
     * @var int
     */
    protected $player1Color;
    /**
     * player2Color
     * @Column(name="player2Color")
     * @var int
     */
    protected $player2Color;
    /**
     * lastGoX
     * @Column(name="lastGoX")
     * @var int
     */
    protected $lastGoX;
    /**
     * lastGoY
     * @Column(name="lastGoY")
     * @var int
     */
    protected $lastGoY;

    /**
     * @param int $roomId
     * @return self
     */
    public function setRoomId(int $roomId)
    {
        $this->roomId = $roomId;
        return $this;
    }

    public function getRoomId(){
        return $this->roomId;
    }

    /**
     * @param int $color
     * @return self
     */
    public function setPlayer1Color(int $color)
    {
        $this->player1Color = $color;
        return $this;
    }

    public function getPlayer1Color(){
        return $this->player1Color;
    }
    public function setPlayer2Color(int $color)
    {
        $this->player2Color = $color;
        return $this;
    }

    public function getPlayer2Color(){
        return $this->player2Color;
    }

    public function getGobangMap(){
        return $this->gobangMap;
    }

    public function setGobangMap(array $map) {
        $this->gobangMap = $map;
        return $this;
    }

    public function setSize(int $size)
    {
        $this->size = $size;
        return $this;
    }

    public function getSize(){
        return $this->size;
    }

    public function setCurrentColor(int $color){
        $this->currentColor = $color;
        return $this;
    }

    public function getCurrentColor(){
        return $this->currentColor;
    }

    public function initMap(){
        $this->gobangMap = [];
        for($i=0; $i<$this->size; $i++){
            for($j=0; $j<$this->size; $j++){
                $this->gobangMap[$i][$j] = GobangCell::NONE;
            }
        }
    }

    public function setCell(int $x, int $y, int $value)
    {
        $this->gobangMap[$x][$y] = $value;
        $this->lastGoX = $x;
        $this->lastGoY = $y;
    }

    public function referee(int $x, int $y): int
    {
        $color = $this->gobangMap[$x][$y] ?? GobangCell::NONE;
        if(GobangCell::NONE === $color)
        {
            return GobangCell::NONE;
        }
        static $directionRules = [
            'leftRight'             =>  ['x' => 1, 'y' => 0],
            'upDown'                =>  ['x' => 0, 'y' => 1],
            'LeftUpperRightLower'   =>  ['x' => -1, 'y' => -1],
            'RightUpperLowerLeft'   =>  ['x' => 1, 'y' => -1],
        ];
        foreach($directionRules as $directionRule)
        {
            $pieceCount = 1;
            $xStep = $directionRule['x'];
            $yStep = $directionRule['y'];
            foreach([1, -1] as $num)
            {
                for($i = 1; $i < 5; ++$i)
                {
                    $tmpX = $x + $xStep * $i * $num;
                    $tmpY = $y + $yStep * $i * $num;
                    if($color === ($this->gobangMap[$tmpX][$tmpY] ?? GobangCell::NONE))
                    {
                        if(++$pieceCount >= 5)
                        {
                            return $color;
                        }
                    }
                    else
                    {
                        break;
                    }
                }
            }
        }
        return GobangCell::NONE;
    }

}