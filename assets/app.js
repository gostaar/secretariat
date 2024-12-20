window.addEventListener('DOMContentLoaded', () => {
    // handleGoogleEventButton();
    if (window.location.pathname.startsWith('/user')) {
        changeFragmentUser();
        detailsEvent();
    }
    
    if (window.location.pathname === '/') {
        changeFragmentSite(); 
    }
});

async function changeFragmentSite() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    document.body.addEventListener('click', async function (event) {
        if (event.target && event.target.classList.contains('change-fragment-site')) {
            const fragment = event.target.dataset.fragment;
            const subFragment = event.target.dataset.subfragment;

            loadingIndicator.style.display = 'block';

            try {
                // Effectuer la requête AJAX
                const response = await fetch(`/?fragment=${fragment}&subFragment=${subFragment}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await response.json();

                // Masquer l'indicateur de chargement après la réponse
                loadingIndicator.style.display = 'none';

                if (subFragment === 'service') {
                    document.querySelector('#fragmentContent').addEventListener('click', function (event) {
                        if (event.target && event.target.classList.contains('btn-link')) {
                            const button = event.target.closest('.list-group-flush');
                            if (button) {
                                const btn = button.querySelector('.btn-link');
                                if (btn.classList.contains('collapsed')) {
                                    button.classList.remove('selected');
                                } else {
                                    button.classList.add('selected');
                                }
                            }
                        }
                    });
                }

                if (subFragment === 'job') {
                    document.querySelector('#fragmentContent').addEventListener('change', function (event) {
                        if (event.target && event.target.id === 'job') {
                            const customJobField = document.getElementById('customJob');
                            if (event.target.value === 'Autre') {
                                customJobField.classList.remove('d-none');
                            } else {
                                customJobField.classList.add('d-none');
                            }
                        }
                    });
                }

                if (data.fragmentContent) {
                    const fragmentContent = document.getElementById('fragmentContent');
                    if (fragmentContent) {
                        fragmentContent.innerHTML = data.fragmentContent;
                    }
                }

                if (data.subFragmentContent) {
                    const subFragmentContent = document.getElementById('subFragmentContent');
                    if (subFragmentContent) {
                        subFragmentContent.innerHTML = data.subFragmentContent;
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
                loadingIndicator.style.display = 'none'; // Masquer l'indicateur en cas d'erreur
            }
        }
    });
}


async function changeFragmentUser() {
    const buttons = document.querySelectorAll('.change-fragment');
    const loadingIndicator = document.getElementById('loadingIndicator');

    buttons.forEach(button => {
        button.addEventListener('click', async function() {  // Ajout de async ici pour la fonction click
            const fragment = button.getAttribute('data-fragment');
            
            // Affichage de l'indicateur de chargement
            loadingIndicator.style.display = 'block';

            try {
                // Récupération des données avec fetch
                const response = await fetch(`/user?fragment=${fragment}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', 
                    },
                });

                // Vérification de la réponse
                if (response.ok) {
                    const data = await response.json();  // Attendre la conversion en JSON

                    // Masquer l'indicateur de chargement une fois les données récupérées
                    loadingIndicator.style.display = 'none';

                    // Mettre à jour le contenu du fragment
                    document.getElementById('fragmentContent').innerHTML = data.fragmentContent;

                    // Mettre à jour les classes des boutons pour l'état actif
                    buttons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                } else {
                    throw new Error('Erreur lors de la récupération des données');
                }

            } catch (error) {
                console.error('Erreur:', error);
                loadingIndicator.style.display = 'none'; // Masquer l'indicateur en cas d'erreur
            }
        });
    });
}

// function handleGoogleEventButton() {
//     const btnNewGoogleEvent = document.getElementById('btnNewGoogleEvent');
//     if (btnNewGoogleEvent) {
//         console.log("btnNewGoogleEvent");
//         btnNewGoogleEvent.addEventListener('click', () => toggleSectionAgenda('link-newGoogleEvent'));
//     }
// }


// function clearData() {
//     sessionStorage.clear();
//     localStorage.clear();
//     const cookies = document.cookie.split(';');
//     cookies.forEach(cookie => {
//         const cookieName = cookie.split('=')[0].trim();
//         document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/; secure; sameSite=None`;
//     });
// }

// Détails event
function detailsEvent(){
    const eventList = document.querySelectorAll('#eventList .list-group-item');
    const modalTitle = document.getElementById('eventTitle');
    const modalDate = document.getElementById('eventDate');
    const modalDescription = document.getElementById('eventDescription');
    const modalLocation = document.getElementById('eventLocation');
    const modalEnd = document.getElementById('eventEnd');
    const modalGoogle = document.getElementById('eventGoogle');
    const modalGoogleId = document.getElementById('eventGoogleId');

    eventList.forEach(item => {
        item.addEventListener('click', () => {
            modalTitle.textContent = `Titre : ${item.dataset.title}`;
            modalDate.textContent = `Début : ${item.dataset.start}`;
            modalEnd.textContent = `Fin : ${item.dataset.end}`;
            modalDescription.textContent = `Description : ${item.dataset.description}`;
            modalLocation.textContent = `Lieu : ${item.dataset.location}`;
            modalGoogle.textContent = `Google Calendar ID : ${item.dataset.google}`;
            modalGoogleId.textContent = `Google Event ID : ${item.dataset.googleid}`;
        });
    });
}
