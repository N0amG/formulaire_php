<?php
try {
    $pdo = new PDO('sqlite:database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer une table utilisateurs
    $query = "CREATE TABLE IF NOT EXISTS formulaire (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL
              )";
    $pdo->exec($query);
    
    
    $query = "DROP TABLE IF EXISTS formulaire";
    $pdo->exec($query);

    echo "Table créée avec succès.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
 

