<div class="table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Contenu</th>
                <th>Auteur</th>
                <th>Sujet</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= $post['id'] ?></td>
                    <td class="text-ellipsis">
                        <?= htmlspecialchars(mb_substr($post['content'], 0, 100)) ?><?= mb_strlen($post['content']) > 100 ? '...' : '' ?>
                    </td>
                    <td><?= htmlspecialchars($post['author_name'] ?? 'Anonyme') ?></td>
                    <td>
                        <a href="<?= $url('/topics/' . $post['topic_id']) ?>">
                            <?= htmlspecialchars(mb_substr($post['topic_title'] ?? '', 0, 30)) ?>
                        </a>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
                    <td>
                        <?php if (!empty($post['deleted_at'])): ?>
                            <span class="badge badge-danger">Supprimé</span>
                        <?php else: ?>
                            <span class="badge badge-success">Actif</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <?php if (empty($post['deleted_at'])): ?>
                            <form method="POST" action="<?= $url('/admin/posts/' . $post['id'] . '/delete') ?>" class="inline-form">
                                <button type="submit" class="btn btn-danger" data-confirm="Êtes-vous sûr de vouloir supprimer ce message ?">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="<?= $url('/admin/posts/' . $post['id'] . '/restore') ?>" class="inline-form">
                                <button type="submit" class="btn btn-success">Restaurer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

