<?php
// Récupérer les données JSON envoyées
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Vérifier que les données sont présentes
if (!$data || !isset($data['stock_id']) || !isset($data['quantite'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

$stock_id = $data['stock_id'];
$quantite = $data['quantite'];

// Vérifier que la quantité est un nombre positif
if (!is_numeric($quantite) || $quantite < 0) {
    echo json_encode(['success' => false, 'message' => 'Quantité invalide']);
    exit;
}

try {
    // Mettre à jour le stock dans la base de données
    $query = "UPDATE stock SET quantite = ? WHERE id = ?";
    $result = dbquery($query, [$quantite, $stock_id]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Stock mis à jour']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
}
?>