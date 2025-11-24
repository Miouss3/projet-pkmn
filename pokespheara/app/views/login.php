<?php require __DIR__ . '/../../app/views/header.php'; ?>
<?php require __DIR__ . '/../../assets/templates/head.php'; ?>

<video id="background-video" autoplay loop muted playsinline>
    <source src="/assets/img/background.mp4" type="video/mp4">
</video>
<body>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<div class="create-container">
    <h1>Connexion</h1>
    <form class="labelogin" method="POST" action="/login">
        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required><br><br>

        <label>Mot de passe :</label>
        <input  type="password" name="password" required><br><br>
                
        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore de compte ? <a href="/users/create">Créer un compte</a></p>
    </div>
</body>
</html>
