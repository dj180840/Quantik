<a href="FormulaireQuantikOne.php">Restart</a>
<form action="FormulaireQuantikThree.php" method ="get">
	<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include "ActionQuantik.php";
	session_start();
	if(!isset($_SESSION['tableau'])) {
		echo 'issetTableau';
		$tableau = new PlateauQuantik();
	} else {
		$tableau = unserialize($_SESSION['tableau']);
	}
	if (!isset($_SESSION['ArrayBlanc'])) {
		echo 'issetArray Blanc';
		$tB = new ArrayPieceQuantik();
		$tB = $tB->initPiecesBlanches();
	} else {
		$tB = unserialize($_SESSION['ArrayBlanc']);
	}
	if (!isset($_SESSION['ArrayNoir'])) {
		echo 'issetArray Noir';
		$tN = new ArrayPieceQuantik();
		$tN = $tN->initPiecesNoires();
	} else {
		$tN = unserialize($_SESSION['ArrayNoir']);
	}
	if ($_GET) {
		if (isset($_GET['PosPlateau'])) {
			$PosPlateau = $_GET['PosPlateau'];
			$posx = (int)$PosPlateau[0];
			$posy = (int)$PosPlateau[2];
			echo 'posx : '+$posx;
			echo 'posy : '+$posy;
		} elseif (isset($_GET['PiecesDispo'])) {
			$PiecePosition = $_GET['PiecesDispo'];
			$PiecePosition = intval($PiecePosition);
			$PieceNom = $tB->getPieceQuantik($PiecePosition);
			/*if($PiecePosition[0] == 0) {				//Blanc
				if($PiecePosition[2] == 1) {			//Cube
					$PieceNom = PieceQuantik::initWhiteCube();
				} else if($PiecePosition[2] == 2) {	//Cone
					$PieceNom = PieceQuantik::initWhiteCone();
				} else if($PiecePosition[2] == 3) {	//Cylindre
					$PieceNom = PieceQuantik::initWhiteCylindre();
				} else if($PiecePosition[2] == 4) {	//Sphere
					$PieceNom = PieceQuantik::initWhiteSphere();
				}
			} else {								//Noir
				if($PiecePosition[0] == 1) {			//Cube
					$PieceNom = PieceQuantik::initBlackSphere();
				} else if($PiecePosition[2] == 2) {	//Cone
					$PieceNom = PieceQuantik::initBlackSphere();
				} else if($PiecePosition[2] == 3) {	//Cylindre
					$PieceNom = PieceQuantik::initBlackSphere();
				} else if($PiecePosition[2] == 4) {	//Sphere
					$PieceNom = PieceQuantik::initBlackSphere();
				}
			}*/
			echo 'Selectionner la case où ajouter la piece :'.$PieceNom;
		}  else if (isset($_GET['trio'])) {
			$String = $_GET['trio'];
			$Piece = PieceQuantik::initVoid();
			if($String[0] == 0) {				//Blanc
				if($String[2] == 1) {			//Cube
					$Piece = PieceQuantik::initWhiteCube();
				} else if($String[2] == 2) {	//Cone
					$Piece = PieceQuantik::initWhiteCone();
				} else if($String[2] == 3) {	//Cylindre
					$Piece = PieceQuantik::initWhiteCylindre();
				} else if($String[2] == 4) {	//Sphere
					$Piece = PieceQuantik::initWhiteSphere();
				} else if($String[2] == 0) {	//Vide
					$Piece = PieceQuantik::initVoid();
				}
			} else {							//Noir
				if($String[0] == 1) {			//Cube
					$Piece = PieceQuantik::initBlackSphere();
				} else if($String[2] == 2) {	//Cone
					$Piece = PieceQuantik::initBlackSphere();
				} else if($String[2] == 3) {	//Cylindre
					$Piece = PieceQuantik::initBlackSphere();
				} else if($String[2] == 4) {	//Sphere
					$Piece = PieceQuantik::initBlackSphere();
				}
			}
			$posx = (int)$String[4];
			$posy = (int)$String[6];
			echo 'posx : '+$posx;
			echo 'posy : '+$posy;
			echo $Piece;
			$action = new ActionQuantik($tableau);
			if($Piece != PieceQuantik::initVoid() && $action->isValidePose($posx, $posy, $Piece)){
				$action->posePiece($posx, $posy, $Piece);
				echo 'piece ajoutée';
			} else {
				$erreurPlacement;
			}
		}
	}

	function getDebutHTML():String{
		$s = "<!DOCTYPE html> <html lang=\"fr\">
		<head>
		<title>Quantik</title>
		<meta charset=\"utf-8\" />
		<link rel=\"stylesheet\" href=\"style.css\" />
		</head>
		<body>";
		return $s;
	}

	function getFinHTML():String{
		$s = "</body>
		</html>";
		return $s;
	}

	function getDivPiecesDisponibles(ArrayPieceQuantik $a):string {
		$res = "";
		for($i = 0; $i < $a->getTaille(); $i++) {
			$res = $res."<button type='submit' name='PiecesDispo' value='";
			$res = $res.$a->getPieceQuantik($i)."' enabled >";
			$res = $res.$a->getPieceQuantik($i);
			$res = $res."</button>";
		}
		return $res;
	}

	function getFormSelectionPiece(ArrayPieceQuantik $a):string {
		$res = "";
		for($i = 0; $i < $a->getTaille(); $i++) {
			$res = $res."<button type='submit' name='PiecesDispo' value='";
			$res = $res.$a->getPieceQuantik($i)."' disabled >";
			$res = $res.$a->getPieceQuantik($i);
			$res = $res."</button>";
		}
		return $res;
	}

	function getDivPlateauQuantik(PlateauQuantik $p):string {
		for($i = 0; $i < 4; $i++) {
			$array[$i] = $pl->getRow($i);
		}
		$x = 0;
		$y = 0;
		$piece = "";

		if($a->getPieceQuantik($i)->getCouleur() == 0) {			//Blanc
				if($a->getPieceQuantik($i)->getForme() == 1) {			//Cube
					$piece = $piece."0 1";
				} else if($a->getPieceQuantik($i)->getForme() == 2) {	//Cone
					$piece = $piece."0 2";
				} else if($a->getPieceQuantik($i)->getForme() == 3) {	//Cylindre
					$piece = $piece."0 3";
				} else if($a->getPieceQuantik($i)->getForme() == 4) {	//Sphere
					$piece = $piece."0 4";
				}
			} else {													//Noir
				if($a->getPieceQuantik($i)->getForme() == 1) {			//Cube
					$piece = $piece."1 1";
				} else if($a->getPieceQuantik($i)->getForme() == 2) {	//Cone
					$piece = $piece."1 2";
				} else if($a->getPieceQuantik($i)->getForme() == 3) {	//Cylindre
					$piece = $piece."1 3";
				} else if($a->getPieceQuantik($i)->getForme() == 4) {	//Sphere
					$piece = $piece."1 4";
				}
			}

			$s = '<p><table>';
			foreach($array as $value =>$v) {
				$s = $s.'<tr>';
				foreach ($v as $key => $val) {
					if($val == '<p>Vide </p>') {
						$s = $s."<td>"."<button type='submit' name='trio' value='".$piece." ".$x." ".$y."' enabled >".$val."</button>"."</td>";
					} else {
						$s = $s."<td>"."<button type='submit' name='trio' disabled >".$x." ".$y."</button>"."</td>";
					}
					$y++;			
				}
				$x++;
				$y = 0;
				$s = $s."</tr>";
			}
			$s = $s.'</table></p>';
			return $s;
		}

		function getFormPlateauQuantik(PlateauQuantik $pl, PieceQuantik $p):string {
			for($i = 0; $i < 4; $i++) {
				$array[$i] = $pl->getRow($i);
			}
			$x = 0;
			$y = 0;
			global $PiecePosition;
			/*$piece = "";

		if($p->getCouleur() == 0) {					//Blanc
				if($p->getForme() == 1) {			//Cube
					$piece = $piece."0 1";
				} else if($p->getForme() == 2) {	//Cone
					$piece = $piece."0 2";
				} else if($p->getForme() == 3) {	//Cylindre
					$piece = $piece."0 3";
				} else if($p->getForme() == 4) {	//Sphere
					$piece = $piece."0 4";
				}
		} else {									//Noir
				if($p->getForme() == 1) {			//Cube
					$piece = $piece."1 1";
				} else if($p->getForme() == 2) {	//Cone
					$piece = $piece."1 2";
				} else if($p->getForme() == 3) {	//Cylindre
					$piece = $piece."1 3";
				} else if($p->getForme() == 4) {	//Sphere
					$piece = $piece."1 4";
				}
			}*/

			$s = '<p><table>';
			foreach($array as $value =>$v) {
				$s = $s.'<tr>';
				foreach ($v as $key => $val) {
					if($val == '<p>Vide </p>') {
						$s = $s."<td>"."<button type='submit' name='trio' value='".$PiecePosition." ".$x." ".$y."' enabled >".$val."</button>"."</td>";
					} else {
						$s = $s."<td>"."<button type='submit' name='trio' disabled >".$val."</button>"."</td>";
					}
					$y++;			
				}
				$x++;
				$y = 0;
				$s = $s."</tr>";
			}
			$s = $s.'</table></p>';
			return $s;
		}

		echo getDebutHTML();

		if(isset($PosPlateau)) {
			$tB = new ArrayPieceQuantik();
			$tB = $tB->initPiecesBlanches();
			$affichepiecesBlanches = getDivPiecesDisponibles($tB);
			echo $affichepiecesBlanches;
			echo "</br>";

			$tN = new ArrayPieceQuantik();
			$tN = $tN->initPiecesNoires();
			$affichepiecesNoires = getDivPiecesDisponibles($tN);
			echo $affichepiecesNoires;
		} else if(isset($PiecePosition)) {
			$affichetab = getFormPlateauQuantik($tableau, $PieceNom);
			echo $affichetab;
		} else if(isset($String)) {
			$affichetab = getFormPlateauQuantik($tableau, $Piece);
			echo $affichetab;
		}

		$_SESSION['tableau'] = serialize($tableau);
		$_SESSION['ArrayBlanc'] = serialize($tB);
		$_SESSION['ArrayNoir'] = serialize($tN);
		echo '</form>';
		echo getFinHTML();
		?>