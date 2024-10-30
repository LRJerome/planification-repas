home-> 
{% extends 'base.html.twig' %}

{% block title %}Planning des repas{% endblock %}

{% block body %}
<div class="container mt-4">
    <h1 class="text-center mb-4">Planning de tous les repas fait et à faire</h1>

    <div class="mb-4 d-flex justify-content-center gap-3">
        <a href="{{ path('repas_new') }}" class="btn btn-success btn-lg">
            <i class="fa-solid fa-utensils"></i> Créer une recette
        </a>
        <a href="{{ path('planning_new') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-calendar-plus"></i> Ajouter au planning
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th class="d-none d-md-table-cell">Petit Déjeuner</th>
                    <th class="d-none d-md-table-cell">Encas Matin</th>
                    <th>Déjeuner</th>
                    <th class="d-none d-md-table-cell">Encas Après-midi</th>
                    <th>Dîner</th>
                </tr>
            </thead>
            <tbody>
                {% for date, dayPlanning in planningsByDate %}
                    <tr>
                        <td>{{ dayPlanning.date|date('d/m/Y') }}</td>
                        {{ _self.repas_cell(dayPlanning.petitDejeuner, 'Petit Déjeuner', loop.index, dayPlanning.nombrePersonnesPetitDejeuner, 'd-none d-md-table-cell') }}
                        {{ _self.repas_cell(dayPlanning.encasMatin, 'Encas Matin', loop.index, dayPlanning.nombrePersonnesEncasMatin, 'd-none d-md-table-cell') }}
                        {{ _self.repas_cell(dayPlanning.dejeuner, 'Déjeuner', loop.index, dayPlanning.nombrePersonnesDejeuner) }}
                        {{ _self.repas_cell(dayPlanning.encasApresMidi, 'Encas Après-midi', loop.index, dayPlanning.nombrePersonnesEncasApresMidi, 'd-none d-md-table-cell') }}
                        {{ _self.repas_cell(dayPlanning.diner, 'Dîner', loop.index, dayPlanning.nombrePersonnesDiner) }}
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="6">Aucun planning trouvé pour cette période.</td>
                    </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</div>
{% endblock %}

{% macro repas_cell(repas, type, index, nombrePersonnes, extra_classes='') %}
<td class="{{ extra_classes }}">
    {% if repas %}
        <div class="text-truncate" style="max-width: 150px;" title="{{ repas.nom }}">
            <strong>{{ repas.nom|length > 20 ? repas.nom|slice(0, 20) ~ '...' : repas.nom }}</strong>
            {% if nombrePersonnes %}
                <small class="text-muted">({{ nombrePersonnes }} pers.)</small>
            {% endif %}
        </div>
        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{ type|replace({' ': ''}) }}{{ index }}" aria-label="Voir les détails de {{ type }}">
            <i class="fa-solid fa-eye"></i> Voir
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modal{{ type|replace({' ': ''}) }}{{ index }}" tabindex="-1" aria-labelledby="modalLabel{{ type|replace({' ': ''}) }}{{ index }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel{{ type|replace({' ': ''}) }}{{ index }}">{{ type }} : {{ repas.nom }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        {% if nombrePersonnes %}
                            <div class="alert alert-info" role="alert">
                                <strong>Nombre de personnes :</strong> {{ nombrePersonnes }} personne{% if nombrePersonnes > 1 %}s{% endif %}
                            </div>
                        {% endif %}

                        <h6 class="fw-bold">Ingrédients :</h6>
                        <ul class="list-group mb-3">
                            {% for ingredient in repas.ingredients %}
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ ingredient.nom }}
                                    <span class="badge bg-primary">{{ ingredient.quantiteDefaut }} {{ ingredient.unite }}</span>
                                </li>
                            {% else %}
                                <li class="list-group-item">Aucun ingrédient disponible.</li>
                            {% endfor %}
                        </ul>

                        {% if repas.recette %}
                            <h6 class="fw-bold">Recette :</h6>
                            <p>{{ repas.recette }}</p>
                        {% else %}
                            <p class="text-muted">Aucune recette disponible.</p>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    {% else %}
        <p class="text-muted">Pas de {{ type|lower }} prévu</p>
    {% endif %}
