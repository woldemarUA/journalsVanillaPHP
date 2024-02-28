// Définit l'interface du chat et charge la liste des chats
export function chatInterFace(chatData, socket) {
  // Récupère l'élément du DOM où afficher la liste des chats
  const chatList = document.getElementById('chat-list');
  // Vide la liste actuelle pour la mise à jour
  chatList.innerHTML = '';

  // Parcourt chaque chat dans les données reçues
  for (const [index, chat] of Object.entries(chatData)) {
    // Destructure les données nécessaires de chaque chat
    const { chatId, chatInit, chatName } = chat; // chatInit est l'ID de l'utilisateur actif

    // Crée un nouvel élément de liste pour le chat
    const chatListItem = document.createElement('a');
    chatListItem.href = '#';
    chatListItem.className = 'list-group-item list-group-item-action';
    // Définit un gestionnaire de clic pour charger les messages du chat sélectionné
    chatListItem.onclick = () => fetchMessages(socket, chat.chatId, chatInit);
    chatListItem.innerHTML = chatName;

    // Assigne l'ID du chat à l'élément pour référence
    chatListItem.id = chat.chatId;
    // Ajoute l'élément à la liste des chats dans l'interface utilisateur
    chatList.appendChild(chatListItem);
  }
}

// Récupère les messages pour un chat spécifique
function fetchMessages(socket, chatId, chatInit) {
  // Construit l'URL pour la requête de récupération des messages
  let url = `/getMessages?chatId=${encodeURIComponent(chatId)}`;

  // Lance la requête pour obtenir les messages
  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw new Error("La réponse du réseau n'était pas ok.");
      }
      return response.json(); // On suppose que le serveur répond avec du JSON
    })
    .then((data) => {
      // Traite les données reçues ici
      renderChatBody(socket, data, chatInit, chatId);
    })
    .catch((error) => {
      console.error(
        'Il y a eu un problème avec votre opération fetch :',
        error
      );
    });
}

// Prépare l'interface du corps du chat
const chatBodyContainer = document.getElementById('chat-body');
const chatBody = document.createElement('div');
chatBody.className = 'row';
const inputRow = document.createElement('div');
inputRow.className = 'row';
if (chatBodyContainer) {
  chatBodyContainer.appendChild(chatBody);
  chatBodyContainer.appendChild(inputRow);
}

// Affiche les messages dans le corps du chat et permet de répondre
function renderChatBody(socket, messages, userId, chatId) {
  // Efface les messages précédents
  chatBody.innerHTML = '';

  // Détermine l'ID du destinataire à partir du premier message
  let recId;
  if (messages.length > 0) {
    const msg = messages[0];
    recId = msg.senderId === userId ? msg.recipientId : msg.senderId;
  }
  // Affiche chaque message dans l'interface utilisateur
  for (const message of messages) {
    updateChatBody(userId, message);
  }

  // Crée l'interface de réponse
  const reply = document.createElement('div');
  const replyInput = document.createElement('input');
  const replyBtn = document.createElement('button');
  reply.className = 'input-group mb-3';
  replyInput.type = 'text';
  replyInput.className = 'form-control';
  replyInput.setAttribute('aria-label', 'Réponse');
  replyInput.setAttribute('aria-describedby', `btn-chat-reply-${chatId}`);
  replyBtn.className = 'btn btn-outline-secondary btn-sm';
  replyBtn.innerText = 'Envoyer';
  replyBtn.id = `btn-chat-reply-${chatId}`;
  replyBtn.type = 'button';
  // Envoie la réponse lorsque le bouton est cliqué
  replyBtn.onclick = () => {
    replySubmission(
      socket,
      replyInput.value,
      userId,
      recId,
      chatId,
      replyInput
    );
  };
  // Permet également d'envoyer la réponse avec la touche Entrée
  replyInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      replySubmission(
        socket,
        replyInput.value,
        userId,
        recId,
        chatId,
        replyInput
      );
    }
  });

  // Ajoute les éléments de réponse à l'interface utilisateur
  reply.appendChild(replyInput);
  reply.appendChild(replyBtn);
  inputRow.appendChild(reply);
}

// Gère l'envoi de la réponse
function replySubmission(socket, msg, userId, recId, chatId, replyInput) {
  // Efface le champ de saisie
  replyInput.value = '';

  // Envoie le message
  sendMessage(socket, msg, userId, recId, chatId);
}

// Envoie un message via WebSocket
function sendMessage(socket, msg, senderId, recipientId, chatId) {
  // Vérifie si la connexion WebSocket est ouverte
  if (socket && socket.readyState === WebSocket.OPEN) {
    const command = {
      type: 'sendMessage',
      senderId: senderId,
      recipientId: recipientId,
      message: msg,
      chatId: chatId,
    };

    // Envoie le message au serveur
    socket.send(JSON.stringify(command));
  } else {
    console.error("WebSocket n'est pas connecté ou prêt.");
  }
}

// Met à jour l'interface du chat avec un nouveau message
export function updateChatBody(userId, message) {
  const row = document.createElement('div');
  // Ajuste l'alignement en fonction de l'expéditeur
  row.className = `d-flex ${
    message.senderId === userId
      ? 'justify-content-start'
      : 'justify-content-end'
  } `;
  const msg = document.createElement('span');
  // Change la couleur en fonction de l'expéditeur
  msg.className =
    message.senderId === userId
      ? 'badge rounded-pill bg-primary  my-1'
      : 'badge rounded-pill bg-secondary my-1';
  msg.innerText = message.message;
  row.appendChild(msg);
  chatBody.appendChild(row);
  // Fait défiler automatiquement au dernier message
  chatBody.scrollTop = chatBody.scrollHeight;
}
