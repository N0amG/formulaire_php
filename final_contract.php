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
} else {
    echo "<p>Erreur : données du formulaire manquantes.</p>";
}

$host = 'localhost';        // Hôte (souvent localhost)
$dbname = 'formulaire_db';  // Nom de la base de données
$username = 'root';  // Nom d'utilisateur
$password = '';   // Mot de passe

// Connexion PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Activer le mode d'erreur de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Récupérer les données du formulaire
    $date_creation = date('Y-m-d');
    $num_partners = $_POST['numPartners'];
    $activity_type = $_POST['activityType'];
    $partnership_name = $_POST['partnershipName'];
    $official_address = $_POST['officialAdress'];
    $start_date = $_POST['date'];
    $profit_loss_distribution = $_POST['distributionOfProfitsAndLosses'];
    $signing_partner_count = $_POST['partnerCount'];
    $country_code = $_POST['country'];
    $country_name = isset($countries[$country_code]) ? $countries[$country_code] : 'Pays inconnu';

    // Vérifier si le partenariat existe déjà
    $queryCheck = "SELECT COUNT(*) FROM formulaire WHERE partnership_name = :partnership_name";
    $stmtCheck = $pdo->prepare($queryCheck);
    $stmtCheck->execute([':partnership_name' => $partnership_name]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
        echo ""; // Le partenariat existe déjà ne rien faire
    } else {
        // Préparer la requête SQL pour insérer les données
        $query = "INSERT INTO formulaire 
                  (date_creation, num_partners, activity_type, partnership_name, 
                   official_address, start_date, profit_loss_distribution, signing_partner_count, 
                   country_code, country_name) 
                  VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                          :official_address, :start_date, :profit_loss_distribution, 
                          :signing_partner_count, :country_code, :country_name)";

        // Préparer et exécuter la requête
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':date_creation' => $date_creation,
            ':num_partners' => $num_partners,
            ':activity_type' => $activity_type,
            ':partnership_name' => $partnership_name,
            ':official_address' => $official_address,
            ':start_date' => $start_date,
            ':profit_loss_distribution' => $profit_loss_distribution,
            ':signing_partner_count' => $signing_partner_count,
            ':country_code' => $country_code,
            ':country_name' => $country_name
        ]);

        // Les données ont été insérées avec succès
    }

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>