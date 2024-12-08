<?php 

use Dompdf\Dompdf;
use Dompdf\Options;

require_once('functions.php');
require_once('includes/dompdf/autoload.inc.php');

$pdo = connectDB();

// Récupérer l'ID du contrat depuis l'URL
$contractId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($contractId > 0) {
    // Construire l'URL de la page à charger
    $url = "http://localhost/formulaire/display_contract.php?id=" . $contractId;
    consoleLog($url);
    // Récupérer le contenu HTML de la page
    $htmlContent = file_get_contents($url);

    if ($htmlContent !== false) {
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($htmlContent);

        // Définir la taille et l'orientation du papier
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Envoyer le PDF au navigateur
        $dompdf->stream('monfichier.pdf', ['Attachment' => 0]);
    } else {
        echo "Erreur : Impossible de récupérer le contenu HTML de la page.";
    }
} else {
    echo "Erreur : ID de contrat invalide.";
}