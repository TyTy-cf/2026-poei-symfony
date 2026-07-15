# Cours Symfony


## Sommaire


- [1. Compréhension globale](#1-compréhension-globale)
    - [1.1. Injection de dépendance](#11-injection-de-dépendance)
    - [1.2. WebPack](#12-webpack)
- [2. Les Repository](#2-les-repository)
    - [2.1. Méthodes natives aux Repository](#21-méthodes-natives-aux-repository)
    - [2.2. Critères des Repository](#22-critères-des-repository)
    - [2.3. QueryBuilder](#23-querybuilder)
    - [2.4. QueryBuilder avec paramètres](#24-querybuilder-avec-parametres)
- [3. Twig](#3-twig)
    - [3.1. Extends](#31-extends)
    - [3.2. Inclusion de template](#32-inclusion-de-template)
    - [3.3. Instruction Twig](#33-instruction-twig)
- [4. Paramètres de route](#4-paramètres-de-route)
    - [4.1. Via binding d'objet](#41-via-binding-dobjet)
    - [4.2. Via binding de paramètres](#42-via-binding-de-paramètres)
    - [4.3. Via la Request](#43-via-la-request)
    - [4.4. Effectuer une redirection](#44-effectuer-une-redirection)
- [5. Les translations](#5-les-translations)
    - [5.1. Mise en place](#41-mise-en-place)


## 1. Compréhension globale


### 1.1. Injection de dépendance


Communément appelée ID en français, ou DI en anglais (Dependancy Injection), c'est le fait d'indiquer au Framework qu'il doit gérer le cycle de vie de l'objet.

C'est donc le Framework qui va instancier l'objet en question pour vous.

On s'en sert souvent dans les routes des contrôleurs ou dans les `__construct()`, exemple :

On ne peut pas passer par injection de dépendance tout et n'importe quoi, on est limité à certains objet, comme les `Repository`, les `Services`, les classes de Symfony et dans certains cas de figure nos entités. 

- Route d'un Contrôleur :

```php
#[Route('/', name: 'app_home')]
public function home(GameRepository $gameRepository): Response
```

- Dans un `__construct()` :

```php
public function __construct(private GameRepository $gameRepository) {
    
}
```


Retour au [Sommaire](#sommaire)


### 1.2. WebPack


WebPack permet de gérer tout ce qui est "assets" de l'application Symfony, les assets sont tout ce qui est `CSS` (et autres dérivés `SCSS`, `SASS` ou encore `LESS`), `JS` (ou `Typescript` ou `Vue`) ou les images.


Il se base sur le fichier `webpack.config.js` qui est créé à la racine du projet.


WebPack va compiler un fichier d'assets en un fichier regroupant tout notre code.

La configuration se fait via le code suivant :

```js
    .addEntry('scripts', './assets/scripts/app.js')
    .addEntry('styles', './assets/styles/app.css')
```

Ici, on créé deux fichiers : "scripts" et "styles". "scripts" se base sur le fichier `app.js` présent dans le dossier `/assets/scripts` et le fichier "styles" sur le fichier `app.css` présent dans le dossier `/assets/styles`  

On configure le dossier où doit être placé le fichier en sortie, une fois compilée :  

```js
    .setOutputPath('public/build/')
    .setPublicPath('/build')
```

- On indique qu'il sera dans le dossier "public/build"

Ensuite il faut importer les fichiers générés en sortie dans le template Twig : 

```html
        {% block stylesheets %}
            {{ encore_entry_link_tags('styles') }}
        {% endblock %}

        {% block javascripts %}
            {{ encore_entry_script_tags('scripts') }}
        {% endblock %}
```

- `encore_entry_link_tags` : importe un fichier de style (.css), le nom du fichier est passé en paramètre, ici : `styles` 
- `encore_entry_script_tags` : importe un fichier de script (.js), le nom du fichier est passé en paramètre, ici : `scripts` 


Pour que WebPack compile vos fichiers vous devez lancer la commande suivante : `npm run watch`, elle recompile **en direct** les fichiers d'assets.
(Il se base sur une modification pour recompiler !)

En environnement de production : `npm run build`

(PS : si notre environnement Node est sous docker... il faut lancer la commande depuis le **container Node**)
(PS² : pensez à faire du `CTRL + F5` lorsque vous faites des modifications d'assets...)


On peut ajouter une configuration pour copier les images avec WebPack :  

```js
.copyFiles({
    from: './assets/images',
    to: 'images/[path][name].[ext]'
})
```

- Cette configuration copies les images depuis de le dossier "assets/images" dans le le dossier "public/build", qui est créé par WebPack.

Une fois les images copiées dans le dossier "build", on peut les réutiliser sur le site Web avec Twig : 

```html
{{ asset('images/home.png') }}
```

- La fonction Twig `asset`permet d'accéder au contenu du dossier `build`


Retour au [Sommaire](#sommaire)


## 2. Les Repository


### 2.1. Méthodes natives aux Repository


- `$repository->count()` : comptez le nombre de lignes corespondant aux critères (par défaut : `SELECT COUNT(*) FROM _table`)
- `$repository->findOneBy()` : récupère UNE instance de l'objet géré par le Repository OU NULL, selon les critères demandés
- `$repository->find()` : récupère UNE instance de l'objet géré par le Repository OU NULL, uniquement par son `id`
- `$repository->findAll()` : récupère TOUTES les instances de l'objet géré par le Repository sous forme de tableau
- `$repository->findBy()` : récupère TOUTES les instances de l'objet géré par le Repository sous forme de tableau, selon les critères demandés
- `$repository->createQueryBuilder()` : permet de créer nos propres requêtes SQL, une requête qui ne serait pas faisable avec les `find` de base 


Retour au [Sommaire](#sommaire)


### 2.2. Critères des Repository


Les fonctions `findOneBy()`, `findBy()` et `count()` peuvent avoir des crtières (le premier paramère de la fonction), il s'agit d'un tableau associatif permettant d'avoir un `WHERE` et des `AND`, si nécessaire, ils servent à affiner la requête.

Exemple :

```php
$items = $repository->findBy(['price' => '50']);
```

En SQL cela donne la requête suivante :

```sql
SELECT *
FROM item
WHERE price = 50;
```

Note importante : dans le tableau associatif, vous ne pouvez passer que les noms des `attributs` de la classe, pas le nom de la colonne ! Dans les `Repository` on raisonne en OBJET !!!!

PS : Si vous écrivez :

```php
$items = $repository->findBy(['']);
```

Puis `CTRL + ESPACE` entre les simples quotes, alors le plugins Symfony de PHPStorm vous trouveras les attributs de votre objet.


Dans la fonction `findBy()` on peut aussi effectuer un `ORDER BY`ou encore une `LIMIT` :

```php
public function findBy(
    array $criteria,
    array|null $orderBy = null,
    int|null $limit = null,
    int|null $offset = null
): array|object[]
```


Dans les exemples suivants, les critères sont vides (tableau vide : `[]`) car je ne souhaite pas faire de `WHERE`.


- Le deuxième paramètre est l'ORDER BY il fonctionne comme les critères :

```php
$items = $repository->findBy([], ['createdAt' => 'DESC']);
```

=> Trie les `items` par `createdAt` décroissante

- Le troisème paramètre est la LIMIT d'objet à afficher :

```php
$items = $repository->findBy([], ['createdAt' => 'DESC'], 10);
```

=> Trie les `items` par `createdAt` décroissante
=> Affiche seulement 10 `item`

- Le quatrième paramètre est la LIMIT d'objet à afficher :

```php
$items = $repository->findBy([], ['createdAt' => 'DESC'], 10, 10);
```

=> Trie les `items` par `createdAt` décroissante
=> Affiche seulement 10 `item` à partir du 11ème


Retour au [Sommaire](#sommaire)


### 2.3. QueryBuilder


Lorsque l'on utilise la méthode de l'AbstractRepositroy `createQueryBuilder('r')` on instancie un objet de type `QueryBuilder` : un constructeur de requêtes SQL.

Le comportement par défaut du `createQueryBuilder('r')` est de faire la requête SQL suivante :

```sql
SELECT r.*
FROM _table AS r;
```


Depuis un objet `QueryBuilder` on peut utiliser différentes méthodes :
- `join` : permet de faire un innerJoin vers une entité en relation
- `innerJoin` : permet de faire un innerJoin vers une entité en relation
- `leftJoin` : permet de faire un leftJoin vers une entité en relation

Exemple de `QueryBuilder` :

```php
$this->createQueryBuilder('r')
    ->select('r', 'u') // Permet d'indiquer que l'on veut récupérer le contenu de 'r' ET de 'u', par défaut c'est seulement 'r'
    ->join('r.user', 'u');
```

En SQL cela donne :

```sql
SELECT r.*, u.*
FROM _TABLE AS r
JOIN user AS u WHERE r.user_id = u.id;
```

Via le `QueryBuilder` vous pouvez faire TOUTES les actions SQL existantes :

```php
    ->where('r.rating = 5')
    ->orderBy('r.createdAt', 'DESC')
    ->setMaxResults(5); // LIMIT en SQL !
```

Ici on vient ajouter une condition WHERE et un ORDER BY à notre requête :

```sql
SELECT r.*, u.*
FROM _TABLE AS r
JOIN user AS u WHERE r.user_id = u.id
WHERE r.rating = 5
ORDER BY r.created_at DESC
LIMIT 5;
```


Une fois le `QueryBuilder` terminé, vous devez faire l'équivalent d'un `fetch` ou `fetchAll` :

```php
$qb->getQuery() // formatte la requête prorement, prête à être envoyée à la BDD ($stmt->execute ?)
    ->getResult(); // fetchAll
    ->getOneOrNullResult(); // fetch
```


Retour au [Sommaire](#sommaire)


### 2.4. QueryBuilder avec paramètres


Pour ajouter des paramètres dans votre QueryBuilder, il faut passer par des requêtes **préparées** :

```php
    // WHERE c.id IN (1, 5, 6, 8)
    ->where('c IN (:categs)')
    ->setParameter('categs', $game->getCategories())
```

- On écrit dans le WHERE `:alias` (les 2 points sont importants), ici l'alias est `categories`
- Pour chaque `:alias` dans votre WHERE, vous devez avoir un `setParameter`, le premier paramètre est le nom de l'alias (SANS LES DEUX POINTS) déclaré dans le WHERE, le deuxième paramètre, la valeur de l'alias


Retour au [Sommaire](#sommaire)


## 3. Twig


### 3.1. Extends


Un template `twig` peut `extends` d'un autre template, pour cela on utilise :

```html
{% extends 'front/base.html.twig' %}

{% block title %}
    Toute l'actu G4ming !
{% endblock %}
```

Cela impliquera que notre template courant aura accès aux différents "block" du template parent et pourra les redéfinir, au même titre que l'héritage de classe.


Un template enfant peut choisir de redéfinir et conserver le comportement du block parent avec la fonction `parent()` :

```html
{% block title %}
    {{ parent() }}
    Toute l'actu G4ming !
{% endblock %}
```


Retour au [Sommaire](#sommaire)


### 3.2. Inclusion de template


Twig permet d'inclure un template dans un autre template, on utilise `include` :

```html
{% include 'front/partials/_game_loop.html.twig' %}
```

Il peut arriver que l'on veuille dynamiser le template inclus, pour cela on peut passer des variables à celui-ci :

```html
{% include 'front/partials/_game_loop.html.twig' with {
    'games': trends, <!-- 'games' coresponds à une variable utilisée dans le template inclus -->
    'title': "Les tendances"
} %}
```


Retour au [Sommaire](#sommaire)


### 3.3. Instruction Twig


- On vérifier l'existance d'une variable dans un template twig via `defined`, si la variable existe, alors on passe dans la condition, sinon non :

```html
{% if title is defined %}
    <h2>{{ title }}</h2>
{% endif %}
```


Retour au [Sommaire](#sommaire)


## 4. Paramètres de route


### 4.1. Via binding d'objet


L'objectif ici est de lier directement l'id passé en paramètre de l'URL à l'objet passé en paramètre de la fonction :

```php
#[Route('/game/{id}', name: 'app_game_show')]
public function show(
    Game $game // ->find($id)
): Response
{
```

- `Game` possède un attribut `id`, alors implicitement Symfony effectue la requête `SELECT * FROM game WHERE id = $id`


Inconvénient : C'est un `find` dont les relations ne sont pas récupérées, en cas de nécessité, cela peut engrendrer des requêtes supplémentaires.


Retour au [Sommaire](#sommaire)


### 4.2. Via binding de paramètres


L'objectif ici est de lier directement l'id passé en paramètre de l'URL au paramètres `$id`de la fonction (il doit être du même nom pour que cela fonctionne !) :


```php
#[Route('/game/{id}', name: 'app_game_show')]
public function show(string $id): Response
{
```


Inconvénient : Cela implique de traiter l'id dans la fonction, on va probablement avoir besoin du `Repository` en plus


Retour au [Sommaire](#sommaire)


### 4.3. Via la Request


Ici on va injecter l'objet `Request` de Symfony, via celui-ci on peut récupérer l'identifiant passé en paramètre de l'URL :

```php
#[Route('/game/{id}', name: 'app_game_show')]
public function show(Request $request): Response
{
    dd($request->attributes->get('id'));
```


(PS : penser à utiliser le bon `use`: `use Symfony\Component\HttpFoundation\Request;` !!!)


Inconvénient : Rajoute du traitement pour rien ?


On peut aussi récupérer le `corps` de la requête via l'objet `Request`: 

```php
$request->getContent();
```


Retour au [Sommaire](#sommaire)


### 4.4. Effectuer une redirection


Pour effectuer une redirection on utilise la fonction `path` que l'on passe en paramètre au `href` :

```html
<a href="{{ path('app_home') }}">
    Home
</a>
```


Si on appelle une route avec des paramètres, on le fait comme ceci : 

```html
<a href="{{ path('app_game_show', {'id': game.id}) }}">

</a>
```

- Entre les accolades dans la fonction path, on passe un "tableau" associatif où la clé le nom du paramètre définie par la route (ici : `id`), puis sa valeur


Retour au [Sommaire](#sommaire)


### 4.5 Paramètre optionnel


- On ajoute un `?` après la déclaration de la variable dans l'URL, mais bien à l'intérieur des accolades.

```php
#[Route('/{_locale?}', name: 'app_home')]
```


## 5. Les translations


### 5.1. Mise en place


- Créer un fichier `messages.LOCALE.yaml` => `LOCALE` prend le nom des codes de locales ('fr', 'en', 'de', 'es', 'pt', 'it', etc)
- Le nom du fichier est conventionné Symfony !
- Modifier dans le fichier `config/packages/translation.yaml` : modifier la ligne `default_locale:` pour ajouter 'fr' en langue par défaut pour le site
- Pour utiliser les chaînes de traductions dans un tempalte Twig, on utilise le filtre `trans`:

```html
{{ 'home.title'|trans }}
```

- 'home.title' est la clé dans le fichier yaml :

```yaml
title:
    main: "SteamIsh : l'actu G4ming !"
```

- On peut aussi définir un fichier nous-même :
  - Par exemple : `home.fr.yaml`
  - Dans notre template twig on lui indique où chercher la clé de traduction via l'instruction :

```html
{% trans_default_domain 'home' %}
```

- Toujours dans le fichier `config/packages/translation.yaml`, ajouter la ligne suivante, au même niveau que `default_locale`:

```yaml
    set_locale_from_accept_language: true
```

- Cela permet de forcer la récupération de la locale du navigateur du user

- On peut ajouter un fallback, si la langue de l'utilisateur n'existe pas, alors on va chercher celle en "fallback", c'est-à-dire une traduction disponible dans l'ordre :
```yaml
        fallbacks:
            - en
            - fr 
```
=> Ici on met le site en priorité en anglais, si l'anglais n'est pas trouvé, on se replie sur le français
