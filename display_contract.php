<?php
require_once('functions.php');

// Connexion à la base de données
$pdo = connectDB();

// Vérification de l'ID du formulaire dans l'URL
if (isset($_GET['id'])) {
    $formId = (int) $_GET['id'];
    // Récupération des informations du formulaire et des partenaires associés
    try {
        $formData = getFormDataById($pdo, $formId);
    } catch (Exception $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
        exit;
    }
} else {
    echo "<p>Erreur : ID du formulaire manquant.</p>";
    exit;
}

$countries = [
    'FR' => 'de France',
    'US' => 'des États-Unis',
    'CA' => 'de Canada',
    'GB' => 'du Royaume-Uni',
    'DE' => "d'Allemagne",
    'JP' => 'du Japon',
    'CN' => 'de Chine',
    'IN' => "d'Inde",
    'BR' => 'du Brésil',
    'AU' => "d'Australie"
];

require_once('html_utils/header.php');

echo "<div id='final-contract-container'>";
echo "<h1>Contrat de Partenariat Commercial</h1>";
echo "<br><p>Ce contrat est fait ce jour " . date("d/m/Y") . ", en " . htmlspecialchars($formData['data']['num_partners']) . " copies originales, entre :</p>";

foreach ($formData['partners'] as $index => $partner) {
    $partnerName = htmlspecialchars($partner['nom']);
    $contribution = htmlspecialchars($partner['contribution']);

    echo "<p><strong>Partenaire " . ($index + 1) . ":</strong> $partnerName</p>";
    echo "<p><strong>Contribution:</strong> $contribution</p>";
    echo "<br>";
}

// Affichage du reste des informations de manière statique
echo "<br><br><h2>1. Nom du Partenariat et Activité</h2><br>";
echo "<p><strong>Nom du Partenariat : </strong>" . htmlspecialchars($formData['data']['partnership_name']) . "</p>";
echo "<br><p><strong>Nature des activités : </strong> " . htmlspecialchars($formData['data']['activity_type']) . "</p>";
echo "<br><p><strong>Adresse officielle : </strong> " . htmlspecialchars($formData['data']['official_address']) . "</p>";

echo "<br><br><h2>2. Termes</h2>";
echo "<p>Le partenariat commence le " . htmlspecialchars($formData['data']['start_date']) . " et se termine le " . htmlspecialchars($formData['data']['end_date']) . ".</p>";

echo "<br><h2>3. Répartition des bénéfices et des pertes</h2>";
echo "<p>" . htmlspecialchars($formData['data']['profit_loss_distribution']) . "</p>";

echo "<br><h2>4. Modalités bancaires</h2>";
echo "<p>Les chèques doivent être signés par " . htmlspecialchars($formData['data']['signing_partner_count']) . " des partenaires.</p>";

echo "<br><h2>5. Juridiction</h2>";
$countryCode = htmlspecialchars($formData['data']['country_code']);
$countryName = isset($countries[$countryCode]) ? $countries[$countryCode] : "Pays inconnu";
echo "<p>Le présent contrat de partenariat commercial est régi par les lois de l'État $countryName.</p>";
echo "</div>";

require_once('html_utils/footer.php');
?>