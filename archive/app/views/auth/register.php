<?php
$title = 'Inscription';
?>

<h2>Inscription</h2>

<form method="POST" action="<?= $url('/register') ?>" class="form-container-small">
    <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required minlength="6">
    </div>
    
    <div class="form-group">
        <label for="password_confirm">Confirmer le mot de passe</label>
        <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
    </div>
    
    <button type="submit" class="btn btn-primary">S'inscrire</button>
    <a href="<?= $url('/login') ?>" class="btn">Déjà un compte ? Se connecter</a>
</form>

