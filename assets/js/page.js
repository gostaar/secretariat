export function page(){
    document.querySelectorAll('.list-group-flush').forEach(function(button) {
        button.addEventListener('click', function() {
            const btn = button.querySelector('.btn-link');
            if (btn.classList.contains('collapsed')) {
                button.classList.remove('selected');
            } else {
                button.classList.add('selected');
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

}

