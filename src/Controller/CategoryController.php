<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        $reparateurs = $category->getReparateurs();

        // Calculer le nombre d'objets dans cette catégorie
        $nombreObjets = $category->getObjets()->count();

        // Calculer le nombre d'objets réparés dans la catégorie
        $objetsRepares = 0;
        foreach ($category->getObjets() as $objet) {
            if ($objet->isEstFonctionnel()) {
                $objetsRepares++;
            }
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'reparateurs' => $reparateurs,
            'nombreObjets' => $nombreObjets,
            'objetsRepares' => $objetsRepares,
        ]);
    }
}

