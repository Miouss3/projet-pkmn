<div class="table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topics as $topic): ?>
                <tr>
                    <td><?= $topic['id'] ?></td>
                    <td>
                        <a href="<?= $url('/topics/' . $topic['id']) ?>">
                            <?= htmlspecialchars($topic['title']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($topic['author_name'] ?? 'Anonyme') ?></td>
                    <td><?= htmlspecialchars($topic['category_name'] ?? '') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($topic['created_at'])) ?></td>
                    <td>
                        <?php if (!empty($topic['deleted_at'])): ?>
                            <span class="badge badge-danger">Supprimé</span>
                        <?php else: ?>
                            <span class="badge badge-success">Actif</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <?php if (empty($topic['deleted_at'])): ?>
                            <form method="POST" action="<?= $url('/admin/topics/' . $topic['id'] . '/delete') ?>" class="inline-form">
                                <button type="submit" class="btn btn-danger" data-confirm="Êtes-vous sûr de vouloir supprimer ce sujet ?">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="<?= $url('/admin/topics/' . $topic['id'] . '/restore') ?>" class="inline-form">
                                <button type="submit" class="btn btn-success">Restaurer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

