<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1) ROUTER ULTRA SIMPLE
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

function render(string $html): void {
    echo "<!doctype html><meta charset='utf-8'>";
    echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>";
    echo "<div class='container py-4'>{$html}</div>";
}

if ($uri === '/') {
    // Page d'accueil
    render("
        <h1>Trajets à venir</h1>
        <p class='text-muted'>Bienvenue sur <strong>Touche pas au Klaxon</strong>.</p>
        <p><a class='btn btn-primary' href='/trajets'>Voir les trajets</a></p>
    ");
    exit;
}

if ($uri === '/trajets') {
    require_once __DIR__.'/db.php';
    $pdo = db();

    $sql = "
        SELECT t.date_heure_depart AS date,
               ad.nom AS depart,
               aa.nom AS arrivee
        FROM trajet t
        JOIN agence ad ON ad.id_agence = t.agence_depart_id
        JOIN agence aa ON aa.id_agence = t.agence_arrivee_id
        WHERE t.date_heure_depart > NOW()
          AND t.nb_places_disponibles > 0
        ORDER BY t.date_heure_depart ASC
    ";
    $trajets = $pdo->query($sql)->fetchAll();

    $rows = "";
    foreach ($trajets as $t) {
        $rows .= "<tr>
            <td>".htmlspecialchars($t['depart'])."</td>
            <td>".htmlspecialchars($t['arrivee'])."</td>
            <td>".htmlspecialchars($t['date'])."</td>
        </tr>";
    }

    render("
        <h1>Trajets à venir</h1>
        <table class='table table-striped'>
            <thead><tr><th>Départ</th><th>Arrivée</th><th>Date</th></tr></thead>
            <tbody>".($rows ?: "<tr><td colspan='3'><em>Aucun trajet pour le moment.</em></td></tr>")."</tbody>
        </table>
        <p><a href='/'>&larr; Retour</a></p>
    ");
    exit;
}



// 404
http_response_code(404);
render("<h1>404</h1><p>La page <code>{$uri}</code> n'existe pas.</p><p><a href='/'>&larr; Accueil</a></p>");
