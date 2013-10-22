<?php

Class Pawn extends Piece
{
    private $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "pawn";
    }

    public function getAvailablePosition($board)
    {
        $available_positions = array();

        if ($this->getColor() == "white")
        {
            if ($this->getPosY() >= 2 && $this->getPosY() < 8)
            {
                if ($board->getPieceFor($this->getPosX(), $this->getPosY() + 1) == null)
                {
                    $available_positions[] = new Position($this->getPosX(), $this->getPosY() + 1);
                }
                if ($this->getPosX() < 8)
                {
                    $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY() + 1);
                    if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                    {
                        $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() + 1);
                    }
                }
                if ($this->getPosX() > 1)
                {
                    $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY() + 1);
                    if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                    {
                        $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() + 1);
                    }
                }
            }
            if ($this->getPosY() == 2)
            {
                if ($board->getPieceFor($this->getPosX(), $this->getPosY() + 2) == null)
                {
                    $available_positions[] = new Position($this->getPosX(), $this->getPosY() + 2);
                }
            }
            if ($this->getPosY() == 5)
            {
                //EN PASSANT
                if ($this->getPosX() > 1)
                {
                    $piece = $board->getPieceInPassingFor($this->getPosX() - 1, $this->getPosY(), $this->getColor());
                    if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                    {
                        $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY());
                    }
                }
                if ($this->getPosX() < 8)
                {
                    $piece = $board->getPieceInPassingFor($this->getPosX() + 1, $this->getPosY(), $this->getColor());
                    if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                    {
                        $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY());
                    }
                }
            }
        }
        else
        {
            if ($this->getPosY() <= 7 && $this->getPosY() > 1)
            {
                if ($board->getPieceFor($this->getPosX(), $this->getPosY() - 1) == null)
                {
                    $available_positions[] = new Position($this->getPosX(), $this->getPosY() - 1);
                }
                $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY() - 1);
                if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                {
                    $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() - 1);
                }
                $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY() - 1);
                if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                {
                    $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() - 1);
                }
            }
            if ($this->getPosY() == 7)
            {
                if ($board->getPieceFor($this->getPosX(), $this->getPosY() - 2) == null)
                {
                    $available_positions[] = new Position($this->getPosX(), $this->getPosY() - 2);
                }
            }
            if ($this->getPosY() == 4)
            {
                //EN PASSANT
                if ($this->getPosX() > 1)
                {
                    $piece = $board->getPieceInPassingFor($this->getPosX() - 1, $this->getPosY(), $this->getColor());
                    if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                    {
                        $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY());
                    }
                }
                if ($this->getPosX() < 8)
                {
                    $piece = $board->getPieceInPassingFor($this->getPosX() + 1, $this->getPosY(), $this->getColor());
                    if ($piece != null && is_object($piece) && $piece->getColor() != $this->getColor())
                    {
                        $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY());
                    }
                }
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