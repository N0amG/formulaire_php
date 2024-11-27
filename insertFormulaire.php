<?php
include 'db.php';  // Inclure la fonction de connexion à la BDD

function insertFormulaire($formData) {
    $pdo = getDbConnection();
    
    $query = "INSERT INTO formulaire 
              (date_creation, num_partners, activity_type, partnership_name, 
               official_address, start_date, profit_loss_distribution, signing_partner_count, 
               country_code, country_name) 
              VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                      :official_address, :start_date, :profit_loss_distribution, 
                      :signing_partner_count, :country_code, :country_name)";

    $stmt = $pdo->prepare($query);
    $stmt->execute($formData);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $date_creation = date('Y-m-d'); // Date de création
    $num_partners = $_POST['numPartners'];
    $activity_type = $_POST['activityType'];
    $partnership_name = $_POST['partnershipName'];
    $official_address = $_POST['officialAdress'];
    $start_date = $_POST['date'];
    $profit_loss_distribution = $_POST['distributionOfProfitsAndLosses'];
    $signing_partner_count = $_POST['partnerCount'];
    $country_code = $_POST['country'];
    $country_name = isset($countries[$country_code]) ? $countries[$country_code] : 'Pays inconnu';

    // Préparer les données pour l'insertion
    $formData = [
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
    ];

    if (validateFormData($formData)) {
        insertFormulaire($formData);
    } else {
        echo "Les données du formulaire sont invalides.";
    }
}
?>
