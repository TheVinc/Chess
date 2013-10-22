<?php

Class Piece
{
    private $posX;
    private $posY;
    private $color;

    public function __construct()
    {
        echo "const";
        $this->posX = 0;
        $this->posY = 0;
    }

    public function setPosX($posX)
    {
        $this->posX = $posX;
    }

    public function getPosX()
    {
        return $this->posX;
    }

    public function setPosY($posY)
    {
        $this->posY = $posY;
    }

    public function getPosY()
    {
        return $this->posY;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function showInfos()
    {
        echo "---";
        var_dump(get_class($this));
        echo "Pos X : " . $this->posX . "</br>";
        echo "Pos Y : " . $this->posY . "</br>";
    }
}