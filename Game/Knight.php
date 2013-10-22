<?php

Class Knight extends Piece
{
    private $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "knight";
    }

    public function getAvailablePosition($board)
    {
        $available_positions = array();
        if ($this->getPosY() < 7 && $this->getPosX() < 8)
        {
            $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY() + 2);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() + 2);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() + 2);
            }
        }

        if ($this->getPosY() < 7 && $this->getPosX() > 1)
        {
            $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY() + 2);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() + 2);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() + 2);
            }
        }

        if ($this->getPosY() < 8 && $this->getPosX() > 2)
        {
            $piece = $board->getPieceFor($this->getPosX() - 2, $this->getPosY() + 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 2, $this->getPosY() + 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 2, $this->getPosY() + 1);
            }
        }

        if ($this->getPosY() < 8 && $this->getPosX() < 7)
        {
            $piece = $board->getPieceFor($this->getPosX() + 2, $this->getPosY() + 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 2, $this->getPosY() + 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 2, $this->getPosY() + 1);
            }
        }

        if ($this->getPosY() > 1 && $this->getPosX() > 2)
        {
            $piece = $board->getPieceFor($this->getPosX() - 2, $this->getPosY() - 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 2, $this->getPosY() - 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 2, $this->getPosY() - 1);
            }
        }

        if ($this->getPosY() > 2 && $this->getPosX() > 1)
        {
            $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY() - 2);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() - 2);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() - 2);
            }
        }

        if ($this->getPosY() > 2 && $this->getPosX() < 8)
        {
            $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY() - 2);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() - 2);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() - 2);
            }
        }

        if ($this->getPosY() > 1 && $this->getPosX() < 7)
        {
            $piece = $board->getPieceFor($this->getPosX() + 2, $this->getPosY() - 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 2, $this->getPosY() - 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 2, $this->getPosY() - 1);
            }
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