window.addEventListener('DOMContentLoaded', () => {
    initializeSessionStatus();
    initializeButtons();
    handlePageLoad();
    handleGoogleEventButton();
    handleHashChange();
});

window.addEventListener('hashchange', function () {
    const hash = location.hash.replace('#', '');
    showSection(hash);
});

function initializeSessionStatus() {
    const status = document.getElementById('session.status');
    const isAuthenticated = (status.textContent.trim() === 'connecté');
    if (!isAuthenticated) {
        clearData();
    }

    if (isHomePage()) {
        clearData();
    }
}

function isHomePage() {
    return window.location.href === "http://localhost:8080" ||
           window.location.href === "http://localhost:8080/" ||
           window.location.href === "http://localhost:8080/#link-start-part" ||
           window.location.href === "http://localhost:8080/#link-start-pro";
}

function initializeButtons() {
    const proButton = document.getElementById('btnPro');
    const partButton = document.getElementById('btnPart');

    if (proButton && partButton) {
        proButton.addEventListener('click', () => toggleSection('pro'));
        partButton.addEventListener('click', () => toggleSection('part'));
    }

    const buttons = [
        //section acceuil
        { ids: ['btnAcceuil','menuAcceuil'], section: 'link-Acceuil' },
        //section Administratif
        { ids: ['btnAdministratif','menuAdministratif'], section: 'link-Administratif' },
        { ids: ['btnNewDocument'], section: 'link-newDocument'},
        //section Agenda
        { ids: ['btnAgenda','menuAgenda'], section: 'link-Agenda' },
        { ids: ['btnMainAgenda'], section: 'link-MainAgenda' },
        //section Commercial
        { ids: ['btnCommercial','menuCommercial'], section: 'link-Commercial' },
        //section Numerique
        { ids: ['btnNumerique','menuNumerique'], section: 'link-Numerique' },
        //section Profile
        { ids: ['btnProfile','menuProfile'], section: 'link-Profile' },
        //section Profile -> repertoire
        { ids: ['btnRepertoire','menuRepertoire'], section: 'link-Repertoire' },
        { ids: ['btnNewRepertoire'], section: 'link-newRepertoire'},
        { ids: ['fournisseurFolder', "btnFournisseurFolder"], section: 'link-fournisseurFolder'},
        { ids: ['clientFolder', "btnclientFolder"], section: 'link-clientFolder'},
        { ids: ['organismeFolder', 'organismeFolder'], section: 'link-organismeFolder'},
        { ids: ['contactsFolder', 'contactsFolder'], section: 'link-contactsFolder'},
        { ids: ['btnNewRepertoireClient'], section: 'link-newRepertoireClient'},
        { ids: ['btnTelephone','menuTelephone'], section: 'link-Telephone' },
        { ids: ['btnNewRepertoireFournisseur'], section: 'link-newRepertoireFournisseur'},
        { ids: ['btnNewRepertoireOrganisme'], section: 'link-newRepertoireOrganisme'},
        { ids: ['btnNewRepertoirePersonnel'], section: 'link-newRepertoirePersonnel'},
        { ids: ['btnRepertoireInfo'], section: 'link-repertoireInfo'},
        //section Profile -> facture
        { ids: ['btnFactures','menuFactures'], section: 'link-Factures' },
        { ids: ['btnEspacePersonnel'], section: 'link-espacepersonnel'},
        //section Profile -> parametres
        { ids: ['btnParametres', 'menuParametres'], section: 'link-parametres'},
        //section Telephone
        { ids: [''], section: 'link-repertoireInfo'},
    ];

    buttons.forEach(button => {
        button.ids.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) {
                btn.addEventListener('click', () => toggleSectionUser(button.section));
            }
        });
    });
}

function handlePageLoad() {
    const hash = location.hash.replace('#', ''); // Extraire l'ancre de l'URL
    showSection(hash); // Afficher la section correspondante à l'ancre

    const currentHash = window.location.hash;
    const btnToogle = document.getElementById('btnToogle');

    if (currentHash && btnToogle) {
        btnToogle.classList.add('d-none');
    }
}

function handleGoogleEventButton() {
    const btnNewGoogleEvent = document.getElementById('btnNewGoogleEvent');
    if (btnNewGoogleEvent) {
        console.log("btnNewGoogleEvent");
        btnNewGoogleEvent.addEventListener('click', () => toggleSectionAgenda('link-newGoogleEvent'));
    }
}

function showSection(targetId) {
    const allSections = document.querySelectorAll('[id^="link-"]');

    allSections.forEach(section => {
        if (section.id === targetId) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    });
}

function clearData() {
    sessionStorage.clear();
    localStorage.clear();
    const cookies = document.cookie.split(';');
    cookies.forEach(cookie => {
        const cookieName = cookie.split('=')[0].trim();
        document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/; secure; sameSite=None`;
    });
}

function toggleSection(targetId) {
    const sections = ['pro', 'part'];
    const btnPartPro = document.getElementById('btnPartPro');

    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (sectionId === targetId) {
            section.classList.remove('d-none');
            section.classList.add('fade-in');
            location.replace(`/#link-start-${targetId}`);
        } else {
            section.classList.add('d-none');
            section.classList.remove('fade-in');
        }
    });

    if (btnPartPro) {
        btnPartPro.classList.add('d-none');
    }
}

function toggleSectionUser(targetId) {
    const sections = [
        //section acceuil
        //section Administratif
        'link-Administratif', 
        'link-newDocument',
        //section Agenda
        'link-Agenda', 
        'link-MainAgenda', 
        //section Commercial
        'link-Commercial', 
        //section Numerique
        'link-Numerique', 
        //section Profile
        'link-Profile', 
        'link-espacepersonnel', 
        //section Profile -> repertoire
        'link-Repertoire', 
        'link-newRepertoire', 
        'link-fournisseurFolder', 
        'link-clientFolder', 
        'link-organismeFolder', 
        'link-contactsFolder', 
        'link-newRepertoireClient', 
        'link-newRepertoireFournisseur', 
        'link-newRepertoireOrganisme', 
        'link-newRepertoirePersonnel', 
        'link-repertoireInfo', 
        //section Profile -> facture
        'link-Factures',
        //section Profile -> parametres
        'link-parametres', 
        //section Telephone
        'link-Telephone', 
    ];

    const btnToogle = document.getElementById('btnToogle');
    if (btnToogle) {
        btnToogle.classList.add('d-none');
    }

    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (sectionId === targetId) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    });
}

function toggleSectionAgenda(targetId) {
    const sections = ['link-newGoogleEvent'];
    const btnToggleAgenda = document.getElementById('btnToggleAgenda');

    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (sectionId === targetId) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    });

    if (btnToggleAgenda) {
        btnToggleAgenda.classList.add('d-none');
    }
}

// Détails event
document.addEventListener('DOMContentLoaded', () => {
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
});
