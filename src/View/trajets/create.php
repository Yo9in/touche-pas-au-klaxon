<?php
/** @var array $agences */
/** @var array $errors */
/** @var array $old */
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Proposer un trajet') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
          <link rel="stylesheet" href="/css/theme.css">
</head>
<body class="container py-4">

<h1 class="mb-4">Proposer un trajet</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="/trajets" class="row g-3 col-md-6">

    <div class="col-12">
        <label for="agence_depart_id" class="form-label">Agence de départ</label>
        <select name="agence_depart_id" id="agence_depart_id" class="form-select" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($agences as $ag): ?>
                <option value="<?= (int)$ag['id_agence'] ?>"
                    <?= (!empty($old['agence_depart_id']) && (int)$old['agence_depart_id'] === (int)$ag['id_agence']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ag['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label for="agence_arrivee_id" class="form-label">Agence d'arrivée</label>
        <select name="agence_arrivee_id" id="agence_arrivee_id" class="form-select" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($agences as $ag): ?>
                <option value="<?= (int)$ag['id_agence'] ?>"
                    <?= (!empty($old['agence_arrivee_id']) && (int)$old['agence_arrivee_id'] === (int)$ag['id_agence']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ag['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="date_heure_depart" class="form-label">Date/heure de départ</label>
        <input type="datetime-local" name="date_heure_depart" id="date_heure_depart"
               class="form-control"
               value="<?= htmlspecialchars($old['date_heure_depart'] ?? '') ?>" required>
    </div>

    <div class="col-md-6">
        <label for="date_heure_arrivee" class="form-label">Date/heure d'arrivée</label>
        <input type="datetime-local" name="date_heure_arrivee" id="date_heure_arrivee"
               class="form-control"
               value="<?= htmlspecialchars($old['date_heure_arrivee'] ?? '') ?>" required>
    </div>

    <div class="col-md-6">
        <label for="nb_places_total" class="form-label">Nombre total de places</label>
        <input type="number" name="nb_places_total" id="nb_places_total"
               class="form-control" min="1"
               value="<?= htmlspecialchars($old['nb_places_total'] ?? '4') ?>" required>
    </div>

    <div class="col-md-6">
        <label for="nb_places_disponibles" class="form-label">Places disponibles</label>
        <input type="number" name="nb_places_disponibles" id="nb_places_disponibles"
               class="form-control" min="0"
               value="<?= htmlspecialchars($old['nb_places_disponibles'] ?? '4') ?>" required>
    </div>

    <div class="col-12">
        <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
        <textarea name="commentaire" id="commentaire" class="form-control" rows="3"><?= htmlspecialchars($old['commentaire'] ?? '') ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/" class="btn btn-link">Annuler</a>
    </div>

</form>

</body>
</html>
