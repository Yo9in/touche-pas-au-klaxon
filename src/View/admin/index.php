<?php
/** @var array|null $user */
/** @var int $nbUsers */
/** @var int $nbAgences */
/** @var int $nbTrajets */
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Admin') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
          <link rel="stylesheet" href="/css/theme.css">
</head>
<body class="container py-4">

<h1 class="mb-4">Tableau de bord administrateur</h1>

<p class="mb-3">
    Connecté en tant que
    <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
    (<?= htmlspecialchars($user['role']) ?>)
</p>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs</h5>
                <p class="card-text"><?= (int)$nbUsers ?> utilisateurs enregistrés.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h5 class="card-title">Agences</h5>
                <p class="card-text"><?= (int)$nbAgences ?> agences disponibles.</p>
                <a href="/admin/agences" class="btn btn-sm btn-success">Gérer les agences</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-body">
                <h5 class="card-title">Trajets</h5>
                <p class="card-text"><?= (int)$nbTrajets ?> trajets enregistrés.</p>
            </div>
        </div>
    </div>
</div>

<p class="mt-4">
    <a href="/" class="btn btn-link">&larr; Retour au site</a>
</p>

</body>
</html>
