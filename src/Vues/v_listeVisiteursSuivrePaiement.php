<?php
/**
 * Vue Liste des mois
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
<div>
    <h2 class="text-warning">
        Suivre le paiement des fiches de frais
    </h2>
</div>
<div class="row">
    <div class="col-md-4">
        <form action="index.php?uc=suivrePaiementFrais&action=selectionnerMois" 
              method="post">
            <div class="mb-3">
                <label for="visiteur" class="form-label" accesskey="n">Visiteur :</label>
                <select id="visiteur" name="visiteur" class="form-select">
                        <option selected value="">
                                    -Selectionner-
                                </option>
                    <?php
                        foreach ($lesVisiteurs as $unVisiteur) {
                            if ($unVisiteur == $visiteurASelectionner) {
                                ?>
                                <option selected value="<?php echo $unVisiteur['id'] ?>">
                                    <?php echo $unVisiteur['nom'] . ' ' . $unVisiteur['prenom'] ?>
                                </option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $unVisiteur['id'] ?>">
                                    <?php echo $unVisiteur['nom'] . ' ' . $unVisiteur['prenom'] ?>
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