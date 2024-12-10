<?php
require_once('functions.php');
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$pdo = connectDB();

// Récupération de l'ID de l'utilisateur connecté
$userId = $_SESSION['user']['id'];
error_log("User ID: " . $userId);

// Récupération de la liste des contrats associés à l'utilisateur connecté
$query = "SELECT id, partnership_name, date_creation, num_partners 
          FROM formulaire
          WHERE id_compte = :userId";
$contracts = sqlquery($pdo, $query, [':userId' => $userId])->fetchAll(PDO::FETCH_ASSOC);
error_log("Contracts: " . print_r($contracts, true));

$user = getUserByEmail($_SESSION['user']['email']);
?>

<?php require_once('html_utils/header.php'); ?>

<h1>Liste des Contrats de <?php echo $user['prenom'].' '. $user['nom']?> : </h1>
<div id="new-contract-container">
    <a href="new_contract.php" class="new-contract-button button button-medium">Nouveau Contrat</a>
</div>
<br>

<?php if (count($contracts) > 0): ?>
    <ul class="fiche-contract">
        <?php foreach ($contracts as $contract): ?>
            <li>
                <div class="contract">
                    <div class="contract-info">
                        <p><strong>Nom du contrat :</strong> <?php echo htmlspecialchars($contract['partnership_name']); ?></p>
                        <p><strong>Date de création :</strong> <?php echo htmlspecialchars($contract['date_creation']); ?></p>
                        <p><strong>Nombre de partenaires :</strong> <?php echo htmlspecialchars($contract['num_partners']); ?></p>
                    </div>
                    <div class="contract-actions">
                        <a href="display_contract.php?id=<?php echo $contract['id']; ?>" class="display-button button button-small">Afficher</a>
                        <a href="edit_contract.php?id=<?php echo $contract['id']; ?>" class="edit-button button button-small">Édition</a>
                        <a href="delete_contract.php?id=<?php echo $contract['id']; ?>" class="delete-button button button-small" onclick="return confirmDelete()">Supprimer</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun contrat trouvé.</p>
<?php endif; ?>

<?php require_once('html_utils/footer.php'); ?>
<script src="scripts/create_form.js" defer></script>