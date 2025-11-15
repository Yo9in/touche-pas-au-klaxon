<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Gestion des agences') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
          <link rel="stylesheet" href="/css/theme.css">
</head>
<body class="container py-4">

<h1 class="mb-4">Gestion des agences</h1>

<p>
    <a href="/admin" class="btn btn-link">&larr; Retour au tableau de bord</a>
</p>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<h2 class="h4 mt-4">Ajouter une agence</h2>

<form action="/admin/agences/store" method="post" class="row g-3 col-md-4 mb-4">
    <div class="col-12">
        <label for="nom" class="form-label">Nom de l'agence</label>
        <input type="text" name="nom" id="nom" class="form-control" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </div>
</form>

<h2 class="h4 mt-4">Liste des agences</h2>

<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($agences)): ?>
        <tr>
            <td colspan="3"><em>Aucune agence.</em></td>
        </tr>
    <?php else: ?>
        <?php foreach ($agences as $ag): ?>
            <tr>
                <td><?= (int)$ag['id_agence'] ?></td>
                <td><?= htmlspecialchars($ag['nom']) ?></td>
                <td>
                    <form action="/admin/agences/delete" method="post" class="d-inline"
                          onsubmit="return confirm('Supprimer cette agence ?');">
                        <input type="hidden" name="id_agence" value="<?= (int)$ag['id_agence'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
