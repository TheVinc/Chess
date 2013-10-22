<?php

Class Board
{
    private $pieces;
    private $white_pieces;
    private $black_pieces;
    private $player_turn_color;
    private $positions_history;

    public function __construct()
    {
        $white_pieces = array();
        $black_pieces = array();
        $pieces = array();
        $positions_history = array();
    }

    public function movePiece($posX, $posY, $destX, $destY)
    {
        $piece = $this->getPieceFor($posX, $posY);
        $available_positions = array();

        if ($piece != null && is_object($piece))
        {
            $available_positions = $this->getAvailablePositionFor($posX, $posY);
        }

        if (count($available_positions) > 0)
        {
            foreach ($available_positions as $position)
            {
                if ($position->getPosX() == $destX && $position->getPosY() == $destY)
                {
                    $newPositionHistory = array();
                    $originPos = new Position($posX, $posY);
                    $originPos->setPiece($piece);

                    $destPos = new Position($destX, $destY);
                    $destPiece = $this->getPieceFor($destX, $destY);
                    if ($destPiece != null && is_object($destPiece))
                    {
                        $destPos->setPiece($destPiece);
                    }

                    $newPositionHistory[] = $originPos;
                    $newPositionHistory[] = $destPos;

                    /*****CASTLING*****/
                    if ($piece->getType() == "king")
                    {
                        if (abs($destX - $posX) > 1)
                        {
                            if ($destX > $posX)
                            {
                                $rook = $this->getPieceFor(8, $posY);
                                $originPos2 = new Position(8, $posY);
                                $originPos2->setPiece($rook);
                                $destPos2 = new Position($posX + 1, $posY);
                                $rook->setPosX($posX + 1);
                                $rook->setPosY($posY);
                            }
                            else
                            {
                                $rook = $this->getPieceFor(1, $posY);
                                $originPos2 = new Position(1, $posY);
                                $originPos2->setPiece($rook);
                                $destPos2 = new Position($posX - 1, $posY);
                                $rook->setPosX($posX - 1);
                                $rook->setPosY($posY);
                            }
                            $newPositionHistory[] = $originPos2;
                            $newPositionHistory[] = $destPos2;
                        }
                    }
                    if ($piece->getType() == "rook")
                    {
//TODO
                    }
                    /*****END CASTLING*****/
                    $this->positions_history[] = $newPositionHistory;

                    $this->deletePiece($destX, $destY);
                    $piece->setPosX($destX);
                    $piece->setPosY($destY);

                    /**** IS CHECK?? ****/
                    if ($this->isCheck($this->getPlayerTurnColor()))
                    {
                        $this->moveBack();
                        return false;
                    }
                    else
                    {
                        if ($this->getPlayerTurnColor() == "white")
                            $this->setPlayerTurnColor("black");
                        else
                            $this->setPlayerTurnColor("white");
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function moveBack()
    {
        if (count($this->positions_history) > 0)
        {
            $moveHistory = $this->positions_history[count($this->positions_history) - 1];
            $originPos = $moveHistory[0];
            $destPos = $moveHistory[1];
            $piece = $this->getPieceFor($destPos->getPosX(), $destPos->getPosY());
            $destPiece = $destPos->getPiece();

            $transform = false;
            /**** TRANSFORMATION ****/
            if ($originPos->getPosX() == $destPos->getPosX() && $originPos->getPosY() == $destPos->getPosY())
            {
                $this->deletePiece($originPos->getPosX(), $originPos->getPosY());
                $transform = true;
            }
            else
            {
                $piece->setPosX($originPos->getPosX());
                $piece->setPosY($originPos->getPosY());

                /**** CASTLING ****/
                if (count($moveHistory) > 2)
                {
                    $originPos2 = $moveHistory[2];
                    $destPos2 = $moveHistory[3];
                    $piece2 = $this->getPieceFor($destPos2->getPosX(), $destPos2->getPosY());
                    $destPiece2 = $destPos2->getPiece();
                    if ($destPiece2 != null && is_object($destPiece2))
                    {
                        $this->pieces[] = $destPiece2;
                    }
                    $piece2->setPosX($originPos2->getPosX());
                    $piece2->setPosY($originPos2->getPosY());
                }
                /**** END CASTLING ****/

                if ($this->getPlayerTurnColor() == "white")
                    $this->setPlayerTurnColor("black");
                else
                    $this->setPlayerTurnColor("white");
            }
            /**** END TRANSFORMATION ****/

            if ($destPiece != null && is_object($destPiece))
            {
                $this->pieces[] = $destPiece;
            }

            $new_positions_history = array();
            for ($i = 0; $i < count($this->positions_history) - 1; $i++)
            {
                $new_positions_history[] = $this->positions_history[$i];
            }
            $this->positions_history = $new_positions_history;

            if ($transform)
            {
                $this->moveBack();
            }
        }
    }

    public function deletePiece($x, $y)
    {
        $pieces = array();
        foreach ($this->pieces as $piece)
        {
            if ($piece->getPosX() != $x || $piece->getPosY() != $y)
            {
                $pieces[] = $piece;
            }
        }
        $this->pieces = $pieces;
    }

    public function getPiecesThatCanTransform($color)
    {
        $piecesTransformable = array();
        foreach ($this->pieces as $piece)
        {
            if ($piece->getType() == "pawn" && $piece->getColor() == $color)
            {
                if (($color == "white" && $piece->getPosY() == 8) || ($color == "black" && $piece->getPosY() == 1))
                {
                    $piecesTransformable[] = $piece;
                }
            }
        }

        return $piecesTransformable;
    }

    public function transformPiece($posX, $posY, $type, $color)
    {
        $piece = $this->getPieceFor($posX, $posY);

        if ($piece != null && is_object($piece))
        {
            if ($type == "queen")
            {
                $newPiece = new Queen();
                $newPiece->setPosX($posX);
                $newPiece->setPosY($posY);
                $newPiece->setColor($color);
            }
            if ($type == "knight")
            {
                $newPiece = new Knight();
                $newPiece->setPosX($posX);
                $newPiece->setPosY($posY);
                $newPiece->setColor($color);
            }

            $this->deletePiece($posX, $posY);
            $this->pieces[] = $newPiece;

            //Adding transformation to history
            $newPositionHistory = array();
            $originPos = new Position($posX, $posY);
            $originPos->setPiece($newPiece);

            $destPos = new Position($posX, $posY);
            $destPos->setPiece($piece);

            $newPositionHistory[] = $originPos;
            $newPositionHistory[] = $destPos;
            $this->positions_history[] = $newPositionHistory;

            return true;
        }

        return false;
    }

    public function getPieceFor($posX, $posY)
    {
        foreach ($this->pieces as $piece)
        {
            if ($piece->getPosX() == $posX && $piece->getPosY() == $posY)
            {
                return $piece;
            }
        }

        return null;
    }

    public function getPieceInPassingFor($posX, $posY, $playerColor)
    {
        foreach ($this->pieces as $piece)
        {
            if ($piece->getType() == "pawn" && $piece->getColor() != $playerColor && $piece->getPosX() == $posX && $piece->getPosY() == $posY)
            {
                if (count($this->positions_history) > 0)
                {
                    $moveHistory = $this->positions_history[count($this->positions_history) - 1];
                    $originPos = $moveHistory[0];
                    $destPos = $moveHistory[1];
                    $movingPiece = $originPos->getPiece();
                    if ($posY == 5 && $playerColor == "white")
                    {
                        if ($movingPiece->getColor() != $playerColor
                            && $originPos->getPosX() == $posX
                            && $originPos->getPosY() == 7
                            && $destPos->getPosX() == $posX
                            && $destPos->getPosY() == 5
                        )
                        {
                            return $piece;
                        }
                    }
                    if ($posY == 4 && $playerColor == "black")
                    {
                        if ($movingPiece->getColor() != $playerColor
                            && $originPos->getPosX() == $posX
                            && $originPos->getPosY() == 2
                            && $destPos->getPosX() == $posX
                            && $destPos->getPosY() == 4
                        )
                        {
                            return $piece;
                        }
                    }
                }
            }
        }

        return null;
    }

    public function hasAlreadyMove($piece)
    {
        if ($this->positions_history != null)
        {
            foreach ($this->positions_history as $history)
            {
                if ($history[0]->getPiece() == $piece)
                    return true;
            }
        }
        return false;
    }

    public function isCheck($color)
    {
        //TODO
        return false;
    }

    public function getRooksOfColor($color)
    {
        $rooks = array();
        foreach ($this->pieces as $piece)
        {
            if ($piece->getColor() == $color && $piece->getType() == "rook")
            {
                if ($piece->getColor() == "white")
                {
                    if ($piece->getPosY() == 1 && !$this->hasAlreadyMove($piece))
                        $rooks[] = $piece;
                }
                else
                {
                    if ($piece->getPosY() == 8 && !$this->hasAlreadyMove($piece))
                        $rooks[] = $piece;
                }
            }
        }

        return $rooks;
    }

    public function getKingOfColor($color)
    {
        foreach ($this->pieces as $piece)
        {
            if ($piece->getColor() == $color && $piece->getType() == "king")
                return $piece;
        }
    }

    public function getCastlingPositions($piece)
    {
        $available_positions = array();
        if (!$this->hasAlreadyMove($piece))
        {
            if ($piece->getType() == "king")
            {
                $rooks = $this->getRooksOfColor($piece->getColor());
                if (count($rooks) > 0)
                {
                    if (($piece->getPosY() == 1
                        && $piece->getColor() == "white")
                        || ($piece->getPosY() == 8
                            && $piece->getColor() == "black")
                    )
                    {
                        $kingPosX = $piece->getPosX();
                        foreach ($rooks as $rook)
                        {
                            $rookPosX = $rook->getPosX();
                            $good = true;
                            if ($rookPosX < $kingPosX)
                            {
                                for ($i = $rookPosX + 1; $i < $kingPosX; $i++)
                                {
                                    if ($this->getPieceFor($i, $piece->getPosY()) != null)
                                    {
                                        $good = false;
                                        break;
                                    }
                                }
                                if ($good)
                                {
                                    $available_positions[] = new Position($kingPosX - 2, $piece->getPosY());
                                }
                            }
                            else
                            {
                                for ($i = $kingPosX + 1; $i < $rookPosX; $i++)
                                {
                                    if ($this->getPieceFor($i, $piece->getPosY()) != null)
                                    {
                                        $good = false;
                                        break;
                                    }
                                }
                                if ($good)
                                {
                                    $available_positions[] = new Position($kingPosX + 2, $piece->getPosY());
                                }
                            }
                        }
                    }
                }
            }
            if ($piece->getType() == "rook")
            {
                //TODO
//                $king = $this->getKingOfColor($piece->getColor());
            }
        }

        return $available_positions;
    }

    public function getAvailablePositionFor($posX, $posY)
    {
        $piece = $this->getPieceFor($posX, $posY);
        $available_positions = array();

        if ($piece != null && is_object($piece))
        {
            if (!$this->isCheck($piece->getColor()))
            {
                $available_positions = $piece->getAvailablePosition($this);
                if ($piece->getType() == "king" || $piece->getType() == "rook")
                {
                    $castling_available_positions = $this->getCastlingPositions($piece);
                    foreach ($castling_available_positions as $castling_position)
                    {
                        $available_positions[] = $castling_position;
                    }
                }
            }
            else
            {
                if ($piece->getType() == "king")
                    $available_positions = $piece->getAvailablePosition($this);
            }
        }

        return $available_positions;
    }

    public function getNorthAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        for ($y = $posY + 1; $y <= 8; $y++)
        {
            $piece = $this->getPieceFor($posX, $y);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($posX, $y);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($posX, $y);
                $availablePos[] = $position;
            }
        }

        return $availablePos;
    }

    public function getSouthAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        for ($y = $posY - 1; $y > 0; $y--)
        {
            $piece = $this->getPieceFor($posX, $y);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($posX, $y);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($posX, $y);
                $availablePos[] = $position;
            }
        }

        return $availablePos;
    }

    public function getWestAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        for ($x = $posX - 1; $x > 0; $x--)
        {
            $piece = $this->getPieceFor($x, $posY);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($x, $posY);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($x, $posY);
                $availablePos[] = $position;
            }
        }

        return $availablePos;
    }

    public function getEastAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        for ($x = $posX + 1; $x <= 8; $x++)
        {
            $piece = $this->getPieceFor($x, $posY);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($x, $posY);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($x, $posY);
                $availablePos[] = $position;
            }
        }

        return $availablePos;
    }


    public function getNorthEastAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        $x = $posX + 1;
        $y = $posY + 1;
        while ($x <= 8 && $y <= 8)
        {
            $piece = $this->getPieceFor($x, $y);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($x, $y);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($x, $y);
                $availablePos[] = $position;
            }
            $x++;
            $y++;
        }

        return $availablePos;
    }

    public function getSouthEastAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        $x = $posX + 1;
        $y = $posY - 1;
        while ($x <= 8 && $y > 0)
        {
            $piece = $this->getPieceFor($x, $y);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($x, $y);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($x, $y);
                $availablePos[] = $position;
            }
            $x++;
            $y--;
        }

        return $availablePos;
    }

    public function getSouthWestAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        $x = $posX - 1;
        $y = $posY - 1;
        while ($x > 0 && $y > 0)
        {
            $piece = $this->getPieceFor($x, $y);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($x, $y);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($x, $y);
                $availablePos[] = $position;
            }
            $x--;
            $y--;
        }

        return $availablePos;
    }

    public function getNorthWestAvailablePositions($posX, $posY, $color)
    {
        $availablePos = array();
        $x = $posX - 1;
        $y = $posY + 1;
        while ($x > 0 && $y <= 8)
        {
            $piece = $this->getPieceFor($x, $y);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $color)
                {
                    $position = new Position($x, $y);
                    $availablePos[] = $position;
                }
                break;
            }
            else
            {
                $position = new Position($x, $y);
                $availablePos[] = $position;
            }
            $x--;
            $y++;
        }

        return $availablePos;
    }

    public function showBoard()
    {
        echo "</br> *************** Showing White Pieces : *************** </br>";
        foreach ($this->white_pieces as $wp)
        {
            $wp->showInfos();
        }

        echo "</br> *************** Showing Black Pieces : *************** </br>";
        foreach ($this->black_pieces as $bp)
        {
            $bp->showInfos();
        }
    }

    public function initialize()
    {
        $this->player_turn_color = "white";
        /******* WHITE PIECES INIT BEGIN *******/
        //White pawn
        for ($i = 1; $i <= 8; $i++)
        {
            $wp = new Pawn();
            $wp->setPosX($i);
            $wp->setPosY(2);
            $wp->setColor("white");
            $this->white_pieces[] = $wp;
            $this->pieces[] = $wp;
        }

        //White Rook
        $wr1 = new Rook();
        $wr1->setPosX(1);
        $wr1->setPosY(1);
        $wr1->setColor("white");
        $this->white_pieces[] = $wr1;
        $this->pieces[] = $wr1;
        $wr2 = new Rook();
        $wr2->setPosX(8);
        $wr2->setPosY(1);
        $wr2->setColor("white");
        $this->white_pieces[] = $wr2;
        $this->pieces[] = $wr2;

        //White Knight
        $wk1 = new Knight();
        $wk1->setPosX(2);
        $wk1->setPosY(1);
        $wk1->setColor("white");
        $this->white_pieces[] = $wk1;
        $this->pieces[] = $wk1;
        $wk2 = new Knight();
        $wk2->setPosX(7);
        $wk2->setPosY(1);
        $wk2->setColor("white");
        $this->white_pieces[] = $wk2;
        $this->pieces[] = $wk2;

        //White Bishop
        $wb1 = new Bishop();
        $wb1->setPosX(3);
        $wb1->setPosY(1);
        $wb1->setColor("white");
        $this->white_pieces[] = $wb1;
        $this->pieces[] = $wb1;
        $wb2 = new Bishop();
        $wb2->setPosX(6);
        $wb2->setPosY(1);
        $wb2->setColor("white");
        $this->white_pieces[] = $wb2;
        $this->pieces[] = $wb2;

        //White Queen
        $wq = new Queen();
        $wq->setPosX(4);
        $wq->setPosY(1);
        $wq->setColor("white");
        $this->white_pieces[] = $wq;
        $this->pieces[] = $wq;

        //White King
        $wking = new King();
        $wking->setPosX(5);
        $wking->setPosY(1);
        $wking->setColor("white");
        $this->white_pieces[] = $wking;
        $this->pieces[] = $wking;


        /******* WHITE PIECES INIT END *******/

        /******* BLACK PIECES INIT BEGIN *******/
        //Black Rook
        $br1 = new Rook();
        $br1->setPosX(1);
        $br1->setPosY(8);
        $br1->setColor("black");
        $this->black_pieces[] = $br1;
        $this->pieces[] = $br1;
        $br2 = new Rook();
        $br2->setPosX(8);
        $br2->setPosY(8);
        $br2->setColor("black");
        $this->black_pieces[] = $br2;
        $this->pieces[] = $br2;

        //Black Knight
        $bk1 = new Knight();
        $bk1->setPosX(2);
        $bk1->setPosY(8);
        $bk1->setColor("black");
        $this->black_pieces[] = $bk1;
        $this->pieces[] = $bk1;
        $bk2 = new Knight();
        $bk2->setPosX(7);
        $bk2->setPosY(8);
        $bk2->setColor("black");
        $this->black_pieces[] = $bk2;
        $this->pieces[] = $bk2;

        //Black Bishop
        $bb1 = new Bishop();
        $bb1->setPosX(3);
        $bb1->setPosY(8);
        $bb1->setColor("black");
        $this->black_pieces[] = $bb1;
        $this->pieces[] = $bb1;
        $bb2 = new Bishop();
        $bb2->setPosX(6);
        $bb2->setPosY(8);
        $bb2->setColor("black");
        $this->black_pieces[] = $bb2;
        $this->pieces[] = $bb2;

        //Black Queen
        $bq = new Queen();
        $bq->setPosX(4);
        $bq->setPosY(8);
        $bq->setColor("black");
        $this->black_pieces[] = $bq;
        $this->pieces[] = $bq;

        //Black King
        $bking = new King();
        $bking->setPosX(5);
        $bking->setPosY(8);
        $bking->setColor("black");
        $this->black_pieces[] = $bking;
        $this->pieces[] = $bking;

        //Black pawn
        for ($i = 1; $i <= 8; $i++)
        {
            $bp = new Pawn();
            $bp->setPosX($i);
            $bp->setPosY(7);
            $bp->setColor("black");
            $this->black_pieces[] = $bp;
            $this->pieces[] = $bp;
        }

        /******* BLACK PIECES INIT END *******/
    }

    public function setBlackPieces($black_pieces)
    {
        $this->black_pieces = $black_pieces;
    }

    public function getBlackPieces()
    {
        return $this->black_pieces;
    }

    public function setWhitePieces($white_pieces)
    {
        $this->white_pieces = $white_pieces;
    }

    public function getWhitePieces()
    {
        return $this->white_pieces;
    }

    public function setPieces($pieces)
    {
        $this->pieces = $pieces;
    }

    public function getPieces()
    {
        return $this->pieces;
    }

    public function setPlayerTurnColor($player_turn_color)
    {
        $this->player_turn_color = $player_turn_color;
    }

    public function getPlayerTurnColor()
    {
        return $this->player_turn_color;
    }

    public function setPositionsHistory($positions_history)
    {
        $this->positions_history = $positions_history;
    }

    public function getPositionsHistory()
    {
        return $this->positions_history;
    }
}