<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
 * INFOS DE CONNEXION AU SERVEUR DE GESTION DE BDD, CHANGEZ SI BESOIN !
 */
const DB_DRIVER = 'mysql'; // Le driver à utiliser (pgsql, mysql, ...)
const DB_HOST = 'localhost'; // L'url du serveur de gestion de BDD (monurl.fr, localhost, ...)
const DB_PORT = 3306; // Le port d'accès au serveur de gestion, 3306 pour mysql, 5432 par défaut pour postgresql, 5433 pour le postgresql de l'IUT pour MMI
const DB_USERNAME = 'root'; // Le login d'accès au serveur de gestion BDD
const DB_PASSWORD = ''; // Le mdp du login
const DB_DATABASE = 'sae_203'; // Le nom de la base de données, il y en a rarement une seule sur un serveur de BDD !

try {
    $PDO = new PDO(
        DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_DATABASE . ';port=' . DB_PORT,
        DB_USERNAME,
        DB_PASSWORD
    );
} catch (Exception $ex) {
    echo ($ex->getMessage());
    die;
}

/*
* Cette fonction utilise des REQUETES PREPAREE :
* Si des informations dans la requêtes doivent varier, elles sont représentées par un "?"
* Les paramètres dans le tableaux remplaceront ces "?" dans l'ordre
* Exemple de requête : "SELECT * FROM employes WHERE age = ? AND salary >= ?"
* Et la variable $param = [30, 2500]
* Cela équivaut à écrire "SELECT * FROM employes WHERE age = 30 AND salary >= 2500"
*/
function dbquery($sql, $params = []){
    global $PDO;    
    $stmt = $PDO->prepare($sql); // On prépare la requête
    $stmt->execute($params); // On exécute en passant le tableau en paramètres
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // On retourne tous les résultats sous forme d'un tableau 2D
}



