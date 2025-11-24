<div class="mb-20">
    <a href="<?= $url('/categories/create') ?>" class="btn btn-success">Créer une catégorie</a>
</div>

<div class="table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category['id'] ?></td>
                    <td><?= htmlspecialchars($category['name']) ?></td>
                    <td><?= htmlspecialchars(mb_substr($category['description'] ?? '', 0, 50)) ?><?= mb_strlen($category['description'] ?? '') > 50 ? '...' : '' ?></td>
                    <td><?= date('d/m/Y', strtotime($category['created_at'])) ?></td>
                    <td class="actions">
                        <a href="<?= $url('/admin/categories/' . $category['id'] . '/edit') ?>" class="btn">Modifier</a>
                        <form method="POST" action="<?= $url('/admin/categories/' . $category['id'] . '/delete') ?>" class="inline-form">
                            <button type="submit" class="btn btn-danger" data-confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ? Tous les sujets seront également supprimés.">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

