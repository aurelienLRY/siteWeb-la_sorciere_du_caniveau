<?php

// Chemin du fichier JSON
$jsonFilePath = '../json/instagram_images.json';

// Gérer une requête GET pour récupérer les données
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Vérifiez si le fichier JSON existe
  if (file_exists($jsonFilePath)) {
    // Lisez le contenu du fichier JSON
    $jsonData = file_get_contents($jsonFilePath);
    // Envoyez les données au client en tant que réponse JSON
    header('Content-Type: application/json');
    echo $jsonData;
  } else {
    // Si le fichier JSON n'existe pas, envoyez une réponse vide ou un message d'erreur
    http_response_code(404);
    echo json_encode(['error' => 'Aucune donnée disponible.']);
  }
}

// Gérer une requête POST pour sauvegarder les données
// Gérer une requête POST pour sauvegarder les données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupérez les données envoyées par le client en tant que JSON
  $jsonInput = file_get_contents('php://input');
  $data = json_decode($jsonInput, true);
  
  // Vérifiez que toutes les valeurs de data[key] ne sont pas nulles
  $allValuesNotNull = true;
  foreach ($data as $key => $value) {
    if ($value === null) {
      $allValuesNotNull = false;
      break;
    }
  }
  
  if ($allValuesNotNull) {
    // Si toutes les valeurs ne sont pas nulles, vérifiez si le fichier JSON existe
    if (file_exists($jsonFilePath)) {
      // Si le fichier JSON existe, écrasez les données
      file_put_contents($jsonFilePath, $jsonInput);
    } else {
      // Si le fichier JSON n'existe pas, créez-le avec les données
     file_put_contents($jsonFilePath, $jsonInput);
    }
    
    // Répondez avec un message de succès
    echo json_encode(['success' => true]);
  } else {
    // Si au moins une valeur est nulle, envoyez un message d'erreur
    http_response_code(400);
    echo json_encode(['error' => 'Au moins une valeur est nulle.']);
  }
}

