<?php
$title = htmlspecialchars($category['name']);
?>

<h2><?= htmlspecialchars($category['name']) ?></h2>
<?php if (!empty($category['description'])): ?>
    <p class="text-muted mb-20"><?= htmlspecialchars($category['description']) ?></p>
<?php endif; ?>

<div class="mb-20">
    <a href="<?= $url('/topics/create/' . $category['id']) ?>" class="btn btn-primary">Créer un nouveau sujet</a>
</div>

<?php if (empty($topics)): ?>
    <p>Aucun sujet dans cette catégorie pour le moment.</p>
<?php else: ?>
    <ul class="topics-list">
        <?php foreach ($topics as $topic): ?>
            <li class="card">
                <h3>
                    <a href="<?= $url('/topics/' . $topic['id']) ?>">
                        <?= htmlspecialchars($topic['title']) ?>
                    </a>
                </h3>
                <div class="meta">
                    Par <?= htmlspecialchars($topic['author_name'] ?? 'Anonyme') ?> • 
                    <?= date('d/m/Y à H:i', strtotime($topic['created_at'])) ?> • 
                    <?= $topic['post_count'] ?? 0 ?> message(s)
                    <?php if (!empty($topic['last_post_date'])): ?>
                        • Dernier message : <?= date('d/m/Y à H:i', strtotime($topic['last_post_date'])) ?>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= $url('/') ?>" class="btn">← Retour à l'accueil</a>

