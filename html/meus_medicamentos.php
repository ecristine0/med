<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Sao_Paulo');

require_once "conexao.php";

if (!isset($_SESSION['pessoa_id'])) {
    header("Location: login.php");
    exit;
}

$conexao->set_charset("utf8mb4");

$idLogado = (int)$_SESSION['pessoa_id'];
$tipoLogado = $_SESSION['pessoa_tipo'] ?? '';

function escapar($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

// Proteção contra envio de formulários externos.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$mensagem = "";
$tipoMensagem = "";

if (isset($_GET['sucesso'])) {
    $mensagem = "Status da dose atualizado com sucesso!";
    $tipoMensagem = "sucesso";
} elseif (isset($_GET['erro'])) {
    $mensagem = "Não foi possível atualizar o status da dose.";
    $tipoMensagem = "erro";
}

// Verifica o tipo de usuário.
if (!in_array($tipoLogado, ['paciente', 'cuidador'], true)) {
    die("Tipo de usuário inválido.");
}

/*
 * REGISTRO DAS DOSES DO DIA
 *
 * Cria automaticamente uma dose pendente para cada horário
 * cadastrado na prescrição.
 *
 * INSERT IGNORE evita duplicar registros, pois a tabela possui
 * uma chave única para prescrição e horário.
 */

if ($tipoLogado === "paciente") {

    $sqlGerar = "INSERT IGNORE INTO registro_dose
                    (prescricao_id, registro_dose_horario, registro_dose_status)
                 SELECT
                    pr.prescricao_id,
                    TIMESTAMP(CURDATE(), ph.prescricao_horario),
                    'pendente'
                 FROM prescricao pr
                 INNER JOIN prescricao_horario ph
                    ON ph.prescricao_id = pr.prescricao_id
                 WHERE pr.pessoa_paciente_id = ?
                   AND pr.prescricao_data_inicio <= CURDATE()
                   AND (
                        pr.prescricao_data_fim IS NULL
                        OR pr.prescricao_data_fim >= CURDATE()
                   )";
} else {

    $sqlGerar = "INSERT IGNORE INTO registro_dose
                    (prescricao_id, registro_dose_horario, registro_dose_status)
                 SELECT
                    pr.prescricao_id,
                    TIMESTAMP(CURDATE(), ph.prescricao_horario),
                    'pendente'
                 FROM prescricao pr
                 INNER JOIN prescricao_horario ph
                    ON ph.prescricao_id = pr.prescricao_id
                 INNER JOIN cuidado c
                    ON c.fk_pessoa_paciente = pr.pessoa_paciente_id
                 WHERE c.fk_pessoa_cuidador = ?
                   AND pr.prescricao_data_inicio <= CURDATE()
                   AND (
                        pr.prescricao_data_fim IS NULL
                        OR pr.prescricao_data_fim >= CURDATE()
                   )";
}

$stmtGerar = $conexao->prepare($sqlGerar);
$stmtGerar->bind_param("i", $idLogado);
$stmtGerar->execute();
$stmtGerar->close();

/*
 * ATUALIZA O STATUS DA DOSE
 *
 * O sistema verifica se o registro pertence ao paciente
 * ou a um paciente associado ao cuidador.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_dose'])) {

    $token = $_POST['csrf_token'] ?? '';
    $doseId = (int)($_POST['registro_dose_id'] ?? 0);
    $novoStatus = $_POST['status'] ?? '';

    if (
        !hash_equals($_SESSION['csrf_token'], $token) ||
        $doseId <= 0 ||
        !in_array($novoStatus, ['tomada', 'nao_tomada'], true)
    ) {
        header("Location: meus_medicamentos.php?erro=1");
        exit;
    }

    if ($tipoLogado === "paciente") {

        $sqlAtualizar = "UPDATE registro_dose rd
                         INNER JOIN prescricao pr
                            ON pr.prescricao_id = rd.prescricao_id
                         SET
                            rd.registro_dose_status = ?,
                            rd.registro_dose_data_tomada =
                                IF(? = 'tomada', NOW(), NULL),
                            rd.pessoa_registrou_id = ?
                         WHERE rd.registro_dose_id = ?
                           AND DATE(rd.registro_dose_horario) = CURDATE()
                           AND pr.pessoa_paciente_id = ?";
    } else {

        $sqlAtualizar = "UPDATE registro_dose rd
                         INNER JOIN prescricao pr
                            ON pr.prescricao_id = rd.prescricao_id
                         INNER JOIN cuidado c
                            ON c.fk_pessoa_paciente = pr.pessoa_paciente_id
                         SET
                            rd.registro_dose_status = ?,
                            rd.registro_dose_data_tomada =
                                IF(? = 'tomada', NOW(), NULL),
                            rd.pessoa_registrou_id = ?
                         WHERE rd.registro_dose_id = ?
                           AND DATE(rd.registro_dose_horario) = CURDATE()
                           AND c.fk_pessoa_cuidador = ?";
    }

    $stmtAtualizar = $conexao->prepare($sqlAtualizar);

    $stmtAtualizar->bind_param(
        "ssiii",
        $novoStatus,
        $novoStatus,
        $idLogado,
        $doseId,
        $idLogado
    );

    if ($stmtAtualizar->execute()) {
        $stmtAtualizar->close();

        header("Location: meus_medicamentos.php?sucesso=1");
        exit;
    }

    $stmtAtualizar->close();

    header("Location: meus_medicamentos.php?erro=1");
    exit;
}

/*
 * BUSCA OS MEDICAMENTOS E AS DOSES DE HOJE.
 */

if ($tipoLogado === "paciente") {

    $sql = "SELECT
                pr.prescricao_id,
                pr.prescricao_dosagem,
                pr.prescricao_frequencia,
                pr.prescricao_data_inicio,
                pr.prescricao_data_fim,
                pr.prescricao_observacao,

                m.medicamento_nome,
                m.medicamento_foto,
                m.medicamento_categoria,
                m.medicamento_laboratorio,

                (
                    SELECT GROUP_CONCAT(
                        TIME_FORMAT(ph.prescricao_horario, '%H:%i')
                        ORDER BY ph.prescricao_horario
                        SEPARATOR ', '
                    )
                    FROM prescricao_horario ph
                    WHERE ph.prescricao_id = pr.prescricao_id
                ) AS horarios,

                rd.registro_dose_id,
                rd.registro_dose_horario,
                rd.registro_dose_status,
                rd.registro_dose_data_tomada

            FROM prescricao pr

            INNER JOIN medicamento m
                ON m.medicamento_id = pr.medicamento_medicamento_id

            LEFT JOIN registro_dose rd
                ON rd.prescricao_id = pr.prescricao_id
                AND DATE(rd.registro_dose_horario) = CURDATE()

            WHERE pr.pessoa_paciente_id = ?

            ORDER BY
                pr.prescricao_data_inicio DESC,
                m.medicamento_nome,
                rd.registro_dose_horario";
} else {

    $sql = "SELECT
                pr.prescricao_id,
                pr.prescricao_dosagem,
                pr.prescricao_frequencia,
                pr.prescricao_data_inicio,
                pr.prescricao_data_fim,
                pr.prescricao_observacao,

                m.medicamento_nome,
                m.medicamento_foto,
                m.medicamento_categoria,
                m.medicamento_laboratorio,

                p.pessoa_nome AS paciente_nome,

                (
                    SELECT GROUP_CONCAT(
                        TIME_FORMAT(ph.prescricao_horario, '%H:%i')
                        ORDER BY ph.prescricao_horario
                        SEPARATOR ', '
                    )
                    FROM prescricao_horario ph
                    WHERE ph.prescricao_id = pr.prescricao_id
                ) AS horarios,

                rd.registro_dose_id,
                rd.registro_dose_horario,
                rd.registro_dose_status,
                rd.registro_dose_data_tomada

            FROM prescricao pr

            INNER JOIN medicamento m
                ON m.medicamento_id = pr.medicamento_medicamento_id

            INNER JOIN pessoa p
                ON p.pessoa_id = pr.pessoa_paciente_id

            INNER JOIN cuidado c
                ON c.fk_pessoa_paciente = pr.pessoa_paciente_id

            LEFT JOIN registro_dose rd
                ON rd.prescricao_id = pr.prescricao_id
                AND DATE(rd.registro_dose_horario) = CURDATE()

            WHERE c.fk_pessoa_cuidador = ?

            ORDER BY
                p.pessoa_nome,
                pr.prescricao_data_inicio DESC,
                m.medicamento_nome,
                rd.registro_dose_horario";
}

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idLogado);
$stmt->execute();

