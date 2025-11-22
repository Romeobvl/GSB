<?php

/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$lesVisiteurs = $pdo->getLesVisiteurs();
$idVisiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$leMois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($idVisiteur)) {
    $visiteurASelectionner = $pdo->getInfosVisiteurById($idVisiteur);
    $moisASelectionner = $leMois;
    $lesMois = $pdo->getLesMoisDisponiblesAPayer($idVisiteur);
}

switch ($action) {
    case 'selectionner':
        include PATH_VIEWS . 'v_listeVisiteursSuivrePaiement.php';
        include PATH_VIEWS . 'v_listeMoisPaiement.php';
        break;
    
    case 'voirEtatFrais':
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        
        include PATH_VIEWS . 'v_listeVisiteursSuivrePaiement.php';
        include PATH_VIEWS . 'v_listeMoisPaiement.php';
        include PATH_VIEWS . 'v_etatFrais.php';
        break;
}
