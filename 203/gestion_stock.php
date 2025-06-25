<?php
require_once 'db.php';

if (
    isset($_POST['stock_id'], $_POST['nouveau_stock']) &&
    is_numeric($_POST['stock_id']) &&
    is_numeric($_POST['nouveau_stock'])
) {
    $stock_id = (int)$_POST['stock_id'];
    $nouveau_stock = (int)$_POST['nouveau_stock'];

    dbquery("UPDATE stocks SET quantite = ? WHERE id = ?", [$nouveau_stock, $stock_id]);
}

// Redirige vers la page précédente
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>