$resultado = $stmt->get_result();

// Agrupa as doses para não repetir o cartão do medicamento.
$medicamentos = [];

while ($linha = $resultado->fetch_assoc()) {

    $idPrescricao = $linha['prescricao_id'];

    if (!isset($medicamentos[$idPrescricao])) {
        $medicamentos[$idPrescricao] = $linha;
        $medicamentos[$idPrescricao]['doses'] = [];
    }

    if (!empty($linha['registro_dose_id'])) {
        $medicamentos[$idPrescricao]['doses'][] = [
            'id' => $linha['registro_dose_id'],
            'horario' => $linha['registro_dose_horario'],
            'status' => $linha['registro_dose_status'],
            'data_tomada' => $linha['registro_dose_data_tomada']
        ];
    }
}

$total = count($medicamentos);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus medicamentos | MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php require_once "cabecalho.php"; ?>

    <main class="meus-med-page">
        <div class="meus-med-container">

            <header class="meus-med-header">
                <span class="prescricao-etiqueta">MEDTIME</span>

                <h1>Meus <span>medicamentos</span></h1>

                <p>
                    Consulte seus medicamentos e acompanhe
                    os horários e registros de cada dose.
                </p>
            </header>

            <?php if ($mensagem !== ""): ?>
                <div class="prescricao-mensagem <?= escapar($tipoMensagem) ?>">
                    <?= escapar($mensagem) ?>
                </div>
            <?php endif; ?>

            <div class="meus-med-resumo">
                <strong><?= $total ?></strong>

                <span>
                    <?= $total === 1
                        ? "medicamento cadastrado"
                        : "medicamentos cadastrados" ?>
                </span>
            </div>

            <?php if ($total > 0): ?>

                <div class="meus-med-lista">

                    <?php foreach ($medicamentos as $medicamento): ?>

                        <?php
                        $nomeFoto = basename(
                            trim((string)$medicamento['medicamento_foto'])
                        );

                        $caminhoFoto = "";

                        if ($nomeFoto !== "") {
                            $caminhoFoto = "./foto/" . rawurlencode($nomeFoto);
                        }
                        ?>

                        <article class="meus-med-card">

                            <div class="meus-med-conteudo">

                                <?php if ($nomeFoto !== ""): ?>

                                    <img
                                        src="<?= escapar($caminhoFoto) ?>"
                                        alt="<?= escapar($medicamento['medicamento_nome']) ?>"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <div class="meus-med-sem-foto" style="display:none;">
                                        ✚
                                    </div>

                                <?php else: ?>

                                    <div class="meus-med-sem-foto">
                                        ✚
                                    </div>

                                <?php endif; ?>

                                <div class="meus-med-info">

                                    <span class="meus-med-categoria">
                                        <?= escapar($medicamento['medicamento_categoria']) ?>
                                    </span>

                                    <h2>
                                        <?= escapar($medicamento['medicamento_nome']) ?>
                                    </h2>

                                    <?php if ($tipoLogado === "cuidador"): ?>
                                        <p>
                                            <strong>Paciente:</strong>
                                            <?= escapar($medicamento['paciente_nome']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <p>
                                        <strong>Laboratório:</strong>
                                        <?= escapar($medicamento['medicamento_laboratorio']) ?>
                                    </p>

                                    <p>
                                        <strong>Quantidade por dose:</strong>
                                        <?= escapar($medicamento['prescricao_dosagem']) ?>
                                    </p>

                                    <p>
                                        <strong>Frequência:</strong>
                                        <?= (int)$medicamento['prescricao_frequencia'] ?>
                                        <?= (int)$medicamento['prescricao_frequencia'] === 1
                                            ? "vez por dia"
                                            : "vezes por dia" ?>
                                    </p>

                                    <p>
                                        <strong>Horários:</strong>
                                        <?= escapar($medicamento['horarios'] ?? 'Não informado') ?>
                                    </p>

                                    <p>
                                        <strong>Data de início:</strong>
                                        <?= date(
                                            "d/m/Y",
                                            strtotime($medicamento['prescricao_data_inicio'])
                                        ) ?>
                                    </p>

                                    <p>
                                        <strong>Data de término:</strong>

                                        <?= !empty($medicamento['prescricao_data_fim'])
                                            ? date(
                                                "d/m/Y",
                                                strtotime($medicamento['prescricao_data_fim'])
                                            )
                                            : "Não informada" ?>
                                    </p>

                                    <?php if (!empty($medicamento['prescricao_observacao'])): ?>

                                        <div class="meus-med-observacao">
                                            <strong>Observações:</strong>

                                            <p>
                                                <?= nl2br(escapar($medicamento['prescricao_observacao'])) ?>
                                            </p>
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </div>

                            <section class="meus-med-doses">

                                <h3>Registro das doses de hoje</h3>

                                <?php if (!empty($medicamento['doses'])): ?>

                                    <?php foreach ($medicamento['doses'] as $dose): ?>

                                        <div class="meus-med-dose">

                                            <div class="meus-med-dose-info">

                                                <strong>
                                                    <?= date(
                                                        "H:i",
                                                        strtotime($dose['horario'])
                                                    ) ?>
                                                </strong>

                                                <span class="meus-med-status <?= escapar($dose['status']) ?>">

                                                    <?php
                                                    if ($dose['status'] === 'tomada') {
                                                        echo "Dose tomada";
                                                    } elseif ($dose['status'] === 'nao_tomada') {
                                                        echo "Dose não tomada";
                                                    } else {
                                                        echo "Pendente";
                                                    }
                                                    ?>

                                                </span>

                                            </div>

                                            <form method="POST" class="meus-med-dose-form">

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= escapar($_SESSION['csrf_token']) ?>">

                                                <input
                                                    type="hidden"
                                                    name="registro_dose_id"
                                                    value="<?= (int)$dose['id'] ?>">

                                                <button
                                                    type="submit"
                                                    name="status"
                                                    value="tomada"
                                                    class="meus-med-btn tomou <?= $dose['status'] === 'tomada' ? 'ativo' : '' ?>">
                                                    ✓ Tomei
                                                </button>

                                                <button
                                                    type="submit"
                                                    name="status"
                                                    value="nao_tomada"
                                                    class="meus-med-btn nao-tomou <?= $dose['status'] === 'nao_tomada' ? 'ativo' : '' ?>">
                                                    ✕ Não tomei
                                                </button>

                                                <input
                                                    type="hidden"
                                                    name="atualizar_dose"
                                                    value="1">

                                            </form>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <p class="meus-med-sem-dose">
                                        Não há doses previstas para hoje.
                                    </p>

                                <?php endif; ?>

                            </section>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <section class="meus-med-vazio">

                    <div>✚</div>

                    <h2>Nenhum medicamento selecionado</h2>

                    <p>
                        Você ainda não adicionou medicamentos à sua lista.
                        Acesse o catálogo para escolher aqueles que deseja utilizar.
                    </p>

                    <a href="listar_med.php" class="prescricao-botao">
                        Explorar medicamentos
                    </a>

                </section>

            <?php endif; ?>

            <div class="meus-med-acoes">
                <a href="listar_med.php">← Ver catálogo de medicamentos</a>
                <a href="index.php">Voltar para o início</a>
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
$stmt->close();
$conexao->close();
?>