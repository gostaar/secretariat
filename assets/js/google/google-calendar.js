import { handleSignoutClick, injectEventsToFullCalendar, injectEventsToFullCalendarAfterEmpty } from './utils.js';
import { isAccessTokenValid } from './auth.js';
import { showDetailsEvent } from './ui.js';
 
let allEvents = [];
let allEventsForSymfony = [];

// ################ GESTION DES EVENEMENTS ######################
// Fonction pour formater les événements pour Google
function formatEvents(events) {
    return events.map(event => ({
        title: event.summary || 'Aucun titre',
        start: event.start.dateTime || '2024-01-01T00:00:00',
        end: event.end.dateTime || '2024-01-01T00:00:00',
        description: event.description || '',
        location: event.location || '',
    }));
}

// Fonction pour formater les événements pour Symfony
function formatEventsForSymfony(events, calendarId) {
    return events.map(event => ({
        title: event.summary || 'Aucun titre',
        description: event.description || '',
        location: event.location || '',
        start: event.start.dateTime || '2024-01-01T00:00:00',
        end: event.end.dateTime || '2024-01-01T00:00:00',
        googleCalendarEventId: calendarId || '',
    }));
}

// Ajouter un événement dans le calendrier
export async function addEventCalendars(event, eventSymfony, accessToken) {
    injectEventsToFullCalendar([event]);
    const isAuthorized = await isAccessTokenValid();
    if(isAuthorized){
        addEventToGoogle(accessToken, event);
    }
    addEventToSymfony(eventSymfony)
}

// Supprimer tous les événements stockés localement
export function emptyEvents() {
    allEvents = [];
}

// ################ SYNCHRONISATION AVEC GOOGLE ###############
// Récupérer les calendriers Google
export const fetchCalendars = async (accessToken) => {
    const response = await fetch('https://www.googleapis.com/calendar/v3/users/me/calendarList', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${accessToken}`,
        },
    });
    const data = await response.json();
    
    if (!data.items || !Array.isArray(data.items)) {
        return;
    }

    const calendars = data.items.filter(calendar => calendar.id && !calendar.id.includes('addressbook#'));
    
    await Promise.all(
        calendars.map(calendar => getEvents(calendar.id, accessToken))
    );
    
    injectEventsToFullCalendar(allEvents);
};

// Récupérer les événements d'un calendrier Google
async function getEvents(calendarId, accessToken) {
    const now = new Date().toISOString();
    const response = await fetch(`https://www.googleapis.com/calendar/v3/calendars/${calendarId}/events?timeMin=${now}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${accessToken}`,
        },
    });
    const data = await response.json();
    const events = data.items || []; 
    const formattedEvents = formatEvents(events);
    const formattedEventsForSymfony = formatEventsForSymfony(events, calendarId);
    allEvents.push(...formattedEvents);
    allEventsForSymfony.push(...formattedEventsForSymfony);
}

// Synchronisation des événements de Google vers Symfony
async function syncEventsGoogleInSymfony(events) {
    const validEvents = events.filter(event => event.title && event.start && event.end);
    
    if (validEvents.length === 0) {
        return;
    }
    
        await fetch('/create_events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(validEvents)
        });
    
}

// Ajouter un événement sur Google
async function addEventToGoogle(accessToken, eventData) {
    const response = await fetch('/google-calendar/create-event', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ accessToken, eventData }),
    });

    if (!response.ok) {
        throw new Error('Failed to create event');
    }
    return await response.json();
}

//Ajouter un évènement sur Symfony 
async function addEventToSymfony(eventData){
    const response = await fetch('/create_events', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({eventData}),
    });
    if (!response.ok) {
        throw new Error('Failed to create event');
    }
    return await response.json();
}

// Synchronisation des événements de Symfony vers Google
async function syncEventsSymfonyInGoogle() {
    const response = await fetch('/events', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
    });

    if (!response.ok) {
        throw new Error('Échec de la récupération des événements depuis Symfony');
    }

    const data = await response.json();

    if (data.length === 0) {
        console.log('Aucun événement à synchroniser avec Google.');
        return;
    }
    emptyEvents();
    allEvents.push(...data); 

    await syncEventsToGoogle(data);
}

// Fonction pour envoyer les événements de Symfony à Google
async function syncEventsToGoogle(events) {
    const isAuthorized = await isAccessTokenValid();
    if(isAuthorized){
        const cookie = document.cookie.split('; ').find(row => row.startsWith('access_token='));
        const accessToken = cookie.split('=')[1];
        for (let event of events) {
            const calendarId = event.googleCalendarEventId;
            const startDateTime = new Date(event.start).toISOString();
            const endDateTime = new Date(event.end).toISOString();

            const checkEventResponse = await fetch(`https://www.googleapis.com/calendar/v3/calendars/${calendarId}/events?timeMin=${startDateTime}&timeMax=${endDateTime}&q=${event.title}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${accessToken}`,
                },
            });

            if (!checkEventResponse.ok) {
                throw new Error('Erreur lors de la vérification de l\'événement');
            }

            const checkEventData = await checkEventResponse.json();

            if (checkEventData.items && checkEventData.items.length > 0) {
                return;
            }

            const response = await fetch(`https://www.googleapis.com/calendar/v3/calendars/${calendarId}/events`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${accessToken}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    summary: event.title,
                    description: event.description,
                    location: event.location,
                    start: { 
                        dateTime: startDateTime,
                        timeZone: "Europe/Paris"
                    },
                    end: { 
                        dateTime: endDateTime,
                        timeZone: "Europe/Paris"
                    },
                }),
            });

            if (!response.ok) {
                throw new Error('Échec de la synchronisation des événements vers Google');
            }
        }
    } else {handleSignoutClick();}
}

// ############### SYNCHRONISATION GLOBALE ###############
// Synchronisation globale: Google vers Symfony
export async function handleSync(){
    await syncEventsGoogleInSymfony(allEventsForSymfony);
    await updateEventList();
}

// Synchronisation globale: Symfony vers Google
export async function handleSyncSymfony() {
    await syncEventsSymfonyInGoogle();
    injectEventsToFullCalendarAfterEmpty(allEvents);
}

//  ############## MISE A JOUR LISTE EVENEMENTS #############
// Mettre à jour la liste des événements
async function updateEventList() {
    const response = await fetch('/events_byNow');
    if (!response.ok) {
        throw new Error('Erreur lors du chargement des événements');
    }
    const events = await response.json();

    const eventList = document.querySelector('#eventList');
    eventList.innerHTML = '';

    if (events.length > 0) {
        const fragment = document.createDocumentFragment();
        events.forEach(event => {
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item');
            listItem.setAttribute('data-title', event.title);
            listItem.setAttribute('data-start', new Date(event.start).toLocaleString());
            listItem.setAttribute('data-end', new Date(event.end).toLocaleString());
            listItem.setAttribute('data-description', event.description);
            listItem.setAttribute('data-google', event.googleCalendarEventId);
            listItem.setAttribute('data-location', event.location);
            listItem.setAttribute('role', 'button');
            listItem.setAttribute('aria-label', `Afficher les détails de l'événement ${event.title}`);
            
            listItem.innerHTML = `
                <strong>${event.title}</strong><br>
                <small>${new Date(event.start).toLocaleString()}</small><br>
                <p>${event.description}</p>
            `;
            fragment.appendChild(listItem);
            listItem.addEventListener('click', showDetailsEvent); 
        });
        eventList.appendChild(fragment);
    } else {
        eventList.innerHTML = '<li>Aucun événement à venir.</li>';
    }  
}

