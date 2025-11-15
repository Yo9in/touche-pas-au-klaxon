<?php
/** @var string|null $title */
/** @var array<string, mixed> $trajet */
/** @var array<string, mixed>|null $user */
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Détail du trajet') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body class="container py-4">

<h1 class="mb-4">Détail du trajet</h1>

<p><strong>Départ :</strong> <?= htmlspecialchars($trajet['depart_nom']) ?></p>
<p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['arrivee_nom']) ?></p>
<p><strong>Date départ :</strong> <?= htmlspecialchars($trajet['date_heure_depart']) ?></p>
<p><strong>Date arrivée :</strong> <?= htmlspecialchars($trajet['date_heure_arrivee']) ?></p>
<p><strong>Places totales :</strong> <?= (int)$trajet['nb_places_total'] ?></p>
<p><strong>Places disponibles :</strong> <?= (int)$trajet['nb_places_disponibles'] ?></p>

<?php if (!empty($trajet['commentaire'])): ?>
    <p><strong>Commentaire :</strong> <?= nl2br(htmlspecialchars($trajet['commentaire'])) ?></p>
<?php endif; ?>

<hr>

<?php if (!empty($user)): ?>
    <h2 class="h5 mt-3">Conducteur</h2>
    <p><strong>Nom :</strong> <?= htmlspecialchars($trajet['conducteur_prenom'] . ' ' . $trajet['conducteur_nom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($trajet['conducteur_email']) ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($trajet['conducteur_tel']) ?></p>
<?php else: ?>
    <div class="alert alert-info mt-3">
        Connectez-vous pour voir les coordonnées du conducteur.
    </div>
<?php endif; ?>

<p class="mt-4">
    <a href="/" class="btn btn-link">&larr; Retour à la liste</a>
</p>

</body>
</html>
