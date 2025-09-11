<?php

/**
 * Vue Entête
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
 * @link      https://getbootstrap.com/docs/5.3/ Documentation Bootstrap v5
 */

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="./styles/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($estConnecte) {
                ?>
            <div class="header">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h1>
                            <img src="./images/logo.jpg" class="img-fluid" 
                                 alt="Laboratoire Galaxy-Swiss Bourdin" 
                                 title="Laboratoire Galaxy-Swiss Bourdin">
                        </h1>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-pills justify-content-end" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link <?php if (!$uc || $uc == 'accueil') { ?>active<?php } ?>" href="index.php">
                                    <span class="bi bi-house"></span>
                                    Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($uc == 'gererFrais') { ?>active<?php } ?>" href="index.php?uc=gererFrais&action=saisirFrais">
                                    <span class="bi bi-pencil"></span>
                                    Renseigner la fiche de frais
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($uc == 'etatFrais') { ?>active<?php } ?>" href="index.php?uc=etatFrais&action=selectionnerMois">
                                    <span class="bi bi-list-check"></span>
                                    Afficher mes fiches de frais
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($uc == 'deconnexion') { ?>active<?php } ?>" href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                    <span class="bi bi-box-arrow-right"></span>
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            } else {
                ?>   
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-fluid mx-auto d-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
                <?php
            }