<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lernplattform</title>
    <style>
        <?php echo file_get_contents(ROOT_PATH . 'assets/css/style.css'); ?>
        .tab-navigation {
            overflow: hidden;
            background-color: #f1f1f1;
        }

        .tab-navigation a {
            float: left;
            display: block;
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            cursor: pointer;
        }

        .tab-navigation a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Lernplattform</h1>
        </header>
        <nav class="tab-navigation">
            <?php
            $sessionManager = new \SessionManager();
            if ($sessionManager->isLoggedIn()) {
                $role = $sessionManager->getUserRole();
                ?>
                <?php if ($role === 'admin'): ?>
                    <a href="/lernplattform/admin/dashboard">Admin</a>
                    <a href="/lernplattform/trainings/create">Schulungen anlegen</a>
                    <a href="/lernplattform/trainings/my">Meine Schulungen</a>
                    <a href="/lernplattform/certificates/my">Meine Zertifikate</a>
                <?php elseif ($role === 'trainer'): ?>
                    <a href="/lernplattform/trainings/create">Schulungen anlegen</a>
                    <a href="/lernplattform/trainings/my">Meine Schulungen</a>
                    <a href="/lernplattform/certificates/my">Meine Zertifikate</a>
                <?php elseif ($role === 'user'): ?>
                    <a href="/lernplattform/trainings/my">Meine Schulungen</a>
                    <a href="/lernplattform/certificates/my">Meine Zertifikate</a>
                <?php endif; ?>
                <a href="/lernplattform/logout" style="float: right;">Abmelden</a>
                <?php
            } else {
                ?>
                <a href="/lernplattform/register">Registrieren</a>
                <a href="/lernplattform/login" style="float: right;">Einloggen</a>
                <?php
            }
            ?>
        </nav>
        <main>
            <?php echo $content ?? ''; ?>
        </main>
        <footer>
            <p>&copy; <?php echo date("Y"); ?> Deine Firma</p>
        </footer>
    </div>
</body>
</html>