<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Touche pas au Klaxon') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Trajets à venir</h1>

    <div>
        <?php if (!empty($user)): ?>
        <?php if ($user['role'] === 'admin'): ?>
                <a href="/admin" class="btn btn-outline-dark btn-sm me-2">Admin</a>
            <?php endif; ?>
            <a href="/trajets/create" class="btn btn-success btn-sm me-2">Proposer un trajet</a>
            <span class="me-2">
                Connecté en tant que
                <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                (<?= htmlspecialchars($user['role']) ?>)
            </span>
            <a href="/logout" class="btn btn-outline-secondary btn-sm">Se déconnecter</a>
        <?php else: ?>
            <a href="/login" class="btn btn-primary btn-sm">Se connecter</a>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($flash_success)): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($flash_success) ?>
    </div>
<?php endif; ?>

<table class="table table-striped">
    <thead>
    <tr>
        <th>Départ</th>
        <th>Arrivée</th>
        <th>Date départ</th>
        <th>Places dispo</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($trajets)): ?>
        <tr>
            <td colspan="5"><em>Aucun trajet à venir pour le moment.</em></td>
        </tr>
    <?php else: ?>
        <?php foreach ($trajets as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['depart']) ?></td>
                <td><?= htmlspecialchars($t['arrivee']) ?></td>
                <td><?= htmlspecialchars($t['date_depart']) ?></td>
                <td><?= (int)$t['nb_places_disponibles'] ?></td>
                <td>
                    <!-- + d'infos : accessible à tous -->
                    <a href="/trajets/show?id=<?= (int)$t['id_trajet'] ?>"
                       class="btn btn-sm btn-outline-primary mb-1">
                        + d'infos
                    </a>

                    <?php if (!empty($user) && (int)$user['id'] === (int)$t['conducteur_id']): ?>
                        <!-- Bouton Modifier pour le propriétaire -->
                        <a href="/trajets/edit?id=<?= (int)$t['id_trajet'] ?>"
                           class="btn btn-sm btn-warning mb-1">
                            Modifier
                        </a>

                        <!-- Bouton Supprimer pour le propriétaire -->
                        <form action="/trajets/delete" method="post" class="d-inline"
                              onsubmit="return confirm('Supprimer ce trajet ?');">
                            <input type="hidden" name="id_trajet" value="<?= (int)$t['id_trajet'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">
                                Supprimer
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
