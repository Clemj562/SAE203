<?php

include_once 'header.php';
include_once 'db.php';

?>

<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="footer.css">
<link rel="stylesheet" href="global.css">
<link rel="stylesheet" href="produit.css">

<?php

if (!isset($_GET['id-bonbon']) || empty($_GET['id-bonbon'])) {
    header('Location: index.php');
    exit();
}

$id_bonbon = $_GET['id-bonbon'];

$produit = dbquery("SELECT id, nom, prix, description, illustration, type FROM confiseries WHERE id = ?", [$id_bonbon]);

if (empty($produit)) {
    echo "Produit non trouvé";
    exit();
}

$stock_boutiques = dbquery("SELECT confiseries.nom, confiseries.prix, boutiques.nom as nom_boutique, stocks.quantite 
FROM confiseries 
JOIN stocks ON stocks.confiserie_id = confiseries.id 
JOIN boutiques ON boutiques.id = stocks.boutique_id 
WHERE confiseries.id = ?", [$id_bonbon]);

$suggestions = dbquery("SELECT id, nom, prix, description, illustration 
FROM confiseries 
WHERE type = ? AND id != ? 
ORDER BY RAND() 
LIMIT 3", [$produit[0]['type'], $id_bonbon]);

if (empty($suggestions)) {
    $suggestions = dbquery("SELECT id, nom, prix, description, illustration 
    FROM confiseries 
    WHERE id != ? 
    ORDER BY RAND() 
    LIMIT 3", [$id_bonbon]);
}

?>

<body>

<main>

<div class="titre">
  <h2>PRODUIT</h2>
  <h4>Découvrez plus en détail nos produits !</h4>
</div>

<form method="GET" action="recherche-produit.php" class="barre-recherche">
    <input type="text" name="q" placeholder="Rechercher une confiserie..." required>
    <button type="submit">🔍</button>
</form>

<button id="lien-page-retour"><- Retour à la page précédente</button> 

<div class="produit">
    <div class="produit-image">
      <img src="./media/img/<?php echo ($produit[0]['illustration']); ?>" alt="<?php echo ($produit[0]['nom']); ?>">
    </div>
    <div class="produit-texte">
      <h1><?php echo ($produit[0]['nom']); ?></h1>
      <p class="prix"><?php echo($produit[0]['prix']); ?> €</p>
      <p class="description"><?php echo ($produit[0]['description']); ?></p>
    </div>
</div>

<div class="stock-boutiques">
    <h2>Disponibilité par boutique</h2>
    <?php if (!empty($stock_boutiques)): ?>
        <?php foreach($stock_boutiques as $stock): ?>
            <div class="boutique-item">
                <h3><?php echo ($stock['nom_boutique']); ?></h3>
                <p><?php echo ($stock['nom']); ?></p>
                <p><?php echo($stock['prix']); ?> €</p>
                <p>Stock restant : <span><?php echo ($stock['quantite']); ?></span></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Ce produit n'est disponible dans aucune boutique pour le moment.</p>
    <?php endif; ?>
</div>

<div class="suggestions">
    <h2>Ils pourraient vous plaire...</h2>
    <?php if (!empty($suggestions)): ?>
        <div class="suggestions-grid">
            <?php foreach($suggestions as $sg): ?>
                <div class="suggestion-item">
                    <div class="suggestion-image">
                        <img src="./media/img/<?php echo ($sg['illustration']); ?>" alt="<?php echo ($sg['nom']); ?>">
                    </div>
                    <div class="suggestion-info">
                        <h3><?php echo ($sg['nom']); ?></h3>
                        <p class="prix"><?php echo($sg['prix']); ?> €</p>
                        <p class="description"><?php echo ($sg['description']); ?></p>
                        <a href="produit.php?id-bonbon=<?php echo($sg['id']); ?>">
                            <button class="bs-btn">Voir le produit</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune suggestion disponible pour le moment.</p>
    <?php endif; ?>
</div>

</main>

<footer>

<?php include_once 'footer.php'; ?>

</footer>

<script src="script.js"></script>

    </body>

</html>