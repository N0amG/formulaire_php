<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tableau associant les codes de pays aux noms complets
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

    echo "<!DOCTYPE html>";
    echo "<html lang='fr'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Contrat Finalisé</title>";
    echo "<link rel='stylesheet' href='style.css'/>";
    echo "<script src='script.js' defer></script>";
    echo "</head>";
    echo "<body>";
    echo '
    <div id="theme-switcher-container">
      <button type="button" id="theme-switcher">Mode Sombre</button>
    </div>';
    
    // Affichage des noms et contributions des partenaires
    $numPartners = isset($_POST['numPartners']) ? (int) $_POST['numPartners'] : 0;
    echo "<div id='final-contract-container'>";
    echo "<h1>Contrat de Partenariat Commercial</h1>";
    echo "<p>Ce contrat est fait ce jour " . date("d/m/Y") . ", en $numPartners copies originales, entre :</p>";

    for ($i = 1; $i <= $numPartners; $i++) {
        $partnerName = isset($_POST["partner$i"]) ? htmlspecialchars($_POST["partner$i"]) : "Nom non fourni";
        $contribution = isset($_POST["contribution$i"]) ? htmlspecialchars($_POST["contribution$i"]) : "Contribution non fournie";

        echo "<p><strong>Partenaire $i:</strong> $partnerName</p>";
        echo "<p><strong>Contribution:</strong> $contribution</p>";
    }

    // Affichage du reste des informations de manière statique
    echo "<h2>1. Nom du Partenariat et Activité</h2>";
    echo "<p><strong>Nature des activités:</strong> " . htmlspecialchars($_POST['activityType']) . "</p>";
    echo "<p><strong>Nom du Partenariat:</strong> " . htmlspecialchars($_POST['partnershipName']) . "</p>";
    echo "<p><strong>Adresse officielle:</strong> " . htmlspecialchars($_POST['officialAdress']) . "</p>";

    echo "<h2>2. Termes</h2>";
    echo "<p>Le partenariat commence le " . htmlspecialchars($_POST['date']) . " et continue jusqu'à la fin de cet accord.</p>";

    echo "<h2>3. Répartition des bénéfices et des pertes</h2>";
    echo "<p>" . htmlspecialchars($_POST['distributionOfProfitsAndLosses']) . "</p>";

    echo "<h2>4. Modalités bancaires</h2>";
    echo "<p>Les chèques doivent être signés par " . htmlspecialchars($_POST['partnerCount']) . " des partenaires.</p>";

    echo "<h2>5. Juridiction</h2>";
    $countryCode = isset($_POST['country']) ? $_POST['country'] : '';
    $countryName = isset($countries[$countryCode]) ? $countries[$countryCode] : "Pays inconnu";

    echo "<p>Le présent contrat de partenariat commercial est régi par les lois de l'État $countryName.</p>";
    echo "</div>";
    echo "</body>";
    echo "</html>";


    // Sauvegarde des données dans la base de données
    include('functions.php');

    // Connexion à la base de données
    $pdo = connectDB();

    // Récupération des données du formulaire
    $data = getDBData();
    $formData = $data['data'];
    $partners = $data['partners'];
    // Insertion des données dans la base de données
    insertDataIntoForm($pdo, $formData, $partners);
} else {
    echo "<p>Erreur : données du formulaire manquantes.</p>";
}