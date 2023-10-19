<?php

$config = [
    'instagram_access_token' => 'IGQWROU2NvSF96aXNCbWQxdEJMUlhHM1c4Ny1hTlg5ZAkVick1FNkIyR3dtSkgtZA01GSU9kb0prRUVLbUJ2MjVYV1NFbHF6WkN6NDQyNDc0TVphSzk4cjFvLTl3MG1iMDlqazA1WDJ3bXVXZA3B2RFlaT2tVQnp0cDAZD',
];

$accesstoken = $config['instagram_access_token']; // Récupérez l'accessToken depuis votre configuration

$jsonFilePath = '../json/instagram_images.json'; // Chemin du fichier JSON

https://graph.instagram.com/v12.0/la_sorciere_du_caniveau?fields=id&access_token=IGQWROU2NvSF96aXNCbWQxdEJMUlhHM1c4Ny1hTlg5ZAkVick1FNkIyR3dtSkgtZA01GSU9kb0prRUVLbUJ2MjVYV1NFbHF6WkN6NDQyNDc0TVphSzk4cjFvLTl3MG1iMDlqazA1WDJ3bXVXZA3B2RFlaT2tVQnp0cDAZD



function DataFromInstagram($accesstoken)
{
    $url = "https://graph.instagram.com/me/media?fields=id,media_type,media_url&access_token=$accesstoken";
    // Initialisez cURL
    $ch = curl_init();

    // Configurez cURL pour récupérer l'URL
    curl_setopt($ch, CURLOPT_URL, $url);

    // Désactivez la vérification SSL (NON RECOMMANDÉ EN PRODUCTION)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Configurez cURL pour retourner la réponse au lieu de l'afficher
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Exécutez la requête cURL
    $data = curl_exec($ch);
    // Fermez la session cURL
    curl_close($ch);
    if ($data) {
        return $data;
    } else {
        return null;
    }
}

// Gérer une requête GET pour récupérer les données
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupérez les données depuis Instagram
    $instagramData = DataFromInstagram($accesstoken);
    // Si les données ont été récupérées depuis Instagram, sauvegardez-les sur le serveur
    if ($instagramData !== null) {
        // Encodez les données en JSON et écrivez-les dans le fichier JSON
        $data = json_decode($instagramData, true);
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
                file_put_contents($jsonFilePath, json_encode($data));
            } else {
                // Si le fichier JSON n'existe pas, créez-le avec les données
                file_put_contents($jsonFilePath, json_encode($data));
            }
        }
        // Répondez avec les données récupérées depuis Instagram 
        header('Content-Type: application/json');
        echo $instagramData;
    } else {
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
}
