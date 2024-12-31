document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.querySelector('#add-ingredient');
    const container = document.querySelector('.ingredients-collection');
    let index = container ? container.children.length : 0;

    if (addButton && container) {
        addButton.addEventListener('click', function() {
            console.log('Ajout d\'un nouvel ingrédient');
            const prototype = container.dataset.prototype;
            const newForm = prototype.replace(/__name__/g, index);
            const div = document.createElement('div');
            div.innerHTML = newForm;
            div.classList.add('ingredient-item', 'mb-3');
            container.appendChild(div);
            index++;

            // Initialiser Select2 pour le nouveau champ
            $(div).find('.ingredient-select').select2({
                theme: 'bootstrap-5',
                language: 'fr',
                matcher: function(params, data) {
                    // Si pas de recherche, retourner tous les éléments
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    // Ne rien retourner si pas de données
                    if (typeof data.text === 'undefined') {
                        return null;
                    }

                    // Convertir en minuscules pour une recherche insensible à la casse
                    const searchTerm = params.term.toLowerCase();
                    const textToSearch = data.text.toLowerCase();

                    // Vérifier si le texte commence par le terme recherché
                    if (textToSearch.startsWith(searchTerm)) {
                        return data;
                    }

                    // Si on arrive ici, ça ne correspond pas
                    return null;
                }
            });
        });
    } else {
        console.error('Bouton d\'ajout ou conteneur non trouvé');
    }

    window.addEventListener('message', function(event) {
        if (event.data.type === 'ingredientAdded') {
            console.log('Ingrédient ajouté:', event.data);
            const selects = document.querySelectorAll('.ingredient-select');
            const lastSelect = selects[selects.length - 1];
            const newOption = new Option(event.data.nom, event.data.id, false, false);
            
            newOption.dataset.unite = event.data.unite;
            lastSelect.add(newOption);
            // Ne pas modifier la sélection actuelle
        }
    });
});