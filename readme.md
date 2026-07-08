
# 2026 POEI Symfony

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
| rating | integer/smallint | not null (échelle observée 0 à 5, avec une anomalie à 88 — à valider/contraindre via une assertion `Range(min=0, max=5)`) |

**Relations**
- `ManyToOne` vers `User`.
- `ManyToOne` vers `Game`.
