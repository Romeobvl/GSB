<?php

/**
 * Gestion de l'affichage des frais
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

use Outils\Utilitaires;
use Outils\fpdf;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_SESSION['id'];
switch ($action) {
    case 'selectionnerMois':
        $lesMois = $pdo->getLesMoisDisponibles($id);
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        include PATH_VIEWS . 'v_listeMois.php';
        break;
    case 'voirEtatFrais':
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisDisponibles($id);
        $moisASelectionner = $leMois;
        include PATH_VIEWS . 'v_listeMois.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($id, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($id, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include PATH_VIEWS . 'v_etatFrais.php';
        break;
case 'genererPDF':

    if (ob_get_length()) ob_end_clean();

    $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $infosVisiteur = $pdo->getInfosVisiteurById($id);
    $nom = mb_strtoupper($infosVisiteur['nom'], 'utf-8');
    $prenom = $infosVisiteur['prenom'];
    $nomComplet = $nom . ' ' . $prenom;
    $moisComplet = Utilitaires::getDateTextuelle($mois);

    $nomFichier = 'ETATFRAIS_' . $nom . $prenom . '_' . $mois . '.pdf';
    $dossierPDF = '../etatfrais_visiteurs/';

    if (!is_dir($dossierPDF)) {
        mkdir($dossierPDF, 0777, true);
    }

    $cheminAvecDossier = $dossierPDF . $nomFichier;

    // Green-IT : si le PDF existe déjà, on le renvoie sans régénérer
    if (file_exists($cheminAvecDossier)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
        header('Content-Length: ' . filesize($cheminAvecDossier));
        readfile($cheminAvecDossier);
        exit;
    }

    // --- Récupération des données ---
    $lesFraisForfait     = $pdo->getLesFraisForfait($id, $mois);
    $lesPrixForfait      = $pdo->getLesPrixForfait();
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id, $mois);
    $vehicule            = $pdo->getVehiculeByVisiteur($id);
    $prixKm              = $vehicule['prixKilometrique'];

    $prixParId = [];
    foreach ($lesPrixForfait as $unPrix) {
        $prixParId[$unPrix['id']] = $unPrix['montant'];
    }

    // Calcul montant total forfait
    $montantTotalForfait = 0;
    foreach ($lesFraisForfait as $unFrais) {
        $idFrais = $unFrais['idfrais'];
        $prix    = ($idFrais === 'KM') ? $prixKm : ($prixParId[$idFrais] ?? 0);
        $montantTotalForfait += $unFrais['quantite'] * $prix;
    }

    // Calcul montant total hors forfait
    $montantTotalHF = 0;
    foreach ($lesFraisHorsForfait as $unFrais) {
        $montantTotalHF += $unFrais['montant'];
    }

    $montantGlobal = $montantTotalForfait + $montantTotalHF;

    // --- Génération du PDF ---
    $pdf = new fpdf('P', 'mm', 'A4');
    $pdf->AddPage();

    $bleu      = [31, 73, 125];
    $blanc     = [255, 255, 255];
    $grisLight = [240, 245, 250];
    $noir      = [0, 0, 0];
    $pageW     = $pdf->GetPageWidth(); // 210
    $margin    = 15;
    $tableW    = $pageW - 2 * $margin; // 180

    // --- LOGO centré ---
    $largeurLogo = 40;
    $xLogo = ($pageW - $largeurLogo) / 2;
    if (file_exists('images/logo.jpg')) {
        $pdf->Image('images/logo.jpg', $xLogo, 10, $largeurLogo);
    }
    $pdf->SetY(55);

    // --- TITRE ---
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->SetTextColor(...$bleu);
    $pdf->Cell(0, 8, mb_convert_encoding('ÉTAT DE FRAIS ENGAGÉS', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetTextColor(...$noir);
    $pdf->Cell(0, 5, mb_convert_encoding('Document à retourner accompagné des justificatifs.', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Ln(5);

    // --- GRAND TABLEAU PRINCIPAL ---
    $pdf->SetX($margin);

    // En-tête identification : ligne Visiteur + Matricule
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(...$noir);

    $pdf->SetX($margin);
    $pdf->Cell(25, 7, mb_convert_encoding('Visiteur :', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(65, 7, mb_convert_encoding($nomComplet, 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(25, 7, mb_convert_encoding('Matricule :', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, mb_convert_encoding($id, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->SetX($margin);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(25, 7, mb_convert_encoding('Mois :', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, mb_convert_encoding($moisComplet, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(4);

    // Largeurs colonnes du tableau principal
    $colW = [70, 35, 40, 35]; // Libellé | Quantité | Montant unitaire | Total

    // ===== SOUS-TITRE : Frais Forfaitaires =====
    $pdf->SetX($margin);
    $pdf->SetFillColor(...$bleu);
    $pdf->SetTextColor(...$blanc);
    $pdf->SetFont('Arial', 'B', 11);
    // Ligne d'en-tête du tableau
    $pdf->Cell($colW[0], 8, mb_convert_encoding('Frais Forfaitaires', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
    $pdf->Cell($colW[1], 8, mb_convert_encoding('Quantité', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
    $pdf->Cell($colW[2], 8, mb_convert_encoding('Montant unitaire', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
    $pdf->Cell($colW[3], 8, mb_convert_encoding('Total', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(...$noir);
    $fillRow = false;
    foreach ($lesFraisForfait as $unFrais) {
        $idFrais = $unFrais['idfrais'];
        $libelle = $unFrais['libelle'];
        $qte     = $unFrais['quantite'];
        $prix    = ($idFrais === 'KM') ? $prixKm : ($prixParId[$idFrais] ?? 0);
        $total   = $qte * $prix;

        $pdf->SetFillColor(...$grisLight);
        $pdf->SetX($margin);
        $pdf->Cell($colW[0], 7, mb_convert_encoding($libelle, 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', $fillRow);
        $pdf->Cell($colW[1], 7, mb_convert_encoding((string)$qte, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fillRow);
        $pdf->Cell($colW[2], 7, mb_convert_encoding(number_format($prix, 2, ',', ' ') , 'ISO-8859-1', 'UTF-8'), 1, 0, 'R', $fillRow);
        $pdf->Cell($colW[3], 7, mb_convert_encoding(number_format($total, 2, ',', ' ') , 'ISO-8859-1', 'UTF-8'), 1, 1, 'R', $fillRow);
        $fillRow = !$fillRow;
    }

    // Ligne sous-total forfait
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(220, 230, 242);
    $pdf->SetX($margin);
    $pdf->Cell($colW[0] + $colW[1] + $colW[2], 7,
        mb_convert_encoding('Total forfaitaire', 'ISO-8859-1', 'UTF-8'), 1, 0, 'R', true);
    $pdf->Cell($colW[3], 7,
        mb_convert_encoding(number_format($montantTotalForfait, 2, ',', ' ') . ' EUR', 'ISO-8859-1', 'UTF-8'), 1, 1, 'R', true);

    $pdf->Ln(4);

    // ===== SOUS-TITRE : Frais Hors Forfait =====
    $colW2 = [35, 100, 45]; // Date | Libellé | Montant

    $pdf->SetX($margin);
    $pdf->SetFillColor(...$bleu);
    $pdf->SetTextColor(...$blanc);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell($colW2[0], 8, mb_convert_encoding('Date', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
    $pdf->Cell($colW2[1], 8, mb_convert_encoding('Frais hors-forfait — Libellé', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
    $pdf->Cell($colW2[2], 8, mb_convert_encoding('Montant', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(...$noir);
    $fillRow = false;
    foreach ($lesFraisHorsForfait as $unFrais) {
        $pdf->SetFillColor(...$grisLight);
        $pdf->SetX($margin);
        $pdf->Cell($colW2[0], 7, mb_convert_encoding($unFrais['date'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fillRow);
        $pdf->Cell($colW2[1], 7, mb_convert_encoding($unFrais['libelle'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', $fillRow);
        $pdf->Cell($colW2[2], 7, mb_convert_encoding(number_format($unFrais['montant'], 2, ',', ' ') , 'ISO-8859-1', 'UTF-8'), 1, 1, 'R', $fillRow);
        $fillRow = !$fillRow;
    }

    // Ligne sous-total hors forfait
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(220, 230, 242);
    $pdf->SetX($margin);
    $pdf->Cell($colW2[0] + $colW2[1], 7,
        mb_convert_encoding('Total hors-forfait', 'ISO-8859-1', 'UTF-8'), 1, 0, 'R', true);
    $pdf->Cell($colW2[2], 7,
        mb_convert_encoding(number_format($montantTotalHF, 2, ',', ' ') . ' EUR', 'ISO-8859-1', 'UTF-8'), 1, 1, 'R', true);

    $pdf->Ln(4);

    // ===== LIGNE MONTANT GLOBAL =====
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(...$bleu);
    $pdf->SetTextColor(...$blanc);
    $pdf->SetX($margin);
    $pdf->Cell($tableW - 55, 9,
        mb_convert_encoding('MONTANT TOTAL À REMBOURSER', 'ISO-8859-1', 'UTF-8'), 1, 0, 'R', true);
    $pdf->Cell(55, 9,
        mb_convert_encoding(number_format($montantGlobal, 2, ',', ' ') . ' EUR', 'ISO-8859-1', 'UTF-8'), 1, 1, 'R', true);

    $pdf->Ln(12);

    // --- Date et signature ---
    $dateDoc = 'Fait le ' . date('d/m/Y');
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->SetTextColor(...$noir);
    $pdf->SetX($margin);
    $pdf->Cell(0, 6, mb_convert_encoding($dateDoc, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

    $pdf->Ln(8);
    $pdf->SetX(120);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(60, 8, mb_convert_encoding('Signature :', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
    $pdf->SetX(120);
    $pdf->Cell(60, 20, '', 'B', 1); // zone signature

    // --- Sauvegarde et envoi ---
    $pdf->Output('F', $cheminAvecDossier);

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
    header('Content-Length: ' . filesize($cheminAvecDossier));
    readfile($cheminAvecDossier);
    exit;
break;
}