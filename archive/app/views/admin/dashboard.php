<div class="stats-grid">
    <div class="stat-card">
        <h3>Utilisateurs</h3>
        <div class="value"><?= $stats['total_users'] ?></div>
    </div>
    <div class="stat-card">
        <h3>Sujets</h3>
        <div class="value"><?= $stats['total_topics'] ?></div>
    </div>
    <div class="stat-card">
        <h3>Catégories</h3>
        <div class="value"><?= $stats['total_categories'] ?></div>
    </div>
    <div class="stat-card">
        <h3>Utilisateurs bannis</h3>
        <div class="value"><?= $stats['banned_users'] ?></div>
    </div>
</div>

<h2 class="section-title">Derniers sujets</h2>
<div class="table">
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentTopics as $topic): ?>
                <tr>
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<h2 class="section-title-large">Derniers utilisateurs</h2>
<div class="table">
    <table>
        <thead>
            <tr>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Date d'inscription</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentUsers as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="badge <?= $user['role'] === 'admin' ? 'badge-info' : 'badge-success' ?>">
                            <?= $user['role'] === 'admin' ? 'Admin' : 'Utilisateur' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($user['banned']): ?>
                            <span class="badge badge-danger">Banni</span>
                        <?php else: ?>
                            <span class="badge badge-success">Actif</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

