# Cours Symfony


## Sommaire


- [1. L'injection de dépendance](#1-linjection-de-dépendance)
- [2. Les Repository](#2-les-repository)
    - [2.1. Méthodes natives aux `Repository $repository`](#21-méthodes-natives-aux-repository-repository)
    - [2.2. Critères des `Repository $repository`](#22-critères-des-repository-repository)
- [3. Twig](#3-twig)
    - [3.1. Extends](#31-extends)
    - [3.2. Inclusion de template](#32-inclusion-de-template)
    - [3.3. Instruction Twig](#33-instruction-twig)


## 1. L'injection de dépendance


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

## 2. Les Repository


### 2.1. Méthodes natives aux `Repository $repository` :


- `$repository->count()` : comptez le nombre de lignes corespondant aux critères (par défaut : `SELECT COUNT(*) FROM _table`)
- `$repository->findOneBy()` : récupère UNE instance de l'objet géré par le Repository OU NULL, selon les critères demandés
- `$repository->find()` : récupère UNE instance de l'objet géré par le Repository OU NULL, uniquement par son `id`
- `$repository->findAll()` : récupère TOUTES les instances de l'objet géré par le Repository sous forme de tableau
- `$repository->findBy()` : récupère TOUTES les instances de l'objet géré par le Repository sous forme de tableau, selon les critères demandés
- `$repository->createQueryBuilder()` : permet de créer nos propres requêtes SQL, une requête qui ne serait pas faisable avec les `find` de base 


### 2.2. Critères des `Repository $repository` :


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


### 3.3. Instruction Twig


- On vérifier l'existance d'une variable dans un template twig via `defined`, si la variable existe, alors on passe dans la condition, sinon non :

```html
{% if title is defined %}
    <h2>{{ title }}</h2>
{% endif %}
```





