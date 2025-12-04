<?php

/**
 * Vue Liste des frais au forfait
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
<div class="row">    
    <h2>Renseigner ma fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="mb-3">
                        <label for="idFrais" class="form-label"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }                                   
                ?>
                
                                <select id="vehicule" name="vehicule" class="form-select">
                        <option selected value="">
                                    -Selectionner-
                                </option>
                    <?php
                        foreach ($lesVehicules as $unVehicule) {
                            if ($unVehicule == $vehiculeASelectionner) {
                                ?>
                                <option selected value="<?php echo $unVehicule['id'] ?>">
                                    <?php echo $unVehicule['puissance'] . ' CV ' . $unVehicule['carburant'] ?>
                                </option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $unVehicule['id'] ?>">
                                    <?php echo $unVehicule['puissance'] . ' CV ' . $unVehicule['carburant'] ?>
                                </option>
                                <?php
                            }
                        }
                    
                    ?>
                </select>
                
                <button class="btn btn-success" type="submit">Ajouter</button>
                <button class="btn btn-danger" type="reset">Effacer</button>
            </fieldset>
        </form>
    </div>
</div>