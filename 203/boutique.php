<?php

include_once 'header.php';
include_once 'db.php';

?>
<link rel="stylesheet" href="global.css">
<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="footer.css">
<link rel="stylesheet" href="boutique.css">

<style>

.btn-gestion-produit,
.btn-gestion-boutique {
    display: none;
}

</style>

<?php

$user_connected = isset($_SESSION['user_id']);
$user_role = $user_connected ? $_SESSION['user_role'] : null;
$user_id = $user_connected ? $_SESSION['user_id'] : null;
$id_boutique = 

$boutiques = dbquery("SELECT id, nom, utilisateur_id AS uid, numero_rue, nom_adresse, code_postal, ville, pays FROM boutiques WHERE boutiques.id = ?", [$_GET['id-boutique']]);

$stocks = dbquery("SELECT confiseries.nom as confiserie_nom, confiseries.id as confiserie_id, stocks.quantite, stocks.id as stock_id
FROM stocks 
JOIN confiseries ON stocks.confiserie_id = confiseries.id
WHERE stocks.boutique_id = ?", [$_GET['id-boutique']]);

$autres_produits = dbquery("SELECT DISTINCT confiseries.id, confiseries.nom, confiseries.prix, confiseries.poids, confiseries.illustration, stocks.quantite
FROM confiseries 
JOIN stocks ON confiseries.id = stocks.confiserie_id 
WHERE stocks.boutique_id = ? 
AND stocks.quantite > 0 
ORDER BY RAND()
LIMIT 3", [$_GET['id-boutique']]);

$boutiques_select = dbquery("SELECT id, nom FROM boutiques ORDER BY nom ASC");
?>

<main>
    <div class="titre">
        <h2>BOUTIQUE</h2>
        <h4>Venez découvrir toutes nos boutiques !</h4>
    </div>

    <form class="barre-recherche">
        <select name="boutique" id="boutique-select" required>
            <option value="">Sélectionnez une boutique...</option>
            <?php foreach ($boutiques_select as $bt): ?>
                <option value="<?php echo ($bt['id']); ?>">
                    <?php echo ($bt['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php foreach ($boutiques as $b): ?>
    <?php
     if (($user_connected && $user_role == 'admin') || ($user_connected && $user_role == 'gerant' && isset($b['uid']) && $b['uid'] == $user_id)): ?>
        <button class="bouton btn-gestion-stock" data-magasin="<?php echo($b['nom'])?>" data-magasin-id="<?php echo($b['id'])?>">
            Gestion des stocks
        </button>
    <?php endif; ?>
    <?php endforeach; ?>

 
        <button id="lien-page-retour"><- Retour à la page précédente</button> 

        <?php foreach ($boutiques as $b): ?>

        <div class="magasin">
            <div class="contenu-carte">
                <div class="first-lign">
                    <h1><?php echo($b['nom'])?></h1>
                </div>
                <p><?php echo($b['numero_rue']) . ' ' . $b['nom_adresse']?></p>
                <p><?php echo($b['code_postal']) . ' ' . $b['ville']?></p>
                <p><?php echo($b['pays'])?></p>
                <?php if ($user_role == 'admin'): ?>
                        <button class="btn-gestion-admin">BOUTON ADMIN</button>
                        <button class="btn-gestion-produit">Les Confiseries</button>
                        <button class="btn-gestion-boutique">Les Boutiques</button>
                    <?php endif; ?>
            </div>
            <div class="contenu-image">
                <img src="media/img/imageBoutique1.jpg" alt="Image du Magasin">
            </div>
        </div>

<?php endforeach; ?>

<!-- SECTION PRODUITS -->

<div class="autres-produits">
    <h2>Nos produits</h2>
    <div class="produits-grid">
        <?php foreach($autres_produits as $ap): ?>
            <div class="produit-item">
                <a href="produit.php?id-bonbon=<?php echo($ap['id']); ?>"><img src="./media/img/<?php echo($ap['illustration'])?>" alt="<?php echo($ap['nom'])?>"></a>
                <h3><?php echo($ap['nom']); ?></h3>
                <p class="prix"><?php echo($ap['prix']); ?> €</p>
                <p>Sachet de <?php echo($ap['poids']); ?> g</p>
                <p class="stock">Stock: <?php echo($ap['quantite']); ?> unités</p>
                <a class="bs-produit" href="produit.php?id-bonbon=<?php echo($ap['id']); ?>">Voir ce produit</a>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="catalogue.php?id-catalogue=<?php echo($_GET['id-boutique']); ?>">
    <button class="lien-catalogue">Voir tous nos produits</button>
</a>
</div>

<!-- POPUP ADMIN BOUTIQUE -->

<?php if ($user_connected): ?>
<div id="popup-boutiques" class="popup-boutiques popup-hide">
    <div class="popup-overlay-boutiques"></div>
    <div class="popup-content-boutiques">
        <div class="popup-header-boutiques">
            <h2>Gestion des boutiques - <span id="nom-magasin-boutiques"></span></h2>
            <button class="btn-fermer-boutiques">&times;</button>
        </div>
        <div class="contenu-popup-boutiques">
            <?php 
            include_once 'gestion-boutique.php'; 
            ?>
        </div>
    </div>
</div>

<!-- POPUP ADMIN PRODUIT -->
<div id="popup-produit" class="popup-produit popup-hide">
    <div class="popup-overlay-produit"></div>
    <div class="popup-content-produit">
        <div class="popup-header-produit">
            <h2>Gestion des produits</h2>
            <button class="btn-fermer-produit">&times;</button>
        </div>
        <div class="contenu-popup-produit">
            <?php 
            include_once 'gestion-produit.php'; 
            ?>
        </div>
    </div>
</div>

<!-- POPUP GESTION DES STOCKS -->

<div id="popup-stock" class="popup-stock popup-hide">
    <div class="popup-overlay-stock"></div>
    <div class="popup-content-stock">
        <div class="popup-header-stock">
            <h2>Gestion des stocks - <span id="nom-magasin-stock"></span></h2>
            <button class="btn-fermer-stock">&times;</button>
        </div>
        <div class="contenu-stock">
            <?php foreach ($stocks as $s): ?>
        <div class="stock-item">
            <span><?php echo htmlspecialchars($s['confiserie_nom']); ?> : <?php echo (int)$s['quantite']; ?> unités</span>
            <form action="gestion_stock.php" method="post" style="display:inline;">
                <input type="hidden" name="stock_id" value="<?php echo (int)$s['stock_id']; ?>">
                <input type="number" name="nouveau_stock" min="0" value="<?php echo (int)$s['quantite']; ?>">
                <button type="submit">Modifier</button>
            </form>
        </div>
    <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="script.js"></script>


</main>

<footer>
    <?php include_once 'footer.php' ?>
</footer>



<style>

body {
    font-family: Arial, sans-serif;
    background-color:rgb(255, 255, 255);
    margin: 0;
    padding: 0px;
}

.titre h2{
    border-bottom: none;
}

</style>

</body>
