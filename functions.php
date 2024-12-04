<?php
function connectDB() {
    $host = 'localhost';        
    $dbname = 'formulaire_db';  
    $username = 'root';  
    $password = '';   

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
function validateFormData($data) {
    // Validation des données (exemple basique)
    return !empty($data['activity_type']) && !empty($data['partnership_name']);
}

function getDBData(): array {
    // retourner un tableau avec tous les élément du formulaire a mettre dans la base de donnée (sans faire la requete) : id date_creation num_partners activity_type partnership_name official_address start_date profit_loss_distribution signing_partner_count country_code country_name
    
    $partners = [];
    
    for ($i = 1; $i <= $_POST['num_partners']; $i++) {
        $partners[$i] = [
            'name' => $_POST["partner$i"],
            'contribution' => $_POST["contribution$i"]
        ];

    }

    $data = [
        'date_creation' => date('Y-m-d H:i:s'),
        'num_partners' => $_POST['num_partners'],
        'activity_type' => $_POST['activity_type'],
        'partnership_name' => $_POST['partnership_name'],
        'official_address' => $_POST['official_address'],
        'start_date' => $_POST['start_date'],
        'profit_loss_distribution' => $_POST['profit_loss_distribution'],
        'signing_partner_count' => $_POST['signing_partner_count'],
        'country_code' => $_POST['country_code'],
        'country_name' => $_POST['country_name'],
        'partners' => $partners
    ];

    return $data;
}

function sqlquery($pdo, $query, $params = []) {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function insertData($pdo, $data) {
    // Extraire les données du formulaire sans les partenaires
    $formData = $data;
    unset($formData['partners']);

    $query = "INSERT INTO formulaire 
              (date_creation, num_partners, activity_type, partnership_name, 
               official_address, start_date, profit_loss_distribution, signing_partner_count, 
               country_code, country_name) 
              VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                      :official_address, :start_date, :profit_loss_distribution, 
                      :signing_partner_count, :country_code, :country_name)";
    sqlquery($pdo, $query, $formData);
    $idForm = $pdo->lastInsertId();

    $queryPartenaire = "INSERT INTO partenaire (nom) VALUES (:nom)";
    $queryPartenaireFormulaire = "INSERT INTO partenaire_formulaire (formulaire_id, partenaire_id, contribution) 
                                  VALUES (:formulaire_id, :partenaire_id, :contribution)";
    
    foreach ($data['partners'] as $partner) {
        $partnerName = $partner['name'];
        $contribution = $partner['contribution'];

        // Insérer le partenaire
        sqlquery($pdo, $queryPartenaire, [':nom' => $partnerName]);
        $idPartenaire = $pdo->lastInsertId();

        // Insérer la relation formulaire-partenaire avec contribution
        sqlquery($pdo, $queryPartenaireFormulaire, [
            ':formulaire_id' => $idForm,
            ':partenaire_id' => $idPartenaire,
            ':contribution' => $contribution
        ]);
    }
    return $idForm;
}