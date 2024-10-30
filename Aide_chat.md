	Base de données Croissant	Interclassement	Action
	achat_vente	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	app	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	bastille_symfony_blog	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	bastille_symfony_crud	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	bastille_symfony_discovery	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	information_schema	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	meal_planning	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	mysql	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	PDO	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	performance_schema	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	photographe_mariage	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	planification_repas	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	planning	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
	sys	utf8_general_ci	Vérifier les privilèges Vérifier les privilèges
'''
public/js/javascript.js->
document.addEventListener('DOMContentLoaded', function() {

    var list = document.getElementById('ingredient-list');
    var counter = list.children.length;

    // Fonction pour ajouter un nouvel ingrédient
    function addIngredient() {
        var prototype = list.dataset.prototype;
        var newForm = prototype.replace(/__name__/g, counter);
        var div = document.createElement('div');
        div.classList.add('ingredient-item', 'card', 'mb-3', 'p-3', 'shadow-sm');
        div.innerHTML = newForm;

        // Ajouter un bouton "Supprimer"
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'mt-2');
        removeButton.innerHTML = '<i class="fa fa-trash"></i> Supprimer';
        removeButton.addEventListener('click', function() {
            div.remove(); // Supprimer l'élément ajouté
        });

        div.appendChild(removeButton); // Ajouter le bouton "Supprimer" au formulaire

        list.appendChild(div); // Ajouter le nouvel ingrédient à la liste
        counter++;
    }

    // Écouter le clic sur le bouton "Ajouter un ingrédient"
    document.getElementById('add-ingredient').addEventListener('click', function() {
        addIngredient();
    });

    // Activer les boutons "Supprimer" pour les ingrédients déjà présents dans le formulaire
    document.querySelectorAll('.ingredient-item').forEach(function(item) {
        var removeButton = item.querySelector('.remove-ingredient');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                item.remove();
            });
        }
    });
});
'''
src/Controller/HomeController.php->
<?php

namespace App\Controller;

use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PlanningRepository $planningRepository): Response
    {
        // Récupérer les repas des 8 prochains jours
        $date = new \DateTime();
        $plannings = $planningRepository->createQueryBuilder('p')
            ->where('p.date >= :today')
            ->setParameter('today', $date)
            ->orderBy('p.date', 'ASC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
        ]);
    }
}
'''
src/Controller/IngredientController.php->
<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
'''
src/Controller/ListeCoursesController.php->
<?php

namespace App\Controller;

use App\Repository\PlanningRepository;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\IngredientQuantiteType;

class ListeCoursesController extends AbstractController
{
    #[Route('/liste/courses', name: 'liste_courses_index')]
    public function index(SessionInterface $session): Response
    {
        $listeCourses = $session->get('liste_courses', []);
        return $this->render('liste_courses/index.html.twig', [
            'listeCourses' => $listeCourses,
        ]);
    }

    #[Route('/liste-courses/generer', name: 'liste_courses_generer', methods: ['GET'])]
    public function genererListeCourses(
        PlanningRepository $planningRepository, 
        IngredientRepository $ingredientRepository,
        SessionInterface $session
    ): Response {
        $planning = $planningRepository->findByWeek(new \DateTime());
        $listeCourses = $this->calculerListeCourses($planning, $ingredientRepository);
        
        $session->set('liste_courses', $listeCourses);

        return $this->redirectToRoute('liste_courses_index');
    }

    #[Route('/liste-courses/modifier', name: 'liste_courses_modifier', methods: ['GET', 'POST'])]
    public function modifierListeCourses(Request $request, SessionInterface $session): Response
    {
        $listeCourses = $session->get('liste_courses', []);

        $form = $this->createFormBuilder(['ingredients' => $listeCourses])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientQuantiteType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer les modifications'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $session->set('liste_courses', $data['ingredients']);

            $this->addFlash('success', 'La liste de courses a été mise à jour.');
            return $this->redirectToRoute('liste_courses_index');
        }

