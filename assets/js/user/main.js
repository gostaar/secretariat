import { facture } from './facture.js';
import { repertoire } from './repertoire.js';
import { agenda } from './agenda.js';

export async function changeFragmentUser() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const UserContent = document.getElementById('userContent');

    async function loadFragment(fragment) {
        loadingIndicator.style.display = 'flex'; 
        history.pushState(null, '', `?fragment=${fragment}`);

        try {
            const response = await fetch(`/changefragment?fragment=${fragment}`, { method: 'GET' });

            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }

            const data =  await response.json();
            console.log(data.fragmentContent);
            updateFragmentContent(fragment, data.fragmentContent);
        } catch (error) {
            console.error("Erreur lors du chargement du fragment : ", error);
        } finally {
            loadingIndicator.style.display = 'none';
        }
    }

    function updateFragmentContent(fragment, content) {
        document.getElementById('fragmentContent').innerHTML = content;

        switch (fragment) {
            case 'link-Factures':
                facture();
                break;
            case 'link-Repertoire':
                repertoire();
                break;
            case 'link-Agenda':
                // agenda();
                break;
            case 'link-Contact':
                // contact();
                break;
            default:
                break;
        }
    }

    UserContent.addEventListener('click', async function(event) {
        const button = event.target;

        if (button.classList.contains('change-fragment')) {
            event.preventDefault();  
            const fragment = button.getAttribute('data-fragment');
            
            await loadFragment(fragment);
        }
    });

}
