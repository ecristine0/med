<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós | MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php require_once "cabecalho.php"; ?>

    <main class="sobre-page">
        <section class="sobre-container">
            <div class="sobre-header">
                <span class="sobre-etiqueta">CONHEÇA O MEDTIME</span>
                <h1>Sobre <span>nós</span></h1>
                <p>Mais organização, segurança e tranquilidade para cuidar da saúde.</p>
            </div>

            <div class="sobre-conteudo">
                <h2>Como tudo começou</h2>

                <p>
                    Tudo começou com uma ideia simples: tornar o cuidado com a saúde
                    mais fácil e organizado. Muitas pessoas, especialmente idosos e
                    seus cuidadores, enfrentam dificuldades para lembrar os horários
                    dos medicamentos, acompanhar prescrições e manter uma rotina segura.
                </p>

                <p>
                    Pensando nisso, nasceu este sistema. Nosso objetivo é oferecer uma
                    ferramenta prática que ajude no gerenciamento de medicamentos,
                    permitindo cadastrar pacientes, cuidadores, prescrições e horários
                    de forma simples e intuitiva.
                </p>

                <p>
                    Acreditamos que a tecnologia pode ser uma grande aliada no cuidado
                    com a saúde. Por isso, buscamos desenvolver um ambiente confiável,
                    acessível e fácil de usar, contribuindo para que pacientes e
                    familiares tenham mais tranquilidade no dia a dia.
                </p>

                <div class="sobre-destaque">
                    <h2>Nosso propósito</h2>
                    <p>
                        Mais do que um sistema, este projeto representa o compromisso
                        de utilizar a tecnologia para promover organização, segurança
                        e qualidade de vida, ajudando as pessoas a cuidarem melhor
                        de quem mais importa.
                    </p>
                </div>
            </div>

            <div class="sobre-acoes">
                <a href="index.php" class="sobre-voltar">Voltar para o início</a>
                <a href="cadastrar_med.php" class="sobre-botao">Começar agora</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <?php require_once "rodape.php"; ?>
        </div>
    </footer>

</body>

</html>