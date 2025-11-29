<?php
/**
 * Vue Accueil Comptable
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
<hr>
<div>
    <h2>État de la fiche de frais : <strong><?php echo $libEtat ?></strong></h2>
</div>
<h3>Éléments forfaitisés</h3>
<div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $libelle = $unFraisForfait['libelle'];
                    ?>
                    <th><?php echo htmlspecialchars($libelle) ?></th>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $quantite = $unFraisForfait['quantite'];
                    ?>
                    <td class="qteForfait"><?php echo $quantite ?></td>
                    <?php
                }
                ?>
            </tr>
        </table>
    </div>
</div>
<div class="card border-warning mb-3">
    <div class="card-header bg-warning text-white">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>
                    <th class="montant">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                    $date = $unFraisHorsForfait['date'];
                    $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                    $montant = $unFraisHorsForfait['montant'];
                    ?>
                    <tr>
                        <td><?php echo $date ?></td>
                        <td><?php echo $libelle ?></td>
                        <td><?php echo $montant ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div>
    <form method="post" action="index.php?uc=suivrePaiement&action=rembourserFiche" onsubmit="return confirm('Voulez-vous valider le remboursement de cette fiche de frais ?');">

        <input type="hidden" name="visiteur" value="<?php echo $idVisiteur ?>">
        <input type="hidden" name="mois" value="<?php echo $leMois ?>">

        <button id="ok" type="submit" class="btn btn-success">Valider le remboursement</button>
    </form>
</div>