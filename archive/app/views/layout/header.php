<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokespheara - <?= $title ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="<?= $url('/assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <header>
            <h1>Pokespheara</h1>
            <nav>
                <a href="<?= $url('/') ?>">Accueil</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= $url('/topics/create') ?>">Créer un sujet</a>
                    <a href="<?= $url('/categories/create') ?>">Créer une catégorie</a>
                    <?php
                    $userModel = new \App\Models\User();
                    if ($userModel->isAdmin($_SESSION['user_id'])):
                    ?>
                        <a href="<?= $url('/admin') ?>" class="btn-admin">Administration</a>
                    <?php endif; ?>
                    <div class="user-info">
                        <span><?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
                        <a href="<?= $url('/logout') ?>" class="btn">Déconnexion</a>
                    </div>
                <?php else: ?>
                    <a href="<?= $url('/login') ?>" class="btn">Connexion</a>
                    <a href="<?= $url('/register') ?>" class="btn btn-primary">Inscription</a>
                <?php endif; ?>
            </nav>
        </header>
        <main>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

