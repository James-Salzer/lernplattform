<h1>Benutzerverwaltung</h1>

<table class="blueTable">
    <thead>
        <tr>
            <th>Benutzername</th>
            <th>Rolle</th>
            <th>Status</th>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['rolle_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['status_name']); ?></td>
                    <td>
                        <a href="/admin/users/edit/<?php echo $user['id']; ?>">Bearbeiten</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Keine Benutzer gefunden.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
