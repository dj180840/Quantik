<form action="FormulaireQuantikTwo.php" method ="get">
	<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include "ActionQuantik.php";
	session_start();
	$tableau = $_SESSION['tableau'];
	$tB = $_SESSION['ArrayBlanc'];
	if ($_GET) {
		if (isset($_GET['PosPlateau'])) {
			$PosPlateau = $_GET['PosPlateau'];
			$posx = (int)$PosPlateau[0];
			$posy = (int)$PosPlateau[2];
			echo 'posx : '+$posx;
			echo 'posy : '+$posy;
		} elseif (isset($_GET['PiecesDispo'])) {
			$PiecesDispo = $_GET['PiecesDispo'];
		} elseif (isset($_GET['trio'])) {
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
				}
			} else {								//Noir
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
			if(	$action->isValidePose($posx, $posy, $Piece)){
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
			if($a->getPieceQuantik($i)->getCouleur() == 0) {			//Blanc
				if($a->getPieceQuantik($i)->getForme() == 1) {			//Cube
					$res = $res."0 1";
				} else if($a->getPieceQuantik($i)->getForme() == 2) {	//Cone
					$res = $res."0 2";
				} else if($a->getPieceQuantik($i)->getForme() == 3) {	//Cylindre
					$res = $res."0 3";
				} else if($a->getPieceQuantik($i)->getForme() == 4) {	//Sphere
					$res = $res."0 4";
				}
			} else {													//Noir
				if($a->getPieceQuantik($i)->getForme() == 1) {			//Cube
					$res = $res."1 1";
				} else if($a->getPieceQuantik($i)->getForme() == 2) {	//Cone
					$res = $res."1 2";
				} else if($a->getPieceQuantik($i)->getForme() == 3) {	//Cylindre
					$res = $res."1 3";
				} else if($a->getPieceQuantik($i)->getForme() == 4) {	//Sphere
					$res = $res."1 4";
				}
			}
			$res = $res.$a->getPieceQuantik($i);
			$res = $res."' enabled >";
			$res = $res.$a->getPieceQuantik($i);
			$res = $res."</button>";
		}
		return $res;
	}

	function getFormSelectionPiece(ArrayPieceQuantik $a):string {
		$res = "";
		for($i = 0; $i < $a->getTaille(); $i++) {
			$res = $res."<button type='submit' name='PiecesDispo' value='";
			if($a->getPieceQuantik($i)->getCouleur() == 0) {			//Blanc
				if($a->getPieceQuantik($i)->getForme() == 1) {			//Cube
					$res = $res."0 1";
				} else if($a->getPieceQuantik($i)->getForme() == 2) {	//Cone
					$res = $res."0 2";
				} else if($a->getPieceQuantik($i)->getForme() == 3) {	//Cylindre
					$res = $res."0 3";
				} else if($a->getPieceQuantik($i)->getForme() == 4) {	//Sphere
					$res = $res."0 4";
				}
			} else {													//Noir
				if($a->getPieceQuantik($i)->getForme() == 1) {			//Cube
					$res = $res."1 1";
				} else if($a->getPieceQuantik($i)->getForme() == 2) {	//Cone
					$res = $res."1 2";
				} else if($a->getPieceQuantik($i)->getForme() == 3) {	//Cylindre
					$res = $res."1 3";
				} else if($a->getPieceQuantik($i)->getForme() == 4) {	//Sphere
					$res = $res."1 4";
				}
			}
			$res = $res.$a->getPieceQuantik($i);
			$res = $res."' disabled >";
			$res = $res.$a->getPieceQuantik($i);
			$res = $res."</button>";
		}
		return $res;
	}

	function getDivPlateauQuantik(PlateauQuantik $pl):string {
		for($i = 0; $i < 4; $i++) {
			$array[$i] = $pl->getRow($i);
		}
		$x = 0;
		$y = 0;
		$piece = "0 0";

			$s = '<p><table>';
			foreach($array as $value =>$v) {
				$s = $s.'<tr>';
				foreach ($v as $key => $val) {
					if($val == '<p>Vide </p>') {
						$s = $s."<td>"."<button type='submit' name='trio' value='".$piece." ".$x." ".$y."' enabled >".$val."</button>"."</td>";
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

	function getFormPlateauQuantik(PlateauQuantik $pl, PieceQuantik $p):string {
		$pl->setPiece($posx, $posy, $p);
		for($i = 0; $i < 4; $i++) {
			$array[$i] = $p->getRow($i);
		}
		$x = 0;
		$y = 0;

		$s = '<p><table>';
		foreach($array as $value =>$v) {
			$s = $s.'<tr>';
			foreach ($v as $key => $val) {
				if($val == '<p>Vide </p>') {
					$s = $s."<td>"."<button type='submit' name='PosPlateau' value='".$x." ".$y."' enabled >".$val."</button>"."</td>";
				} else {
					$s = $s."<td>"."<button type='submit' name='PosPlateau' disabled >".$val."</button>"."</td>";
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
		echo 'PosPlateau';
		echo $tableau;
		$tB = getDivPiecesDisponibles($tB);
		echo $tB;
	} else if(isset($String)) {
		echo 'String';
		echo $tableau;
		$tB = getDivPiecesDisponibles($tB);
		echo $tB;
	} else {
		echo 'Initialisation de la partie</br>';
		$tableau = new PlateauQuantik();
		echo $tableau;
		/*$afficheTab = getDivPlateauQuantik($tableau);
		echo $afficheTab;*/

		$tB = new ArrayPieceQuantik();
		$tB = $tB->initPiecesBlanches();
		$_SESSION['ArrayBlanc'] = $tB;
		$affichepiecesBlanches = getDivPiecesDisponibles($tB);
		echo $affichepiecesBlanches;
	}

	/*$cubeBlanc = PieceQuantik::initWhiteCube();
	$cubeNoir = PieceQuantik::initBlackCube();
	$coneBlanc = PieceQuantik::initWhiteCone();
	$coneNoir = PieceQuantik::initBlackCone();
	$cylindreBlanc= PieceQuantik::initWhiteCylindre();
	$cylindreNoir = PieceQuantik::initBlackCylindre();
	$sphereBlanc = PieceQuantik::initWhiteSphere();
	$sphereNoir = PieceQuantik::initBlackSphere();
	$cubeBlanc1 = PieceQuantik::initWhiteCube();
	$cubeNoir1 = PieceQuantik::initBlackCube();
	$coneBlanc1 = PieceQuantik::initWhiteCone();
	$coneNoir1 = PieceQuantik::initBlackCone();
	$cylindreBlanc1= PieceQuantik::initWhiteCylindre();
	$cylindreNoir1 = PieceQuantik::initBlackCylindre();
	$sphereBlanc1 = PieceQuantik::initWhiteSphere();
	$sphereNoir1 = PieceQuantik::initBlackSphere();

	$tB = new ArrayPieceQuantik();
	$tB = $tB->initPiecesBlanches();
	$res  = getDivPiecesDisponibles($tB);
	echo $res;
	echo "</br>";

	$tN = new ArrayPieceQuantik();
	$tN = $tN->initPiecesNoires();
	$res  = getDivPiecesDisponibles($tN);
	echo $res;

	$tableau = new PlateauQuantik();
	$tableau->setPiece(0, 0, $cubeBlanc);
	$res = getDivPlateauQuantik($tableau);
	echo $res;*/

	$_SESSION['ArrayBlanc'] = $tB;
	$_SESSION['tableau'] = $tableau;
	echo '</form>';
	echo getFinHTML();
	?>
	<?php


	?>