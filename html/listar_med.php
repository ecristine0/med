<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/conexao.php";

$conexao->set_charset("utf8mb4");

$sql = "SELECT
            medicamento_id,
            medicamento_nome,
            medicamento_foto,
            medicamento_laboratorio,
            medicamento_categoria,
            medicamento_obervacao
        FROM medicamento
        ORDER BY medicamento_nome ASC";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Não foi possível carregar os medicamentos.");
}

$total = $resultado->num_rows;

function escapar($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicamentos | MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php require_once "cabecalho.php"; ?>

    <main class="lista-med-page">
        <div class="lista-med-container">

            <header class="lista-med-header">
                <span class="lista-med-etiqueta">MEDTIME</span>
                <h1>Medicamentos <span>cadastrados</span></h1>
                <p>
                    Consulte os medicamentos registrados no sistema
                    e confira suas principais informações.
                </p>
            </header>

            <div class="lista-med-resumo">
                <div>
                    <span class="lista-med-numero">
                        <?= $total ?>
                    </span>
                    <span class="lista-med-resumo-texto">
                        <?= $total === 1 ? "medicamento cadastrado" : "medicamentos cadastrados" ?>
                    </span>
                </div>

                <a href="cadastrar_med.php" class="lista-med-adicionar">
                    + Cadastrar medicamento
                </a>
            </div>

            <?php if ($total > 0): ?>

                <div class="lista-med-grid">

                    <?php while ($medicamento = $resultado->fetch_assoc()): ?>

                        <article class="lista-med-card">

                            <?php if (!empty($medicamento["medicamento_foto"])): ?>
                                <div class="lista-med-imagem">
                                    <img
                                        src="<?= escapar($medicamento["medicamento_foto"]) ?>"
                                        alt="Foto de <?= escapar($medicamento["medicamento_nome"]) ?>"
                                        loading="lazy">
                                </div>
                            <?php else: ?>
                                <div class="lista-med-sem-imagem">
                                    <span>✚</span>
                                    <p>Sem foto cadastrada</p>
                                </div>
                            <?php endif; ?>

                            <div class="lista-med-card-conteudo">

                                <span class="lista-med-categoria">
                                    <?= escapar($medicamento["medicamento_categoria"]) ?>
                                </span>

                                <h2>
                                    <?= escapar($medicamento["medicamento_nome"]) ?>
                                </h2>

                                <div class="lista-med-informacao">
                                    <span>Laboratório</span>
                                    <p>
                                        <?= escapar($medicamento["medicamento_laboratorio"]) ?>
                                    </p>
                                </div>

                                <div class="lista-med-informacao">
                                    <span>Observações</span>
                                    <p>
                                        <?= trim((string)$medicamento["medicamento_obervacao"]) !== ""
                                            ? nl2br(escapar($medicamento["medicamento_obervacao"]))
                                            : "Nenhuma observação informada." ?>
                                    </p>
                                </div>

                            </div>
                        </article>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <section class="lista-med-vazio">
                    <div class="lista-med-vazio-icone">✚</div>
                    <h2>Nenhum medicamento encontrado</h2>
                    <p>
                        Você ainda não possui medicamentos cadastrados.
                        Comece adicionando o primeiro ao sistema.
                    </p>
                    <a href="cadastrar_med.php" class="lista-med-adicionar">
                        Cadastrar primeiro medicamento
                    </a>
                </section>

            <?php endif; ?>

            <div class="lista-med-voltar">
                <a href="index.php">← Voltar para o início</a>
            </div>

        </div>
    </main>

    <footer>
        <div class="container">
            <?php require_once "rodape.php"; ?>
        </div>
    </footer>

</body>

</html>

<?php
$resultado->free();
$conexao->close();
?>