# Tests Unitaires

Ce dossier contient les tests unitaires pour le forum Pokémon.

## Installation

Pour installer PHPUnit, exécutez :

```bash
composer install
```

## Exécuter les tests

Pour exécuter tous les tests :

```bash
vendor/bin/phpunit
```

Pour exécuter un fichier de test spécifique :

```bash
vendor/bin/phpunit tests/Unit/RouterTest.php
```

## Structure des tests

Les tests sont organisés dans le dossier `tests/Unit/` :

- `RouterTest.php` - Tests pour le Router
- `DatabaseTest.php` - Tests pour la connexion à la base de données
- `UserModelTest.php` - Tests pour le modèle User
- `CategoryModelTest.php` - Tests pour le modèle Category
- `TopicModelTest.php` - Tests pour le modèle Topic
- `PostModelTest.php` - Tests pour le modèle Post

## Écrire de nouveaux tests

Pour écrire un nouveau test :

1. Créez un fichier dans `tests/Unit/` avec le nom `NomDeLaClasseTest.php`
2. Étendez la classe `PHPUnit\Framework\TestCase`
3. Créez des méthodes commençant par `test` pour chaque test

Exemple :

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\MaClasse;

class MaClasseTest extends TestCase
{
    public function testMaMethode(): void
    {
        // Arrange : préparez les données
        $objet = new MaClasse();
        
        // Act : exécutez l'action à tester
        $resultat = $objet->maMethode();
        
        // Assert : vérifiez le résultat
        $this->assertEquals('valeur attendue', $resultat);
    }
}
```

## Notes importantes

- Les tests nécessitant une base de données peuvent nécessiter une configuration de test
- Certains tests peuvent être "skippés" si la base de données n'est pas disponible
- Les tests sont écrits de manière simple et pédagogique pour les débutants

