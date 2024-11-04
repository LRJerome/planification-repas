document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const ingredientFilters = document.querySelectorAll('.ingredient-filter');
    const recipeRows = document.querySelectorAll('.recipe-row');

    let currentCategory = 'all';
    let currentIngredient = 'all';

    function filterRecipes() {
        recipeRows.forEach(row => {
            const category = row.dataset.category;
            const ingredients = row.dataset.ingredients ? row.dataset.ingredients.toLowerCase() : '';

            const matchesCategory = currentCategory === 'all' || category === currentCategory;
            const matchesIngredient = currentIngredient === 'all' || ingredients.includes(currentIngredient);

            row.style.display = (matchesCategory && matchesIngredient) ? '' : 'none';
        });
    }

    // Gestionnaire pour les filtres de catégorie
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Mise à jour de l'état actif des boutons de catégorie
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Mise à jour de la catégorie courante
            currentCategory = this.dataset.filter;
            
            // Application des filtres
            filterRecipes();
        });
    });

    // Gestionnaire pour les filtres d'ingrédients
    ingredientFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            // Mise à jour de l'état actif des filtres d'ingrédients
            ingredientFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            // Mise à jour de l'ingrédient courant
            currentIngredient = this.dataset.ingredient;
            
            // Application des filtres
            filterRecipes();
        });
    });

    // Initialisation : afficher toutes les recettes
    filterRecipes();
}); 