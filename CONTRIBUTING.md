# ToDo & Co - Contributing guide

This file is for developers who want to contribute to the ToDo & Co project. Any contribution to this project is welcome !

But before creating any pull request, please read carefully this file, otherwise if you not follow the guidelines, new features or pull requests won't be approved or merged.
<hr>

## 1. Prerequisites

The starting point for adding a contribution to the project, is to clone this repository, and install the project on your local development machine.

Follow the [**README**](README.md) to install and run the project.

## 2. Workflow

### 2.1 Create an issue

First, if you want to add a **new feature**, or to **fix a bug**, start by creating an [**issue**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/issues).

Add a descriptive title, and, if more precision is needed, add a more detailed description in the dedicated field.

Don't forget to add yourself in the _**Assignees**_ field on the right, as well as the _**Labels**_ and _**Project**_.

For example, for a new feature, add the **feature** _label_, and select **ToDo** in the _project_ field (this adds automatically the issue to the [**project board**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/projects/1), to track the issues statuses). 

When the issue is created, you can move the issue to the **in progress** column of the [**project board**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/projects/1), so the other developers can see this issue is currently in development.

### 2.2 Feature or bugfix development

This project relies on **GIT** to track changes made to the project, and the project mainly follows the [**Git flow**](https://guides.github.com/introduction/flow/) for branch management.

So every new feature or bugfix needs to be done on a new branch, created from the _**master**_ branch.

#### 2.2.1 Branch naming

The project follows a simple naming convention for branches :

- for a new feature : `feature/<issue-id>-<issue-title>`
- for a bugfix : `bugfix/<issue-id>-<issue-title>`

Then, when the branch is created, you can start to add new code to develop the feature, or fix a bug.

In order to keep the codebase clean, maintainable and bug free, the project follows some coding standards.

#### 2.2.2 Coding standards and quality

The project follows the [**PSR-1**](https://www.php-fig.org/psr/psr-1/), [**PSR-4**](https://www.php-fig.org/psr/psr-4/) and [**PSR-12**](https://www.php-fig.org/psr/psr-12/) for the code formatting.

When you installed the project, some tools to help you analyze and fix the code formatting and standards were added :

- [**phpstan**](https://phpstan.org/), a static code analysis tool, highlights errors in code, like missing _types hints_, etc.
- [**Easy Coding Standard**](https://github.com/symplify/easy-coding-standard), a CLI tool that analyzes the code to find issues from a set of rules (based on _PHP Code Sniffer_ and _PHP CS Fixer_). The configuration is available in [`ecs.php`](ecs.php). The tool can be run in a terminal with `vendor/bin/ecs check src/` or `vendor/bin/ecs check src/ --fix` to fix code.

Please run these tools regularly to fix some code smells, like missing _type hints_, format the code to follow _PSRs_, etc.

#### 2.2.3 Tests

This project uses [**PHPUnit**](https://phpunit.de/) tu run tests.

In order to keep the project stable, and with the minimum of bugs, it's required to run the tests, and that **all** tests pass, before committing and pushing to the repository.

Please refer to the [**README**](README.md) to know how to run the tests.

It will also be appreciated that you add tests for the feature or bugfix you work on, to check that the implementation covers the most cases.

#### 2.2.4 Commit message convention

When the feature is complete, you can commit the changes made, following the [**conventional commit**](https://www.conventionalcommits.org/en/v1.0.0/) convention :

- `feat: <commit title>` for a new feature
- `fix: <commit title>` for a bugfix

### 2.3 Pull Request
