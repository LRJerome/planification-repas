document.addEventListener('DOMContentLoaded', function() {
    const addIngredientButton = document.getElementById('add-ingredient');
    const ingredientsCollection = document.querySelector('.ingredients-collection');
    const channel = new BroadcastChannel('ingredients-channel');

    if (addIngredientButton && ingredientsCollection) {
        let index = document.querySelectorAll('.ingredient-row').length;
        let prototypeHtml = ingredientsCollection.dataset.prototype;

        // Fonction pour mettre à jour le prototype avec un nouvel ingrédient
        function updatePrototype(ingredient) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = prototypeHtml;
            
            const select = tempDiv.querySelector('select');
            if (select) {
                // Créer la nouvelle option
                const option = document.createElement('option');
                option.value = ingredient.id;
                option.textContent = ingredient.nom;
                option.dataset.unite = ingredient.unite;
                option.dataset.quantiteDefaut = ingredient.quantiteDefaut;
                
                // Ajouter l'option au select
                select.appendChild(option);
                
                // Trier les options (sauf le placeholder)
                const options = Array.from(select.options).slice(1);
                options.sort((a, b) => a.text.localeCompare(b.text, 'fr', {sensitivity: 'base'}));
                
                // Reconstruire le select
                select.innerHTML = '<option value="">Choisir un ingrédient</option>';
                options.forEach(opt => select.appendChild(opt));
                
                // Mettre à jour le prototype
                prototypeHtml = tempDiv.innerHTML;
                ingredientsCollection.dataset.prototype = prototypeHtml;
                
                // console.log('Prototype mis à jour avec le nouvel ingrédient');
            }
        }

        // Fonction pour mettre à jour les selects existants
        function updateExistingSelects(ingredient) {
            document.querySelectorAll('select[id*="ingredient"]').forEach(select => {
                if (!select.querySelector(`option[value="${ingredient.id}"]`)) {
                    const option = new Option(ingredient.nom, ingredient.id);
                    option.dataset.unite = ingredient.unite;
                    option.dataset.quantiteDefaut = ingredient.quantiteDefaut;
                    
                    // Ajouter l'option
                    select.add(option);
                    
                    // Trier les options
                    const options = Array.from(select.options).slice(1);
                    options.sort((a, b) => a.text.localeCompare(b.text, 'fr', {sensitivity: 'base'}));
                    
                    // Reconstruire le select
                    select.innerHTML = '<option value="">Choisir un ingrédient</option>';
                    options.forEach(opt => select.add(opt));
                    
                    // Sélectionner le nouvel ingrédient
                    select.value = ingredient.id;
                    
                    // console.log('Select existant mis à jour:', select.id);
                }
            });
        }

        // Fonction pour ajouter une nouvelle ligne d'ingrédient
        function addIngredientRow() {
            const newForm = prototypeHtml.replace(/__name__/g, index);
            const div = document.createElement('div');
            div.classList.add('ingredient-row', 'mb-3', 'row', 'align-items-end');
            
            // Extraire les éléments select et input
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newForm;
            const select = tempDiv.querySelector('select');
            const input = tempDiv.querySelector('input');
            
            // Créer la structure HTML
            div.innerHTML = `
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Ingrédient</label>
                        ${select.outerHTML}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Quantité</label>
                        ${input.outerHTML}
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-ingredient w-100">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </div>
            `;

            // Ajouter les classes Bootstrap
            div.querySelector('select').classList.add('form-select');
            div.querySelector('input').classList.add('form-control');

            ingredientsCollection.appendChild(div);
            index++;
        }

        // Gestionnaire d'événement pour le bouton d'ajout
        addIngredientButton.addEventListener('click', function(e) {
            e.preventDefault();
            addIngredientRow();
        });

        // Gestion de la suppression des ingrédients
        ingredientsCollection.addEventListener('click', function(e) {
            const removeButton = e.target.closest('.remove-ingredient');
            if (removeButton) {
                removeButton.closest('.ingredient-row').remove();
            }
        });

        // Écouter les messages du canal de diffusion
        channel.onmessage = function(event) {
            // console.log('Message reçu via BroadcastChannel:', event.data);
            
            if (event.data.type === 'new-ingredient') {
                const ingredient = event.data.ingredient;
                // console.log('Nouvel ingrédient à ajouter:', ingredient);

                // Mettre à jour le prototype et les selects existants
                updatePrototype(ingredient);
                updateExistingSelects(ingredient);
            }
        };
    }
}); 