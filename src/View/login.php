<?php
/** @var string|null $title */
/** @var string|null $error */

// On récupère l'utilisateur éventuellement en session pour le header
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Connexion') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body>

<?php require __DIR__ . '/../partials/header_app.php'; ?>

<div class="container py-4">

    <h1 class="mb-4">Connexion</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/login" class="col-md-4">

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Se connecter
        </button>
        <a href="/" class="btn btn-link">Annuler</a>

    </form>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
