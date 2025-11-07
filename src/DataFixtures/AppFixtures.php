<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Objet;
use App\Entity\Reparateur;
use App\Entity\Reparation;
use App\Enum\NiveauExperience;
use App\Enum\StatutReparation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Chargement des données dans l'ordre des dépendances
        $categories = $this->loadCategories($manager);
        $reparateurs = $this->loadReparateurs($manager, $categories);
        $objets = $this->loadObjets($manager, $categories);
        $this->loadReparations($manager, $objets, $reparateurs);

        $manager->flush();
    }

    /**
     * Charge les catégories dans la base de données
     * @return Category[]
     */
    private function loadCategories(ObjectManager $manager): array
    {
        $categories = [];

        // Catégories prédéfinies pour un repair café
        $categoriesData = [
            [
                'nom' => 'Électronique',
                'couleur' => '#3498db',
                'icone' => 'bi-lightning-charge',
                'description' => 'Appareils électroniques, smartphones, tablettes et ordinateurs'
            ],
            [
                'nom' => 'Électroménager',
                'couleur' => '#e74c3c',
                'icone' => 'bi-plug',
                'description' => 'Petit et gros électroménager, aspirateurs, cafetières'
            ],
            [
                'nom' => 'Audio/Vidéo',
                'couleur' => '#9b59b6',
                'icone' => 'bi-music-note-beamed',
                'description' => 'Chaînes hi-fi, télévisions, lecteurs DVD'
            ],
            [
                'nom' => 'Informatique',
                'couleur' => '#1abc9c',
                'icone' => 'bi-laptop',
                'description' => 'Ordinateurs, imprimantes, périphériques'
            ],
            [
                'nom' => 'Textile',
                'couleur' => '#f39c12',
                'icone' => 'bi-scissors',
                'description' => 'Vêtements, tissus, machines à coudre'
            ],
            [
                'nom' => 'Mécanique',
                'couleur' => '#34495e',
                'icone' => 'bi-gear',
                'description' => 'Vélos, jouets mécaniques, outillage'
            ],
            [
                'nom' => 'Ameublement',
                'couleur' => '#95a5a6',
                'icone' => 'bi-chair',
                'description' => 'Meubles, lampes, décoration'
            ],
            [
                'nom' => 'Jouets',
                'couleur' => '#e67e22',
                'icone' => 'bi-puzzle',
                'description' => 'Jouets électriques ou mécaniques'
            ]
        ];

        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->setNom($data['nom'])
                ->setCouleur($data['couleur'])
                ->setIcone($data['icone'])
                ->setDescription($data['description']);

            $manager->persist($category);
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * Charge les réparateurs dans la base de données
     * @param Category[] $categories
     * @return Reparateur[]
     */
    private function loadReparateurs(ObjectManager $manager, array $categories): array
    {

        $specialiteNames = [
            'Électronique',
            'Électroménager',
            'Audio/Vidéo',
            'Informatique',
            'Textile',
            'Mécanique',
            'Ameublement',
            'Jouets'
        ];

        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->getNom()] = $category;
        }

        $reparateurs = [];
        $niveauxExperience = NiveauExperience::cases();

        for ($i = 0; $i < 15; $i++) {
            $reparateur = new Reparateur();

            // Générer un numéro au format français 06-12-34-56-78
            $telephone = $this->faker->boolean(80) ? sprintf(
                '0%d-%02d-%02d-%02d-%02d',
                $this->faker->numberBetween(6, 7),
                $this->faker->numberBetween(0, 99),
                $this->faker->numberBetween(0, 99),
                $this->faker->numberBetween(0, 99),
                $this->faker->numberBetween(0, 99)
            ) : null;

            // Attribuer des spécialités aléatoires (1 à 3max)
            $reparateur->setNom($this->faker->name())
                ->setEmail($this->faker->unique()->email())
                ->setTelephone($telephone)
                ->setDateInscription(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-2 years', 'now')))
                ->setNiveauExperience($this->faker->randomElement($niveauxExperience))
                ->setPresentation($this->faker->boolean(70) ? $this->faker->paragraph(3) : null)
                ->setEstActif($this->faker->boolean(85)); // 85% de chances d'être actif

            for ($j = 0; $j < $this->faker->numberBetween(1, 3); $j++) {
                $categoryName = $this->faker->randomElement($specialiteNames);
                $reparateur->addSpecialite($categoryMap[$categoryName]);
            }

            $manager->persist($reparateur);
            $reparateurs[] = $reparateur;
        }

        return $reparateurs;
    }

    /**
     * Charge les objets dans la base de données
     * @param Category[] $categories
     * @return Objet[]
     */
    private function loadObjets(ObjectManager $manager, array $categories): array
    {
        $objets = [];

        // Types d'objets réalistes pour un repair café
        $typesObjets = [
            'Machine à café',
            'Grille-pain',
            'Aspirateur',
            'Téléphone portable',
            'Ordinateur portable',
            'Tablette',
            'Télévision',
            'Radio',
            'Chaîne hi-fi',
            'Imprimante',
            'Vélo',
            'Lampe',
            'Sèche-cheveux',
            'Fer à repasser',
            'Mixeur',
            'Robot de cuisine',
            'Console de jeux',
            'Jouet électronique',
            'Lecteur DVD',
            'Enceinte bluetooth'
        ];

        $pannesTypes = [
            'Ne s\'allume plus',
            'Fait du bruit anormal',
            'Ne chauffe plus',
            'Fuite',
            'Bouton cassé',
            'Écran fissuré',
            'Batterie ne charge plus',
            'Court-circuit',
            'Câble endommagé',
            'Ne fonctionne qu\'intermittent',
            'Perte de puissance',
            'Odeur de brûlé',
            'Vibrations anormales',
            'Ne s\'éteint plus',
            'Problème de connectivité'
        ];

        for ($i = 0; $i < 50; $i++) {
            $objet = new Objet();
            $typeObjet = $this->faker->randomElement($typesObjets);

            $objet->setTitre($typeObjet . ' ' . $this->faker->word())
                ->setDescriptionPanne($this->faker->randomElement($pannesTypes) . '. ' . $this->faker->sentence())
                ->setNomProprietaire($this->faker->name())
                ->setEmailProprietaire($this->faker->email())
                ->setDateDepot($this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'))
                ->setEstimationCoutReparation($this->faker->boolean(70) ? (string) $this->faker->randomFloat(2, 0, 150) : null)
                ->setEstFonctionnel($this->faker->boolean(30)) // 30% de chance d'être encore fonctionnel
                ->setPhoto($this->faker->boolean(40) ? 'photo_' . $this->faker->numberBetween(1, 100) . '.jpg' : null)
                ->setCategorie($this->faker->randomElement($categories));

            $manager->persist($objet);
            $objets[] = $objet;
        }

        return $objets;
    }

    /**
     * Charge les réparations dans la base de données
     * @param Objet[] $objets
     * @param Reparateur[] $reparateurs
     */
    private function loadReparations(ObjectManager $manager, array $objets, array $reparateurs): void
    {
        $statuts = StatutReparation::cases();

        $pieces = [
            'Résistance',
            'Condensateur',
            'Fusible',
            'Câble d\'alimentation',
            'Bouton marche/arrêt',
            'Carte mère',
            'Batterie',
            'Écran',
            'Connecteur',
            'Moteur',
            'Courroie',
            'Joint',
            'Vis et boulons',
            'Circuit imprimé',
            'Capteur'
        ];

        // Créer 1 à 3 réparations par objet (aléatoire)
        foreach ($objets as $objet) {
            $nbReparations = $this->faker->numberBetween(1, 3);

            for ($i = 0; $i < $nbReparations; $i++) {
                $reparation = new Reparation();

                $dateDebut = \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-2 months', 'now')
                );

                // Calculer la date de fin (entre 30 min et 5 heures après le début)
                $dureeMinutes = $this->faker->numberBetween(30, 300);
                $dateFin = $dateDebut->modify('+' . $dureeMinutes . ' minutes');

                $statut = $this->faker->randomElement($statuts);

                // Date de fin seulement si réparée ou irréparable
                $hasDateFin = in_array($statut, [StatutReparation::REPAREE, StatutReparation::IRREPARABLE]);

                $reparation->setDateDebut($dateDebut)
                    ->setDateFin($hasDateFin ? $dateFin : null)
                    ->setStatut($statut)
                    ->setCommentaire(in_array($statut, [StatutReparation::REPAREE, StatutReparation::IRREPARABLE])
                        ? $this->faker->paragraph()
                        : ($this->faker->boolean(60) ? $this->faker->sentence() : null)
                    )
                    ->setTempsPasseMinutes($statut === StatutReparation::REPAREE ? $dureeMinutes : null)
                    ->setPiecesUtilisees($statut === StatutReparation::REPAREE && $this->faker->boolean(70)
                        ? implode(', ', $this->faker->randomElements($pieces, $this->faker->numberBetween(1, 3)))
                        : null
                    )
                    ->setObjet($objet)
                    ->setReparateur($this->faker->randomElement($reparateurs));

                $manager->persist($reparation);
            }
        }
    }
}
