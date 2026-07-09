# Cours Symfony

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

## 1. Les Repository


### 1.1. Méthodes natives aux `Repository $repository` :


- `$repository->count()` : comptez le nombre de lignes corespondant aux critères (par défaut : `SELECT COUNT(*) FROM _table`)
- `$repository->findOneBy()` : récupère UNE instance de l'objet géré par le Repository OU NULL, selon les critères demandés
- `$repository->find()` : récupère UNE instance de l'objet géré par le Repository OU NULL, uniquement par son `id`
- `$repository->findAll()` : récupère TOUTES les instances de l'objet géré par le Repository sous forme de tableau
- `$repository->findBy()` : récupère TOUTES les instances de l'objet géré par le Repository sous forme de tableau, selon les critères demandés
- `$repository->createQueryBuilder()` : permet de créer nos propres requêtes SQL, une requête qui ne serait pas faisable avec les `find` de base 


### 1.2. Critères des `Repository $repository` :


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










