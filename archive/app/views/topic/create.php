<?php
$title = 'Créer un sujet';
?>

<h2>Créer un nouveau sujet</h2>

<form method="POST" action="<?= $url('/topics/store') ?>" class="form-container">
    <div class="form-group">
        <label for="category_id">Catégorie</label>
        <select id="category_id" name="category_id" required>
            <option value="">Sélectionnez une catégorie</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" 
                    <?= ($selectedCategory && $selectedCategory['id'] == $category['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="title">Titre du sujet</label>
        <input type="text" id="title" name="title" required placeholder="Ex: Comment obtenir Pikachu ?">
    </div>
    
    <div class="form-group">
        <label for="content">Contenu</label>
        <textarea id="content" name="content" required placeholder="Décrivez votre sujet en détail..."></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Créer le sujet</button>
    <a href="<?= $url('/') ?>" class="btn">Annuler</a>
</form>

