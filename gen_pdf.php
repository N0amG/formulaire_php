<?php 

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
    // Capturer le contenu de display_contract.php
    ob_start();
    include('display_contract.php');
    $htmlContent = ob_get_clean();

    if ($htmlContent !== false) {
        // Supprimer la balise <header> et son contenu
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($htmlContent);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $headerNodes = $xpath->query('//header');

        foreach ($headerNodes as $headerNode) {
            $headerNode->parentNode->removeChild($headerNode);
        }

        // Supposons que $dom est un objet DOMDocument contenant le HTML complet
        $finalContractContainer = $dom->getElementById('final-contract-container');

        if ($finalContractContainer) {
            $htmlContent = $dom->saveHTML($finalContractContainer);

            $options = new Options();
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);

            // Charger le contenu HTML dans Dompdf
            $dompdf->loadHtml($htmlContent);

            // Définir la taille et l'orientation du papier
            $dompdf->setPaper('A4', 'portrait');

            // Rendre le PDF
            $dompdf->render();

            // Envoyer le PDF au navigateur avec les bons en-têtes
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=contract_num_$contractId.pdf");

            echo $dompdf->output();
            exit;
        } else {
            // Gérer les erreurs lors de la récupération du contenu HTML
            echo "Erreur : Impossible de récupérer le contenu du conteneur final-contract.";
        }
    } else {
        // Gérer les erreurs lors de la récupération du contenu HTML
        echo "Erreur : Impossible de récupérer le contenu HTML.";
    }
} else {
    echo "Erreur : ID de contrat invalide.";
}