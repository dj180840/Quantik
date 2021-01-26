<?php

require_once "PieceQuantik.php";
require_once "PlateauQuantik.php";
require_once "ActionQuantik.php";
require_once "QuantikException.php";
require_once "QuantikUIGenerator.php";

session_start();

if (isset($_GET['reset'])) { //pratique pour réinitialiser une partie à la main
    unset($_SESSION['etat']);
    unset($_SESSION['lesBlancs']);
    unset($_SESSION['lesNoirs']);
    unset($_SESSION['couleurActive']);
    unset($_SESSION['plateau']);
    unset($_SESSION['message']);
}

if (empty($_SESSION)) { // initialisation des variables de session
    $_SESSION['lesBlancs'] = ArrayPieceQuantik::initPiecesBlanches();
    $_SESSION['lesNoirs'] = ArrayPieceQuantik::initPiecesNoires();
    $_SESSION['plateau'] = new PlateauQuantik();
    $_SESSION['etat'] = 'choixPiece';
    $_SESSION['couleurActive'] = PieceQuantik::WHITE;
    $_SESSION['message'] = "";
    echo '<meta http-equiv="refresh" content="0;URL=quantik.php?etat=choisirPiece" />';
}

$pageHTML = "";


$tabPiece[0] = $_SESSION['lesBlancs'];
$tabPiece[1] = $_SESSION['lesNoirs'];
$aq = new ActionQuantik($_SESSION['plateau']);
echo "<a href=\"restart.php\">Restart</a>";

// on réalise les actions correspondant à l'action en cours :
    try {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'choisirPiece':
                    $posPiece = $_GET['piece'];
                    $_SESSION['etat'] = 'posePiece';
                    echo "<meta http-equiv='refresh' content='0;URL=quantik.php?piece=".$posPiece;
                    echo "' />";
                    break;
                case 'poserPiece':

                    /* TODO : action pouvant conduire à 2 états selon le résultat : posePiece ou victoire */
                    break;
                case 'annulerChoix':
                    /* TODO */
                    break;
                default:
                    throw new QuantikException("Action non valide");
            }
        }
    } catch (QuantikException $exception) {
            $_SESSION['etat'] = 'bug';
            $_SESSION['message'] = $exception->__toString();
        }

switch($_SESSION['etat']) {
    case 'choixPiece':
        $pageHTML .= QuantikUIGenerator::getPageSelectionPiece($tabPiece, $_SESSION['couleurActive'], $_SESSION['plateau']);
        break;
    case 'posePiece':
        if(isset($_GET['piece'])) {
            $pageHTML .= QuantikUIGenerator::getPagePosePiece($tabPiece, $_SESSION['couleurActive'], (int)$_GET['piece'], $_SESSION['plateau']);
        }elseif(isset($_GET['piecePosition'])){
            echo 'piecePos';
            if($_SESSION['couleurActive'] == 0){
                $Piece = $tabPiece[0]->getPieceQuantik($_GET['piecePosition'][0]);
                $aq->posePiece($_GET['piecePosition'][2], $_GET['piecePosition'][4], $Piece);
                $tabPiece[0]->removePieceQuantik($_GET['piecePosition'][0]);
            }elseif($_SESSION['couleurActive'] == 1){
                $Piece = $tabPiece[1]->getPieceQuantik($_GET['piecePosition'][0]);
                $aq->posePiece($_GET['piecePosition'][2], $_GET['piecePosition'][4], $Piece);
                $tabPiece[1]->removePieceQuantik($_GET['piecePosition'][0]);
            }
            echo $_SESSION['couleurActive'];
            if($_SESSION['couleurActive'] == 0){
                $_SESSION['couleurActive'] = 1;
            } elseif($_SESSION['couleurActive'] == 1) {
                $_SESSION['couleurActive'] = 0;
            }
            $_SESSION['etat'] = 'choixPiece';
            $pageHTML .= '<meta http-equiv="refresh" content="0;URL=quantik.php?etat=choixPiece" />';
            //$pageHTML .= QuantikUIGenerator::getPagePosePiece($tabPiece, $_SESSION['couleurActive'],$_GET['piecePosition'], $_SESSION['plateau']);

        }

        break;
    case 'victoire':
        /* TODO */
        break;
    default: // sans doute etape=bug
        echo QuantikUIGenerator::getPageErreur($_SESSION['message']);
        exit(1);
}
// seul echo nécessaire toute la pageHTML a été générée dans la variable $pageHTML
echo $pageHTML;