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
    $lesMois = $pdo->getLesMoisDisponiblesAValider($idVisiteur);
    if (isset($leMois)) {
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    }
}


switch ($action) {
    case 'selectionner' :
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        include PATH_VIEWS . 'v_listeMoisValider.php';
        break;

    case 'validerFrais':
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        include PATH_VIEWS . 'v_listeMoisValider.php';
        include PATH_VIEWS . 'v_validerFrais.php';
        break;

    case 'majFraisForfait':

        $lesFrais = filter_input_array(INPUT_POST, [
                    'lesFrais' => [
                        'filter' => FILTER_SANITIZE_NUMBER_INT,
                        'flags' => FILTER_REQUIRE_ARRAY
                    ]
                ])['lesFrais'];

        $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);

        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);

        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        include PATH_VIEWS . 'v_listeMoisValider.php';
        include PATH_VIEWS . 'v_validerFrais.php';
        break;

    case 'majFraisHorsForfait':
        $submitType = filter_input(INPUT_POST, 'submitType', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        switch ($submitType) {
            case 'Corriger':
                $idFraisHors = filter_input(INPUT_POST, 'idFraisHors', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $lesFraisHorsD = filter_input(INPUT_POST, 'lesFraisHorsD', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $lesFraisHorsL = filter_input(INPUT_POST, 'lesFraisHorsL', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $lesFraisHorsM = filter_input(INPUT_POST, 'lesFraisHorsM', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $lesFraisHorsD = DateTime::createFromFormat('d/m/Y', $lesFraisHorsD);
                $lesFraisHorsD = $lesFraisHorsD->format('Y-m-d');

                $pdo->majFraisHorsForfait($idFraisHors, $lesFraisHorsD, $lesFraisHorsL, $lesFraisHorsM);

                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);

                include PATH_VIEWS . 'v_listeVisiteurs.php';
                include PATH_VIEWS . 'v_listeMoisValider.php';
                include PATH_VIEWS . 'v_validerFrais.php';
                break;
            case 'Refuser':
                $idFraisHors = filter_input(INPUT_POST, 'idFraisHors', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $libelleRefuse = filter_input(INPUT_POST, 'lesFraisHorsL', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pdo->refuserFraisHorsForfait($idFraisHors, $libelleRefuse);
                
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
                include PATH_VIEWS . 'v_listeVisiteurs.php';
                include PATH_VIEWS . 'v_listeMoisValider.php';
                include PATH_VIEWS . 'v_validerFrais.php';
                break;
             case 'Reporter':
                $pdo->reporterFraisHorsForfait($idFraisHors);
                
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
                include PATH_VIEWS . 'v_listeVisiteurs.php';
                include PATH_VIEWS . 'v_listeMoisValider.php';
                include PATH_VIEWS . 'v_validerFrais.php';
                break;
        }
        break;


    case 'validationFinale':
        $number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $etat = "VA";
        $pdo->majNbJustificatifs($idVisiteur, $leMois, $number);
        $pdo->majEtatFicheFrais($idVisiteur, $leMois, $etat);

        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        include PATH_VIEWS . 'v_listeMoisValider.php';
        include PATH_VIEWS . 'v_validerFrais.php';
        break;
}
