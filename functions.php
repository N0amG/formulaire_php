<?php
function connectDB()
{
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
function validateFormData($data)
{
    // Validation des données (exemple basique)
    return !empty($data['activity_type']) && !empty($data['partnership_name']);
}

function getDBData(): array {
    // Retourner un tableau avec tous les éléments du formulaire à mettre dans la base de données (sans faire la requête)
    $partners = [];
    
    $numPartners = $_POST['numPartners'] ?? 0;
    for ($i = 1; $i <= $numPartners; $i++) {
        $partners[] = [
            'name' => $_POST["partner$i"] ?? '',
            'contribution' => $_POST["contribution$i"] ?? 0
        ];
    }

    $data = [
        'date_creation' => date('Y-m-d H:i:s'),
        'num_partners' => $numPartners,
        'activity_type' => $_POST['activityType'] ?? '',
        'partnership_name' => $_POST['partnershipName'] ?? '',
        'official_address' => $_POST['officialAdress'] ?? '',
        'start_date' => $_POST['date'] ?? '',
        'profit_loss_distribution' => $_POST['distributionOfProfitsAndLosses'] ?? '',
        'signing_partner_count' => $_POST['partnerCount'] ?? 0,
        'country_code' => $_POST['country'] ?? '',
        'country_name' => $_POST['country'] ?? ''
    ];

    return ['data' => $data, 'partners' => $partners];
}

function sqlquery($pdo, $query, $params = [])
{
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function insertDataIntoForm($pdo, $data, $partners) {
    try {
        $query = "INSERT INTO formulaire 
                  (date_creation, num_partners, activity_type, partnership_name, 
                   official_address, start_date, profit_loss_distribution, signing_partner_count, 
                   country_code, country_name) 
                  VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                          :official_address, :start_date, :profit_loss_distribution, 
                          :signing_partner_count, :country_code, :country_name)";
        sqlquery($pdo, $query, $data);
        $idForm = $pdo->lastInsertId();

        $queryPartenaire = "INSERT INTO partenaire (nom) VALUES (:nom)";
        $queryPartenaireFormulaire = "INSERT INTO partenaire_formulaire (formulaire_id, partenaire_id, contribution) 
                                      VALUES (:formulaire_id, :partenaire_id, :contribution)";
        
        foreach ($partners as $partner) {
            $partnerName = $partner['name'];
            $contribution = $partner['contribution'];

            // Insérer le partenaire
            try {
                sqlquery($pdo, $queryPartenaire, [':nom' => $partnerName]);
                $idPartenaire = $pdo->lastInsertId();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Code d'erreur pour violation de contrainte d'unicité
                    // Récupérer l'ID du partenaire existant
                    $stmt = sqlquery($pdo, "SELECT id FROM partenaire WHERE nom = :nom", [':nom' => $partnerName]);
                    $idPartenaire = $stmt->fetchColumn();
                } else {
                    throw $e;
                }
            }

            // Insérer la relation formulaire-partenaire avec contribution
            try {
                sqlquery($pdo, $queryPartenaireFormulaire, [
                    ':formulaire_id' => $idForm,
                    ':partenaire_id' => $idPartenaire,
                    ':contribution' => $contribution
                ]);
            } catch (PDOException $e) {
                if ($e->getCode() != 23000) { // Ignorer les doublons dans la table de relation
                    throw $e;
                }
            }
        }
        return $idForm;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Code d'erreur pour violation de contrainte d'unicité
            // Gérer les doublons ici (par exemple, afficher un message ou ignorer l'erreur)
            echo ""; //Erreur : Un enregistrement avec les mêmes données existe déjà.";
        } else {
            // Gérer les autres erreurs
            echo "Erreur : " . $e->getMessage();
        }
        return false;
    }
}