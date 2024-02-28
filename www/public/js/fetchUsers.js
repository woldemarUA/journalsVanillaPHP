// Fonction pour récupérer la liste des utilisateurs depuis le serveur
export function fetchUsers(socket) {
  fetch('usersList')
    .then((response) => {
      if (!response.ok) {
        throw new Error(`La réponse du réseau n'était pas valide`);
      }
      return response.json(); // Convertit la réponse en JSON
    })
    .then((data) => {
      newChatTabEl(data, socket); // Traite les données reçues pour créer un nouvel onglet de chat
    })
    .catch(
      (error) =>
        console.error(
          'Il y a eu un problème avec votre opération fetch :',
          error
        ) // Gère les erreurs de la requête
    );
}

// Fonction pour créer un élément de liste pour chaque utilisateur récupéré et l'ajouter à l'interface
function newChatTabEl(arr, socket) {
  const el = document.getElementById('new-chat-user-list'); // Récupère l'élément de la liste des nouveaux chats
  el.innerHTML = ''; // Réinitialise la liste pour éviter les doublons
  for (const user of arr) {
    const { id, username, email } = user; // Destructure l'objet utilisateur pour obtenir id, username et email

    // Crée les éléments HTML nécessaires pour afficher chaque utilisateur
    const li = document.createElement('li');
    li.className = 'list-group-item list-group-item-action';
    const row = document.createElement('div');
    const uNameCol = document.createElement('div');
    const emailCol = document.createElement('div');
    const devider = document.createElement('div');
    const usernameEl = document.createElement('span');
    const emailEl = document.createElement('span');

    // Configure les actions et le style des éléments
    li.id = id;
    li.onclick = () => createChatFromTabs({ id, username }, socket); // Définit l'action lors du clic sur un utilisateur
    row.className = 'row';
    uNameCol.className = 'col';
    devider.className = 'col';
    emailCol.className = 'col';

    usernameEl.innerText = username; // Ajoute le nom d'utilisateur au span
    emailEl.innerText = email; // Ajoute l'email au span
    uNameCol.appendChild(usernameEl);
    emailCol.appendChild(emailEl);
    row.appendChild(uNameCol);
    row.appendChild(emailCol);
    row.appendChild(devider);

    li.appendChild(row);

    el.appendChild(li); // Ajoute l'élément li à la liste des utilisateurs dans l'interface
  }
}

// Fonction pour créer un chat à partir des onglets d'utilisateurs
function createChatFromTabs(target, socket) {
  fetch('fetchUserId')
    .then((response) => {
      if (!response.ok) {
        throw new Error(`La réponse du réseau n'était pas valide`);
      }
      return response.json(); // Convertit la réponse en JSON
    })
    .then((host) => {
      let chatName = `${host.username} avec ${target.username}`; // Crée un nom pour le chat

      createChat(chatName, [host.userId, target.id], socket); // Appelle la fonction pour créer le chat
    })
    .catch(
      (error) =>
        console.error(
          'Il y a eu un problème avec votre opération fetch :',
          error
        ) // Gère les erreurs de la requête
    );
}

// Fonction pour créer un nouveau chat
function createChat(chatTitle, participants, socket) {
  if (socket && socket.readyState === WebSocket.OPEN) {
    const command = {
      type: 'createChat',
      chatTitle: chatTitle,
      participants: participants,
    };

    socket.send(JSON.stringify(command)); // Envoie la commande de création de chat via WebSocket
  } else {
    console.error('WebSocket is not connected or not ready.'); // Gère l'état déconnecté du WebSocket
  }
}
