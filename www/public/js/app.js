// Importe des fonctions depuis d'autres fichiers JS pour gérer les utilisateurs, les chats et l'interface du chat
import { fetchUsers } from './fetchUsers.js';
import { fetchChats } from './getAllChats.js';
import { updateChatBody } from './chatInterFace.js';

let socket; // Garde une référence à la connexion WebSocket
let userId; // Stocke l'ID de l'utilisateur

// Initialise une connexion WebSocket si l'URL de la fenêtre contient 'chat'
if (window.location.href.includes('chat')) {
  socket = new WebSocket('ws://localhost:8080');

  // Événement déclenché une fois la connexion WebSocket établie
  socket.onopen = function (event) {
    console.log('WebSocket connection established');
    // Récupère l'ID de l'utilisateur depuis le serveur
    fetch('/fetchUserId')
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        userId = data.userId; // Assigne l'ID de l'utilisateur
      });
  };

  // Gère les messages entrants via la connexion WebSocket
  socket.onmessage = function (event) {
    const messageData = JSON.parse(event.data);

    // Si un nouveau chat est créé, recharge la liste des chats
    if (messageData.type === 'chatCreated') {
      fetchChats(socket);
      const activeChatTab = document.getElementById('active-chat-tab');
      if (activeChatTab) {
        activeChatTab.click();
      } else {
        console.log('Active chat tab button not found');
      }
    }
    // Met à jour l'interface du chat avec le nouveau message reçu
    if (messageData.type === 'chatMessage') {
      updateChatBody(userId, messageData);
    }
  };

  // Gère la fermeture de la connexion WebSocket
  socket.onclose = function (event) {
    console.log('WebSocket closed:', event);
  };

  // Gère les erreurs de la connexion WebSocket
  socket.onerror = function (error) {
    console.log('WebSocket Error:', error);
  };
}

// Fonction pour envoyer un message dans un chat
function sendMessage(chatId, message, senderId, recipientId) {
  // Vérifie si la connexion WebSocket est établie et prête
  if (socket && socket.readyState === WebSocket.OPEN) {
    const command = {
      type: 'sendMessage',
      chatId: chatId,
      message: message,
      recipientId: recipientId,
      senderId: senderId,
    };
    // Envoie le message via la connexion WebSocket
    socket.send(JSON.stringify(command));
  } else {
    console.error('WebSocket is not connected or not ready.');
  }
}

// Attache des gestionnaires d'événements une fois que le DOM est entièrement chargé
document.addEventListener('DOMContentLoaded', (event) => {
  if (window.location.href.includes('chat')) {
    fetchChats(socket); // Charge initialement les chats
    const logOutBtn = document.getElementById('logout');
    logOutBtn.addEventListener('click', () => {
      // Ferme la connexion WebSocket lors de la déconnexion
      if (socket && socket.readyState === WebSocket.OPEN) {
        socket.close();
      }
    });
    const newChatButton = document.getElementById('new-chat-tab');
    if (newChatButton) {
      // Attache un gestionnaire d'événements au bouton pour créer un nouveau chat
      newChatButton.addEventListener('click', function () {
        fetchUsers(socket);
      });
    }
  }
});
