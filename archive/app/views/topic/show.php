<?php
$title = htmlspecialchars($topic['title']);
?>

<h2><?= htmlspecialchars($topic['title']) ?></h2>
<div class="meta mb-20">
    Catégorie : <a href="<?= $url('/categories/' . $topic['category_id']) ?>"><?= htmlspecialchars($topic['category_name'] ?? '') ?></a> • 
    Par <?= htmlspecialchars($topic['author_name'] ?? 'Anonyme') ?> • 
    <?= date('d/m/Y à H:i', strtotime($topic['created_at'])) ?>
</div>

<div class="post-item">
    <div class="post-header">
        <span class="post-author"><?= htmlspecialchars($topic['author_name'] ?? 'Anonyme') ?></span>
        <span class="post-date"><?= date('d/m/Y à H:i', strtotime($topic['created_at'])) ?></span>
    </div>
    <div class="post-content">
        <?= nl2br(htmlspecialchars($topic['content'])) ?>
    </div>
</div>

<h3 class="section-title-large">Réponses (<?= count($posts) ?>)</h3>

<?php if (empty($posts)): ?>
    <p>Aucune réponse pour le moment.</p>
<?php else: ?>
    <ul class="posts-list">
        <?php foreach ($posts as $post): ?>
            <li class="post-item">
                <div class="post-header">
                    <span class="post-author"><?= htmlspecialchars($post['author_name'] ?? 'Anonyme') ?></span>
                    <span class="post-date"><?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="post-content">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="divider-top">
        <h3>Répondre au sujet</h3>
        <form method="POST" action="<?= $url('/posts/store') ?>">
            <input type="hidden" name="topic_id" value="<?= $topic['id'] ?>">
            <div class="form-group">
                <label for="content">Votre message</label>
                <textarea id="content" name="content" required placeholder="Écrivez votre réponse ici..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Poster la réponse</button>
        </form>
    </div>
<?php else: ?>
    <p class="mt-30">
        <a href="<?= $url('/login') ?>">Connectez-vous</a> pour répondre à ce sujet.
    </p>
<?php endif; ?>

<a href="<?= $url('/categories/' . $topic['category_id']) ?>" class="btn">← Retour à la catégorie</a>

