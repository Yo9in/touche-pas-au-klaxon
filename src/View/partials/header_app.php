<?php
/** @var array|null $user */
?>
<header class="mb-4 border-bottom bg-white shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <!-- GAUCHE : nom de l'application -->
        <a href="/" class="navbar-brand fw-bold text-primary text-decoration-none">
            Touche pas au Klaxon
        </a>

        <!-- DROITE : selon l'état de connexion -->
        <div>
            <?php if (empty($user)): ?>
                <!-- Utilisateur NON connecté -->
                <a href="/login" class="btn btn-primary btn-sm">
                    Se connecter
                </a>
            <?php else: ?>
                <!-- Utilisateur connecté -->
                <?php if ($user['role'] === 'admin'): ?>
                    <!-- Petit bonus : lien vers admin, mais ce n'est pas dans la consigne stricte -->
                    <a href="/admin" class="btn btn-outline-dark btn-sm me-2">
                        Tableau de bord admin
                    </a>
                <?php endif; ?>

                <a href="/trajets/create" class="btn btn-success btn-sm me-2">
                    Proposer un trajet
                </a>

                <span class="me-2">
                    <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                </span>

                <a href="/logout" class="btn btn-outline-secondary btn-sm">
                    Se déconnecter
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
