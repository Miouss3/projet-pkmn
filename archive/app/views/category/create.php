<?php
$title = 'Créer une catégorie';
?>

<h2>Créer une nouvelle catégorie</h2>

<form method="POST" action="<?= $url('/categories/store') ?>" class="form-container-medium">
    <div class="form-group">
        <label for="name">Nom de la catégorie</label>
        <input type="text" id="name" name="name" required placeholder="Ex: Général, Échanges, Stratégie...">
    </div>
    
    <div class="form-group">
        <label for="description">Description (optionnel)</label>
        <textarea id="description" name="description" placeholder="Décrivez le sujet de cette catégorie..."></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Créer la catégorie</button>
    <a href="<?= $url('/') ?>" class="btn">Annuler</a>
</form>

