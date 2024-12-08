<?php 

// Définition de la constante pour empêcher l'accès direct à ce fichier
define('IN_GEN_PDF', true);

use Dompdf\Dompdf;
use Dompdf\Options;

// Charger les dépendances nécessaires
require_once('functions.php');
require_once('includes/dompdf/autoload.inc.php');

// Activer l'affichage des erreurs pour débogage (en développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$pdo = connectDB();

// Récupérer l'ID du contrat depuis l'URL
$contractId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($contractId > 0) {
    // Construire l'URL de la page à charger
    $url = "http://localhost/formulaire/display_contract.php?id=" . $contractId;

    // Utiliser cURL pour récupérer le contenu HTML
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Suivre les redirections si nécessaire
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout après 10 secondes
    $htmlContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch); // Récupère l'erreur si cURL échoue
    curl_close($ch);

    if ($httpCode == 200 && $htmlContent !== false) {
        // Initialiser Dompdf avec des options
        $options = new Options();
        $options->set('defaultFont', 'Arial'); // Définit une police par défaut
        $options->set('isHtml5ParserEnabled', true); // Active le support HTML5

        $dompdf = new Dompdf($options);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($htmlContent);

        // Définir la taille et l'orientation du papier
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Enregistrer le fichier PDF généré pour débogage
        file_put_contents('debug_output.pdf', $dompdf->output());

        // Envoyer le PDF au navigateur avec les bons en-têtes
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=monfichier.pdf");

        echo $dompdf->output();
        exit;
    } else {
        // Gérer les erreurs lors de la récupération du contenu HTML
        echo "Erreur : Impossible de récupérer le contenu HTML.";
        if ($curlError) {
            echo "<br>Erreur cURL : " . htmlspecialchars($curlError);
        }
        echo "<br>Code HTTP : " . $httpCode;
    }
} else {
    echo "Erreur : ID de contrat invalide.";
}
