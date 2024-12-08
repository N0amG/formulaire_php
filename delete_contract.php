<?php
include('functions.php');

// Connexion à la base de données
$pdo = connectDB();

// Vérification de l'ID du formulaire dans l'URL
if (isset($_GET['id'])) {
    $formId = (int)$_GET['id'];

    try {
        // Début de la transaction
        $pdo->beginTransaction();

        // Suppression des relations dans la table partenaire_formulaire
        $queryDeleteRelations = "DELETE FROM partenaire_formulaire WHERE formulaire_id = :formulaire_id";
        sqlquery($pdo, $queryDeleteRelations, [':formulaire_id' => $formId]);

        // Suppression du formulaire
        $queryDeleteForm = "DELETE FROM formulaire WHERE id = :id";
        sqlquery($pdo, $queryDeleteForm, [':id' => $formId]);

        // Validation de la transaction
        $pdo->commit();

        // Redirection vers index.php
        header("Location: index.php");
    } catch (Exception $e) {
        // Annulation de la transaction en cas d'erreur
        $pdo->rollBack();
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Erreur : ID du formulaire manquant.</p>";
}
?>