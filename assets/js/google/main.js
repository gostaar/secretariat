import { handlePageSpecificUI, updateUIBasedOnState, callFetchCalendar, handleSyncClick, handleSyncSymfonyClick, showDetailsEvent } from './ui.js';
import { handleAuthButtonClick, handleSubmitClick, gapiLoaded, gisLoaded} from './auth.js';
import { initialisationFullCalendar, handleSignoutClick } from './utils.js';

window.gapiLoaded = gapiLoaded;
window.gisLoaded = gisLoaded;

document.addEventListener('DOMContentLoaded', async () => {
    initialisationFullCalendar();
    callFetchCalendar();
    if(handlePageSpecificUI()){ 
        ready();
    }
});

async function ready() {
    updateUIBasedOnState();

    const authButton = document.getElementById('authorize_button');
    const signoutButton = document.getElementById('signout_button');
    const createEventForm = document.getElementById('create-event-form');
    const syncButton = document.getElementById("syncButton");
    const syncButtonSymfony = document.getElementById("syncButtonSymfony");
    const listEventDetails = document.querySelectorAll('#eventList li');

    authButton.addEventListener('click', handleAuthButtonClick);
    signoutButton.addEventListener('click', handleSignoutClick);
    createEventForm.addEventListener('submit', handleSubmitClick);
    syncButton.addEventListener('click', async () => { await handleSyncClick();}); 
    syncButtonSymfony.addEventListener('click', async () => { await handleSyncSymfonyClick(); }); 
    listEventDetails.forEach(item => { item.addEventListener('click', showDetailsEvent); });
}