<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="global.css">
<link rel="stylesheet" href="footer.css">
<link rel="stylesheet" href="catalogue.css">


<?php

include_once 'header.php';
include_once 'db.php';

$produits = dbquery("SELECT confiseries.id, confiseries.nom, confiseries.prix, stocks.confiserie_id, stocks.quantite, boutiques.id, stocks.boutique_id, description, illustration FROM confiseries 
JOIN stocks ON confiseries.id = stocks.confiserie_id 
JOIN boutiques ON stocks.boutique_id = boutiques.id
WHERE boutique_id = ?", [$_GET['id-catalogue']]);

$boutiques_select = dbquery("SELECT id, nom FROM boutiques ORDER BY nom ASC");

?>

<main>

  <div class="titre">
    <h2>CATALOGUE</h2>
    <h4>Des produits variés pour une découverte<br>encore plus surprenante !</h4>
  </div>


  <div class="boutique-buttons">
    <a href="catalogue.php?id-catalogue=1"><button class="hero-btn">La Mika-Line</button></a>
    <a href="catalogue.php?id-catalogue=2"><button class="hero-btn">Ok Bonbons</button></a>
    <a href="catalogue.php?id-catalogue=3"><button class="hero-btn">Saccharo</button></a>
  </div>

  <div class="catalogue">

    <?php foreach ($produits as $p) { 
    if ($p['quantite'] == 0) continue;
?>

      <div class="produit">
        <div class="fond">
          <a href="produit.php?id-bonbon=<?php echo ($p['id']); ?>">
            <img src="media/img/<?php echo ($p['illustration']) ?>" alt="Image du produit">
          </a>
        </div>
        <div class="contenu-carte">
          <h1><?php echo ($p['nom']) ?></h1>
          <p><?php echo ($p['prix']) ?></p>
         <?php if (isset($p['id-catalogue'])): ?>
            <p class="stock">Stock: <?php echo $p['quantite']; ?> unités</p>
          <?php endif; ?>
          <p class="stock">Stock: <?php echo($p['quantite']); ?> unités</p>
          <p><?php echo ($p['description']) ?></p>
        </div>
      </div>

    <?php } ?>

  </div>

  <script src="script.js"></script>

  <footer>
    <?php include_once 'footer.php' ?>
  </footer>