# ToDo & Co - Contributing guide

This file is for developers who want to contribute to the ToDo & Co project. Any contribution to this project is welcome !

But before creating any pull request, please read carefully this file, otherwise if you not follow the guidelines, new features or pull requests won't be approved or merged.
<hr>

<small>**French version is available [here](CONTRIBUTING-FR.md)**</small>

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

As this project is built with the Symfony Framework, please follow the framework's [**best practices**](https://symfony.com/doc/current/best_practices.html).

#### 2.2.3 Tests

This project uses [**PHPUnit**](https://phpunit.de/) to run tests.

In order to keep the project stable, and with the minimum of bugs, it's required to run the tests, and that **all** tests pass, before committing and pushing to the repository.

Please refer to the [**README**](README.md) to know how to run the tests.

It will also be appreciated that you add tests for the feature or bugfix you work on, to check that the implementation covers the most cases.

#### 2.2.4 Commit message convention

To easily track changes, or to rollback any change made to the codebase, don't forget to commit often and early, following the [**conventional commit**](https://www.conventionalcommits.org/en/v1.0.0/) convention :

- `feat: <commit title>` for a new feature
- `fix: <commit title>` for a bugfix

### 2.3 Pull Request

When the feature or bugfix is done, you can make a pull request.

Go to the [**Pull requests page**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/pulls), and click on the _New pull request_ button.

On the _pull request_ creation page, you can add a title, and a description.

In the _**description**_ field, don't forget to link the issue related to the current pull request. It is mandatory to automatically move the issue in the right column in the [**project board**](https://github.com/OlivierFL/Floch_Olivier_8_19062021/projects/1). The syntax is : `Closes #<issue-id>`.

And, same as creating an [issue](#21-create-an-issue), please fill the fields on the right of the page, to add the mandatory data, like the **_Reviewers_**, the **_Labels_**, etc.

When the request is created, [**SonarCloud**](https://sonarcloud.io/dashboard?id=OlivierFL_Floch_Olivier_8_19062021) and [**CodeClimate**](https://codeclimate.com/github/OlivierFL/Floch_Olivier_8_19062021) analyses will run automatically.

Please check that the results meet the following project requirements for code quality :
- SonarCloud :
    - Quality Gate passed
    - 0 bugs
    - 0 vulnerabilities
    - 0 security hotspots
    - 0 code smells
    - 0% duplication
- CodeClimate :
    - 0 issues detected

Otherwise, if any of these requirements are not met, **the pull request will not be approved and merged.** Please check and fix the issues, until all the checks pass.

If all is green, and the pull request has been approved by the _reviewers_, the pull request can be merged.

# 3. Thank you for contributing

Thanks for reading this guide, we hope you can contribute and add new features to this project !
