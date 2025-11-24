<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - <?= $title ?? 'Dashboard' ?></title>
    <link rel="stylesheet" href="<?= $url('/assets/css/admin.css') ?>">
    <script src="<?= $url('/assets/js/admin.js') ?>" defer></script>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Administration</h2>
            <nav>
                <a href="<?= $url('/admin') ?>" class="<?= ($title ?? '') === 'Dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="<?= $url('/admin/topics') ?>" class="<?= ($title ?? '') === 'Sujets' ? 'active' : '' ?>">Sujets</a>
                <a href="<?= $url('/admin/posts') ?>" class="<?= ($title ?? '') === 'Messages' ? 'active' : '' ?>">Messages</a>
                <a href="<?= $url('/admin/categories') ?>" class="<?= ($title ?? '') === 'Catégories' ? 'active' : '' ?>">Catégories</a>
                <a href="<?= $url('/admin/users') ?>" class="<?= ($title ?? '') === 'Utilisateurs' ? 'active' : '' ?>">Utilisateurs</a>
                <a href="<?= $url('/') ?>" class="return-link">← Retour au forum</a>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1><?= $title ?? 'Dashboard' ?></h1>
                <div class="user-info">
                    <span><?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
                    <a href="<?= $url('/logout') ?>" class="btn btn-danger">Déconnexion</a>
                </div>
            </div>
            
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

