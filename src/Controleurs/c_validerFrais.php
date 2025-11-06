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

$leVisiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($leVisiteur)) {
    $visiteurASelectionner = $pdo->getInfosVisiteurById($leVisiteur);
}
$id = $leVisiteur;

switch ($action) {
    case 'selectionner' :
        $lesMois = $pdo->getLesMoisDisponibles($leVisiteur);
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        include PATH_VIEWS . 'v_listeMoisValider.php';
        break;

    case 'voirEtatFrais':
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($id, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($id, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

        include PATH_VIEWS . 'v_listeVisiteurs.php';
        include PATH_VIEWS . 'v_listeMoisValider.php';
        include PATH_VIEWS . 'v_etatFrais.php';
}
