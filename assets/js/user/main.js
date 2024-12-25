import { facture } from './facture.js';
import { repertoire } from './repertoire.js';
import { contact } from './contact.js';
import { agenda } from './agenda.js';

export async function changeFragmentUser() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const UserContent = document.getElementById('userContent');

    UserContent.addEventListener('click', async function(event) {
        const button = event.target;

        if (button.classList.contains('change-fragment')) {
            const fragment = button.getAttribute('data-fragment');
            loadingIndicator.style.display = 'flex';

            try {
                const response = await fetch(`/user?fragment=${fragment}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    loadingIndicator.style.display = 'none';

                    if (fragment === 'link-Factures') {
                        facture();
                    };

                    if (fragment === 'link-Repertoire') {
                        repertoire();
                    };

                    if (fragment === 'link-Contact') {
                        contact();
                    };
                    
                    if (fragment === 'link-Agenda'){
                        // agenda();
                    };

                    document.getElementById('fragmentContent').innerHTML = data.fragmentContent; 

                } else {
                    throw new Error('Erreur lors de la récupération des données');
                }
            } catch (error) {
                loadingIndicator.style.display = 'none';
            }
        }
    });
}