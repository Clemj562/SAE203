<?php
include_once 'db.php';

function ajouterProduit($nom, $description, $illustration, $type, $prix) {
    $confiseriesExistantes = dbquery("SELECT * FROM confiseries WHERE nom = ?", [$nom]);
    if (count($confiseriesExistantes) > 0) {
        echo "Erreur : une confiserie avec ce nom existe déjà.";
        return false;
    }
    // On suppose que l'ID est auto-incrémenté
    $result = dbquery(
        "INSERT INTO confiseries (nom, description, illustration, type, prix) VALUES (?, ?, ?, ?, ?)",
        [$nom, $description, $illustration, $type, $prix]
    );
    if ($result !== false) {
        echo "Produit ajouté avec succès.";
        return true;
    } else {
        echo "Erreur lors de l'ajout de la confiserie.";
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $illustration = $_POST['illustration'];
    $prix = $_POST['prix'];
    $type = $_POST['type'];
    ajouterProduit($nom, $description, $illustration, $type, $prix);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>GESTION ADMIN</title>
</head>
<body>
    <h2>Ajouter une nouvelle confiserie</h2>
    <form method="post">
        <div>
            <label>Nom :</label>
            <input type="text" name="nom" required>
        </div>
        <div>
            <label>Description :</label>
            <input type="text" name="description" required>
        </div>
        <div>
            <label>Illustration :</label>
            <input type="text" name="illustration" required>
        </div>
        <div>
            <label>Prix :</label>
            <input type="text" name="prix" required>
        </div>
        <div>
            <label>Type :</label>
            <input type="text" name="type">
        </div>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>