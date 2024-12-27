import { facture } from './facture.js';
import { repertoire } from './repertoire.js';
import { agenda } from './agenda.js';

export async function changeFragmentUser() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const UserContent = document.getElementById('userContent');

    async function loadFragment(fragment, dossierId = null) {
        console.log(`dossierId : ${dossierId}`);
        loadingIndicator.style.display = 'flex'; 
        let url = `/changefragment?fragment=${fragment}`;
        if (dossierId) { url += `&dossier=${dossierId}`;}
        history.pushState(null, '', `?fragment=${fragment}${dossierId ? '&dossier=' + dossierId : ''}`);

        try {
            const response = await fetch(url, { method: 'GET' });

            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }

            const data =  await response.json();
           
            updateFragmentContent(fragment);
            document.getElementById('fragmentContent').innerHTML = data.fragmentContent;


        } catch (error) {
            console.error("Erreur lors du chargement du fragment : ", error);
        } finally {
            loadingIndicator.style.display = 'none';
        }
    }

    function updateFragmentContent(fragment) {
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
            const dossier = button.getAttribute('data-dossier');
console.log(dossier);
            dossier ? await loadFragment(fragment, dossier) : await loadFragment(fragment); 
            
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    const fragmentFromUrl = urlParams.get('fragment');

    if (fragmentFromUrl) {
        updateFragmentContent(fragmentFromUrl);
    }

    window.addEventListener('popstate', async function() {
        const urlParams = new URLSearchParams(window.location.search);
        const fragmentFromUrl = urlParams.get('fragment');
        const dossierIdFromUrl = urlParams.get('dossier');  // Récupère l'ID du dossier si présent
    
        if (fragmentFromUrl) {
            dossierIdFromUrl ? await loadFragment(fragmentFromUrl, dossierIdFromUrl) : await loadFragment(fragmentFromUrl);
        }
    });
}
