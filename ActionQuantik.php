<?php

include "ArrayPieceQuantik.php";

class ActionQuantik
{
    protected PlateauQuantik $plateau;

    public function __construct(PlateauQuantik $plateau)
    {
        $this->plateau = $plateau;
    }

    public function getPlateau(): PlateauQuantik
    {
        return $this->plateau;
    }

    public function isValidePose(int $rowNum, int $colNum, PieceQuantik $piece): bool
    {
        if($this->plateau->getPiece($rowNum, $colNum) != PieceQuantik::initVoid()){
            return false;
        }
        $pieceRow = $this->plateau->getRow($rowNum);
        $pieceCol = $this->plateau->getCol($colNum);
        $pieceCorner = $this->plateau->getCorner(PlateauQuantik::getCornerFromCoord($rowNum, $colNum));

        $resultat = $this->isPieceValide($pieceCorner, $piece) and $this->isPieceValide($pieceCol, $piece) and $this->isPieceValide($pieceRow, $piece) ;
        return $resultat;
    }


    private static function isPieceValide(array $pieces, PieceQuantik $p): bool
    {
        switch ($p->getForme()){
            case PieceQuantik::CUBE :
                return !(in_array(PieceQuantik::initBlackCube(), $pieces) or in_array(PieceQuantik::initWhiteCube(), $pieces));
            case PieceQuantik::CONE :
                return !(in_array(PieceQuantik::initBlackCone(), $pieces) or in_array(PieceQuantik::initWhiteCone(), $pieces));
            case PieceQuantik::CYLINDRE :
                return !(in_array(PieceQuantik::initBlackCylindre(), $pieces) or in_array(PieceQuantik::initWhiteCylindre(), $pieces));
            case PieceQuantik::SPHERE :
                return !(in_array(PieceQuantik::initBlackSphere(), $pieces) or in_array(PieceQuantik::initWhiteSphere(), $pieces));
        }
    }


	public function posePiece(int $rowNum, int $colNum, PieceQuantik $piece):void {
		$this->plateau->setPiece($rowNum, $colNum, $piece);
	}



	public function __toString():String
	{
		$s = '<p>Vous avez ';
		for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
			if($this->isRowWin($i) || $this->isColWin($i) || $this->isCornerWin($i)) {
				$s = $s.'Gagné ^^</p>';
                return $s;
			}
		}
        $s = '';
        return $s;
	}

    private static function isComboWin(array $pieces): bool
    {
        echo $pieces[0];
        echo $pieces[1];
        echo $pieces[2];
        echo $pieces[3];
        /*if(in_array(PieceQuantik::initVoid(), $pieces)) {
            return false;
        }

        return (in_array(PieceQuantik::initBlackSphere(), $pieces) xor in_array(PieceQuantik::initWhiteSphere(), $pieces) and
                in_array(PieceQuantik::initBlackCube(),$pieces) xor in_array(PieceQuantik::initWhiteCube(), $pieces) and
                in_array(PieceQuantik::initBlackCone(),$pieces) xor in_array(PieceQUantik::initWhiteCone(),$pieces) and
                in_array(PieceQuantik::initBlackCylindre(),$pieces) xor in_array(PieceQuantik::initWhiteCylindre(), $pieces));
       */
        for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $tabP[$i] = false;
        }
        for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $temp = $pieces[$i]->getForme()-1;
            if($temp > -1) {
                $tabP[$i] = true;
            }
        }

        for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            if($tabP[$i] == false) {
                return false;
            }
        }
        return true;
    }

    public function isRowWin(int $numRow): bool
    {
        $row = $this->plateau->getRow($numRow);
        return $this->isComboWin($row);
    }

    public function isColWin(int $numCol): bool
    {
        $col = $this->plateau->getCol($numCol);
        return $this->isComboWin($col);

    }

    public function isCornerWin(int $dir): bool
    {
        $cor = $this->plateau->getCorner($dir);

        return $this->isComboWin($cor);
    }
}

?>
