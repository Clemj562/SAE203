<?php
include_once 'db.php';

function ajouterBoutique($nom, $ville, $pays, $numero_rue, $utilisateur_id, $nom_adresse, $code_postal) {
    $boutiquesExistantes = dbquery("SELECT * FROM boutiques WHERE nom = ?", [$nom]);
    if (count($boutiquesExistantes) > 0) {
        echo "Erreur : une boutique avec ce nom existe déjà.";
        return false;
    }
    $param = [$nom, $ville, $pays, $numero_rue, $utilisateur_id, $nom_adresse, $code_postal];
    $result = dbquery("INSERT INTO boutiques (nom, ville, pays, numero_rue, utilisateur_id, nom_adresse, code_postal) VALUES (?, ?, ?, ?, ?, ?, ?)", $param);
    if ($result !== false) {
        echo "Boutique ajoutée avec succès !";
        return true;
    } else {
        echo "Erreur lors de l'ajout de la boutique.";
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $ville = $_POST['ville'];
    $pays = $_POST['pays'];
    $numero_rue = $_POST['numero_rue'];
    $utilisateur_id = $_POST['utilisateur_id'];
    $nom_adresse = $_POST['nom_adresse'];
    $code_postal = $_POST['code_postal'];
    ajouterBoutique($nom, $ville, $pays, $numero_rue, $utilisateur_id, $nom_adresse, $code_postal);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une boutique</title>
</head>
<body>
    <h2>Ajouter une nouvelle boutique</h2>
    <form method="post">
        <div>
            <label>Nom de la boutique :</label>
            <input type="text" name="nom" required>
        </div>
        <div>
            <label>Ville :</label>
            <input type="text" name="ville" required>
        </div>
        <div>
            <label>Pays :</label>
            <input type="text" name="pays" required>
        </div>
        <div>
            <label>Numéro et rue :</label>
            <input type="text" name="numero_rue">
        </div>
        <div>
            <label>ID utilisateur :</label>
            <input type="number" name="utilisateur_id">
        </div>
        <div>
            <label>Nom de l'adresse :</label>
            <input type="text" name="nom_adresse">
        </div>
        <div>
            <label>Code postal :</label>
            <input type="text" name="code_postal">
        </div>
        <div>
            <label>Gérant :</label>
            <input type="text" name="gerant">
        </div>
        <div>
            <label>ID boutique (optionnel) :</label>
            <input type="text" name="id" placeholder="Laisser vide pour auto-génération">
        </div>
        <button type="submit">Ajouter la boutique</button>
    </form>
</body>
</html>