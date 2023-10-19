async function getImg() {
  try {
    const response = await fetch("../backend/api/api.php");
    if (!response.ok) {
      throw new Error("La requête à l'API Instagram a échoué.");
    }
    const data = await response.json();
    console.log(data);
    // Utilisez map pour extraire les URL des médias
    const mediaUrls = data.data.map((item) => item.media_url);

    return mediaUrls;
  } catch (error) {
    console.error(error);
    return null; // Retournez null pour gérer les erreurs
  }
}

// Fonction principale pour charger et afficher les données
async function pictInsta(nombreDePhoto) {
  try {
    let photosUrl = [];
    // Récupérer les données depuis Instagram
    photosUrl = await getImg();

    if (!nombreDePhoto) {
      nombreDePhoto = photosUrl.length;
    }

    // Si les données sont toujours nulles, affichez un message d'erreur
    if (photosUrl === null) {
      console.error(
        "Aucune donnée n'a pu être récupérée depuis Instagram ni depuis le serveur."
      );
      return; // Arrêtez l'exécution si aucune donnée n'est disponible
    } else {
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
    }
  } catch (error) {
    console.error("Une erreur s'est produite :", error);
  }
}

pictInsta(6); // Charge et affiche jusqu'à 6 photos
