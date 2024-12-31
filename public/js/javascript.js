const routes = {
    newIngredient: document.querySelector('body').dataset.newIngredientPath
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé avec succes');

    var container = document.querySelector('.ingredients-collection');
    if (!container) {
        console.error('Container not found');
        return;
    }

    var addButton = document.querySelector('#add-ingredient');
    var createButton = document.querySelector('#create-ingredient');
    var modalElement = document.getElementById('createIngredientModal');
    
    if (!modalElement) {
        console.error('Modal element not found');
        return;
    }
    
    var modal = new bootstrap.Modal(modalElement);
    
    var index = container.children.length;
    
    console.log('Initial index:', index);

    function addIngredientForm(event) {
        console.log('Adding new ingredient form');
        
        var prototype = container.dataset.prototype;
        
        var newForm = prototype.replace(/__name__/g, index);
        
        var div = document.createElement('div');
        
        div.innerHTML = newForm;
        
        div.classList.add('ingredient-item', 'mb-3');
        
        var removeButton = document.createElement('button');
        
        removeButton.textContent = 'Supprimer';
        
        removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-ingredient');
        
        div.appendChild(removeButton);
        
        container.appendChild(div);
        
        index++;
        
        // console.log('New ingredient form added, new index:', index);
    }

    function removeIngredientForm(event) {
        
         if (event.target.classList.contains('remove-ingredient')) {
            
            // console.log('Removing ingredient form');
            
             event.target.closest('.ingredient-item').remove();
         }
     }

     function openCreateIngredientModal(event) {
         
        // console.log('Opening create ingredient modal');
         
         modal.show();
     }

     async function saveNewIngredient(formData) {
         try {
             const response = await fetch(routes.newIngredient, {
                 method: 'POST',
                 body: formData
             });
             console.log('Server response:', response);
             if (response.ok) {
                // console.log('New ingredient added successfully');
                 modal.hide();
                 form.reset();
             } else {
                 console.error('Error creating new ingredient:', response.statusText);
                 alert(`Erreur lors de la création de l'ingrédient: ${response.statusText}`);
             }
         } catch (error) {
            // console.error('Erreur:', error);
             alert("Une erreur est survenue lors de la communication avec le serveur");
         }
     }

     addButton.addEventListener('click', addIngredientForm);
     
     container.addEventListener('click', removeIngredientForm);
     
     createButton.addEventListener('click', openCreateIngredientModal);
     
     document.getElementById('saveNewIngredient').addEventListener('click', saveNewIngredient);

     console.log("Event listeners attached");
});