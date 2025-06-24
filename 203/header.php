<?php

session_start();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link id="icon" rel="icon" href="flavicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="global.css">
</head>
<body>

<header>
    <div class="contenu-header">
      <img id="logo-header" src="./media/img/logoconfiz.png" alt="Logo Confiz">
      <nav class="header-nav">
          <ul>
              <li><a href="index.php">À propos</a></li>
              <li><a href="catalogue.php?id-catalogue=1">Catalogue</a></li>
              <li><a href="boutique.php?id-boutique=1">Boutique</a></li>
          </ul>
      </nav>
      <?php if (isset($_SESSION['user_id'])):?>
          <a id="btn-login" href="logout.php"><button id="connexion">Se déconnecter</button></a>
      <?php else:?>
          <a id="btn-login" href="login.php"><button id="connexion">Se Connecter</button></a>
      <?php endif; ?>
    </div>
</header>
    
<script>
    const icon = document.getElementById('logo-header')
    icon.addEventListener('click', function() {
        window.location.href = 'index.php';
    });
</script>
    

