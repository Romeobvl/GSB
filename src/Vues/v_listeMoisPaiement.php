<?php
/**
 * Vue Liste des mois à Suivre les Paiements
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */
?>
<div class="row">
    <div class="col-md-4">
        <form action="index.php?uc=suivrePaiementFrais&action=voirEtatFrais" 
              method="post" role="form">

            <input type="hidden" name="visiteur" value="<?php echo $idVisiteur ?>">

            <div class="mb-3">
                <label for="mois" class="form-label" accesskey="n">Mois : </label>
                <select id="mois" name="mois" class="form-select">


                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?>
                            </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <button id="ok" type="submit" class="btn btn-success">Valider</button>
            <button id="annuler" type="reset" class="btn btn-danger">Effacer</button>
        </form>
    </div>
</div>