        return $this->render('liste_courses/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function calculerListeCourses($planning, $ingredientRepository)
    {
        $listeCourses = [];
        $typesRepas = ['petitDejeuner', 'encasMatin', 'dejeuner', 'encasApresMidi', 'diner'];

        foreach ($planning as $jour) {
            foreach ($typesRepas as $typeRepas) {
                $repas = $this->getRepasParType($jour, $typeRepas);
                if (!$repas) {
                    continue;
                }

                $nombrePersonnes = $this->getNombrePersonnesParType($jour, $typeRepas) ?: 1;

                foreach ($repas->getIngredients() as $ingredient) {
                    $quantiteDefaut = $ingredient->getQuantiteDefaut();
                    if ($quantiteDefaut === null) {
                        continue;
                    }

                    $quantiteNecessaire = floatval($quantiteDefaut) * $nombrePersonnes;
                    $nomIngredient = $ingredient->getNom();

                    if (!isset($listeCourses[$nomIngredient])) {
                        $listeCourses[$nomIngredient] = [
                            'quantite' => 0,
                            'unite' => $ingredient->getUnite() ?: ''
                        ];
                    }
                    $listeCourses[$nomIngredient]['quantite'] += $quantiteNecessaire;
                }
            }
        }

        foreach ($listeCourses as &$item) {
            $item['quantite'] = ceil($item['quantite']);
        }

        return $listeCourses;
    }

    private function getRepasParType($jour, $typeRepas)
    {
        switch ($typeRepas) {
            case 'petitDejeuner':
                return $jour->getPetitDejeuner();
            case 'encasMatin':
                return $jour->getEncasMatin();
            case 'dejeuner':
                return $jour->getDejeuner();
            case 'encasApresMidi':
                return $jour->getEncasApresMidi();
            case 'diner':
                return $jour->getDiner();
            default:
                return null;
        }
    }

    private function getNombrePersonnesParType($jour, $typeRepas)
    {
        switch ($typeRepas) {
            case 'petitDejeuner':
                return $jour->getNombrePersonnesPetitDejeuner() ?: 1;
            case 'encasMatin':
                return $jour->getNombrePersonnesEncasMatin() ?: 1;
            case 'dejeuner':
                return $jour->getNombrePersonnesDejeuner() ?: 1;
            case 'encasApresMidi':
                return $jour->getNombrePersonnesEncasApresMidi() ?: 1;
            case 'diner':
                return $jour->getNombrePersonnesDiner() ?: 1;
            default:
                return 1;
        }
    }
}
'''
src/Controller/PlanningController.php->
<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    #[Route('/', name: 'planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        $today = new \DateTime();
        $plannings = $planningRepository->findByWeek($today);

        // Organiser les plannings par date
        $planningsByDate = [];
        foreach ($plannings as $planning) {
            $dateKey = $planning->getDate()->format('Y-m-d');
            if (!isset($planningsByDate[$dateKey])) {
                $planningsByDate[$dateKey] = [
                    'date' => $planning->getDate(),
                    'petitDejeuner' => $planning->getPetitDejeuner(),
                    'nombrePersonnesPetitDejeuner' => $planning->getNombrePersonnesPetitDejeuner(),
                    'encasMatin' => $planning->getEncasMatin(),
                    'nombrePersonnesEncasMatin' => $planning->getNombrePersonnesEncasMatin(),
                    'dejeuner' => $planning->getDejeuner(),
                    'nombrePersonnesDejeuner' => $planning->getNombrePersonnesDejeuner(),
                    'encasApresMidi' => $planning->getEncasApresMidi(),
                    'nombrePersonnesEncasApresMidi' => $planning->getNombrePersonnesEncasApresMidi(),
                    'diner' => $planning->getDiner(),
                    'nombrePersonnesDiner' => $planning->getNombrePersonnesDiner()
                ];
            } else {
                // Mettre à jour les repas s'ils sont définis dans ce planning
                if ($planning->getPetitDejeuner()) {
                    $planningsByDate[$dateKey]['petitDejeuner'] = $planning->getPetitDejeuner();
                    $planningsByDate[$dateKey]['nombrePersonnesPetitDejeuner'] = $planning->getNombrePersonnesPetitDejeuner();
                }
                if ($planning->getEncasMatin()) {
                    $planningsByDate[$dateKey]['encasMatin'] = $planning->getEncasMatin();
                    $planningsByDate[$dateKey]['nombrePersonnesEncasMatin'] = $planning->getNombrePersonnesEncasMatin();
                }
                if ($planning->getDejeuner()) {
                    $planningsByDate[$dateKey]['dejeuner'] = $planning->getDejeuner();
                    $planningsByDate[$dateKey]['nombrePersonnesDejeuner'] = $planning->getNombrePersonnesDejeuner();
                }
                if ($planning->getEncasApresMidi()) {
                    $planningsByDate[$dateKey]['encasApresMidi'] = $planning->getEncasApresMidi();
                    $planningsByDate[$dateKey]['nombrePersonnesEncasApresMidi'] = $planning->getNombrePersonnesEncasApresMidi();
                }
                if ($planning->getDiner()) {
                    $planningsByDate[$dateKey]['diner'] = $planning->getDiner();
                    $planningsByDate[$dateKey]['nombrePersonnesDiner'] = $planning->getNombrePersonnesDiner();
                }
            }
        }

        return $this->render('planning/index.html.twig', [
            'planningsByDate' => $planningsByDate,
        ]);
    }

        #[Route('/new', name: 'planning_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $planning = new Planning();
            $form = $this->createForm(PlanningType::class, $planning);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($planning);
                $entityManager->flush();

                return $this->redirectToRoute('planning_index');
            }

            return $this->render('planning/new.html.twig', [
                'planning' => $planning,
                'form' => $form->createView(),
            ]);
        }

    #[Route('/{id}', name: 'planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{id}/edit', name: 'planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $entityManager->remove($planning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('planning_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/semaine', name: 'planning_semaine', methods: ['GET'])]
public function planingSemaine(PlanningRepository $planningRepository): Response
{
    // Logique pour récupérer le planning de la semaine
    $plannings = $planningRepository->findAll(); // À adapter selon vos besoins

    return $this->render('planning/semaine.html.twig', [
        'plannings' => $plannings,
    ]);
}
}
'''
src/Controller/RepasController.php->
<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Form\RepasType;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepasController extends AbstractController
{
    #[Route("/repas", name:"repas_index", methods:['GET'])]
    public function index(Request $request, RepasRepository $repasRepository): Response
    {
        $categories = ['low_carb', 'post_training', 'en_cas', 'autre'];
        
        // Récupérer les catégories sélectionnées depuis la requête
        $selectedCategories = $request->query->all('categories', []);
        
        // Si aucune catégorie n'est sélectionnée, utiliser toutes les catégories
        if (empty($selectedCategories)) {
            $selectedCategories = $categories;
        }

        // Assurez-vous que $selectedCategories est un tableau
        $selectedCategories = (array) $selectedCategories;

        $repas = $repasRepository->findByCategories($selectedCategories);

        return $this->render('repas/index.html.twig', [
            'repas' => $repas,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
        ]);
    }

    #[Route('/repas/new', name: 'repas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repas = new Repas();
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($repas->getIngredientQuantites() as $ingredientQuantite) {
                $repas->addIngredient($ingredientQuantite['ingredient']);
                // Ici, vous pouvez gérer la quantité si nécessaire
            }
            $entityManager->persist($repas);
            $entityManager->flush();

            return $this->redirectToRoute('repas_index');
        }

