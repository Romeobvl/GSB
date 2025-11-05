<?php
/**
 * Vue État de Frais
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
<div>
    <form method="POST" action="index.php?uc=validerFrais&action=selectionnerVisiteur">
        <label for="visiteur" class="form-label" accesskey="n">Choisir le visiteur:</label>
        <select id="visiteur" name="visiteur" class="form-select">
        <?php
        foreach ($LesVisiteurs as $unVisiteur) {
            $visiteur = $unVisiteur;
            if ($visiteur == $VisiteurASelectionner) {
                ?>
                <option selected value="<?php echo $visiteur['id'] ?>">
                    <?php echo $visiteur['nom'] . " " . $visiteur['prenom'] ?>
                </option>
                <?php
            } else {
                ?>
                 <option value="<?php echo $visiteur['id'] ?>">
                    <?php echo $visiteur['nom'] . " " . $visiteur['prenom'] ?>
                </option>
                <?php
            }        
        }
        ?>
        </select>
    </form>

    <form
    <label for="lstMois" class="form-label" accesskey="n">Mois : </label>
    <select id="lstMois" name="lstMois" class="form-select">
        <?php
        foreach ($lesMois as $unMois) {
            $mois = $unMois['mois'];
            $numAnnee = $unMois['numAnnee'];
            $numMois = $unMois['numMois'];
            if ($mois == $moisASelectionner) {
                ?>
                <option selected value="<?php echo $mois ?>">
                    <?php echo $numMois . '/' . $numAnnee ?> </option>
                <?php
            } else {
                ?>
                <option value="<?php echo $mois ?>">
                    <?php echo $numMois . '/' . $numAnnee ?> </option>
                <?php
            }
        }
        ?>   
    </select>
    </form>
</div>
    <h2 class ="text-warning">
        Valider la fiche de frais
    </h2>
    <div>Eléments forfaitisés</div>
    <div>
        <ul class="list-group">
            <li class = "list-group-item">Forfait Étape:</li>
            <li class = "list-group-item">Frais Kilomètrique</li>
            <li class = "list-group-item">Nuitée Hôtel</li>
            <li class = "list-group-item">Repas Restaurant</li>
        </ul>
    </div>
    <input id="ok" type="submit" value="Valider" class="btn btn-success" 
           role="button">
    <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
           role="button">
</div>