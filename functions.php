<?php
// functions.php

function consoleLog($message)
{
    echo "<script>console.log('$message')</script>";
}
function connectDB()
{
    $db = [
        'host' => 'localhost',
        'dbname' => 'formulaire_db',
        'username' => 'root',
        'password' => '',
    ];

    try {
        $pdo = new PDO("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        consoleLog("Connexion réussie");
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function getPOSTData(): array
{
    $partners = [];
    $partnerNames = $_POST['partner'] ?? [];
    $partnerContributions = $_POST['contribution'] ?? [];
    $partnerIds = $_POST['partner_id'] ?? [];

    $numPartners = count($partnerNames);

    for ($i = 0; $i < $numPartners; $i++) {
        $partners[] = [
            'id' => !empty($partnerIds[$i]) ? $partnerIds[$i] : null,
            'name' => $partnerNames[$i] ?? '',
            'contribution' => $partnerContributions[$i] ?? ''
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
        $pdo->beginTransaction();

        // Insérer le formulaire dans la table 'formulaire'
        $query = "INSERT INTO formulaire 
          (date_creation, num_partners, activity_type, partnership_name, 
           official_address, start_date, end_date, profit_loss_distribution, signing_partner_count, 
           country_code) 
          VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                  :official_address, :start_date, :end_date, :profit_loss_distribution, 
                  :signing_partner_count, :country_code)";
        sqlquery($pdo, $query, $data);
        $idForm = $pdo->lastInsertId();

        if (!$idForm) {
            throw new Exception("Erreur lors de l'insertion du formulaire.");
        }

        $submittedPartnerIds = [];
        foreach ($partners as $partner) {
            if (!empty($partner['id'])) {
                // Partenaire existant, récupérer l'ID
                $submittedPartnerIds[] = $partner['id'];
                $idPartenaire = $partner['id'];
            } else {
                // Nouveau partenaire, insérer
                $stmt = $pdo->prepare('INSERT INTO partenaire (nom) VALUES (:nom)');
                $stmt->execute(['nom' => $partner['name']]);
                $idPartenaire = $pdo->lastInsertId();
                $submittedPartnerIds[] = $idPartenaire;
            }

            // Insérer la relation formulaire-partenaire avec contribution
            $stmt = $pdo->prepare('INSERT INTO partenaire_formulaire (formulaire_id, partenaire_id, contribution) VALUES (:formulaire_id, :partenaire_id, :contribution)');
            $stmt->execute([
                'formulaire_id' => $idForm,
                'partenaire_id' => $idPartenaire,
                'contribution' => $partner['contribution']
            ]);
        }

        $pdo->commit();
        return $idForm;
    } catch (Exception $e) {
        $pdo->rollBack();
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
    return insertDataIntoForm($pdo, $formData, $partners);

}

function getFormDataById($pdo, $formId) {
    // Récupérer les informations du formulaire
    $queryForm = "SELECT * FROM formulaire WHERE id = :id";
    $formData = sqlquery($pdo, $queryForm, [':id' => $formId])->fetch(PDO::FETCH_ASSOC);

    if (!$formData) {
        throw new Exception("Formulaire non trouvé pour l'ID $formId");
    }

    // Récupérer les partenaires associés au formulaire
    $queryPartners = "SELECT p.id, p.nom, pf.contribution FROM partenaire p
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

function updateContract($pdo, $formId, $contractData, $partnersData) {
    try {
        $pdo->beginTransaction();

        // Mise à jour du formulaire
        $stmt = $pdo->prepare('UPDATE formulaire SET date_creation = :date_creation, num_partners = :num_partners, activity_type = :activity_type, partnership_name = :partnership_name, official_address = :official_address, start_date = :start_date, end_date = :end_date, profit_loss_distribution = :profit_loss_distribution, signing_partner_count = :signing_partner_count, country_code = :country_code WHERE id = :id');
        $contractData['id'] = $formId;
        $stmt->execute($contractData);

        // Obtenir la liste des partenaires actuels associés au contrat
        $stmt = $pdo->prepare('SELECT partenaire_id FROM partenaire_formulaire WHERE formulaire_id = :formulaire_id');
        $stmt->execute(['formulaire_id' => $formId]);
        $currentPartnerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $submittedPartnerIds = [];
        foreach ($partnersData as $partner) {
            if (!empty($partner['id'])) {
                // Partenaire existant, mettre à jour
                $submittedPartnerIds[] = $partner['id'];

                // Mettre à jour le nom du partenaire
                $stmt = $pdo->prepare('UPDATE partenaire SET nom = :nom WHERE id = :id');
                $stmt->execute(['nom' => $partner['name'], 'id' => $partner['id']]);

                // Mettre à jour la contribution dans partenaire_formulaire
                $stmt = $pdo->prepare('UPDATE partenaire_formulaire SET contribution = :contribution WHERE formulaire_id = :formulaire_id AND partenaire_id = :partenaire_id');
                $stmt->execute([
                    'contribution' => $partner['contribution'],
                    'formulaire_id' => $formId,
                    'partenaire_id' => $partner['id']
                ]);
            } else {
                // Nouveau partenaire, insérer
                // Insérer le partenaire dans la table 'partenaire'
                $stmt = $pdo->prepare('INSERT INTO partenaire (nom) VALUES (:nom)');
                $stmt->execute(['nom' => $partner['name']]);
                $newPartnerId = $pdo->lastInsertId();

                // Lier le nouveau partenaire au formulaire
                $stmt = $pdo->prepare('INSERT INTO partenaire_formulaire (formulaire_id, partenaire_id, contribution) VALUES (:formulaire_id, :partenaire_id, :contribution)');
                $stmt->execute([
                    'formulaire_id' => $formId,
                    'partenaire_id' => $newPartnerId,
                    'contribution' => $partner['contribution']
                ]);

                $submittedPartnerIds[] = $newPartnerId;
            }
        }

        // Supprimer les partenaires qui ne sont plus dans le formulaire
        $partnersToDelete = array_diff($currentPartnerIds, $submittedPartnerIds);
        if (!empty($partnersToDelete)) {
            // Supprimer les entrées de partenaire_formulaire
            $stmt = $pdo->prepare('DELETE FROM partenaire_formulaire WHERE formulaire_id = :formulaire_id AND partenaire_id IN (' . implode(',', array_map('intval', $partnersToDelete)) . ')');
            $stmt->execute(['formulaire_id' => $formId]);

            // Supprimer les partenaires s'ils ne sont plus associés à d'autres formulaires
            $stmt = $pdo->prepare('DELETE FROM partenaire WHERE id IN (' . implode(',', array_map('intval', $partnersToDelete)) . ') AND id NOT IN (SELECT partenaire_id FROM partenaire_formulaire)');
            $stmt->execute();
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}