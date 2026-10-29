<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php require_once "cabecalho.php"; ?>
    <main>
        <div class="hero">
            <div class="container">
                <div class="texto">
                    <h5>Cuide da sua saúde com facilidade</h5>
                    <h2>
                        Gerencie seus
                        <span>medicamentos</span>
                        de forma simples
                    </h2>

                    <p>
                        O MedTime ajuda você a manter o controle da sua medicação,
                        garantindo que cada dose seja tomada no momento certo.
                    </p>
                    <a href="cadastrar_med.php" class="hero-button">Começar Agora →</a>
                </div>

                <div class="imagem">
                    <img src="foto/remediocapa.png" alt="Imagem medicamentos">
                </div>
            </div>
        </div>

        <div class="estatisticas">
            <div class="container">
                <div>
                    <h3>98%</h3>
                    <p>Taxa de Adesão</p>
                </div>

                <div>
                    <h3>24/7</h3>
                    <p>Monitoramento</p>
                </div>

                <div>
                    <h3>100%</h3>
                    <p>Seguro</p>
                </div>
            </div>
        </div>

        <div class="funcionalidades">
            <div class="container">
                <h2>Por que escolher o MedTime?</h2>

                <p class="subtitulo">
                    Funcionalidades pensadas para sua saúde
                </p>

                <div class="cards">
                    <div class="card">
                        <div class="card-titulo">
                            <img src="foto/reloginho.jpg" alt="Ícone lembrete">
                            <h4>Lembretes Inteligentes</h4>
                        </div>

                        <p>
                            Nunca esqueça seus medicamentos
                            com notificações personalizadas.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-titulo">
                            <img src="foto/calendariozinho.jpg" alt="Ícone calendário">
                            <h4>Calendário de Doses</h4>
                        </div>

                        <p>
                            Visualize sua programação de medicamentos
                            em um só lugar.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-titulo">
                            <img src="foto/segurancinha.jpg" alt="Ícone segurança">
                            <h4>Seguro e Privado</h4>
                        </div>

                        <p>
                            Seus dados de saúde permanecem protegidos.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cta">
            <h2>Pronto para começar?</h2>

            <p>
                Cuide melhor da sua saúde com o MedTime.
            </p>

            <button>
                <a href="login.php">Cadastre</a>
            </button>
        </div>

    </main>

    <footer>
        <div class="container">
            <?php require_once "rodape.php"; ?>
        </div>
    </footer>

</body>

</html>