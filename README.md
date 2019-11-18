# Symfony project

## Index
- [Git Commands](Git)
- [Security](#Security)
- [Form](#Form)
- [Listener Subscriber](#Listener-Subscriber)
- [API Rest](#API-Rest)
- [API-Platform](#API-Platform)
- [Tests PHPUnit](#PHPUnit)
- [GitLab CI/CD](#GitLab)
- [Fixtures and Faker](#Fixtures)
- [Pagination](#Pagination)
- [Flash Messages](#Flash)

---------------------------------------------------------------------------
## Git Commands
[Top](#Index)

#### Clone project
```
git clone https://github.com/ermineaweb/sortircom && cd sortircom
```
#### Pull
```
git pull
```
#### Push
```
git add .
git commit -m "un court message pour s'y retrouver"
git push
```

---------------------------------------------------------------------------
## Security
[Top](#Index)

## AuthenticationGuardClass

#### 1. Create the User security entity then add more fields
```
bin/console make:user
bin/console make:entity
```
#### 2. Play migrations
```
bin/console d:d:c
bin/console make:migration
bin/console d:m:m
```
#### 3. Create AuthenticatorClass, SecurityController and the login form
```
bin/console make:auth
```
#### 4. Create registration form
```
bin/console make:registration-form
```

---------------------------------------------------------------------------
## Form
[Top](#Index)


```twig
{{ form_start(form) }}

    {{ form_label(form.titre) }}
    {{ form_widget(form.titre) }}
    {{ form_help(form.titre) }}

    {{ form_label(form.description) }}
    {{ form_widget(form.description) }}
    {{ form_help(form.description) }}

<button class="button">{{ button_label|default('Enregistrer') }}</button>
{{ form_end(form) }}
```

---------------------------------------------------------------------------
## Listener-Subscriber
[Top](#Index)


/ TODO


---------------------------------------------------------------------------
## API-Rest
[Top](#Index)

#### 1. Your entity must implements "JsonSerializable" interface, and implements the "jsonSerialize()" method

```php
class Entity implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "titre" => $this->titre,
            "description" => $this->description,			
            ];
    }
}
```
#### 2. In your EntityController.php

```php
/**
 * @Route("/api", name="api_")
 */
class EntityController extends AbstractController
{
    /**
    * @Route("/entity", name="entity")
    */
    public function showAll(EntityRepository $repository)
    {
        return $this->json($repository->findAll());
    }
}
```

---------------------------------------------------------------------------
## API-Platform
[Top](#Index)

Ressources
[Site officiel](https://api-platform.com/)

#### 1. Create new symfony/website-skeleton project

#### 2. Add api-platform
```
composer require api
```
#### 3. 

## API-Platform-Admin
[Top](#Index)

ressources
[Doc Api-Platform](https://api-platform.com/docs/admin/)
[Doc React Admin](https://marmelab.com/react-admin/Readme.html)

#### 1. new create-react-app

#### 2. Add api-platform-admin
```
yarn add @api-platform/admin
```
#### 3. Create the admin interface
```javascript
import React from "react";
import { HydraAdmin } from "@api-platform/admin";

export default () => (
  <HydraAdmin entrypoint="https://monsite/api"></HydraAdmin>
);
```

---------------------------------------------------------------------------
## PHPUnit
[Top](#Index)

#### 1. create functionnal test
```
bin/console make:functional-test
```
#### 2. execute functionnals tests
```
bin/phpunit
or only one
bin/phpunit tests/MyControllerTest.php
```

---------------------------------------------------------------------------
## GitLab
[Top](#Index)

ressources

https://gitlab.com/help/ci/examples/php.md

#### token ssh
```
63c7a295edf1fd4b3208c9493b94053e3a96d2b9
```

#### 1. create project CI/CD and bind to the git repository

#### 2. create this .gitlab-ci.yml in the project root
```yaml
image: php:7.2-cli
# mysql 5.6 est stable pour ce que l'on veut faire
# mysql > 8 entraine un bug a resoudre
services:
    - mysql:5.6
# on envoie les vendors en cache
cache:
    paths:
    - vendor/
    - bin/.phpunit/
 
stages:
    - build
    - test
    - deploy
 
variables:
    # Configure mysql service (https://hub.docker.com/_/mysql/)
    MYSQL_DATABASE: sandbox
    MYSQL_USER: romain
    MYSQL_ROOT_PASSWORD: rom
    MYSQL_PASSWORD: rom
 
before_script:
    # yqq = "oui" a tout, "qq" pour mute le systeme completement
    - apt-get update -yqq && apt-get install -yqq git zlib1g-dev    
    # driver mysql
    - docker-php-ext-install pdo_mysql zip
    # on installe des outils pour avoir des messages d'erreur
    #- pecl install xdebug
    #- docker-php-ext-enable xdebug
    # installer composer en mode silence (-s)
    - curl -sS https://getcomposer.org/installer | php
    # installe les vendors sans messages (-q)
    - php composer.phar install -q
    # on passe en environnement de test
    - cp .env.gitlab .env

build:
    stage: build
    script:
        # drop la base si elle existe
        - php bin/console doctrine:database:drop -q --force
        # création de la base
        - php bin/console doctrine:database:create
        # force l'update de la base
        - php bin/console doctrine:schema:update -q --force
        # envoie les fixtures
        - php bin/console doctrine:fixture:load -q --no-interaction

test:
    # pour tester sur une version spécifique de php, ajouter les images
    #image: php:5.6
    stage: test
    script:
        - php bin/phpunit

deploy:
    stage: deploy
    script:
        - echo "deploy work !"
    when: manual
#         - 'which ssh-agent || ( apt-get update -y && apt-get install openssh-client -y )'
#         - eval $(ssh-agent -s)
#         - ssh-add <(echo "$SSH_PRIVATE_KEY")
#         - mkdir -p ~/.ssh
#         - echo -e "Host *\n\tStrictHostKeyChecking no\n\n" > ~/.ssh/config
```

We dont use maker:migration, because if you do that, you can have some issues with test environment.
By default, maker is used with --dev environment.


#### 2. create .env.gitlab in the project root
```
# permet de passer en environnement de test
# pas d'envoi de mail, gestion differente du cache etc...
APP_ENV=test
APP_SECRET=asecretkey
MAILER_URL=null://localhost
DATABASE_URL=mysql://romain:rom@mysql/sandbox
```

---------------------------------------------------------------------------
## Fixtures
[Top](#Index)

Ressources :
- [Official fixtures documentation](https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html)
- [Faker Data](https://github.com/fzaninotto/Faker)

Prérequis : on a créé une entity "User" avec les attributs suivants :
- firstName
- lastName
- adress

#### 1. install fixtures
```
composer require --dev orm-fixtures
```
#### 2. install faker data
```
composer require fzaninotto/faker
```
#### 3. create fixtures
```
bin/console make:fixtures
```
#### 4. template code
in the new fixtures file :
```php
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	
        // creation du faker
        $faker = \Faker\Factory::create('fr_FR');
    	
        // on boucle pour hydrater des User a partir du faker
        for ($i = 0; $i < 50; $i++)
        {
            $user = new User();
            $user->setName($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setAdress($faker->address);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
```
#### 5. create fake datas and save them
```
bin/console doctrine:fixtures:load
```

---------------------------------------------------------------------------
## Pagination
[Top](#Index)

ressources

[Doctrine pagination documentation](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/pagination.html)

#### 1. Create search function in the entity repository

```php
public function search(?string $firstname, $page = 1, $nbResult): Paginator
{
    $query = $this->createQueryBuilder('u')
        ->where('u.firstname LIKE :firstname')
        ->setParameter('firstname', '%'.$firstname.'%')
        ->setFirstResult(($page - 1) * $nbResult)
        ->setMaxResults($nbResult)
        ->getQuery();

    return new Paginator($query);
}
 ```

#### 2. Get results in the controller and send in the template

```php
/**
* @Route("/users/{page}", name="users")
*/
public function users(Request $request, UserRepository $repository, $page = 1)
{
    // /users/2?firstname=toto
    $firstname = $request->query->get('firstname');
    $paginator = $repository->search($firstname, $page);

    return $this->render(
        'users.html.twig',
        compact('paginator','page')
    );
}
```

#### 3. Twig template

```twig
    <!-- Navbar pour la recherche, en GET, avec le parametre a chercher (ici firstname) -->
    <nav class="navbar navbar-light bg-light">
        <form class="form-inline">
            <input class="form-control mr-sm-2" name="firstname" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </nav>
    <!-- le paginator a une fonction renvoyant le nombre de resultat -->
    {{ paginator|length }} utilisateurs

    <ul class="list-group">
        {% for user in paginator %}
            <li class="list-group-item">
                {{ user.firstname }} {{ user.username }} - {{ user.email }}
            </li>
        {% endfor %}
    </ul>

    <nav aria-label="paginator">
        <ul class="pagination pagination-lg">
            {% set nbPage = ((paginator|length)/10)|round(0,'ceil') %}
            {% for i in 1..nbPage %}
                <li class="page-item {% if (page == i) %}disabled{% endif %}">
                    <a
                        class="page-link"
                        href="{{ path('users', {'page': i}) }}?firstname={{ app.request.query.get('firstname') }}"
                    >
                        {{i}}
                    </a>
                </li>
            {% endfor %}
        </ul>
    </nav>
```

---------------------------------------------------------------------------
## Flash
[Top](#Index)

```php
{% for label, messages in app.flashes %}
    {% for message in messages %}
        {% if label == 'success' %}
            <div class="alert alert-success">
        {% endif %}
        {% if label == 'danger' %}
            <div class="alert alert-danger">
        {% endif %}
        {% if label == 'warning' %}
            <div class="alert alert-warning">
        {% endif %}
        {% if label == 'info' %}
            <div class="alert alert-info">
        {% endif %}
        {% if label == 'primary' %}
            <div class="alert alert-primary">
        {% endif %}
            {{ message }}
        </div>
    {% endfor %}
{% endfor %}

// OR simple version if you dont need more

{% for label, messages in app.flashes %}
    {% for message in messages %}
        <div class="text-center alert alert-{{ label }}">
            {{ message }}
        </div>
    {% endfor %}
{% endfor %}
```
