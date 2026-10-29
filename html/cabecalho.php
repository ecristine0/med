<?php
$logado = isset($_SESSION['pessoa']);
?>

<header class="header">
    <div class="container">

        <div class="logo">
            <img src="foto/logo.png" alt="MedTime">
            <span>MedTime</span>
        </div>

        <nav class="menu">
            <a href="index.php" class="btn-home">Início</a>

            <?php if ($logado): ?>
                <a href="logout.php" class="btn-login">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Entrar</a>
            <?php endif; ?>
        </nav>

    </div>
</header>
