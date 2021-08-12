# Introduction

L'application est basée sur le _Framework_ Symfony, et utilise le [composant Security](https://symfony.com/doc/current/components/security.html) pour la gestion de l'authentification.

Cette documentation détaille comment fonctionne le processus d'authentification dans l'application ToDo & Co, où se situent les fichiers de configuration et quels fichiers modifier.

<hr>

<small>**Version anglaise disponible [ici](Authentication-EN.md)**</small>

<hr>

# 1. Authentication

Dans Symfony, le processus d'authentification nécessite d'avoir une classe _User_, peu importe la façon dont les utilisateurs se connectent à l'application, ou comment les données utilisateurs sont stockées.

## 1.1. La classe _User_

Dans Symfony, un utilisateur est représenté par une entité _User_. Cette entité contient les propriétés nécessaires à l'identification de l'utilisateur (nom d'utilisateur, email, mot de passe, etc.).

- Cette classe doit implémenter _UserInterface_.
- La classe _User_ est une entité _Doctrine_, et toutes les données des utilisateurs sont stockées dans la base de données du projet, dans la table `user`.
- Il est nécessaire de choisir un attribut de la classe _User_, qui permet d'identifier de façon unique chaque utilisateur. Pour ce projet, il s'agit de l'**email**. Il est possible de modifier cet attribut dans le fichier `config/packages/security.yaml`, dans la clé `providers`.
- Pour vérifier l'unicité de chaque utilisateur (par exemple lors de la validation des formulaires de création/modification de l'utilisateur), l'annotation `@UniqueEntity("email")` doit être présente dans la classe _User_. Avec Symfony 5.3, une nouvelle méthode `getUserIdentifier()` permet de récupérer l'attribut identifiant de façon unique l'utilisateur. Dans ce projet, cette méthode retourne l'**email** de l'utilisateur.
- Dans un souci de sécurité, le mot de passe de l'utilisateur doit être _hashé_ avant d'être stocké en base de données. Pour activer le _hashage_ des mots de passe, un algorithme de _hashage_ doit être configuré dans la clé `password_hashers` du fichier `security.yaml`. Par défaut, l'option `auto` est définie, Symfony utilise le meilleur algorithme disponible (dans Symfony 5.3, il s'agit de _bcrypt_).
- Lorsqu'il y a besoin de _hasher_ les mots de passe dans le code, par exemple lors de la création d'un nouvel utilisateur, il suffit d'utiliser `UserPasswordHasherInterface` en tant que service, en utilisant l'injection de dépendances :

  ```php
  $hashedPassword = $passwordHasher->hashPassword($user, $rawPassword);
  ```

## 1.2. Firewall

User authentication in Symfony is managed by a firewall. The configuration of this firewall defines how the user will be authenticated (login form, API token, etc.), and to which parts of the application the user has access.

The firewall configuration is available in `security.yaml`, under the `firewalls` key.

In this project, the `main` firewall defines that all the URLs of the site needs an authentication (`pattern` key) via the login form (`entry_point` key), e.g. all unauthenticated users will be redirected to the login page, to authenticate and have access to these URLs.

The `SecurityController` is in charge of rendering the login page, which contains the login form, and also to get the last authentication error (if wrong credentials were provided by the user).

Then, when the login form is submitted, authentication will be handled by Symfony's _Authenticators_, so no other action is needed to authenticate users.

## 2. Authorization

In order to restrict users access to some parts of the site, e.g. the `admin` section, an authorization process will be used.

This processed is based on the `roles` of the user. Inside the _User class_, the `getRoles()` method retrieves the user's roles stored in database. If the user has no role, a default `ROLE_USER` is added automatically.

For this project another role has been added, the role `ROLE_ADMIN`. This role grants access to all the URLs starting by `/users` (users creation, update and deletion) to the users who have this role.

If needed, it's possible to add new roles when creating/updating users, the only mandatory rule is that a role __must__ start with `ROLE_`. After that part, any string will be valid (e.g. `ROLE_TASK_UPDATE`).

To deny access to some URLs of the site, there are two ways :

- the `access_control` key in `security.yaml`
- in `Controllers` or in a _Twig_ template

For this project, the two options have been used, depending on the needs.

The `access_control` key in `security.yaml` is used to grant access to users who have the `ROLE_ADMIN` role to all URLs starting by `/users`, via the `- { path: ^/users, roles: ROLE_ADMIN }` option.

In the code, it's possible to use `$this->denyAccessUnlessGranted(<role>)` in a Controller, to deny access to a route for example.

Or, in a Twig template it's possible to use :

```html
{% if is_granted('ROLE_ADMIN') %}
  <a href="...">Supprimer</a>
{% endif %}
```

to show a link only if the user has the role `ROLE_ADMIN`

No matter the way used, if the user does not have access to the section of the site, a 403 HTTP error will be sent.

# Learn more

- [The Security documentation](https://symfony.com/doc/current/security.html)
- [The Security component](https://symfony.com/doc/current/components/security.html)
- [The Authentication part of the security component](https://symfony.com/doc/current/components/security/authentication.html)
