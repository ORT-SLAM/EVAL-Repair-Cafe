<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Repository\ObjetRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/objet')]
class ObjetController extends AbstractController
{
    #[Route('s', name: 'app_objet_index', methods: ['GET'])]
    public function index(
        Request $request,
        ObjetRepository $objetRepository,
        CategoryRepository $categoryRepository
    ): Response {
        // Récupérer le filtre de catégorie
        $categorieId = $request->query->get('categorie');
        
        if ($categorieId) {
            $categorie = $categoryRepository->find($categorieId);
            $objets = $categorie ? $objetRepository->findByCategorie($categorie) : [];
        } else {
            $objets = $objetRepository->findAll();
        }
        
        // Toutes les catégories pour les filtres
        $categories = $categoryRepository->findAll();

        return $this->render('objet/index.html.twig', [
            'objets' => $objets,
            'categories' => $categories,
            'categorieSelectionnee' => $categorieId,
        ]);
    }

    #[Route('/{id}', name: 'app_objet_show', methods: ['GET'])]
    public function show(Objet $objet): Response
    {
        return $this->render('objet/show.html.twig', [
            'objet' => $objet,
        ]);
    }

    #[Route('/create/faker', name: 'app_objet_create', methods: ['GET'])]
    public function create(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ): Response {
        $faker = Factory::create('fr_FR');
        
        // Créer un objet avec Faker
        $objet = new Objet();
        
        $typesObjets = [
            'Machine à café', 'Grille-pain', 'Aspirateur', 'Téléphone portable',
            'Ordinateur portable', 'Télévision', 'Radio', 'Imprimante'
        ];
        
        $pannes = [
            'Ne s\'allume plus', 'Fait du bruit anormal', 'Ne chauffe plus',
            'Écran fissuré', 'Batterie ne charge plus', 'Câble endommagé'
        ];
        
        $categories = $categoryRepository->findAll();
        
        $objet->setTitre($faker->randomElement($typesObjets) . ' ' . $faker->word())
            ->setDescriptionPanne($faker->randomElement($pannes) . '. ' . $faker->sentence())
            ->setNomProprietaire($faker->name())
            ->setEmailProprietaire($faker->email())
            ->setDateDepot((new \DateTime())->format('Y-m-d'))
            ->setEstimationCoutReparation($faker->boolean(70) ? (string) $faker->randomFloat(2, 10, 150) : null)
            ->setEstFonctionnel($faker->boolean(30))
            ->setPhoto($faker->boolean(40) ? 'photo_' . $faker->numberBetween(1, 100) . '.jpg' : null)
            ->setCategorie($faker->randomElement($categories));
        
        $entityManager->persist($objet);
        $entityManager->flush();
        
        $this->addFlash('success', 'Objet créé avec succès !');
        
        return $this->redirectToRoute('app_objet_show', ['id' => $objet->getId()]);
    }
}

