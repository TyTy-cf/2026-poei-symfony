
# 2026 POEI Symfony


## Sommaire

- [Installation](#installation)
- [Helper](#helper)
- [Exo](#exo)
    - [1. Création d'entités](#1-création-dentités)
        - [Vue d'ensemble des tables](#vue-densemble-des-tables)
        - [Entité `Category`](#entité-category)
        - [Entité `Country`](#entité-country)
        - [Entité `Publisher`](#entité-publisher)
        - [Entité `Game`](#entité-game)
        - [Entité `User`](#entité-user)
        - [Entité `Review`](#entité-review)
    - [2. Faire la home de SteamIsh](#2-faire-la-home-de-steamish)
    - [3. Modifier les requêtes de la home](#3-modifier-les-requêtes-de-la-home)
    - [4. Faire la page de détail d'un jeu](#4-faire-la-page-de-détail-dun-jeu)
    - [5. Faire la page de détail d'un user](#5-faire-la-page-de-détail-dun-user)
    - [6. Ajouter des liens](#6-ajouter-des-liens)
    - [7. Le temps de jeu total](#7-le-temps-de-jeu-total)
    - [8. Faire un footer](#8-faire-un-footer)
    - [9. Translations](#9-translations)
    - [10. Prévoir un message flash](#10-prévoir-un-message-flash)
    - [11. Faire la page d'une catégorie](#11-faire-la-page-dune-catégorie)
    - [12. Faire la page d'un publisher](#12-faire-la-page-dun-publisher)
    - [13. Faire un formulaire pour les Category](#13-faire-un-formulaire-pour-les-category)
    - [14. Faire un AdminController`](#14-faire-un-admin-controller)


## Installation

Commencez par vous créer une branche, puis :

Allez dans le fichier `docker/Dockerfile`, modifier les lignes :

```
ARG USER_ID=1001
ARG GROUP_ID=1001
```

Par votre id (1000)


Lancer la commande `make init`

## Helper

- URL Site : https://localhost:8443/
- PhpMyAdmin : http://localhost:8080/


## Exo


### 1. Création d'entités

#### Vue d'ensemble des tables

| Table | Rôle |
|---|---|
| `category` | Catégorie de jeu (genre) |
| `country` | Pays (nationalité, drapeau) |
| `publisher` | Éditeur de jeu |
| `game` | Jeu vidéo |
| `game_category` | Table de liaison Many-to-Many entre `game` et `category` |
| `game_country` | Table de liaison Many-to-Many entre `game` et `country` |
| `user` | Utilisateur de la plateforme |
| `review` | Avis/critique laissé par un utilisateur sur un jeu |

---

#### Entité `Category`

Représente un genre de jeu (Stratégie, FPS, RPG, etc.).

| Champ | Type Doctrine | Contrainte |
|---|---|---|
| id | integer | PK, auto-increment |
| name | string(255) | not null |
| image | string(255) | nullable |
| slug | string(255) | unique, not null |

**Relations**
- `ManyToMany` vers `Game`

---

#### Entité `Country`

Représente un pays, utilisé pour la nationalité des éditeurs, la disponibilité des jeux, et le pays des utilisateurs.

| Champ | Type Doctrine | Contrainte |
|---|---|---|
| id | integer | PK, auto-increment |
| code | string(5) | not null (code ISO ex: fr, us) |
| name | string(255) | not null |
| nationality | string(255) | not null |
| slug | string(255) | unique, not null |
| urlFlag | string(255) | nullable |

**Relations**
- `OneToMany` vers `Publisher` (un pays peut avoir plusieurs éditeurs).
- `OneToMany` vers `User` (un pays peut avoir plusieurs utilisateurs).
- `ManyToMany` vers `Game.

---

#### Entité `Publisher`

Représente un éditeur/studio de jeu.

| Champ | Type Doctrine | Contrainte |
|---|---|---|
| id | integer | PK, auto-increment |
| country | ManyToOne(Country) | nullable |
| createdAt | datetime | not null |
| name | string(255) | not null |
| slug | string(255) | unique, not null |
| website | string(255) | nullable |

**Relations**
- `ManyToOne` vers `Country`.
- `OneToMany` vers `Game` (un éditeur publie plusieurs jeux).

---

#### Entité `Game`

Représente un jeu vidéo.

| Champ | Type Doctrine | Contrainte |
|---|---|---|
| id | integer | PK, auto-increment |
| publisher | ManyToOne(Publisher) | nullable |
| name | string(255) | not null |
| price | integer ou decimal | not null |
| description | text | nullable |
| publishedAt | datetime | not null |
| thumbnailCover | string(255) | nullable (chemin image ou base64) |
| slug | string(255) | unique, not null |
| thumbnailCoverLink | string(255) | nullable |

**Relations**
- `ManyToOne` vers `Publisher`.
- `ManyToMany` vers `Category`.
- `ManyToMany` vers `Country`.
- `OneToMany` vers `Review` (un jeu a plusieurs avis).

---

#### Entité `User`

Représente un utilisateur de la plateforme.

| Champ | Type Doctrine | Contrainte |
|---|---|---|
| id | integer | PK, auto-increment |
| country | ManyToOne(Country) | nullable |
| email | string(255) | unique, not null |
| roles | json ou array | not null |
| password | string(255) | not null |
| createdAt | datetime | not null |
| name | string(255) | not null |
| nickname | string(255) | not null |
| profileImage | string(255) | nullable |
| wallet | integer | not null, default 0 |

**Relations**
- `ManyToOne` vers `Country`.
- `OneToMany` vers `Review` (un utilisateur poste plusieurs avis).

---

#### Entité `Review`

Représente un avis/critique laissé par un utilisateur sur un jeu.

| Champ | Type Doctrine | Contrainte |
|---|---|---|
| id | integer | PK, auto-increment |
| user | ManyToOne(User) | not null |
| game | ManyToOne(Game) | not null |
| content | text | not null |
| createdAt | datetime | not null |
| downvote | integer | not null, default 0 |
| upvote | integer | not null, default 0 |
| rating | integer/smallint | not null (échelle observée 0 à 5 ) |

**Relations**
- `ManyToOne` vers `User`.
- `ManyToOne` vers `Game`.


### 2. Faire la home de SteamIsh


**Fichiers impactés : ** `HomeController` & `front/home/index.html.twig` 


Vous devez créer les blocs suivants sur la page Twig :
- H2 : "Les tendances" ; on affichera ici les 9 derniers jeux sortis
- H2 : "Les meilleurs sorties" ; on affichera ici les 9 derniers par prix décroissants
- H2 : "Ils nous font confiance" ; on affichera les 5 derniers commentaires dont le `rating` est 5 (Cette partie n'est pas dans une div ayant la classe container)
- H2 : "Les tops jeux" ; on affichera 6 jeux triés par nom décroisants
- H2 : "Catégories" ; on affichera 9 catégories triés par ordre alphabétique


Vous essairai de faire un CSS convenable... inspirez vous d'Instant-Gaming : https://www.instant-gaming.com/fr


Pour les jeux vous afficherez :
- `name`
- `price`
- `thumbnailCover`


Pour les commentaires vous afficherez :
- `rating`
- `content` (si vous vous en sentez => tronquer à 50 caractères, utiliser `|slice`)
- `user.profileImage`
- `game.name`
- `createdAt` ("Le xx/xx/xx")


Pour les catégories vous afficherez :
- `name`
- `image`


### 3. Modifier les requêtes de la home


- "trends" (Les tendances) : les 9 jeux les plus joués (Query custom !)
- "bests" (Les meilleures sorties) : doit être les 9 derniers jeux sortis
- "tops" (Les tops jeux) : les 6 jeux avec le meilleur rating 


### 4. Faire la page de détail d'un jeu


- Créer le Controller : `Game`
- Créer une fonction `show` dans celui-ci, dont la route sera : `/game/{slug}`
- Optimiser la requête de récupération d'un jeu...
- La fonction doit afficher le détail d'un jeu dont le slug est passé en paramètre
- Inspirez vous de la page de détail d'Instant-Gaming : https://www.instant-gaming.com/fr/22977-acheter-halo-campaign-evolved-premium-edition-xbox-series-x-s-pc-microsoft-store/
- Faites en sorte que lorsque l'on clique sur une card d'un jeu depuis la home, on soit redirigé sur ce controller
- Pareil depuis le nom d'un jeu depuis un commentaire


### 5. Faire la page de détail d'un user


- Créer le Controller : `User`
- Créer une fonction `show` dans celui-ci, dont la route sera : `/user/{name}`
- Optimiser la requête de récupération d'un user...
- La fonction doit afficher le détail d'un user dont le name est passé en paramètre
- On affichera : 
  - Le nickname du user
  - "Inscrit le xx/xx/xx"
  - La liste de ses jeux possédés **avec leur temps de jeu** (seulement par jeu ici)
  - La liste de ses commentaires postés
  - **En réutilisant des traitements déjà réalisés... calculer et afficher le temps de jeu total de l'utilisateur au format "hh:mm"**


### 6. Ajouter des liens


- Ajouter un lien vers la page d'un jeu depuis la card `review`
- Ajouter un lien vers la page d'un user depuis la card `review`


### 7. Le temps de jeu total


- Voir exercice 5, ajouter les comportements en gras


### 8. Faire un footer


- Je veux que dans mon footer on affiche une liste des 5 catégories de jeu les plus jouées (trié par temps de jeu total)
- Afficher simplement les noms des 5 jeux les plus commentés (avec un lien vers la page de show de celu-ci)
- Je pense que vous rencontrerez rapidement un problème... Il y a une solution qui a été évoquée dans le cours de ce matin ?
- Un minima de CSS ?


### 9. Translations


Bien sûr on utilisera dorénavant que les chaînes de traductions dans le site


### 10. Prévoir un message flash


- Sur la page `show` d'un user, ajouter un message `flash` si celui-ci n'existe pas, rediriger l'utilisateur sur la home


### 11. Faire la page d'une catégorie


- Créer le Controller : `Category`
- Créer une fonction `show` dans celui-ci, dont la route sera : `/category/{slug}`
- Optimiser la requête de récupération d'une catégorie, **si nécessaire**
- La fonction doit afficher les jeux ayant cette catégorie


### 12. Faire la page d'un publisher


- Créer le Controller : `Publisher`
- Créer une fonction `show` dans celui-ci, dont la route sera : `/publisher/{slug}`
- Optimiser la requête de récupération d'un éditeur de jeu, **si nécessaire**
- La fonction doit afficher les informations de l'éditeur, ainsi que ses jeux créés


### 13. Faire un formulaire pour les Category


- Faire un formulaire qui gère l'ajout d'une Category (ne faites pas de lien dans le header pour le moment, on y accède par l'URL)
- Faire un formulaire qui gère la modification d'une Category (ne faites pas de lien dans le header pour le moment, on y accède par l'URL)
- Ajouter les règles de validations dans l'entité
- Le slug ne doit pas apparaître dans le formulaire !

Utilisez cette fonction (dans un service !) pour générer le slug de la catégorie à partir de son nom : 

```php

    public function slugify(string $text): string
    {
        $replace = [
            '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
            '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä'=> 'Ae',
            '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
            'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
            'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
            'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
            'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
            'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
            'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
            'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
            'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
            'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
            'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
            'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
            'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
            '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
            'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
            'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
            'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
            'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
            'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
            'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
            'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
            'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
            'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
            'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
            'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
            '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
            'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
            'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
            'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
            'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
            'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
            'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
            'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
            'ю' => 'yu', 'я' => 'ya'
        ];

        // make a human readable string
        $text = strtr($text, $replace);

        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d.]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // remove unwanted characters
        $text = preg_replace('~[^-\w.]+~', '', $text);

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // add a - before an uppercase letter
        $text = preg_replace('/(?<!\ )[A-Z]/', '$0', $text);

        // check if the 1st letter become a - and remove it
        if (substr($text, 0, 1) === '-')
        {
            $text = substr($text, 1);
        }

        return strtolower($text);
    }
```


### 14. Faire un AdminController


- Il aura une route de chemin : `/{_locale}/admin` (adapter le nom de la route pour qu'elle soit sécurisée...)
- Il s'agit du dashboard Admin
- La vue doit `extends` un `base.html.twig` spécifique à l'admin
- Cette vue doit avoir un menu sur la gauche, avec les liens des différents menus admins et le contenu affiché sur la partie de droite (Environ 20% pour le menu et 80% pour le contenu)
- Ajouter dans le menu de gauche un lien vers la nouvelle catégorie
- Modifier le `header.html.twig` pour qu'il aille vers le `AdminController`
- La page par défaut de l'`AdminController`, afficher les informations suivantes sous forme de table HTML :
  - Les 8 dernières ventes de jeu (`userOwnGames.createdAt`), afficher : "nom du jeu", "nom user", "date d'achat"
  - Les 8 derniers commentaires postés, afficher : "nom du jeu", "par qui", "date de création"
  - Les 8 derniers jeux sortis, afficher : "nom du jeu", "date de sortie"
  - Les 8 derniers utilisateurs inscrits (avec lien vers la page de leur profil), afficher : "nom du user", "date d'inscription", "nb jeux achetés"


### 15. Finaliser le "CRUD" des Catégories


- Le faire dans le `CategoryController` Admin
- Faire un `index` des catégories, on affichera une table HTML avec les infos suivantes :
  - `name`
  - La présence ou non, d'une `image`
  - `slug`
  - Le nombre de jeux de cette catégorie
  - Il y aura une cinquième colonne,nommée "Actions", elle contient les actions suivantes :
    - `edit` => Lien vers la fonction de modification d'une catégorie
    - `delete` => Lien vers la fonction de suppression d'une catégorie (à faire !)
    - `show` => Lien vers le détail de la catégorie (à faire !), ici on affichera toutes les infos de la catégorie (y compris l'image) et si vous le souhaite, les jeux présent dans cette catégorie
    - PS : utiliser de belles icônes (Fontawsome ?) pour les différentes actions, c'est plus parlant... et ça prend moins de place
  - Vous pouvez, si vous le souhaiter, ajouter un lien vers le `new` pour une catégorie (genre un icone `+` à côté du titre de l'index)


### 16. Faire le formulaire d'ajout d'un avis


- Créer un `ReviewType`, il ne devra gérer que les propriétés `content` & `rating` (Regarder pour adapter le Type d'input de la propriété `content`, celle-ci peut être élevée...)
- Tout va se passer dans le `app_game_show`
- Si un utilisateur est connecté, alors on va créer le formulaire des commentaires et le passer à la vue
- Dans le Twig, si le formulaire existe, alors on l'affiche (peut-être au début du bloc des avis ?)
- Une fois le formulaire soumis, l'objet review doit `set` des valeurs par défaut :
  - `createdAt` : la date actuelle
  - `upVote` : toujours à 0 (déjà fait normalement)
  - `downVote` : toujours à 0 (déjà fait normalement)
  - `user` : l'utilisateur connecté
  - `game` : le jeu de la page actuelle
- Il n'y aura pas de redirection après, on revient sur la page du jeu


### 17. Faire une barre de recherche


- Elle sera placée dans le [header.html.twig](templates/front/common/header.html.twig)
- Faites quelques chose de **simple** (voir Bootstrap : input group)
- Au moment de valider le form, on renvoie sur une route du `GameController` : `search` (nom de la route : `app_game_search`, path de la route : `/{_locale}/game/search`) [PS : mettez la bien en premier dans le controller pour éviter les problèmes de route !]
- La fonction doit récupérer tous les jeux avant la chaîne de caratère saisie dans le form, par exemple :
  - Je saisie : "Wor"
  - Je recherche les jeux dont le nom contient "%Wor%"
- La template twig de la page affichera la **liste complète des jeux ayant la chaine de caractère**
- Si la chaîne est vide, alors on affichera tous les jeux