        return $this->render('repas/new.html.twig', [
            'repas' => $repas,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/repas/{id}/edit', name: 'repas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('repas_index');
        }

        return $this->render('repas/edit.html.twig', [
            'repas' => $repas,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/repas/{id}', name: 'repas_delete', methods: ['POST'])]
    public function delete(Request $request, Repas $repas, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repas->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repas);
            $entityManager->flush();
        }

        return $this->redirectToRoute('repas_index');
    }
    #[Route('/{id}', name: 'repas_show', methods: ['GET'])]
public function show(Repas $repas): Response
{
    return $this->render('repas/show.html.twig', [
        'repas' => $repas,
    ]);
}
}
'''
src/Entity/Ingredient.php->
<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quantiteDefaut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unite = null;

    /**
     * @var Collection<int, Repas>
     */
    #[ORM\ManyToMany(targetEntity: Repas::class, mappedBy: 'ingredient')]
    private Collection $repas;

    /**
     * @var Collection<int, ListeCourses>
     */
    #[ORM\OneToMany(targetEntity: ListeCourses::class, mappedBy: 'Ingredient')]
    private Collection $listeCourses;

    public function __construct()
    {
        $this->repas = new ArrayCollection();
        $this->listeCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantiteDefaut(): ?string
    {
        return $this->quantiteDefaut;
    }

    public function setQuantiteDefaut(?string $quantiteDefaut): static
    {
        $this->quantiteDefaut = $quantiteDefaut;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * @return Collection<int, Repas>
     */
    public function getRepas(): Collection
    {
        return $this->repas;
    }

    public function addRepa(Repas $repa): static
    {
        if (!$this->repas->contains($repa)) {
            $this->repas->add($repa);
            $repa->addIngredient($this);
        }

        return $this;
    }

    public function removeRepa(Repas $repa): static
    {
        if ($this->repas->removeElement($repa)) {
            $repa->removeIngredient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeCourses>
     */
    public function getListeCourses(): Collection
    {
        return $this->listeCourses;
    }

    public function addListeCourse(ListeCourses $listeCourse): static
    {
        if (!$this->listeCourses->contains($listeCourse)) {
            $this->listeCourses->add($listeCourse);
            $listeCourse->setIngredient($this);
        }

        return $this;
    }

    public function removeListeCourse(ListeCourses $listeCourse): static
    {
        if ($this->listeCourses->removeElement($listeCourse)) {
            // set the owning side to null (unless already changed)
            if ($listeCourse->getIngredient() === $this) {
                $listeCourse->setIngredient(null);
            }
        }

        return $this;
    }
}
'''
src/Entity/ListeCourses.php->
<?php

namespace App\Entity;

use App\Repository\ListeCoursesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeCoursesRepository::class)]
class ListeCourses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enStock = null;

    #[ORM\ManyToOne(inversedBy: 'listeCourses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $Ingredient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isEnStock(): ?bool
    {
        return $this->enStock;
    }

    public function setEnStock(?bool $enStock): static
    {
        $this->enStock = $enStock;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->Ingredient;
    }

    public function setIngredient(?Ingredient $Ingredient): static
    {
        $this->Ingredient = $Ingredient;

        return $this;
    }
}
'''
src/Entity/Planning.php->
<?php
namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $petitDejeuner = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesPetitDejeuner = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $encasMatin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesEncasMatin = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $dejeuner = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesDejeuner = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $encasApresMidi = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesEncasApresMidi = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $diner = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesDiner = null;

    // Getters et setters pour chaque propriété

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPetitDejeuner(): ?Repas
    {
        return $this->petitDejeuner;
    }

    public function setPetitDejeuner(?Repas $petitDejeuner): self
    {
        $this->petitDejeuner = $petitDejeuner;

        return $this;
    }

    public function getEncasMatin(): ?Repas
    {
        return $this->encasMatin;
    }

    public function setEncasMatin(?Repas $encasMatin): self
    {
        $this->encasMatin = $encasMatin;

        return $this;
    }

    public function getDejeuner(): ?Repas
    {
        return $this->dejeuner;
    }

    public function setDejeuner(?Repas $dejeuner): self
    {
        $this->dejeuner = $dejeuner;

        return $this;
    }

    public function getEncasApresMidi(): ?Repas
    {
        return $this->encasApresMidi;
    }

    public function setEncasApresMidi(?Repas $encasApresMidi): self
    {
        $this->encasApresMidi = $encasApresMidi;

        return $this;
    }

    public function getDiner(): ?Repas
    {
        return $this->diner;
    }

    public function setDiner(?Repas $diner): self
    {
        $this->diner = $diner;

        return $this;
    }

    /**
     * Get the value of nombrePersonnes
    //  */ 
    // public function getNombrePersonnes()
    // {
    //     return $this->nombrePersonnes;
    // }

    // /**
    //  * Set the value of nombrePersonnes
    //  *
    //  * @return  self
    //  */ 
    // public function setNombrePersonnes($nombrePersonnes)
    // {
    //     $this->nombrePersonnes = $nombrePersonnes;

    //     return $this;
    // }

    /**
     * Get the value of nombrePersonnesPetitDejeuner
     */ 
    public function getNombrePersonnesPetitDejeuner()
    {
        return $this->nombrePersonnesPetitDejeuner;
    }

    /**
     * Set the value of nombrePersonnesPetitDejeuner
     *
     * @return  self
     */ 
    public function setNombrePersonnesPetitDejeuner($nombrePersonnesPetitDejeuner)
    {
        $this->nombrePersonnesPetitDejeuner = $nombrePersonnesPetitDejeuner;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesEncasMatin
     */ 
    public function getNombrePersonnesEncasMatin()
    {
        return $this->nombrePersonnesEncasMatin;
    }

    /**
     * Set the value of nombrePersonnesEncasMatin
     *
     * @return  self
     */ 
    public function setNombrePersonnesEncasMatin($nombrePersonnesEncasMatin)
    {
        $this->nombrePersonnesEncasMatin = $nombrePersonnesEncasMatin;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesDejeuner
     */ 
    public function getNombrePersonnesDejeuner()
    {
        return $this->nombrePersonnesDejeuner;
    }

    /**
     * Set the value of nombrePersonnesDejeuner
     *
     * @return  self
     */ 
    public function setNombrePersonnesDejeuner($nombrePersonnesDejeuner)
    {
        $this->nombrePersonnesDejeuner = $nombrePersonnesDejeuner;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesEncasApresMidi
     */ 
    public function getNombrePersonnesEncasApresMidi()
    {
        return $this->nombrePersonnesEncasApresMidi;
    }

    /**
     * Set the value of nombrePersonnesEncasApresMidi
     *
     * @return  self
     */ 
    public function setNombrePersonnesEncasApresMidi($nombrePersonnesEncasApresMidi)
    {
        $this->nombrePersonnesEncasApresMidi = $nombrePersonnesEncasApresMidi;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesDiner
     */ 
    public function getNombrePersonnesDiner()
    {
        return $this->nombrePersonnesDiner;
    }

    /**
     * Set the value of nombrePersonnesDiner
     *
     * @return  self
     */ 
    public function setNombrePersonnesDiner($nombrePersonnesDiner)
    {
        $this->nombrePersonnesDiner = $nombrePersonnesDiner;

        return $this;
    }
}
'''
src/Entity/Repas.php->
<?php

