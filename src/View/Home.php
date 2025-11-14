<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Touche pas au Klaxon') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Trajets à venir</h1>

    <div>
        <?php if (!empty($user)): ?>
            <span class="me-3">
                Connecté en tant que <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                (<?= htmlspecialchars($user['role']) ?>)
            </span>
            <a href="/logout" class="btn btn-outline-secondary btn-sm">Se déconnecter</a>
        <?php else: ?>
            <a href="/login" class="btn btn-primary btn-sm">Se connecter</a>
        <?php endif; ?>
    </div>
</div>

<table class="table table-striped">
    <thead>
    <tr>
        <th>Départ</th>
        <th>Arrivée</th>
        <th>Date départ</th>
        <th>Places dispo</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($trajets)): ?>
        <tr>
            <td colspan="4"><em>Aucun trajet à venir pour le moment.</em></td>
        </tr>
    <?php else: ?>
        <?php foreach ($trajets as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['depart']) ?></td>
                <td><?= htmlspecialchars($t['arrivee']) ?></td>
                <td><?= htmlspecialchars($t['date_depart']) ?></td>
                <td><?= (int)$t['nb_places_disponibles'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
