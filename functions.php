<?php
// functions.php

function consoleLog($message)
{
    // Affiche un message dans la console du navigateur
    echo "<script>console.log('$message')</script>";
}

function connectDB()
{
    // Configuration des informations de connexion à la base de données
    $db = [
        'host' => 'localhost',
        'dbname' => 'formulaire_db',
        'username' => 'root',
        'password' => '',
    ];

    try {
        // Création d'une nouvelle connexion PDO
        $pdo = new PDO("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password']);
        // Configuration de l'attribut ERRMODE pour lancer des exceptions en cas d'erreur
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // En cas d'erreur de connexion, affichage du message d'erreur et arrêt du script
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
    echo 'Données : '.$_POST['date_debut'] ?? '';

    $data = [
        'date_creation' => date('Y-m-d H:i:s'),
        'num_partners' => $numPartners,
        'activity_type' => $_POST['activityType'] ?? '',
        'partnership_name' => $_POST['partnershipName'] ?? '',
        'official_address' => $_POST['officialAdress'] ?? '',
        'start_date' => $_POST['date_debut'] ?? '',
        'end_date' => $_POST['date_fin'] ?? '',
        'profit_loss_distribution' => $_POST['distributionOfProfitsAndLosses'] ?? '',
        'signing_partner_count' => $_POST['partnerCount'] ?? 0,
        'country_code' => $_POST['country'] ?? '',
    ];

    return ['data' => $data, 'partners' => $partners];
}

function sqlquery($pdo, $query, $params = [])
{
    // Prépare et exécute une requête SQL avec des paramètres
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function formatDateForDisplay($date)
{
    // Formate une date au format 'd/m/Y'
    return date('d/m/Y', strtotime($date));
}
function insertDataIntoForm($pdo, $data, $partners)
{
    try {
        // Démarrer une transaction
        $pdo->beginTransaction();

        // Récupérer l'ID de l'utilisateur connecté à partir de la session
        session_start();
        if (!isset($_SESSION['user'])) {
            throw new Exception("Utilisateur non connecté.");
        }
        $userId = $_SESSION['user']['id'];

        // Ajouter l'ID de l'utilisateur aux données du formulaire
        $data['id_compte'] = $userId;

        // Vérifier si le nom du partenariat existe déjà
        $stmt = $pdo->prepare('SELECT id FROM formulaire WHERE partnership_name = :partnership_name');
        $stmt->execute(['partnership_name' => $data['partnership_name']]);
        $existingForm = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingForm) {
            // Si le nom du partenariat existe déjà, lancer une exception
            throw new Exception("Le nom du partenariat existe déjà. Veuillez choisir un autre nom.");
        }

        // Insérer le formulaire dans la table 'formulaire'
        $query = "INSERT INTO formulaire 
          (date_creation, num_partners, activity_type, partnership_name, 
           official_address, start_date, end_date, profit_loss_distribution, signing_partner_count, 
           country_code, id_compte) 
          VALUES (:date_creation, :num_partners, :activity_type, :partnership_name, 
                  :official_address, :start_date, :end_date, :profit_loss_distribution, 
                  :signing_partner_count, :country_code, :id_compte)";
        sqlquery($pdo, $query, $data);
        $idForm = $pdo->lastInsertId();

        if (!$idForm) {
            // Si l'insertion du formulaire échoue, lancer une exception
            throw new Exception("Erreur lors de l'insertion du formulaire.");
        }

        foreach ($partners as $partner) {
            // Vérifier si le partenaire existe déjà
            $stmt = $pdo->prepare('SELECT id FROM partenaire WHERE nom = :nom');
            $stmt->execute(['nom' => $partner['name']]);
            $existingPartner = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingPartner) {
                // Le partenaire existe, utiliser son ID
                $idPartenaire = $existingPartner['id'];
            } else {
                // Nouveau partenaire, insérer
                $stmt = $pdo->prepare('INSERT INTO partenaire (nom) VALUES (:nom)');
                $stmt->execute(['nom' => $partner['name']]);
                $idPartenaire = $pdo->lastInsertId();
            }

            // Insérer la relation formulaire-partenaire avec contribution
            $stmt = $pdo->prepare('INSERT INTO partenaire_formulaire (formulaire_id, partenaire_id, contribution) VALUES (:formulaire_id, :partenaire_id, :contribution)');
            $stmt->execute([
                'formulaire_id' => $idForm,
                'partenaire_id' => $idPartenaire,
                'contribution' => $partner['contribution']
            ]);
        }

        // Valider la transaction
        $pdo->commit();
        return $idForm;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
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

function getFormDataById($pdo, $formId)
{
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
        'start_date' => $formData['start_date'], // Utiliser formatDateForInput pour les champs de formulaire
        'end_date' => $formData['end_date'], // Utiliser formatDateForInput pour les champs de formulaire
        'profit_loss_distribution' => $formData['profit_loss_distribution'],
        'signing_partner_count' => $formData['signing_partner_count'],
        'country_code' => $formData['country_code'],
    ];

    return ['data' => $data, 'partners' => $partners];
}

function updateContract($pdo, $formId, $contractData, $partnersData)
{
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
                // Vérifier si le partenaire existe déjà
                $stmt = $pdo->prepare('SELECT id FROM partenaire WHERE nom = :nom');
                $stmt->execute(['nom' => $partner['name']]);
                $existingPartner = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingPartner) {
                    // Le partenaire existe, utiliser son ID
                    $newPartnerId = $existingPartner['id'];
                } else {
                    // Nouveau partenaire, insérer
                    $stmt = $pdo->prepare('INSERT INTO partenaire (nom) VALUES (:nom)');
                    $stmt->execute(['nom' => $partner['name']]);
                    $newPartnerId = $pdo->lastInsertId();
                }

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

function fetchPartnerNames($term) {
    $pdo = connectDB();

    $stmt = $pdo->prepare('SELECT nom FROM partenaire WHERE nom LIKE :term');
    $stmt->execute(['term' => '%' . $term . '%']);

    return json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
}

function fetchContributions($term) {
    $pdo = connectDB();

    $stmt = $pdo->prepare('SELECT contribution FROM partenaire_formulaire WHERE contribution LIKE :term');
    $stmt->execute(['term' => '%' . $term . '%']);

    return json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
}


function getUserByEmail($email) {
    $pdo = connectDB();

    $stmt = $pdo->prepare('SELECT * FROM compte WHERE email = :email');
    $stmt->execute(['email' => $email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function passwordVerify($email, $password) {
    $pdo = connectDB();

    $stmt = $pdo->prepare('SELECT mdp FROM compte WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdo = null; // Close the connection

    return ($user && $password === $user['mdp']);
}

?>