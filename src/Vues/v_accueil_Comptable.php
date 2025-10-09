<?php

/**
 * Vue Accueil
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

?>
<div id="accueil">
    <h2>
        Gestion des frais<small class="text-muted"> - Comptable : 
            <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title">
                    <i class="bi bi-bookmark"></i>
                    Navigation
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 d-grid gap-2 d-md-block">
                        <a href="index.php?uc=gererFrais&action=saisirFrais"
                           class="btn btn-success btn-lg" role="button">
                            <i class="bi bi-pencil"></i>
                            <br>Renseigner la fiche de frais</a>
                        <a href="index.php?uc=etatFrais&action=selectionnerMois"
                           class="btn btn-warning btn-lg text-white" role="button">
                            <i class="bi bi-list-ul"></i>
                            <br>Afficher mes fiches de frais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>