<?php

/**
 * Gestion de l'accueil
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

if ($estConnecte && $_SESSION['role'] == "visiteur") {
    include_once PATH_VIEWS . 'v_entete.php';
	include PATH_VIEWS . 'v_accueil.php';
} elseif ($estConnecte && $_SESSION['role'] == "comptable") {
    include_once PATH_VIEWS . 'v_entete.php';
	include PATH_VIEWS . 'v_accueil_Comptable.php';
} else {
    include PATH_VIEWS . 'v_connexion.php';
}
