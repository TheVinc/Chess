<?php

Class Position
{
    private $posX;
    private $posY;
    private $piece;

    public function __construct($x, $y)
    {
        $this->posX = $x;
        $this->posY = $y;
        $this->piece = null;
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

    public function toArray()
    {
        $myArray = array();
        $myArray["posX"] = $this->getPosX();
        $myArray["posY"] = $this->getPosY();
        if ($this->getPiece() != null && is_object($this->getPiece()))
            $myArray["piece"] = $this->getPiece()->toArray();

        return $myArray;
    }

    public function setPiece($piece)
    {
        $this->piece = $piece;
    }

    public function getPiece()
    {
        return $this->piece;
    }
}
