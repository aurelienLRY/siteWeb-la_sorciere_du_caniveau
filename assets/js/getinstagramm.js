const accessToken =
  "IGQWROU2NvSF96aXNCbWQxdEJMUlhHM1c4Ny1hTlg5ZAkVick1FNkIyR3dtSkgtZA01GSU9kb0prRUVLbUJ2MjVYV1NFbHF6WkN6NDQyNDc0TVphSzk4cjFvLTl3MG1iMDlqazA1WDJ3bXVXZA3B2RFlaT2tVQnp0cDAZD";
let photosUrl = [];
// Fonction pour sauvegarder les données sur le serveur via une requête POST
async function saveDataToServer(data) {
  try {
    const response = await fetch("../backend/api/api-img-insta.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    if (!response.ok) {
      throw new Error("La requête au serveur a échoué.");
    }
    console.log("Données sauvegardées sur le serveur.");
  } catch (error) {
    console.error(
      "Une erreur s'est produite lors de la sauvegarde des données sur le serveur :",
      error
    );
    throw error; // Propagez l'erreur pour gérer la sauvegarde sur le serveur
  }
}

// Fonction pour charger les données depuis le serveur via une requête GET
async function loadDataFromServer() {
  try {
    const response = await fetch("../backend/api/api-img-insta.php"); // Remplacez par le chemin correct de votre backend
    if (!response.ok) {
      throw new Error("La requête au serveur a échoué.");
    }
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(
      "Une erreur s'est produite lors de la récupération des données depuis le serveur :",
      error
    );
    return [];
  }
}

// Fonction pour charger les données depuis Instagram
async function fetchDataFromInstagram() {
  try {
    const response = await fetch(
      "https://graph.instagram.com/me/media?fields=id,media_type,media_url&access_token=" +
        accessToken
    );
    if (!response.ok) {
      throw new Error("La requête à l'API Instagram a échoué.");
    }
    const data = await response.json();

    // Utilisez map pour extraire les URL des médias
    const mediaUrls = data.data.map((item) => item.media_url);

    // Si des données ont été récupérées depuis Instagram, sauvegardez-les sur le serveur
    if (mediaUrls.length > 0) {
      saveDataToServer(data);
    }

    return mediaUrls;
  } catch (error) {
    console.error(
      "Une erreur s'est produite lors de la récupération des données depuis Instagram :",
      error
    );
    return null; // Retournez null pour gérer les erreurs
  }
}

// Fonction principale pour charger et afficher les données
async function main(nombreDePhoto) {
  try {
    // Récupérer les données depuis Instagram
    photosUrl = null;
    //await fetchDataFromInstagram();

    // Si les données ne sont pas disponibles sur Instagram, récupérez-les depuis le serveur
    if (photosUrl === null) {
      photosUrl = await loadDataFromServer();
    }

    // Si les données sont toujours nulles, affichez un message d'erreur
    if (photosUrl === null) {
      console.error(
        "Aucune donnée n'a pu être récupérée depuis Instagram ni depuis le serveur."
      );
      return; // Arrêtez l'exécution si aucune donnée n'est disponible
    }
    if (!nombreDePhoto) {
      nombreDePhoto = photosUrl.length;
    }
    // Affichez les données
    const gridInsta = document.querySelector(".grid-insta");

    for (let i = 0; i < nombreDePhoto && i < photosUrl.length; i++) {
      const img = document.createElement("img");
      img.src = photosUrl[i];
      const div = document.createElement("div");
      div.classList.add("grid-insta_item");
      div.appendChild(img);
      gridInsta.appendChild(div);
      console.log("Url " + i + ":" + photosUrl[i]);
    }
  } catch (error) {
    console.error("Une erreur s'est produite :", error);
  }
}

main(); // Charge et affiche jusqu'à 6 photos
