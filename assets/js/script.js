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