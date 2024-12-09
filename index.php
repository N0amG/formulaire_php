<?php
require_once('functions.php');

// Connexion à la base de données
$pdo = connectDB();

// Récupération de la liste des contrats
$query = "SELECT id, partnership_name, date_creation, num_partners FROM formulaire";
$contracts = sqlquery($pdo, $query)->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once('html_utils/header.php'); ?>

<h1>Liste des Contrats</h1>
<div>
    <a href="create_contract.php" class="new-contract-button">Nouveau Contrat</a>
</div>
<br>

<?php if (count($contracts) > 0): ?>
    <ul>
        <?php foreach ($contracts as $contract): ?>
            <li>
                <div class="contract">
                    <div class="contract-info">
                        <p><strong>Nom du contrat :</strong> <?php echo htmlspecialchars($contract['partnership_name']); ?></p>
                        <p><strong>Date de création :</strong> <?php echo htmlspecialchars($contract['date_creation']); ?></p>
                        <p><strong>Nombre de partenaires :</strong> <?php echo htmlspecialchars($contract['num_partners']); ?></p>
                    </div>
                    <div class="contract-actions">
                        <a href="display_contract.php?id=<?php echo $contract['id']; ?>" class="display-button">Afficher</a>
                        <a href="edit_contract.php?id=<?php echo $contract['id']; ?>" class="edit-button">Édition</a>
                        <a href="delete_contract.php?id=<?php echo $contract['id']; ?>" class="delete-button">Supprimer</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun contrat trouvé.</p>
<?php endif; ?>

<?php require_once('html_utils/footer.php'); ?>