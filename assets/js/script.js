document.querySelectorAll('.list-group-flush').forEach(function(button) {
    button.addEventListener('click', function() {
        const btn = button.querySelector('.btn-link'); // Trouver l'enfant avec .btn-link
        if (btn.classList.contains('collapsed')) {
            button.classList.remove('selected'); // Retirer 'selected' du parent
        } else {
            button.classList.add('selected'); // Ajouter 'selected' au parent
        }
    });
});

const job = document.getElementById('job');
if(job){
    job.addEventListener('change', function() {
        const customJobField = document.getElementById('customJob');
        if (this.value === 'Autre') {
            customJobField.classList.remove('d-none'); // Affiche le champ texte
        } else {
            customJobField.classList.add('d-none'); // Cache le champ texte
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    //site
    const navLinks = document.querySelectorAll('#navbarNav .nav-link, #navbarNav button');

    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navbarCollapse = document.getElementById('navbarNav');
            
            if (navbarCollapse.classList.contains('show')) {
                menuToggle.click();
            }
        });
    });

    //user
    const navMenus = document.querySelectorAll('#submenu3 .nav-link, #menuAcceuil, #menuAdministratif, #btnAgenda', '#menuCommercial', '#menuNumerique', '#menuTelephone');

    navMenus.forEach(link => {
        link.addEventListener('click', function() {
            const menuToggle = document.getElementById('submenu');
            const navbarCollapse = document.getElementById('submenu3');
            
            if (navbarCollapse.classList.contains('show')) {
                menuToggle.click();
            }
        });
    });

    document.getElementById('btnAjouterRepertoire').addEventListener('click', function() {
        this.classList.add('d-none');
        document.getElementById('formAjouterRepertoire').classList.remove('d-none');
    });

    document.getElementById('hideAjouterRepertoire').addEventListener('click', function() {
        document.getElementById('formAjouterRepertoire').classList.add('d-none');
        document.getElementById('btnAjouterRepertoire').classList.remove('d-none');
    }); 
    
    // document.getElementById('formDossier').addEventListener('submit', function(event) {
    //     event.preventDefault();
    //     this.reset();
    // });

});

