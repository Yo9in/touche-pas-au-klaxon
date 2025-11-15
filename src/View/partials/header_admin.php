<?php
/** @var array $user */
?>
<header class="mb-4 border-bottom bg-white shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <!-- GAUCHE : nom de l'appli, lien vers dashboard -->
        <a href="/admin" class="navbar-brand fw-bold text-primary text-decoration-none">
            Touche pas au Klaxon
        </a>

        <!-- DROITE : menu horizontal + bouton déconnexion -->
        <nav class="d-flex align-items-center">
            <!-- Menu admin : adapte selon ton sujet -->
            <a href="/admin" class="nav-link px-2">Tableau de bord</a>
            <a href="/admin/agences" class="nav-link px-2">Agences</a>
            <!-- Si tu as d'autres pages admin, ajoute-les ici -->
            <!-- <a href="/admin/trajets" class="nav-link px-2">Trajets</a> -->
            <!-- <a href="/admin/utilisateurs" class="nav-link px-2">Utilisateurs</a> -->

            <span class="px-3 border-start mx-3">
                <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
            </span>

            <a href="/logout" class="btn btn-outline-secondary btn-sm">
                Se déconnecter
            </a>
        </nav>
    </div>
</header>
