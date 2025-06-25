<?php 

include_once 'header.php';
include_once 'db.php';

?>

<link rel="stylesheet" href="footer.css">
<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="index.css">
<link rel="stylesheet" href="global.css">

<link href="https://fonts.googleapis.com/css2?family=Corinthia:wght@400;700&family=Great+Vibes&family=Kaushan+Script&family=Yatra+One&display=swap" rel="stylesheet">

<?php

$user_connected = isset($_SESSION['user_id']);
$user_role = $user_connected ? $_SESSION['user_role'] : null;
$user_id = $user_connected ? $_SESSION['user_id'] : null;

$user_info = dbquery("SELECT utilisateurs.id, utilisateurs.username, utilisateurs.prenom, utilisateurs.role, utilisateurs.nom 
FROM utilisateurs 
WHERE utilisateurs.id = ?", [$user_id]);

$ui = $user_info ? $user_info[0] : null;

$boutiques_gerant = dbquery("
                SELECT id, nom 
                FROM boutiques 
                WHERE utilisateur_id = ?
            ", [$user_id]);
?>


<body>
    <main>
        <section id="hero">
            <div class="hero-container">
                <svg width="400" height="400">
                    <circle cx="250" cy="200" r="180" fill="#ffffff" stroke="#ffffff" stroke-width="16"/>
                </svg>
                <div class="hero-image">
                    <img src="./media/img/ourson.png" alt="Image d'Ourson">
                </div>
                <div class="hero-content">
                    <h1>Bienvenue <?php echo htmlspecialchars($ui['username']); ?> chez <span class="confiz">Confiz'</span></h1>
                    <p>Découvrez nos confiseries, différents choix pour différents goûts !</p>
                    <div class="hero-buttons">
                        <a href="catalogue.php?id-catalogue=1"><button class="hero-btn">Voir le catalogue</button></a>
                        <a href="boutique.php?id-boutique=1"><button class="hero-btn">Nos boutiques</button></a>
                    </div>
                </div>
            </div>
        </section>
        <img class="vague" src="./media/img/fond-confiz.jpg" alt="vague illustration">
<?php if ($user_connected && isset($ui)): ?>
    
    <?php if ($user_role === 'admin'): ?>
        <div class="info-utilisateur">
            <h2>Bonjour <?php echo ($ui['username']); ?></h2>
            <h5>Vous avez des droits sur toutes les boutiques cher Admin !</h5>
        </div>
    <?php endif; ?>
            
    <?php if ($user_role === 'gerant'): ?>
        <div class="info-utilisateur">
            <h2>Bonjour <?php echo ($ui['username']); ?></h2>
            <h5>Voici vos boutiques :</h5>
            
            <?php if (!empty($boutiques_gerant)): ?>
                <ul class="liste-boutiques">
                    <?php foreach ($boutiques_gerant as $btq): ?>
                        <li>
                            <a href="boutique.php?id-boutique=<?php echo $btq['id']; ?>" class="lien-boutique">
                                <?php echo ($btq['nom']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune boutique assignée pour le moment.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>


        <div class="message-index">
           <h1>Vous cherchez un produit ?</h1>
           <h4>C'est juste ici !</h4>
        </div>
        
<form method="GET" action="recherche-produit.php" class="barre-recherche">
    <input type="text" name="q" placeholder="Rechercher une confiserie..." required>
    <button type="submit">🔍</button>
</form>
<?php endif; ?>

        <section id="best-sellers">
            <div class="bs-produit">
                <h2>Best-Sellers</h2>
                <div class="produits-container">
                    <div class="produit">
                        <img src="./media/img/bonbon2.png" alt="Produit 1">
                        <h3>Les Acidulés</h3>
                        <p>Description du produit 1</p>
                        <a href="produit.php?id-bonbon=2"><button class="bs-btn">Voir le produit</button></a>
                    </div>

                    <div class="produit">
                        <img src="./media/img/bonbon3.png" alt="Produit 2">
                        <h3>Les Formes</h3>
                        <p>Description du produit 2</p>
                        <a href="produit.php?id-bonbon=3"><button class="bs-btn">Voir le produit</button></a>
                    </div>

                    <div class="produit">
                        <img src="./media/img/bonbon7.png" alt="Produit 3">
                        <h3>Les Originaux</h3>
                        <p>Description du produit 3</p>
                        <a href="produit.php?id-bonbon=7"><button class="bs-btn">Voir le produit</button></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="A-propos">
        <div class="bio-container">
            <div class="bio-logo-box">
                <img src="./media/img/logoconfiz.png" alt="Logo Confiz" class="bio-logo">
            </div>
            
            <div class="bio-content" id="mini-bio">
                <h1 class="bio-titre">Mini bio</h1>
                <h3 class="bio-sous-titre">Des informations sucrées sur nous ?</h3>
                <p class="bio-description">
                    Jeff Beyoff, franco américain, arrivé à Paris pour des études de marketing, décide de créer en 2012 la société « Confiz », une centrale d'achat de confiseries. Camarade de Jack Dermitt, héritier de la société allemande « Haribo », il choisit d'exploiter le catalogue produit de la société de son ami pour offrir au réseau de magasins franchisés qu'il construit, la possibilité de gérer leur réapprovisionnement très simplement : en ligne !
                    <br><br>
                    Si le catalogue repose essentiellement sur des produits « Haribo » Jeff Beyoff souhaite que l'identité de Confiz prédomine. Une identité « festive » à l'image de ce que Jeff veut apporter à ses revendeurs.
                    <br><br>
                    Après tout.. c'est Confiz, c'est sa plateforme ! qui permet la prise de commandes !
                    <br><br>
                    D'autre part, Jeff qui a une bonne connaissance de l'UX se préoccupe avant tout de la facilité pour tous revendeurs de passer leur commande. Il insiste sur la facilité de trouver les produits et de passer commande.
                </p>
            </div>
        </div>

</section>
<section class="carte">
    <?php include_once 'carte.php'?> 
</section>
    </main>

    <footer>
        <?php include_once 'footer.php'; ?>
    </footer>

    
<style>
    
body {
    background-color: #ffffff;
    margin: 0;
    padding: 0;
}

</style>

</body>

</html>