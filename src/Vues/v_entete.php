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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    </head>
    <body>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($estConnecte) {

                if ($_SESSION['role'] === 'visiteur') {
                    $txt1 = 'Renseigner la fiche de frais';
                    $txt2 = 'Afficher mes fiches de frais';
                    $icon1 = 'bi bi-pencil';
                    $icon2 = 'bi bi-list-check';
                    $active = 'active';
                    $text = '';
                    $uc1 = 'gererFrais';
                    $uc2 = 'etatFrais';
                    $act1 = 'saisirFrais';
                    $act2 = 'selectionneMois';
                } elseif ($_SESSION['role'] === 'comptable') {
                    $txt1 = 'Valider les fiche de frais';
                    $txt2 = 'Suivre le paiement des fiches de frais';
                    $icon1 = 'bi bi-check';
                    $icon2 = 'bi bi-currency-euro';
                    $active = 'active bg-warning text-white';
                    $text = 'text-warning';
                    $uc1 = 'validerFrais';
                    $uc2 = 'suivrePaiementFrais';
                    $act1 = 'validerFrais';
                    $act2 = 'suivrrePaiementFrais';
                }
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
                                    <a class="nav-link <?php if (!$uc || $uc == 'accueil') { ?><?php echo $active ?><?php } ?> <?php echo $text ?> " href="index.php">
                                        <span class="bi bi-house"></span>
                                        Accueil
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($uc == $uc1) { ?><?php echo $active ?><?php } ?> <?php echo $text ?> " href="index.php?uc=<?php echo $uc1 ?>&action=<?php echo $act1 ?>">
                                        <span class="<?php echo $icon1 ?>"></span>
                                        <?php echo $txt1 ?> 
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($uc == $uc2) { ?><?php echo $active ?><?php } ?> <?php echo $text ?> " href="index.php?uc=<?php echo $uc2 ?>&action=<?php echo $act2 ?>">
                                        <span class="<?php echo $icon2 ?>"></span>
                                        <?php echo $txt2 ?> 
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($uc == 'deconnexion') { ?><?php echo $active ?><?php } ?> <?php echo $text ?> " href="index.php?uc=deconnexion&action=demandeDeconnexion">
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