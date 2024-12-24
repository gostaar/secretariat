export function repertoire() {
  // Gestion des événements pour un élément spécifique
  function addEvent(selector, event, handler) {
      const element = document.querySelector(selector);
      if (element) {
          element.addEventListener(event, handler);
      }
  }

  // Ajout ou suppression des classes
  function toggleClass(element, addClass, removeClass) {
      if (element) {
          element.classList.add(addClass);
          element.classList.remove(removeClass);
      }
  }

  // Afficher le menu contextuel
  function showContextMenu(e, dossierId) {
      const menu = document.getElementById('contextMenu');
      toggleClass(menu, 'd-block', 'd-none');
      menu.style.left = e.pageX + 'px';
      menu.style.top = e.pageY + 'px';

      setupContextMenuActions(dossierId);
  }

  // Configuration des actions du menu contextuel
  function setupContextMenuActions(dossierId) {
      const dossier = document.getElementById(`dossier_${dossierId}`);

      // Ouvrir le dossier
      addEvent('#openDossier', 'click', () => {
          window.location.href = '/repertoire/' + dossierId;
      });

      // Renommer le dossier
      addEvent('#renameDossier', 'click', () => {
          $('#renameDossierModal').modal('show');
      });

      addEvent('#submitNewDossierName', 'click', () => {
          const newName = document.getElementById('newDossierName').value;
          if (newName) {
              fetch(`/update_dossier/${dossierId}`, {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ name: newName })
              })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          dossier.querySelector('p').textContent = newName;
                          $('#renameDossierModal').modal('hide');
                      }
                  })
                  .catch(handleError);
          }
      });

      // Supprimer le dossier
      addEvent('#deleteDossier', 'click', () => {
          if (confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')) {
              fetch(`/delete_dossier/${dossierId}`, { method: 'DELETE' })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          removeDossier(dossierId);
                      }
                  })
                  .catch(handleError);
          }
      });
  }

  // Supprimer un dossier et gérer l'affichage
  function removeDossier(dossierId) {
      const dossier = document.getElementById(`dossier_${dossierId}`);
      dossier.remove();

      const container = document.getElementById('dossier-container');
      if (!container.children.length) {
          container.classList.remove('justify-content-start');
          container.classList.add('justify-content-center');
          container.innerHTML = '<div class="text-center"><p>Aucun dossier.</p></div>';
      }

      const menu = document.getElementById('contextMenu');
      toggleClass(menu, 'd-none', 'd-block');
  }

  // Gérer les erreurs
  function handleError(error) {
      console.error('Une erreur s\'est produite :', error);
      alert('Une erreur s\'est produite. Veuillez réessayer.');
  }

  // Filtrer les dossiers
  function filterDossiers() {
      const searchInput = document.getElementById('searchBarRepertoire').value.toLowerCase();
      const dossiers = document.querySelectorAll('.repertoire');
      dossiers.forEach(dossier => {
          const dossierName = dossier.querySelector('p').textContent.toLowerCase();
          toggleClass(dossier, dossierName.includes(searchInput) ? 'd-flex' : 'd-none', dossierName.includes(searchInput) ? 'd-none' : 'd-flex');
      });
  }

  // Initialisation des événements principaux
  document.addEventListener('click', (e) => {
      if (e.target && e.target.classList.contains('dossier')) {
          e.preventDefault();
          e.stopPropagation();

          const dossierId = e.target.getAttribute('data-id');
          showContextMenu(e, dossierId);
      } else {
          const contextMenu = document.getElementById('contextMenu');
          toggleClass(contextMenu, 'd-none', 'd-block');
      }
  });

  const searchBar = document.getElementById('searchBarRepertoire');
  if (searchBar) {
      searchBar.addEventListener('keyup', filterDossiers);
  }
}
