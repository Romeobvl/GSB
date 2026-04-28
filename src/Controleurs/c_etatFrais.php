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

        // Utilise ta méthode à toi
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

        // Génération du PDF
        $pdf = new fpdf('P', 'mm', 'A4');
        $pdf->AddPage();

        // Logo centré
        $largeurLogo = 50;
        $xLogo = ($pdf->GetPageWidth() - $largeurLogo) / 2;
        if (file_exists('images/logo.jpg')) {
            $pdf->Image('images/logo.jpg', $xLogo, 10, $largeurLogo);
        }
        $pdf->SetY(50);

        // Titre
        $pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(31, 73, 125);
$pdf->Cell(0, 10, mb_convert_encoding('ÉTAT DE FRAIS ENGAGÉS', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, mb_convert_encoding('Document à retourner accompagné des justificatifs.', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(8);

// Identification
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 8, mb_convert_encoding('Visiteur :', 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(60, 8, mb_convert_encoding($nomComplet, 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(30, 8, mb_convert_encoding('Matricule :', 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(40, 8, mb_convert_encoding($id, 'ISO-8859-1', 'UTF-8'), 0, 1);

$pdf->Cell(30, 8, mb_convert_encoding('Mois :', 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(60, 8, mb_convert_encoding($moisComplet, 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->Ln(5);

// --- FRAIS FORFAIT ---
$lesFraisForfait = $pdo->getLesFraisForfait($id, $mois);
$lesPrixForfait  = $pdo->getLesPrixForfait();

// Indexer les prix par id pour accès rapide
$prixParId = [];
foreach ($lesPrixForfait as $unPrix) {
    $prixParId[$unPrix['id']] = $unPrix['montant'];
}

// Récupérer le prix KM selon le véhicule du visiteur
$vehicule = $pdo->getVehiculeByVisiteur($id);
$prixKm = $vehicule['prixKilometrique'];

// En-tête tableau forfait
$w = [60, 40, 40, 40];
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(31, 73, 125);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($w[0], 8, mb_convert_encoding('Frais Forfaitaires', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell($w[1], 8, mb_convert_encoding('Quantité', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell($w[2], 8, mb_convert_encoding('Montant unitaire', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell($w[3], 8, mb_convert_encoding('Total', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);

foreach ($lesFraisForfait as $unFrais) {
    $idFrais = $unFrais['idfrais'];
    $libelle = $unFrais['libelle'];
    $qte     = $unFrais['quantite'];
    $prix    = ($idFrais === 'KM') ? $prixKm : ($prixParId[$idFrais] ?? 0);
    $total   = $qte * $prix;

    $pdf->Cell($w[0], 7, mb_convert_encoding($libelle, 'ISO-8859-1', 'UTF-8'), 1, 0);
    $pdf->Cell($w[1], 7, mb_convert_encoding((string)$qte, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell($w[2], 7, mb_convert_encoding(number_format($prix, 2, ',', ' '), 'ISO-8859-1', 'UTF-8'), 1, 0, 'R');
    $pdf->Cell($w[3], 7, mb_convert_encoding(number_format($total, 2, ',', ' '), 'ISO-8859-1', 'UTF-8'), 1, 1, 'R');
}

$pdf->Ln(10);

// --- FRAIS HORS FORFAIT ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, mb_convert_encoding('Frais hors-forfait', 'ISO-8859-1', 'UTF-8'), 0, 1);

$w2 = [40, 100, 40];
$pdf->SetFillColor(31, 73, 125);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($w2[0], 8, mb_convert_encoding('Date', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell($w2[1], 8, mb_convert_encoding('Libellé', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell($w2[2], 8, mb_convert_encoding('Montant', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);

$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id, $mois);
$montantTotalHF = 0;

foreach ($lesFraisHorsForfait as $unFrais) {
    $pdf->Cell($w2[0], 7, mb_convert_encoding($unFrais['date'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell($w2[1], 7, mb_convert_encoding($unFrais['libelle'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
    $pdf->Cell($w2[2], 7, mb_convert_encoding(number_format($unFrais['montant'], 2, ',', ' '), 'ISO-8859-1', 'UTF-8'), 1, 1, 'R');
    $montantTotalHF += $unFrais['montant'];
}

// Ligne total HF
    $pdf->Cell($w2[0] + $w2[1], 7, mb_convert_encoding('Total hors-forfait', 'ISO-8859-1', 'UTF-8'), 1, 0, 'R');
    $pdf->Cell($w2[2], 7, mb_convert_encoding(number_format($montantTotalHF, 2, ',', ' '), 'ISO-8859-1', 'UTF-8'), 1, 1, 'R');
    
$pdf->Ln(15);

// Zone signature
$pdf->SetX(120);
$pdf->Cell(60, 10, mb_convert_encoding('Signature :', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

// Sauvegarde sur le serveur puis envoi au navigateur
$pdf->Output('F', $cheminAvecDossier);

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
header('Content-Length: ' . filesize($cheminAvecDossier));
readfile($cheminAvecDossier);
exit;
break;
}