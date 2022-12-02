# LiveCoding TDD

## Generateur

`console make:entity` : Génére une nouvelle entité
`console make:migration` : Génére la migration des entités
`console make:controller ProductController` : Génère un controller
`console make:fixtures` : Crée un fichier de fixture
`console make:test` : Généré un fichier de test

## Commandes

`console doctrine:migrations:migrate` : Exécute la migration
`console doctrine:schema:create` : Crée le schéma de db
`console --env=test doctrine:fixtures:load` : Load la fixture sur l'env de test
`phpunit` : Exécute les tests

Add `--env=test` to execute in test env
