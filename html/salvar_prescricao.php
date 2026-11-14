<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "conexao.php";

if (!isset($_SESSION['pessoa_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: listar_med.php");
    exit;
}

$conexao->set_charset("utf8mb4");

$idLogado = (int)$_SESSION['pessoa_id'];
$tipoLogado = $_SESSION['pessoa_tipo'] ?? '';

function voltarComErro($mensagem)
{
    echo "<!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Erro | MedTime</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>";

    require_once "cabecalho.php";

    echo "<main class='prescricao-page'>
        <section class='prescricao-container'>
            <div class='prescricao-mensagem erro'>"
            . htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') .
            "</div>
            <a href='listar_med.php' class='prescricao-botao'>
                Voltar aos medicamentos
            </a>
        </section>
    </main>";

    require_once "rodape.php";

    echo "</body></html>";
    exit;
}

// Recebe os dados do formulário.
$idMedicamento = filter_input(INPUT_POST, "medicamento_id", FILTER_VALIDATE_INT);
$idPacienteSelecionado = filter_input(INPUT_POST, "paciente_id", FILTER_VALIDATE_INT);

$dosagem = filter_input(INPUT_POST, "dosagem", FILTER_VALIDATE_FLOAT);
$frequencia = filter_input(INPUT_POST, "frequencia", FILTER_VALIDATE_INT);

$dataInicio = $_POST['data_inicio'] ?? '';
$dataFim = $_POST['data_fim'] ?? '';
$observacao = trim($_POST['observacao'] ?? '');

$horarios = $_POST['horarios'] ?? [];

if (!is_array($horarios)) {
    voltarComErro("Os horários informados são inválidos.");
}

// Verifica os dados básicos.
if (!$idMedicamento || !$dosagem || $dosagem <= 0) {
    voltarComErro("Informe um medicamento e uma dosagem válida.");
}

if (!$frequencia || $frequencia < 1 || $frequencia > 6) {
    voltarComErro("A frequência deve estar entre 1 e 6 tomadas por dia.");
}

if (count($horarios) !== $frequencia) {
    voltarComErro("A quantidade de horários precisa corresponder à frequência diária.");
}

if (strlen($observacao) > 255) {
    voltarComErro("As observações podem ter no máximo 255 caracteres.");
}

// Verifica as datas.
$dataInicioObjeto = DateTime::createFromFormat('!Y-m-d', $dataInicio);

if (!$dataInicioObjeto || $dataInicioObjeto->format('Y-m-d') !== $dataInicio) {
    voltarComErro("Informe uma data de início válida.");
}

$dataFimBanco = null;

if ($dataFim !== '') {
    $dataFimObjeto = DateTime::createFromFormat('!Y-m-d', $dataFim);

    if (!$dataFimObjeto || $dataFimObjeto->format('Y-m-d') !== $dataFim) {
        voltarComErro("Informe uma data de término válida.");
    }

    if ($dataFimObjeto < $dataInicioObjeto) {
        voltarComErro("A data de término não pode ser anterior à data de início.");
    }

    $dataFimBanco = $dataFim;
}

// Valida e organiza os horários.
$horariosValidos = [];

foreach ($horarios as $horario) {
    if (!is_string($horario) ||
        !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horario)) {
        voltarComErro("Um dos horários informados é inválido.");
    }

    if (in_array($horario, $horariosValidos, true)) {
        voltarComErro("Não informe horários repetidos.");
    }

    $horariosValidos[] = $horario . ":00";
}

sort($horariosValidos);

// Verifica se o medicamento existe.
$sqlMedicamento = "SELECT medicamento_id
                   FROM medicamento
                   WHERE medicamento_id = ?";

$stmtMedicamento = $conexao->prepare($sqlMedicamento);
$stmtMedicamento->bind_param("i", $idMedicamento);
$stmtMedicamento->execute();

if ($stmtMedicamento->get_result()->num_rows === 0) {
    voltarComErro("O medicamento selecionado não existe.");
}

$stmtMedicamento->close();

// Define o paciente e o cuidador responsáveis pela prescrição.
$idPaciente = null;
$idCuidador = null;

if ($tipoLogado === "paciente") {
    // O paciente só pode cadastrar medicamentos para si mesmo.
    $idPaciente = $idLogado;
} elseif ($tipoLogado === "cuidador") {

    if (!$idPacienteSelecionado || $idPacienteSelecionado <= 0) {
        voltarComErro("Selecione um paciente válido.");
    }

    // Confirma que o paciente está associado ao cuidador.
    $sqlAssociacao = "SELECT cuidado_id
                      FROM cuidado
                      WHERE fk_pessoa_cuidador = ?
                      AND fk_pessoa_paciente = ?";

    $stmtAssociacao = $conexao->prepare($sqlAssociacao);
    $stmtAssociacao->bind_param(
        "ii",
        $idLogado,
        $idPacienteSelecionado
    );
    $stmtAssociacao->execute();

    if ($stmtAssociacao->get_result()->num_rows === 0) {
        voltarComErro("Esse paciente não está associado à sua conta.");
    }

    $stmtAssociacao->close();

    $idPaciente = $idPacienteSelecionado;
    $idCuidador = $idLogado;

} else {
    voltarComErro("Tipo de usuário inválido.");
}

// Define o primeiro horário completo da prescrição.
$primeiroHorario = substr($horariosValidos[0], 0, 5);
$primeiraDose = $dataInicio . " " . $primeiroHorario . ":00";

try {
    $conexao->begin_transaction();

    // Salva a prescrição principal.
    $sqlPrescricao = "INSERT INTO prescricao (
                        medicamento_medicamento_id,
                        pessoa_paciente_id,
                        pessoa_cuidador_id,
                        prescricao_frequencia,
                        prescricao_dosagem,
                        prescricao_horario_primeira_dose,
                        prescricao_data_inicio,
                        prescricao_data_fim,
                        prescricao_intervalo_horas,
                        prescricao_observacao
                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)";

    $stmtPrescricao = $conexao->prepare($sqlPrescricao);

    $stmtPrescricao->bind_param(
        "iiiidssss",
        $idMedicamento,
        $idPaciente,
        $idCuidador,
        $frequencia,
        $dosagem,
        $primeiraDose,
        $dataInicio,
        $dataFimBanco,
        $observacao
    );

    $stmtPrescricao->execute();

    $idPrescricao = $conexao->insert_id;

    $stmtPrescricao->close();

    // Salva cada horário associado à prescrição.
    $sqlHorario = "INSERT INTO prescricao_horario
                   (prescricao_id, prescricao_horario)
                   VALUES (?, ?)";

    $stmtHorario = $conexao->prepare($sqlHorario);

    foreach ($horariosValidos as $horario) {
        $stmtHorario->bind_param(
            "is",
            $idPrescricao,
            $horario
        );

        $stmtHorario->execute();
    }

    $stmtHorario->close();

    $conexao->commit();

    header("Location: meus_medicamentos.php?sucesso=1");
    exit;

} catch (Throwable $erro) {
    $conexao->rollback();

    voltarComErro("Não foi possível salvar o medicamento. Verifique os dados e tente novamente.");
}