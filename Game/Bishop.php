<?php

Class Bishop extends Piece
{
    private $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "bishop";
    }

    public function getAvailablePosition($board)
    {
        $available_positions = array();
        $availablePos = $board->getNorthEastAvailablePositions($this->getPosX(), $this->getPosY(), $this->getColor());
        foreach ($availablePos as $pos)
        {
            $available_positions[] = $pos;
        }
        $availablePos = $board->getSouthEastAvailablePositions($this->getPosX(), $this->getPosY(), $this->getColor());
        foreach ($availablePos as $pos)
        {
            $available_positions[] = $pos;
        }
        $availablePos = $board->getSouthWestAvailablePositions($this->getPosX(), $this->getPosY(), $this->getColor());
        foreach ($availablePos as $pos)
        {
            $available_positions[] = $pos;
        }
        $availablePos = $board->getNorthWestAvailablePositions($this->getPosX(), $this->getPosY(), $this->getColor());
        foreach ($availablePos as $pos)
        {
            $available_positions[] = $pos;
        }

        return $available_positions;
    }

    public function toArray()
    {
        $myArray = array();
        $myArray["posX"] = $this->getPosX();
        $myArray["posY"] = $this->getPosY();
        $myArray["color"] = $this->getColor();
        $myArray["type"] = $this->getType();

        return $myArray;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}