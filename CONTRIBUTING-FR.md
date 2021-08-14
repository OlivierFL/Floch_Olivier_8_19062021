# ToDo & Co - Guide de contribution

Ce guide est destiné aux développeurs souhaitant participer au projet ToDo & Co. Toutes les contributions à ce projet sont les bienvenues !

Avant de faire une _pull request_, veuillez lire attentivement ce guide et ses différentes recommendations. Si celles-ci ne sont pas suivies, les nouvelles fonctionnalités ainsi que les _pull requests_ ne pourront être approuvées et fusionnées.
<hr>

<small>**Version en anglais disponible [ici](CONTRIBUTING-FR.md)**</small>

<hr>

## 1. Prérequis

Afin de contribuer au projet, commencez par cloner le dépôt et installez le projet sur votre machine de développement.

Le [**README**](README.md) détaille les étapes nécessaires à l'installation et au lancement du projet.

## 2. Workflow

### 2.1 Création d'une _issue_

Pour ajouter une nouvelle fonctionnalité ou corriger un bug, commencez par créer une [**issue**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/issues).

Ajoutez un titre, et, s'il y a besoin de plus de précisions, une description détaillée dans le champ correspondant.

N'oubliez de vous ajouter dans le champ _**Assignees**_, et également de renseigner les _**Labels**_ et le _**Project**_.

Par exemple, pour une nouvelle fonctionnalité, ajoutez le _label_ **feature**, et sélectionnez **ToDo** dans le champ _project_ (pour ajouter automatiquement l'_issue_ dans le [**project board**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/projects/1), afin de suivre le statut des _issues_).

Lorsque l'_issue_ est créée, veillez à déplacer celle-ci dans la colonne **in progress** du [**project board**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/projects/1), afin que les autres développeurs soient informés que la fonctionnalité est en cours de développement.

### 2.2 Développement d'une fonctionnalité ou correction d'un bug

Le projet utilise sur **GIT** pour suivre les différents changements apportés au projet, et la gestion des branches est basée sur le [**Git flow**](https://guides.github.com/introduction/flow/).

Chaque nouvelle fonctionnalité ou correction de bug doit être faite sur une nouvelle branche, créée depuis la branche _**master**_.

#### 2.2.1 Nommage des branches

Le projet suit une convention de nommage simple :

- pour une nouvelle fonctionnalité : `feature/<issue-id>-<issue-title>`
- pour une correction de bug : `bugfix/<issue-id>-<issue-title>`

Lorsque la branche est créée, vous pouvez commencer le travail de développement.

Afin de conserver le code du projet le plus maintenable possible, et sans bugs, le code ajouté doit respecter certains standards.

#### 2.2.2 Qualité du code et standards

Le projet suit les recommandations des [**PSR-1**](https://www.php-fig.org/psr/psr-1/), [**PSR-4**](https://www.php-fig.org/psr/psr-4/) and [**PSR-12**](https://www.php-fig.org/psr/psr-12/) pour le formatage du code.

Lorsque vous avez installé le projet sur votre machine, des outils facilitant le respect de ces recommandations ont été ajoutées : 

- [**phpstan**](https://phpstan.org/), outil d'analyse statique du code, met en évidence les erreurs dans le code, par exemple le _typage des attributs_, etc.
- [**Easy Coding Standard**](https://github.com/symplify/easy-coding-standard), outil en ligne de commande qui analyse le code pour trouver d'éventuelles erreurs, en se basant sur des règles prédéfinies (issues de _PHP Code Sniffer_ et _PHP CS Fixer_). La configuration est disponible dans le fichier [`ecs.php`](ecs.php). L'outil se lance depuis un terminal, avec `vendor/bin/ecs check src/` ou `vendor/bin/ecs check src/ --fix` pour corriger les erreurs.

Veillez à utiliser ces outils régulièrement pour fixer les _code smells_, comme des _typages_ manquants, formater le code en respectant les _PSR_, etc.

Ce projet étant développé avec le _Framework_ Symfony, veillez également à respecter les [**best practices**](https://symfony.com/doc/current/best_practices.html) de celui-ci.

#### 2.2.3 Tests

Le projet utilise [**PHPUnit**](https://phpunit.de/) pour les tests.

Pour garder le projet stable, et avec le minimum de bugs, il est impératif de lancer **tous** les tests et qu'ils passent, avant de _commit_ et de pousser les changements sur le dépôt. 

Le [**README**](README.md) détaille les différentes étapes pour lancer les tests.

L'ajout de tests couvrant les différents cas de la nouvelle fonctionnalité ou de la correction de bug que vous ajoutez sera bienvenu.

#### 2.2.4 Convention de nommage des commits

Afin de suivre facilement les changements, ou pour annuler une modification apportée au code, pensez à faire des _commits_ réguliers, en utilisant cette convention (basée sur [**conventional commit**](https://www.conventionalcommits.org/en/v1.0.0/)) :

- `feat: <titre du commit>` pour une nouvelle fonctionnalité
- `fix: <titre du commit>` pour une correction de bug

### 2.3 Pull Request

Lorsque la nouvelle fonctionnalité ou la correction de bug est terminée, vous pouvez créer une _pull request_.

Rendez-vous sur la [**page des Pull requests**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/pulls), et cliquez sur le bouton _New pull request_. 

Sur la page de création de la _pull request_, vous pouvez ajouter un titre, ainsi qu'une description.

Dans le champ _**description**_, il est nécessaire de mentionner l'_issue_ relative à la _pull request_. Cela permet de déplacer automatiquement l'_issue_ dans la colonne appropriée dans le [**project board**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/projects/1). La syntaxe à utiliser est : `Closes #<issue-id>`.

De le même manière que lors de la création d'une [_**issue**_](#21-cration-dune-_issue_), veillez à renseigner les champs situés sur la droite de la page, pour ajouter les informations nécessaires, par exemple les **_Reviewers_**, les **_Labels_**, etc.

Les analyses [**SonarCloud**](https://sonarcloud.io/dashboard?id=OlivierFL_Floch_Olivier_8_19062021) et [**CodeClimate**](https://codeclimate.com/github/OlivierFL/Floch_Olivier_8_19062021) sont lancées automatiquement lorsque la _pull request_ est créée.

Veuillez vérifier que la qualité du code est conforme aux standards du projet :
- SonarCloud :
    - Quality Gate passed
    - 0 bugs
    - 0 vulnerabilities
    - 0 security hotspots
    - 0 code smells
    - 0% duplication
- CodeClimate :
    - 0 issues detected

Sinon, si ces standards ne sont pas respectés, la **pull request ne pourra pas être approuvée et ne sera pas fusionnée**. Veuillez apporter les corrections nécessaires, jusqu'à ce que toutes les analyses soient valides.

Lorsque toutes les analyses sont au vert, et que la _pull request_ a été approuvée par les _reviewers_, la _pull request_ peut-être fusionnée.

# 3. Merci pour votre contribution

Merci d'avoir lu ce guide, nous espérons que vous pourrez contribuer et apporter de nouvelles fonctionnalités à ce projet !
