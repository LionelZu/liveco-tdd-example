# LiveCoding TDD

Dans les commandes ci-dessous, les alias suivants sont utilisés :

```
alias composer="php composer.phar"
alias symf="symfony"
alias console="php bin/console"
alias phpunit="php bin/phpunit"
```

## Generateur

- `console make:entity` : Génére une nouvelle entité
- `console make:migration` : Génére la migration des entités
- `console make:controller ProductController` : Génère un controller
- `console make:fixtures` : Crée un fichier de fixture
- `console make:test` : Généré un fichier de test

## Commandes

- `console doctrine:migrations:migrate` : Exécute la migration
- `console doctrine:schema:create` : Crée le schéma de db
- `console --env=test doctrine:fixtures:load` : Load la fixture sur l'env de test
- `phpunit` : Exécute les tests

Add `--env=test` to execute in test env

## Memo

### Récupérer la bdd dans un test

```php
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->createNativeQuery('DELETE FROM Achat', new ResultSetMapping())->execute();
        $this->entityManager->createNativeQuery('DELETE FROM Adherent', new ResultSetMapping())->execute();

        $this->entityManager->flush();
        $this->entityManager->close();
        $this->entityManager = null;
    }
```

### Validation

```php
use Symfony\Component\Validator\Validator\ValidatorInterface;
ValidatorInterface $validator


$errors = $validator->validate(
    $beginStr,
    [ new NotBlank(), new Constraints\Date() ]
);

```
