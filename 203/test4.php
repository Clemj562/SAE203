<?php
include_once ("header.php");

const DB_DRIVER = 'mysql';
const DB_HOST = 'localhost';
const DB_PORT = 3306;
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = 'sae_203';

try {
    $pdo = new PDO(DB_DRIVER.":host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_DATABASE.";charset=utf8", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des boutiques pour le filtre
$stmt_boutiques = $pdo->query("SELECT id, nom FROM boutiques ORDER BY nom");
$boutiques = $stmt_boutiques->fetchAll(PDO::FETCH_ASSOC);

// Gestion du filtre par boutique
$boutique_selectionnee = isset($_GET['boutique']) ? (int)$_GET['boutique'] : 0;

// Requête pour récupérer les confiseries selon la boutique sélectionnée
// On récupère : id, nom, prix, illustration de confiseries + boutique_id de stocks + id de boutiques
if ($boutique_selectionnee > 0) {
    $sql = "SELECT c.id, c.nom, c.prix, c.illustration, c.description, s.boutique_id, b.nom as nom_boutique 
            FROM confiseries c 
            INNER JOIN stocks s ON c.id = s.confiserie_id 
            INNER JOIN boutiques b ON s.boutique_id = b.id 
            WHERE s.boutique_id = :boutique_id AND s.quantite > 0
            ORDER BY c.nom";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':boutique_id', $boutique_selectionnee, PDO::PARAM_INT);
} else {
    // Afficher toutes les confiseries disponibles (avec stock > 0)
    $sql = "SELECT c.id, c.nom, c.prix, c.illustration, c.description, s.boutique_id, b.nom as nom_boutique 
            FROM confiseries c 
            INNER JOIN stocks s ON c.id = s.confiserie_id 
            INNER JOIN boutiques b ON s.boutique_id = b.id 
            WHERE s.quantite > 0
            ORDER BY b.nom, c.nom";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$confiseries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Confiseries</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .boutique-btn {
                margin: 5px;
                padding: 10px 15px;
                cursor: pointer;
                background-color: #f3f3f3;
                border: 1px solid #ccc;
                border-radius: 4px;
                text-decoration: none;
                display: inline-block;
            }
            .boutique-btn:hover {
                background-color: #e0e0e0;
            }
            .boutique-btn.active {
                background-color: #007cba;
                color: white;
            }
            .carte {
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
                margin: 10px;
                width: 200px;
                text-align: center;
                box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
            }
            .carte img {
                width: 100px;
                height: 100px;
                object-fit: cover;
            }
            .cartes-container {
                display: flex;
                flex-wrap: wrap;
            }
            .nom-produit-catalogue {
                font-size: 16px;
                margin: 10px 0;
            }
            .prix-produit-catalogue {
                color: #007cba;
                font-weight: bold;
            }
            .carte a {
                display: inline-block;
                margin-top: 10px;
                padding: 8px 15px;
                background-color: #007cba;
                color: white;
                text-decoration: none;
                border-radius: 3px;
            }
            .carte a:hover {
                background-color: #005a8b;
            }
        </style>
</head>
<body>
    <body>
        <!-- Filtres par boutique -->
        <div>
            <h2>Choisir une boutique :</h2>
            <a href="?boutique=0" class="boutique-btn <?php echo $boutique_selectionnee == 0 ? 'active' : ''; ?>">
                Toutes les boutiques
            </a>
            <?php foreach ($boutiques as $boutique): ?>
                <a href="?boutique=<?php echo $boutique['id']; ?>" 
                   class="boutique-btn <?php echo $boutique_selectionnee == $boutique['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($boutique['nom']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Génération des cartes produits en PHP (remplace le JavaScript) -->
        <div id="catalogue" class="cartes-container">
            <?php if (count($confiseries) > 0): ?>
                <?php foreach ($confiseries as $produit): ?>
                    <div class="carte">
                        <img src="images/<?php echo htmlspecialchars($produit['illustration']); ?>" 
                             alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                        
                        <h2 class="nom-produit-catalogue">
                            <?php echo htmlspecialchars($produit['nom']); ?>
                        </h2>
                        
                        <h4 class="prix-produit-catalogue">
                            <?php echo number_format($produit['prix'], 2, ',', ' '); ?> €
                        </h4>
                        
                        <a href="produit.php?id=<?php echo $produit['id']; ?>">
                            Voir le produit
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun produit disponible pour cette boutique.</p>
            <?php endif; ?>
        </div>
    </body>
</html>

<style>
    :root{
        --primary-color: #4CAF50;
        --secondary-color: #8a4141; 
        --text-color: black; 
        --border-color: #ccc; 
    }

    h1 {
        color: var(--primary-color);
    }

    h3 {
        color: var(--secondary-color);
    }

    .titre h2 {
        font-size: 5rem;
        color: black;
        font-style: normal;
    }

    .titre h5 {
        font-size: 2.5rem;
        color: black;
        font-style: normal;
    }

    .titre .bordure {
        width: 50%;
        display: block;
        margin-left: auto;
        margin-right: auto;
        border: 1.5px solid black;
    }

    .produits {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .produit {
        padding: 1rem;
        border: 1px solid var(--border-color);
        width: 200px;
        text-align: center;
        border-radius: 10px;
    }
    .produit img {
        width: 100%;
        height: auto;
    }

    .produit .nom {
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .produit .prix {
        margin-top: 0.3rem;
        color: #333;
    }
</style>


<?php

include_once ("header.php");

?>

<h1>CATALOGUE</h1>
<h3>Description</h3>

<h2>Vous avez choisi le magasin de</h2>
<h2 class="nom-boutique">Lannion</h2>

<!--
<h1> EXEMPLE PROF </h1>

<?php

//contenu BDD

$un_super_tableau = [

    ["id" => 1, "nom" => "Carambar", "prix" => 0.50, "description" => "Un bonbon caramel qui colle aux dents !", "image" => "carambar.jpg"],
    ["id" => 2, "nom" => "Chupa Chups", "prix" => 0.70, "description" => "Une sucette qui fait plaisir à tout le monde !", "image" => "chupa_chups.jpg"],
    ["id" => 3, "nom" => "Dragibus", "prix" => 1.00, "description" => "Des bonbons gélifiés en forme de petits ronds colorés.", "image" => "dragibus.jpg"],
    ["id" => 4, "nom" => "Haribo Fraise Tagada", "prix" => 1.20, "description" => "Des fraises en guimauve recouvertes de sucre.", "image" => "fraise_tagada.jpg"],
    ["id" => 5, "nom" => "M&M's", "prix" => 1.50, "description" => "Des chocolats enrobés de sucre avec un 'M' dessus.", "image" => "mms.jpg"],
    ["id" => 6, "nom" => "Tic Tac", "prix" => 0.80, "description" => "Des petites pastilles rafraîchissantes.", "image" => "tic_tac.jpg"]
];


foreach ($un_super_tableau as $produit) {
    foreach ($produit as $clef => $valeur){
        echo ($valeur);
        echo ("<br>");


    }
}


// récupérer les 10 premiers produits de la BDD

$produits = dbquery("SELECT * FROM confiseries LIMIT 10");


?>
