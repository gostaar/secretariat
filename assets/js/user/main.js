import { facture } from './facture.js';
import { repertoire } from './repertoire.js';
import { agenda } from './agenda.js';

export async function changeFragmentUser() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const UserContent = document.getElementById('userContent');

    UserContent.addEventListener('click', async function(event) {
        const button = event.target;

        if (button.classList.contains('change-fragment')) {
            const fragment = button.getAttribute('data-fragment');
            loadingIndicator.style.display = 'flex';
            history.pushState(null, '', `?fragment=${fragment}`);

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

                    switch (fragment) {
                        case 'link-Factures':
                            facture();
                            break;
                        case 'link-Repertoire':
                            repertoire();
                            break;
                        case 'link-Contact':
                            // contact();
                            break;
                        case 'link-Agenda':
                            // agenda();
                            break;
                        default:
                            break;
                    }

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