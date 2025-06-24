<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="global.css">
<link rel="stylesheet" href="footer.css">

<style>
    .barre-recherche {
        display: flex;
        max-width: 500px;
        margin: 20px auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 25px;
        overflow: hidden;
        background: white;
    }

    .barre-recherche input {
        flex: 1;
        padding: 12px 20px;
        border: none;
        font-size: 16px;
        outline: none;
    }

    .barre-recherche button {
        padding: 12px 20px;
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .barre-recherche button:hover {
        background: #0056b3;
    }

    .resultats-recherche {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .produits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .produit-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease;
        background: white;
    }

    .produit-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .produit-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .produit-info {
        padding: 15px;
    }

    .produit-info h3 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .prix {
        font-weight: bold;
        color: #e74c3c;
        font-size: 18px;
        margin: 10px 0;
    }

    .description {
        color: #666;
        margin: 10px 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .voir-produit {
        display: inline-block;
        padding: 8px 16px;
        background: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 10px;
    }

    .voir-produit:hover {
        background: #218838;
    }

    .aucun-resultat {
        text-align: center;
        color: #666;
        font-style: italic;
        margin: 40px 0;
    }

    .info-recherche {
        margin: 20px 0;
        color: #666;
    }
</style>

<?php

include_once 'header.php';
include_once 'db.php';

$terme_recherche = isset($_GET['q']) ? trim($_GET['q']) : '';

$resultats = [];

if (!empty($terme_recherche)) {
    $terme_sql = '%' . $terme_recherche . '%';
    $resultats = dbquery("SELECT id, nom, prix, description, illustration, type 
                         FROM confiseries 
                         WHERE nom LIKE ? 
                         OR description LIKE ? 
                         OR type LIKE ?
                         ORDER BY nom ASC", 
                         [$terme_sql, $terme_sql, $terme_sql]);
}

?>

<main>
<form method="GET" action="recherche-produit.php" class="barre-recherche">
    <input type="text" 
           name="q" 
           placeholder="Rechercher une confiserie..." 
           value="<?php echo ($terme_recherche); ?>"
           required>
    <button type="submit">🔍 Rechercher</button>
</form>
<div class="resultats-recherche">
    <?php if (!empty($terme_recherche)): ?>
        <div class="info-recherche">
            <h2>Résultats pour "<?php echo ($terme_recherche); ?>"</h2>
            <p><?php echo count($resultats); ?> produit(s) trouvé(s)</p>
        </div>
        
        <?php if (!empty($resultats)): ?>
            <div class="produits-grid">
                <?php foreach ($resultats as $produit): ?>
                    <div class="produit-item">
                        <img src="./media/img/<?php echo ($produit['illustration']); ?>" 
                             alt="<?php echo ($produit['nom']); ?>">
                        <div class="produit-info">
                            <h3><?php echo ($produit['nom']); ?></h3>
                            <p class="prix"><?php echo($produit['prix']); ?> €</p>
                            <p class="description"><?php echo ($produit['description']); ?></p>
                            <p><strong>Type :</strong> <?php echo ($produit['type']); ?></p>
                            <a href="produit.php?id-bonbon=<?php echo($produit['id']); ?>" class="voir-produit">
                                Voir le produit
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="aucun-resultat">
                Aucun produit trouvé pour "<?php echo ($terme_recherche); ?>". 
                <br>Essayez avec d'autres mots-clés.
            </p>
        <?php endif; ?>
    <?php else: ?>
        <div class="info-recherche">
            <h2>Rechercher des confiseries</h2>
            <p>Utilisez la barre de recherche ci-dessus pour trouver vos confiseries préférées !</p>
        </div>
    <?php endif; ?>
</div>

<script src="script.js"></script>

</main>

<footer>
<?php include_once 'footer.php'; ?>
</footer>