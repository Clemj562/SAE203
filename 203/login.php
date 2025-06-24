<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="footer.css">
<?php

include_once 'header.php';
include_once 'db.php';
include_once 'function.php';

?>

<div class="login-container">
    <div class="login-box">
        <h2 class="login-title">Connexion</h2>
        <form action="login.php" method="post">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Se connecter">
        </form>

        <?php
        if (isset($_POST["password"])) {
            $isConnected = check_login($_POST["username"], $_POST["password"]);

            if ($isConnected) {
                $_SESSION["user_id"] = $isConnected['id'];
                $_SESSION["user_role"] = $isConnected['role'];
                header("Location: index.php"); 
                exit();
            } else {
                echo '<div class="error-message">Identifiants incorrects. Veuillez réessayer.</div>';
            }
        }
        ?>
    </div>
</div>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #ffeef7 0%, #ffe0f0 50%, #ffd4e8 100%);
        display: flex;
        flex-direction: column;
    }

    #icon {
        border-radius: 100%;
    }

    header {
        display: flex;
        justify-content: center;
        position: sticky;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(10px);
        box-shadow: 0 0 8px #17171a0d, 0 2px 8px #17171a14;
    }

    .contenu-header {
        max-width: 1200px;
        margin: 0.5rem 0rem;
        margin-top: 0.7rem;
        padding: 0 158px;
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        border: none;
        box-shadow: none;
        align-items: baseline;
    }

    header #logo-header {
        height: 80px;
        width: auto;
        border-radius: 100%;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        transition: transform 0.3s ease;
    }

    header #logo-header:hover {
        transform: scale(1.05) rotate(2deg);
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 30px;
        align-items: center;
    }

    nav li {
        position: relative;
    }

    nav a {
        color: rgb(0, 0, 0);
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        padding: 10px 18px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Bouton de connexion */
    #connexion {
        background: linear-gradient(45deg, #4ecdc4, #44a08d);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(68, 160, 141, 0.3);
    }

    #connexion:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(68, 160, 141, 0.4);
        background: linear-gradient(45deg, #5dd9d1, #4db3a0);
        text-decoration: none;
    }

    #connexion:active {
        transform: translateY(-1px);
    }

    .login-container {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .login-box {
        background: rgba(255, 192, 203, 0.15);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 182, 193, 0.3);
        border-radius: 20px;
        padding: 3rem 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .login-title {
        text-align: center;
        color: #6b4c7c;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .login-box label {
        display: block;
        color: #7a5b8a;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
        width: 100%;
        padding: 1rem;
        border: 2px solid rgba(255, 182, 193, 0.4);
        border-radius: 12px;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.8);
        color: #5a4b6a;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .login-box input[type="text"]:focus,
    .login-box input[type="password"]:focus {
        outline: none;
        border-color: #d896a7;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 0 15px rgba(216, 150, 167, 0.3);
        transform: scale(1.02);
    }

    .login-box input[type="submit"] {
        width: 100%;
        background: linear-gradient(45deg, #d896a7, #c084a1);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 1rem;
        box-shadow: 0 8px 20px rgba(208, 150, 167, 0.4);
    }

    .login-box input[type="submit"]:hover {
        background: linear-gradient(45deg, #e2a6b7, #d094ab);
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(208, 150, 167, 0.5);
    }

    .login-box input[type="submit"]:active {
        transform: translateY(0);
    }

    .error-message {
        color: #c2185b;
        text-align: center;
        margin-top: 1rem;
        font-weight: 600;
        background: rgba(194, 24, 91, 0.1);
        padding: 0.8rem;
        border-radius: 8px;
        border-left: 4px solid #c2185b;
    }

</style>
</body>

</html>