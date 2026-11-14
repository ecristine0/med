<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "conexao.php";

if (!isset($_SESSION['pessoa_id'])) {
    header("Location: login.php");
    exit;
}

$idLogado = (int) $_SESSION['pessoa_id'];
$tipoLogado = $_SESSION['pessoa_tipo'];

$mensagem = "";
$tipoMensagem = "";

if ($tipoLogado !== "cuidador" && $tipoLogado !== "paciente") {
    $mensagem = "Tipo de usuário inválido.";
    $tipoMensagem = "erro";
} else {

    // Cadastra uma nova associação.
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idSelecionado = filter_input(INPUT_POST, "pessoa_id", FILTER_VALIDATE_INT);

        if (!$idSelecionado || $idSelecionado === $idLogado) {
            $mensagem = "Selecione uma pessoa válida.";
            $tipoMensagem = "erro";
        } else {
            if ($tipoLogado === "cuidador") {
                $tipoEsperado = "paciente";
                $idCuidador = $idLogado;
                $idPaciente = $idSelecionado;
            } else {
                $tipoEsperado = "cuidador";
                $idCuidador = $idSelecionado;
                $idPaciente = $idLogado;
            }

            $sqlPessoa = "SELECT pessoa_nome, pessoa_data_nascimento
                          FROM pessoa
                          WHERE pessoa_id = ? AND pessoa_tipo = ?";

            $stmtPessoa = $conexao->prepare($sqlPessoa);
            $stmtPessoa->bind_param("is", $idSelecionado, $tipoEsperado);
            $stmtPessoa->execute();
            $resultadoPessoa = $stmtPessoa->get_result();

            if ($resultadoPessoa->num_rows === 0) {
                $mensagem = "A pessoa selecionada não foi encontrada ou não possui o tipo esperado.";
                $tipoMensagem = "erro";
            } else {
                $paciente = $resultadoPessoa->fetch_assoc();

                $sqlVerifica = "SELECT cuidado_id
                                FROM cuidado
                                WHERE fk_pessoa_cuidador = ?
                                AND fk_pessoa_paciente = ?";

                $stmtVerifica = $conexao->prepare($sqlVerifica);
                $stmtVerifica->bind_param("ii", $idCuidador, $idPaciente);
                $stmtVerifica->execute();
                $resultadoVerifica = $stmtVerifica->get_result();

                if ($resultadoVerifica->num_rows > 0) {
                    $mensagem = "Essa associação já está cadastrada.";
                    $tipoMensagem = "erro";
                } else {
                    $nomePaciente = $paciente['pessoa_nome'];
                    $dataNascimento = $paciente['pessoa_data_nascimento'];
                    $fotoPaciente = "";

                    $sqlInserir = "INSERT INTO cuidado
                                   (cuidado_nome, cuidado_data_nas, cuidado_foto,
                                    fk_pessoa_cuidador, fk_pessoa_paciente)
                                   VALUES (?, ?, ?, ?, ?)";

                    $stmtInserir = $conexao->prepare($sqlInserir);
                    $stmtInserir->bind_param(
                        "sssii",
                        $nomePaciente,
                        $dataNascimento,
                        $fotoPaciente,
                        $idCuidador,
                        $idPaciente
                    );

                    if ($stmtInserir->execute()) {
                        $mensagem = "Associação cadastrada com sucesso!";
                        $tipoMensagem = "sucesso";
                    } else {
                        $mensagem = "Não foi possível cadastrar a associação.";
                        $tipoMensagem = "erro";
                    }

                    $stmtInserir->close();
                }

                $stmtVerifica->close();
            }

            $stmtPessoa->close();
        }
    }

    // Busca as pessoas que já estão associadas ao usuário.
    if ($tipoLogado === "cuidador") {
        $sqlAssociados = "SELECT p.pessoa_nome, p.pessoa_email
                          FROM cuidado c
                          INNER JOIN pessoa p
                          ON p.pessoa_id = c.fk_pessoa_paciente
                          WHERE c.fk_pessoa_cuidador = ?
                          ORDER BY p.pessoa_nome";
    } else {
        $sqlAssociados = "SELECT p.pessoa_nome, p.pessoa_email
                          FROM cuidado c
                          INNER JOIN pessoa p
                          ON p.pessoa_id = c.fk_pessoa_cuidador
                          WHERE c.fk_pessoa_paciente = ?
                          ORDER BY p.pessoa_nome";
    }

    $stmtAssociados = $conexao->prepare($sqlAssociados);
    $stmtAssociados->bind_param("i", $idLogado);
    $stmtAssociados->execute();
    $associados = $stmtAssociados->get_result();

    // Busca apenas pessoas que ainda não estão associadas.
    $tipoBusca = ($tipoLogado === "cuidador") ? "paciente" : "cuidador";

    if ($tipoLogado === "cuidador") {
        $sqlLista = "SELECT p.pessoa_id, p.pessoa_nome, p.pessoa_email
                     FROM pessoa p
                     WHERE p.pessoa_tipo = ?
                     AND p.pessoa_id <> ?
                     AND NOT EXISTS (
                         SELECT 1
                         FROM cuidado c
                         WHERE c.fk_pessoa_cuidador = ?
                         AND c.fk_pessoa_paciente = p.pessoa_id
                     )
                     ORDER BY p.pessoa_nome";
    } else {
        $sqlLista = "SELECT p.pessoa_id, p.pessoa_nome, p.pessoa_email
                     FROM pessoa p
                     WHERE p.pessoa_tipo = ?
                     AND p.pessoa_id <> ?
                     AND NOT EXISTS (
                         SELECT 1
                         FROM cuidado c
                         WHERE c.fk_pessoa_paciente = ?
                         AND c.fk_pessoa_cuidador = p.pessoa_id
                     )
                     ORDER BY p.pessoa_nome";
    }

    $stmtLista = $conexao->prepare($sqlLista);
    $stmtLista->bind_param("sii", $tipoBusca, $idLogado, $idLogado);
    $stmtLista->execute();
    $pessoas = $stmtLista->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associar pessoa - MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php require_once "cabecalho.php"; ?>

    <main class="associacao-page">
        <section class="associacao-container">

            <div class="associacao-header">
                <span class="associacao-etiqueta">MEDTIME</span>

                <h1>
                    <?php echo ($tipoLogado === "cuidador")
                        ? "Meus pacientes"
                        : "Meus cuidadores"; ?>
                </h1>

                <p>
                    <?php echo ($tipoLogado === "cuidador")
                        ? "Veja os pacientes que estão sob seus cuidados."
                        : "Veja os cuidadores associados à sua conta."; ?>
                </p>
            </div>

            <?php if ($mensagem !== ""): ?>
                <div class="associacao-mensagem <?php echo $tipoMensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <?php if ($tipoLogado === "cuidador" || $tipoLogado === "paciente"): ?>

                <div class="associacao-lista">

                    <h2>
                        <?php echo ($tipoLogado === "cuidador")
                            ? "Pacientes associados"
                            : "Cuidadores associados"; ?>
                    </h2>

                    <?php if ($associados->num_rows > 0): ?>

                        <?php while ($associado = $associados->fetch_assoc()): ?>
                            <div class="associacao-item">
                                <div class="associacao-item-icone">
                                    <?php echo strtoupper(substr($associado['pessoa_nome'], 0, 1)); ?>
                                </div>

                                <div class="associacao-item-info">
                                    <strong>
                                        <?php echo htmlspecialchars($associado['pessoa_nome']); ?>
                                    </strong>

                                    <span>
                                        <?php echo htmlspecialchars($associado['pessoa_email']); ?>
                                    </span>
                                </div>

                                <span class="associacao-status">Associado</span>
                            </div>
                        <?php endwhile; ?>

                    <?php else: ?>

                        <p class="associacao-vazio">
                            Você ainda não possui
                            <?php echo ($tipoLogado === "cuidador") ? "pacientes" : "cuidadores"; ?>
                            associados.
                        </p>

                    <?php endif; ?>

                </div>

                <div class="associacao-nova">
                    <h2>
                        <?php echo ($tipoLogado === "cuidador")
                            ? "Adicionar paciente"
                            : "Adicionar cuidador"; ?>
                    </h2>

                    <p>
                        Selecione uma pessoa para criar uma nova associação.
                    </p>

                    <?php if ($pessoas->num_rows > 0): ?>

                        <form method="POST" class="associacao-form">

                            <label for="pessoa_id">
                                <?php echo ($tipoLogado === "cuidador")
                                    ? "Escolha um paciente"
                                    : "Escolha um cuidador"; ?>
                            </label>

                            <select name="pessoa_id" id="pessoa_id" required>
                                <option value="">Selecione uma pessoa</option>

                                <?php while ($pessoa = $pessoas->fetch_assoc()): ?>
                                    <option value="<?php echo (int) $pessoa['pessoa_id']; ?>">
                                        <?php
                                        echo htmlspecialchars($pessoa['pessoa_nome'])
                                            . " - "
                                            . htmlspecialchars($pessoa['pessoa_email']);
                                        ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>

                            <button type="submit" class="associacao-button">
                                Confirmar associação
                            </button>

                        </form>

                    <?php else: ?>

                        <p class="associacao-vazio">
                            Não há novas pessoas disponíveis para associação.
                        </p>

                    <?php endif; ?>
                </div>

            <?php endif; ?>

            <a href="index.php" class="associacao-voltar">Voltar para o início</a>

        </section>
    </main>

    <footer>
        <div class="container">
            <?php require_once "rodape.php"; ?>
        </div>
    </footer>

</body>

</html>