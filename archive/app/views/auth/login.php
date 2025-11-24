<?php
$title = 'Connexion';
?>

<h2>Connexion</h2>

<form method="POST" action="<?= $url('/login') ?>" class="form-container-small">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Se connecter</button>
    <a href="<?= $url('/register') ?>" class="btn">Pas encore de compte ? S'inscrire</a>
</form>

