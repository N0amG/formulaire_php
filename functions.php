<?php
// functions.php

function connectDB()
{
    $config = include('config.php');
    $db = $config['db'];

    try {
        $pdo = new PDO("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password']);
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

function getPOSTData(): array
{
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
        'end_date' => $_POST['date_fin'] ?? '',
        'profit_loss_distribution' => $_POST['distributionOfProfitsAndLosses'] ?? '',
        'signing_partner_count' => $_POST['partnerCount'] ?? 0,
        'country_code' => $_POST['country'] ?? '',
    ];

    return ['data' => $data, 'partners' => $partners];
}

function sqlquery($pdo, $query, $params = [])
{
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function insertDataIntoForm($pdo, $data, $partners)
{
    try {
        $query = "INSERT INTO formulaire 
          (date_creation, num_partners, activity_type, partnership_name, 
           official_address, start_date, end_date, profit_loss_distribution, signing_partner_count, 
           country_code) 
          VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                  :official_address, :start_date, :end_date, :profit_loss_distribution, 
                  :signing_partner_count, :country_code)";
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
        // Gérer les erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}

function pushToDatabase()
{

    // Connexion à la base de données
    $pdo = connectDB();

    // Récupération des données du formulaire
    $data = getPOSTData();
    $formData = $data['data'];
    $partners = $data['partners'];
    // Insertion des données dans la base de données
    insertDataIntoForm($pdo, $formData, $partners);

}

function getFormDataById($pdo, $formId): array
{
    // Récupérer les informations du formulaire
    $queryForm = "SELECT * FROM formulaire WHERE id = :id";
    $stmtForm = sqlquery($pdo, $queryForm, [':id' => $formId]);
    $formData = $stmtForm->fetch(PDO::FETCH_ASSOC);

    if (!$formData) {
        throw new Exception("Formulaire non trouvé pour l'ID $formId");
    }

    // Récupérer les partenaires associés au formulaire
    $queryPartners = "SELECT p.nom, pf.contribution FROM partenaire p
                      JOIN partenaire_formulaire pf ON p.id = pf.partenaire_id
                      WHERE pf.formulaire_id = :formulaire_id";
    $partners = sqlquery($pdo, $queryPartners, [':formulaire_id' => $formId])->fetchAll(PDO::FETCH_ASSOC);

    $data = [
        'date_creation' => $formData['date_creation'],
        'num_partners' => $formData['num_partners'],
        'activity_type' => $formData['activity_type'],
        'partnership_name' => $formData['partnership_name'],
        'official_address' => $formData['official_address'],
        'start_date' => $formData['start_date'],
        'end_date' => $formData['end_date'],
        'profit_loss_distribution' => $formData['profit_loss_distribution'],
        'signing_partner_count' => $formData['signing_partner_count'],
        'country_code' => $formData['country_code'],
    ];

    return ['data' => $data, 'partners' => $partners];
}

function updateContract($pdo, $formId, $formData, $partners) {
    try {
        // Début de la transaction
        $pdo->beginTransaction();

        // Mise à jour des informations du formulaire
        $queryUpdateForm = "UPDATE formulaire SET 
            date_creation = :date_creation, 
            num_partners = :num_partners, 
            activity_type = :activity_type, 
            partnership_name = :partnership_name, 
            official_address = :official_address, 
            start_date = :start_date, 
            end_date = :end_date, 
            profit_loss_distribution = :profit_loss_distribution, 
            signing_partner_count = :signing_partner_count, 
            country_code = :country_code 
            WHERE id = :id";
        $formData['id'] = $formId;
        sqlquery($pdo, $queryUpdateForm, $formData);

        // Suppression des relations existantes dans la table partenaire_formulaire
        $queryDeleteRelations = "DELETE FROM partenaire_formulaire WHERE formulaire_id = :formulaire_id";
        sqlquery($pdo, $queryDeleteRelations, [':formulaire_id' => $formId]);

        // Insertion des nouvelles relations dans la table partenaire_formulaire
        $queryPartenaireFormulaire = "INSERT INTO partenaire_formulaire (formulaire_id, partenaire_id, contribution) 
                                      VALUES (:formulaire_id, :partenaire_id, :contribution)";
        foreach ($partners as $partner) {
            $partnerName = $partner['name'];
            $contribution = $partner['contribution'];

            // Récupérer l'ID du partenaire existant ou insérer un nouveau partenaire
            $stmt = sqlquery($pdo, "SELECT id FROM partenaire WHERE nom = :nom", [':nom' => $partnerName]);
            $idPartenaire = $stmt->fetchColumn();
            if (!$idPartenaire) {
                sqlquery($pdo, "INSERT INTO partenaire (nom) VALUES (:nom)", [':nom' => $partnerName]);
                $idPartenaire = $pdo->lastInsertId();
            }

            // Insérer la relation formulaire-partenaire avec contribution
            sqlquery($pdo, $queryPartenaireFormulaire, [
                ':formulaire_id' => $formId,
                ':partenaire_id' => $idPartenaire,
                ':contribution' => $contribution
            ]);
        }

        // Validation de la transaction
        $pdo->commit();
    } catch (Exception $e) {
        // Annulation de la transaction en cas d'erreur
        $pdo->rollBack();
        throw $e;
    }
}