namespace App\Entity;

use App\Repository\RepasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: RepasRepository::class)]
class Repas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $recette = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'repas')]
    private Collection $ingredients;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeRepas = null;

    private $ingredientQuantites;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->ingredientQuantites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getRecette(): ?string
    {
        return $this->recette;
    }

    public function setRecette(?string $recette): self
    {
        $this->recette = $recette;
        return $this;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }
        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getTypeRepas(): ?string
    {
        return $this->typeRepas;
    }

    public function setTypeRepas(?string $typeRepas): self
    {
        $this->typeRepas = $typeRepas;
        return $this;
    }

    public function getIngredientQuantites()
    {
        return $this->ingredientQuantites;
    }

    public function addIngredientQuantite($ingredientQuantite): self
    {
        $this->ingredientQuantites[] = $ingredientQuantite;
        return $this;
    }

    public function removeIngredientQuantite($ingredientQuantite): self
    {
        $this->ingredientQuantites->removeElement($ingredientQuantite);
        return $this;
    }
}
'''
src/Form/IngredientQuantiteType.php->
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class IngredientQuantiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Ingrédient',
                'disabled' => true,
            ])
            ->add('quantite', NumberType::class, [
                'label' => 'Quantité',
            ])
            ->add('unite', TextType::class, [
                'label' => 'Unité',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
'''
src/Form/IngredientType.php->
<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'ingrédient',
            ])
            ->add('quantiteDefaut', TextType::class, [
                'label' => 'Quantité par défaut',
                'required' => false,
            ])
            ->add('unite', TextType::class, [
                'label' => 'Unité',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
'''
src/Form/ListeCoursesType.php->
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeCoursesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('field_name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
'''
src/Form/PlanningType.php->
<?php

namespace App\Form;

use App\Entity\Planning;
use App\Entity\Repas;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('petitDejeuner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesPetitDejeuner', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('encasMatin', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesEncasMatin', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('dejeuner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesDejeuner', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('encasApresMidi', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesEncasApresMidi', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ])
            ->add('diner', EntityType::class, [
                'class' => Repas::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('nombrePersonnesDiner', IntegerType::class, [
                'label' => 'Nb personnes',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
'''
src/Form/RepasType.php->
<?php

namespace App\Form;

use App\Entity\Repas;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Low-carb' => 'low_carb',
                    'Post-training' => 'post_training',
                    'En-cas' => 'en_cas',
                    'Autre' => 'autre',
                ],
                'label' => 'Type de recettes',
                'required' => true,
            ])
            ->add('description')
            ->add('recette')
            ->add('ingredientQuantites', CollectionType::class, [
                'entry_type' => IngredientQuantiteType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repas::class,
        ]);
    }
}
'''
src/Repository/IngredientRepository.php-><?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

//    /**
//     * @return Ingredient[] Returns an array of Ingredient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ingredient
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
'''
src/Repository/ListeCoursesRepository.php->
<?php

namespace App\Repository;

use App\Entity\ListeCourses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeCourses>
 */
class ListeCoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeCourses::class);
    }

//    /**
//     * @return ListeCourses[] Returns an array of ListeCourses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ListeCourses
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
'''
src/Repository/PlanningRepository.php->
<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function findByWeek(\DateTime $date): array
    {
        $startOfWeek = (clone $date)->modify('monday this week');
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');

        $result = $this->createQueryBuilder('p')
            ->andWhere('p.date BETWEEN :start AND :end')
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();


        return $result;
    }
}
'''
src/Repository/RepasRepository.php->
<?php

namespace App\Repository;

use App\Entity\Repas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Repas>
 */
class RepasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repas::class);
    }

    public function findByCategories(array $categories): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.categorie IN (:categories)')
            ->setParameter('categories', $categories)
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Repas[] Returns an array of Repas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Repas
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
'''

