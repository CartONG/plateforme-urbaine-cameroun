# **Introduction à Symfony et API Platform**

**Objectif** : Comprendre l’architecture de Symfony et voir où API Platform intervient pour automatiser la création d’APIs.

---

## **1. Symfony : La base du framework**

### 📌 **Architecture globale**
Symfony suit le **modèle MVC (Modèle - Vue - Contrôleur)** :  
- **Modèle (Entity/Repository)** : Gère les données et la base (Doctrine).  
- **Vue (Twig/Vue.js/React)** : Affiche les données (pas couvert ici).  
- **Contrôleur** : Contient la logique métier et gère les requêtes HTTP.  

💡 **Symfony est basé sur des composants** (Routing, Security, Console...) qui sont indépendants et réutilisables.  

---

### ⚡ **Les routes et contrôleurs**

#### **1️⃣ Déclaration des routes**
Symfony utilise le **Routing Component** pour mapper une URL à un contrôleur.  

- Déclaration via **attributs PHP** :  
```php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/books', name: 'book_list', methods: ['GET'])]
    public function list(): Response
    {
        return new Response('Liste des livres');
    }
}
```
💡 Symfony détecte automatiquement les routes dans les **contrôleurs**.  

- Déclaration via **YAML** (`config/routes.yaml`) :  
```yaml
book_list:
  path: /books
  controller: App\Controller\BookController::list
  methods: GET
```

---

#### **2️⃣ Gestion des contrôleurs**
Un **contrôleur Symfony** est une classe qui retourne une **Response** en fonction d’une requête.  

Exemple de **contrôleur renvoyant une page HTML** :  
```php
#[Route('/book/{id}', name: 'book_detail')]
public function detail(int $id): Response
{
    return $this->render('book/detail.html.twig', ['id' => $id]);
}
```

Exemple de **contrôleur renvoyant du JSON** :  
```php
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/books', name: 'api_books')]
public function apiBooks(): JsonResponse
{
    return $this->json(['books' => ['Symfony pour les nuls', 'API Platform en action']]);
}
```

---

### 📐 **Doctrine : Le lien entre base de données et Symfony**
- **Entité** : Représente une table SQL.  
- **Repository** : Permet de récupérer des données.  

Exemple d’**entité Doctrine** :  
```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Book {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    public string $title;
}
```

---

## **2. Où API Platform surcharge Symfony ?**  

API Platform s’appuie **entierement sur Symfony** et remplace certaines parties du framework pour simplifier la création d’APIs.  

💪 **Routes et contrôleurs générés automatiquement**  
API Platform génère automatiquement une API REST et GraphQL à partir d’une simple **entité Doctrine**.  

💡 **Avant API Platform : Routes et contrôleurs à la main**  
```php
#[Route('/api/books', name: 'api_books')]
public function list(EntityManagerInterface $em): JsonResponse
{
    $books = $em->getRepository(Book::class)->findAll();
    return $this->json($books);
}
```

💡 **Avec API Platform : Plus besoin de contrôleur !**  
```php
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity]
class Book {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    public string $title;
}
```
🚀 API Platform génère :  
- **GET /api/books** → Liste des livres.  
- **POST /api/books** → Créer un livre.  
- **PUT /api/books/{id}** → Modifier un livre.  
- **DELETE /api/books/{id}** → Supprimer un livre.  

💪 **Système d’autorisations intégré**  
API Platform utilise **Symfony Security** pour contrôler l’accès aux données.  

Exemple : Bloquer la suppression aux non-admins  
```php
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

#[Delete(security: "is_granted('ROLE_ADMIN')")]
```

💪 **Validation automatique des données**  
```php
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class Book {
    #[Assert\NotBlank]
    public string $title;
}
```
💡 **Sans API Platform, il faudrait valider les données manuellement dans un contrôleur !**  

---

## **3. Conclusion**  
🎯 **Symfony** = Framework PHP puissant basé sur MVC avec des contrôleurs, routes et Doctrine.  
🚀 **API Platform** = Extends Symfony en **remplaçant les routes et contrôleurs manuels** pour une génération automatique d’APIs.  

🔥 **Avec API Platform, on peut créer une API REST complète en quelques lignes !**  

💡 **Questions ? Démo live ?**

