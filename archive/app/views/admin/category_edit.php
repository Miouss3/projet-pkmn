<form method="POST" action="<?= $url('/admin/categories/' . $category['id'] . '/update') ?>" class="form-container-medium">
    <div class="form-group">
        <label for="name">Nom de la catégorie</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Mettre à jour</button>
    <a href="<?= $url('/admin/categories') ?>" class="btn">Annuler</a>
</form>

