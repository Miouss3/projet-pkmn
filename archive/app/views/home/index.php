<?php
$title = 'Accueil';
?>

<h2>Catégories du Forum</h2>

<?php if (empty($categories)): ?>
    <p>Aucune catégorie disponible pour le moment.</p>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= $url('/categories/create') ?>" class="btn btn-primary">Créer la première catégorie</a>
    <?php endif; ?>
<?php else: ?>
    <div class="categories-grid">
        <?php foreach ($categories as $category): ?>
            <div class="card">
                <h3>
                    <a href="<?= $url('/categories/' . $category['id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                </h3>
                <?php if (!empty($category['description'])): ?>
                    <p><?= htmlspecialchars($category['description']) ?></p>
                <?php endif; ?>
                <div class="meta">
                    <?= $category['topic_count'] ?? 0 ?> sujets • 
                    <?= $category['post_count'] ?? 0 ?> messages
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

