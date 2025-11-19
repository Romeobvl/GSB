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
<hr>
<div class="card card-info">
    <div class="card-header bg-warning text-white">Eléments forfaitisés</div>
    <div class="card-body">
        <form method="post" action="index.php?uc=validerFrais&action=majFraisForfait" onsubmit="return confirm('Voulez-vous valider les changements ?');">

            <input type="hidden" name="visiteur" value="<?php echo $idVisiteur ?>">
            <input type="hidden" name="mois" value="<?php echo $leMois ?>">

            <table class="table table-bordered table-responsive">
                <tr>
                    <?php
                    foreach ($lesFraisForfait as $unFraisForfait) {
                        $libelle = $unFraisForfait['libelle'];
                        ?>
                        <th> <?php echo htmlspecialchars($libelle) ?></th>
                        <?php
                    }
                    ?>
                </tr>
                <tr>

                    <?php
                    foreach ($lesFraisForfait as $unFraisForfait) {
                        $idFrais = $unFraisForfait['idfrais'];
                        $quantite = $unFraisForfait['quantite'];
                        ?>
                        <td class="qteForfait">
                            <input type="text" id="idFrais"
                                   name="lesFrais[<?php echo $idFrais ?>]"
                                   value="<?php echo $quantite ?>"
                                   class="form-control">
                        </td>

                        <?php
                    }
                    ?>

                </tr>
            </table>

            <button class="btn btn-success" type="submit">Corriger</button>
            <button class="btn btn-danger" type="reset">Réinitialiser</button>
        </form>
    </div>
</div>

<br>

<div class="card card-info">
    <div class="card-header bg-warning text-white">Descriptif des éléments hors forfait</div>
    <div class="card-body">

        <table class="table table-bordered table-responsive">
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>                
            </tr>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {

                $idFraisHorsForfait = $unFraisHorsForfait['id'];
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];
                ?>
                <form method="post" action="index.php?uc=validerFrais&action=majFraisHorsForfait" onsubmit="return confirm('Voulez-vous valider les changements ?');">

                    <input type="hidden" name="visiteur" value="<?php echo $idVisiteur ?>">
                    <input type="hidden" name="mois" value="<?php echo $leMois ?>">
                    <input type="hidden" name="idFraisHors" value="<?php echo $idFraisHorsForfait ?>">

                    <tr>
                        <td class="qteForfait">
                            <input type="text" id="idFrais"
                                   name="lesFraisHorsD"
                                   value="<?php echo $date ?>"
                                   class="form-control">
                        </td>
                        <td class="qteForfait">
                            <input type="text" id="idFrais"
                                   name="lesFraisHorsL"
                                   value="<?php echo $libelle ?>"
                                   class="form-control">
                        </td>
                        <td class="qteForfait">
                            <input type="text" id="idFrais"
                                   name="lesFraisHorsM"
                                   value="<?php echo $montant ?>"
                                   class="form-control">
                        </td>
                        <td>            
                            <button class="btn btn-success" type="submit" name="submitType" value="Corriger">Corriger</button>
                            <button class="btn btn-danger" type="reset">Réinitialiser</button>
                            <button class="btn btn-dark" type="submit" name="submitType" value="Refuser">Refuser</button>
                        </td>
                    </tr>

                </form>
                <?php
            }
            ?>
        </table>
    </div>
</div>

<br>

<form method="post" action="index.php?uc=validerFrais&action=validationFinale" onsubmit="return confirm('Voulez-vous valider les changements ?');">
    <input type="hidden" name="visiteur" value="<?php echo $idVisiteur ?>">
    <input type="hidden" name="mois" value="<?php echo $leMois ?>"> 
    Nombre de justificatif: 
    <input type="number" id="number" name="number" value="<?php echo $nbJustificatifs ?>" size="1" min="0" max="15" />
    <button class="btn btn-success" type="submit">Valider la fiche</button>
</form>