</td>
{% endmacro %}
planning/index->
{% extends 'base.html.twig' %}

{% block title %}Planning des repas{% endblock %}

{% block body %}
<div class="container mt-4">
    <h1 class="text-center mb-4">Repas des 8 prochains jours</h1>

    <div class="mb-4 d-flex justify-content-center gap-3">
        <a href="{{ path('repas_new') }}" class="btn btn-success btn-lg">
            <i class="fa-solid fa-utensils"></i> Créer une recette
        </a>
        <a href="{{ path('planning_new') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-calendar-plus"></i> Ajouter au planning
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th class="d-none d-md-table-cell">Petit Déjeuner</th>
                    <th class="d-none d-md-table-cell">Encas Matin</th>
                    <th>Déjeuner</th>
                    <th class="d-none d-md-table-cell">Encas Après-midi</th>
                    <th>Dîner</th>
                </tr>
            </thead>
            <tbody>
                {% for date, dayPlanning in planningsByDate %}
                    <tr>
                        <td>{{ dayPlanning.date|date('d/m/Y') }}</td>
                        {{ _self.repas_cell(dayPlanning.petitDejeuner, 'Petit Déjeuner', loop.index, dayPlanning.nombrePersonnesPetitDejeuner, 'd-none d-md-table-cell') }}
                        {{ _self.repas_cell(dayPlanning.encasMatin, 'Encas Matin', loop.index, dayPlanning.nombrePersonnesEncasMatin, 'd-none d-md-table-cell') }}
                        {{ _self.repas_cell(dayPlanning.dejeuner, 'Déjeuner', loop.index, dayPlanning.nombrePersonnesDejeuner) }}
                        {{ _self.repas_cell(dayPlanning.encasApresMidi, 'Encas Après-midi', loop.index, dayPlanning.nombrePersonnesEncasApresMidi, 'd-none d-md-table-cell') }}
                        {{ _self.repas_cell(dayPlanning.diner, 'Dîner', loop.index, dayPlanning.nombrePersonnesDiner) }}
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="6">Aucun planning trouvé pour cette période.</td>
                    </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</div>
{% endblock %}

{% macro repas_cell(repas, type, index, nombrePersonnes, extra_classes='') %}
<td class="{{ extra_classes }}">
    {% if repas %}
        <div class="text-truncate" style="max-width: 150px;" title="{{ repas.nom }}">
            <strong>{{ repas.nom|length > 20 ? repas.nom|slice(0, 20) ~ '...' : repas.nom }}</strong>
            {% if nombrePersonnes %}
                <small class="text-muted">({{ nombrePersonnes }} pers.)</small>
            {% endif %}
        </div>
        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{ type|replace({' ': ''}) }}{{ index }}" aria-label="Voir les détails de {{ type }}">
            <i class="fa-solid fa-eye"></i> Voir
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modal{{ type|replace({' ': ''}) }}{{ index }}" tabindex="-1" aria-labelledby="modalLabel{{ type|replace({' ': ''}) }}{{ index }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel{{ type|replace({' ': ''}) }}{{ index }}">{{ type }} : {{ repas.nom }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        {% if nombrePersonnes %}
                            <div class="alert alert-info" role="alert">
                                <strong>Nombre de personnes :</strong> {{ nombrePersonnes }} personne{% if nombrePersonnes > 1 %}s{% endif %}
                            </div>
                        {% endif %}

                        <h6 class="fw-bold">Ingrédients :</h6>
                        <ul class="list-group mb-3">
                            {% for ingredient in repas.ingredients %}
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ ingredient.nom }}
                                    <span class="badge bg-primary">{{ ingredient.quantiteDefaut }} {{ ingredient.unite }}</span>
                                </li>
                            {% else %}
                                <li class="list-group-item">Aucun ingrédient disponible.</li>
                            {% endfor %}
                        </ul>

                        {% if repas.recette %}
                            <h6 class="fw-bold">Recette :</h6>
                            <p>{{ repas.recette }}</p>
                        {% else %}
                            <p class="text-muted">Aucune recette disponible.</p>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    {% else %}
        <p class="text-muted">Pas de {{ type|lower }} prévu</p>
    {% endif %}
</td>
{% endmacro %}
