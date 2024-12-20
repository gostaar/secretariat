export function repertoire(){
    document.querySelectorAll('.dossier').forEach(function(dossier) {
        dossier.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var dossierId = dossier.getAttribute('data-id');

            var menu = document.getElementById('contextMenu');
            menu.style.display = 'block';
            menu.style.left = e.pageX + 'px';  
            menu.style.top = e.pageY + 'px';  

            document.getElementById('openDossier').onclick = function() {
                window.location.href = '/repertoire/' + dossierId; 
            };

            document.getElementById('renameDossier').onclick = function() {
                $('#renameDossierModal').modal('show');
              };
              
              document.getElementById('submitNewDossierName').onclick = function() {
                var newName = document.getElementById('newDossierName').value;
              
                if (newName) {
                  fetch('/update_dossier/' + dossierId, {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name: newName })
                  })
                  .then(response => response.json())
                  .then(data => {
                    if (data.success) {
                      dossier.querySelector('p').textContent = newName;
                      $('#renameDossierModal').modal('hide');
                    }
                  })
                  .catch(error => console.error('Error:', error));
                }
              };

            document.getElementById('deleteDossier').onclick = function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')) {
                    fetch('/delete_dossier/' + dossierId, {
                        method: 'DELETE',
                    })
                    .then(response => response.json())
                    .then(data => { 
                        if (data.success) {
                            const dossierElement = document.getElementById(`dossier_${dossierId}`);
                            dossierElement.remove(); 
                            if (document.getElementById('dossier-container').children.length === 0) {
                                    document.getElementById('dossier-container').classList.remove('justify-content-start');
                                    document.getElementById('dossier-container').classList.add('justify-content-center');
                                    const container = document.createElement('div');
                                    container.classList.add('text-center');
                                    const message = document.createElement('p');
                                    message.textContent = 'Aucun dossier.';
                                    container.appendChild(message);
                                    document.getElementById('dossier-container').appendChild(container);
                            }

                            menu.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            };
        });
    });

    document.addEventListener('click', function() {
        const contextMenu = document.getElementById('contextMenu');
        if (contextMenu) {
            contextMenu.style.display = 'none';
        }
    });


    const searchBar = document.getElementById('searchBarRepertoire');
    const dossierList = document.getElementById('dossierRepertoireList');

    // Fonction de filtrage des dossiers
    function filterDossiers() {
        const searchInput = searchBar.value.toLowerCase();
        const dossiers = dossierList.querySelectorAll('.repertoire');

        dossiers.forEach(dossier => {
            const dossierName = dossier.querySelector('p').textContent.toLowerCase();
            if (dossierName.includes(searchInput)) {
              dossier.classList.remove('d-none'); 
              dossier.classList.add('d-flex');
            } else {
              dossier.classList.remove('d-flex');
              dossier.classList.add('d-none');
            }
        });
    }

    // Attache l'événement à la barre de recherche
    if(searchBar){searchBar.addEventListener('keyup', filterDossiers)};

}