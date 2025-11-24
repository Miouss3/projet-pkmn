<div class="table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <form method="POST" action="<?= $url('/admin/users/' . $user['id'] . '/role') ?>" class="inline-form">
                            <select name="role" class="select-inline role-select">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <?php if ($user['banned']): ?>
                            <span class="badge badge-danger">
                                Banni
                                <?php if (!empty($user['banned_until'])): ?>
                                    (jusqu'au <?= date('d/m/Y', strtotime($user['banned_until'])) ?>)
                                <?php else: ?>
                                    (permanent)
                                <?php endif; ?>
                            </span>
                            <?php if (!empty($user['ban_reason'])): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($user['ban_reason']) ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge badge-success">Actif</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                    <td class="actions">
                        <?php if ($user['banned']): ?>
                            <form method="POST" action="<?= $url('/admin/users/' . $user['id'] . '/unban') ?>" class="inline-form">
                                <button type="submit" class="btn btn-success">Débannir</button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn btn-danger ban-user-btn" data-user-id="<?= $user['id'] ?>" data-username="<?= htmlspecialchars($user['username']) ?>">Bannir</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal pour bannir un utilisateur -->
<div id="banModal" class="modal">
    <div class="modal-content">
        <h2>Bannir l'utilisateur <span id="banUsername"></span></h2>
        <form method="POST" id="banForm" class="modal-form" data-base-url="<?= $url('/admin/users/') ?>">
            <div class="form-group">
                <label for="reason">Raison du bannissement</label>
                <textarea id="reason" name="reason" placeholder="Optionnel"></textarea>
            </div>
            <div class="form-group">
                <label for="until">Bannir jusqu'au (optionnel, laisser vide pour bannissement permanent)</label>
                <input type="datetime-local" id="until" name="until">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-danger">Bannir</button>
                <button type="button" class="btn" id="closeBanModal">Annuler</button>
            </div>
        </form>
    </div>
</div>

