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
<div id ="validerFrais">
    Choisir le visiteur: 

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