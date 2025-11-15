<?php
/** @var string|null $title */
/** @var array<int, array<string, mixed>> $trajets */
/** @var array<string, mixed>|null $user */
/** @var string|null $flash_success */
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Touche pas au Klaxon') ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body>

<?php require __DIR__ . '/partials/header_app.php'; ?>

<div class="container py-4">

    <h1 class="mb-3">Trajets à venir</h1>

    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($flash_success) ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>Agence de départ</th>
            <th>Date de départ</th>
            <th>Agence d'arrivée</th>
            <th>Date d'arrivée</th>
            <th>Places dispo</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($trajets)): ?>
            <tr>
                <td colspan="6">
                    <em>Aucun trajet à venir pour le moment.</em>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($trajets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['depart']) ?></td>
                    <td><?= htmlspecialchars($t['date_depart']) ?></td>
                    <td><?= htmlspecialchars($t['arrivee']) ?></td>
                    <td><?= htmlspecialchars($t['date_arrivee']) ?></td>
                    <td><?= (int)$t['nb_places_disponibles'] ?></td>
                    <td>
                        <?php if (!empty($user)): ?>
                            <!-- Bouton MODALE : infos complémentaires -->
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary mb-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#trajetModal"
                                    data-depart="<?= htmlspecialchars($t['depart'], ENT_QUOTES) ?>"
                                    data-datedepart="<?= htmlspecialchars($t['date_depart'], ENT_QUOTES) ?>"
                                    data-arrivee="<?= htmlspecialchars($t['arrivee'], ENT_QUOTES) ?>"
                                    data-datearrivee="<?= htmlspecialchars($t['date_arrivee'], ENT_QUOTES) ?>"
                                    data-conducteur="<?= htmlspecialchars($t['conducteur_prenom'] . ' ' . $t['conducteur_nom'], ENT_QUOTES) ?>"
                                    data-email="<?= htmlspecialchars($t['conducteur_email'], ENT_QUOTES) ?>"
                                    data-telephone="<?= htmlspecialchars($t['conducteur_tel'], ENT_QUOTES) ?>"
                                    data-placestotales="<?= (int)$t['nb_places_total'] ?>"
                                    data-placesdispo="<?= (int)$t['nb_places_disponibles'] ?>">
                                + d'infos
                            </button>
                        <?php endif; ?>

                        <?php if (!empty($user) && (int)$user['id'] === (int)$t['conducteur_id']): ?>
                            <!-- Modifier -->
                            <a href="/trajets/edit?id=<?= (int)$t['id_trajet'] ?>"
                               class="btn btn-sm btn-warning mb-1">
                                Modifier
                            </a>

                            <!-- Supprimer -->
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

</div>

<!-- MODALE Bootstrap pour les infos complémentaires -->
<div class="modal fade" id="trajetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informations sur le trajet</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p><strong>Départ :</strong> <span id="modalDepart"></span></p>
                <p><strong>Date départ :</strong> <span id="modalDateDepart"></span></p>
                <p><strong>Arrivée :</strong> <span id="modalArrivee"></span></p>
                <p><strong>Date arrivée :</strong> <span id="modalDateArrivee"></span></p>
                <hr>
                <p><strong>Conducteur :</strong> <span id="modalConducteur"></span></p>
                <p><strong>Téléphone :</strong> <span id="modalTelephone"></span></p>
                <p><strong>Email :</strong> <span id="modalEmail"></span></p>
                <p><strong>Places totales :</strong> <span id="modalPlacesTotales"></span></p>
                <p><strong>Places disponibles :</strong> <span id="modalPlacesDispo"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var trajetModal = document.getElementById('trajetModal');

        if (trajetModal) {
            trajetModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                if (!button) return;

                var depart = button.getAttribute('data-depart');
                var dateDepart = button.getAttribute('data-datedepart');
                var arrivee = button.getAttribute('data-arrivee');
                var dateArrivee = button.getAttribute('data-datearrivee');
                var conducteur = button.getAttribute('data-conducteur');
                var email = button.getAttribute('data-email');
                var telephone = button.getAttribute('data-telephone');
                var placesTotales = button.getAttribute('data-placestotales');
                var placesDispo = button.getAttribute('data-placesdispo');

                document.getElementById('modalDepart').textContent = depart || '';
                document.getElementById('modalDateDepart').textContent = dateDepart || '';
                document.getElementById('modalArrivee').textContent = arrivee || '';
                document.getElementById('modalDateArrivee').textContent = dateArrivee || '';
                document.getElementById('modalConducteur').textContent = conducteur || '';
                document.getElementById('modalTelephone').textContent = telephone || '';
                document.getElementById('modalEmail').textContent = email || '';
                document.getElementById('modalPlacesTotales').textContent = placesTotales || '';
                document.getElementById('modalPlacesDispo').textContent = placesDispo || '';
            });
        }
    });
</script>
</body>
</html>
