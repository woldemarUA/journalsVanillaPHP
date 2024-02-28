// Importe l'interface de chat depuis un autre fichier pour une utilisation dans ce contexte.
import { chatInterFace } from './chatInterFace.js';

// Fonction pour récupérer les chats depuis le serveur.
export const fetchChats = (socket) => {
  // Lance une requête pour obtenir les données des chats.
  fetch('getChats')
    .then((response) => {
      // Vérifie si la réponse du serveur est valide.
      if (!response.ok) {
        throw new Error("La réponse du réseau n'était pas valide");
      }
      return response.json(); // Convertit la réponse en JSON.
    })
    .then((data) => {
      const chatList = document.getElementById('chat-list'); // Récupère l'élément HTML qui contiendra la liste des chats.
      chatList.innerHTML = ''; // Efface la liste des chats actuelle pour préparer l'affichage des nouvelles données.

      // Vérifie si l'objet data (contenant les chats) est vide.
      if (Object.keys(data).length === 0) {
        // Si c'est le cas, affiche un message indiquant qu'aucun chat n'est disponible.
        chatList.innerHTML =
          'Aucun discussions. <br>Vous pouvez démarrer une nouvelle en cliquant sur l\'onglet en haut, <br> "Démarrer une nouvelle discussion"';
        return; // Sort de la fonction si aucune donnée n'est présente.
      }

      // Si des données sont présentes, initialise l'interface de chat avec ces données et le socket.
      chatInterFace(data, socket);
    })
    .catch((error) =>
      // Gère les erreurs potentielles lors de la récupération ou du traitement des données.
      console.error('Il y a eu un problème avec votre opération fetch :', error)
    );
};
