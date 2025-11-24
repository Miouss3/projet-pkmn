<?php require __DIR__ . '/../../app/views/header.php'; ?>
<?php require __DIR__ . '/../../assets/templates/head.php'; ?>


<video id="background-video" autoplay loop muted playsinline>
    <source src="/assets/img/background.mp4" type="video/mp4">
</video>

    <div class="create-container">
        <h1>Créer un compte</h1>

        <form action="/users/store" method="post">
            <label>Nom (pseudo) :
                <input type="text" name="nom" required>
            </label>

            <label>Email :
                <input type="email" name="email" required>
            </label>

            <label>Mot de passe :
                <input type="password" name="password" required>
            </label>

            <button type="submit">Créer</button>

            <p>Déjà un compte ? <a href="/login">Se connecter</a></p>
        </form>
    </div>

</body>
</html>
