<?php
// update_num_partners.php
session_start();

if (isset($_POST['numPartners'])) {
    $_SESSION['numPartners'] = (int)$_POST['numPartners'];
    echo json_encode(['message' => 'Nombre de partenaires mis à jour']);
} else {
    echo json_encode(['message' => 'Erreur : nombre de partenaires non défini']);
}
?>