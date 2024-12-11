window.addEventListener('DOMContentLoaded', () => {
    const status = document.getElementById('session.status');
    const isAuthenticated = (status.textContent.trim() === 'connecté');
    if (!isAuthenticated) {clearData() };

    const menuToggle = document.querySelector('#navbarNav');
    const menuLinks = document.querySelectorAll('#navbarNav a');

    // Ajouter un gestionnaire d'événements à chaque lien
    menuLinks.forEach(link => {
        link.addEventListener('click', function () {
            // Vérifier si le menu est visible
            if (menuToggle.classList.contains('show')) {
                // Supprimer la classe pour fermer le menu
                const collapseElement = bootstrap.Collapse.getInstance(menuToggle);
                if (collapseElement) {
                    collapseElement.hide();
                }
            }
        });
    });

    const proButton = document.getElementById('btnPro');
    const partButton = document.getElementById('btnPart');

    if (proButton && partButton) {
        proButton.addEventListener('click', () => toggleSection('pro'));
        partButton.addEventListener('click', () => toggleSection('part'));
    } 

    const btnProfile = document.getElementById('btnProfile');

    const buttons = [
        { ids: ['btnProfile','menuProfile'], section: 'link-Profile' },
        { ids: ['btnAdministratif','menuAdministratif'], section: 'link-Administratif' },
        { ids: ['btnAgenda','menuAgenda'], section: 'link-Agenda' },
        { ids: ['btnCommercial','menuCommercial'], section: 'link-Commercial' },
        { ids: ['btnNumerique','menuNumerique'], section: 'link-Numerique' },
        { ids: ['btnRepertoire','menuRepertoire'], section: 'link-Repertoire' },
        { ids: ['btnTelephone','menuTelephone'], section: 'link-Telephone' },
        { ids: ['btnFactures','menuFactures'], section: 'link-Factures' },
        { ids: ['btnAcceuil','menuAcceuil'], section: 'link-Acceuil' },
        { ids: ['btnMainAgenda'], section: 'link-MainAgenda' },
        { ids: ['btnNewRepertoire'], section: 'link-newRepertoire'},        
        { ids: ['fournisseurFolder', "btnFournisseurFolder"], section: 'link-fournisseurFolder'},        
        { ids: ['clientFolder', "btnclientFolder"], section: 'link-clientFolder'},        
        { ids: ['organismeFolder', 'organismeFolder'], section: 'link-organismeFolder'},        
        { ids: ['contactsFolder', 'contactsFolder'], section: 'link-contactsFolder'},       
        { ids: ['btnNewRepertoireClient'], section: 'link-newRepertoireClient'},       
        { ids: ['btnNewRepertoireFournisseur'], section: 'link-newRepertoireFournisseur'},       
        { ids: ['btnNewRepertoireOrganisme'], section: 'link-newRepertoireOrganisme'},       
        { ids: ['btnNewRepertoirePersonnel'], section: 'link-newRepertoirePersonnel'},       
        { ids: ['btnParametres', 'menuParametres'], section: 'link-parametres'},       
        { ids: ['btnEspacePersonnel'], section: 'link-espacepersonnel'},       
        { ids: [''], section: 'link-repertoireInfo'},       
    ];
    
    if(btnProfile){
        
        buttons.forEach(button => {
            button.ids.forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.addEventListener('click', () => toggleSectionUser(button.section));
                }
            });
        });
    }

    const btnNewGoogleEvent = document.getElementById('btnNewGoogleEvent');
    if(btnNewGoogleEvent){
        console.log("btnNewGoogleEvent");
        btnNewGoogleEvent.addEventListener('click', () => toggleSectionAgenda('link-newGoogleEvent'));
    }

    // Vérifier et afficher la section correspondant à l'ancre
    const hash = location.hash.replace('#', ''); // Extraire l'ancre de l'URL
    showSection(hash); // Afficher la section correspondante à l'ancre

    const currentHash = window.location.hash;
    const btnToogle = document.getElementById('btnToogle');

    // Si un hash est présent dans l'URL, cacher btnToogle
    if (currentHash && btnToogle) {
        btnToogle.classList.add('d-none');
    }

});

window.addEventListener('hashchange', function () {
    const hash = location.hash.replace('#', '');
    showSection(hash);
});

function showSection(targetId) {
    const allSections = document.querySelectorAll('[id^="link-"]');

    allSections.forEach(section => {
        if (section.id === targetId) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    });
    
    //history.replaceState(null, '', window.location.pathname);
}

function clearData(){
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
        'link-Profile', 
        'link-Administratif', 
        'link-Agenda', 
        'link-Commercial', 
        'link-Numerique', 
        'link-Repertoire', 
        'link-Telephone', 
        'link-Factures',
        'link-MainAgenda',
        'link-newRepertoire',
        'link-fournisseurFolder',
        'link-clientFolder',
        'link-organismeFolder',
        'link-contactsFolder',
        'link-newRepertoireClient',
        'link-newRepertoireFournisseur',
        'link-newRepertoireOrganisme',
        'link-newRepertoirePersonnel',
        'link-parametres',
        'link-espacepersonnel',
        'link-repertoireInfo'
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
        if(sectionId === targetId){
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    });

    
    if (btnToggleAgenda ) {
        btnToggleAgenda.classList.add('d-none');
    }
}

// détails event

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