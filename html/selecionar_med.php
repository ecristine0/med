<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "conexao.php";

if (!isset($_SESSION['pessoa_id'])) {
    header("Location: login.php");
    exit;
}

$conexao->set_charset("utf8mb4");

$idLogado = (int) $_SESSION['pessoa_id'];
$tipoLogado = $_SESSION['pessoa_tipo'] ?? '';

$idMedicamento = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$idMedicamento || $idMedicamento <= 0) {
    header("Location: listar_med.php");
    exit;
}

function escapar($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

// Busca o medicamento selecionado.
$sqlMedicamento = "SELECT *
                   FROM medicamento
                   WHERE medicamento_id = ?";

$stmtMedicamento = $conexao->prepare($sqlMedicamento);
$stmtMedicamento->bind_param("i", $idMedicamento);
$stmtMedicamento->execute();

$resultadoMedicamento = $stmtMedicamento->get_result();

if ($resultadoMedicamento->num_rows === 0) {
    header("Location: listar_med.php");
    exit;
}

$medicamento = $resultadoMedicamento->fetch_assoc();
$stmtMedicamento->close();

// Monta o caminho da foto.
$nomeFoto = basename(trim((string)$medicamento['medicamento_foto']));

$caminhoFoto = "";

if ($nomeFoto !== "") {
    $caminhoFoto = "./foto/" . rawurlencode($nomeFoto);
}

// Se for cuidador, busca os pacientes associados.
$pacientes = null;

if ($tipoLogado === "cuidador") {
    $sqlPacientes = "SELECT p.pessoa_id, p.pessoa_nome, p.pessoa_email
                     FROM cuidado c
                     INNER JOIN pessoa p
                     ON p.pessoa_id = c.fk_pessoa_paciente
                     WHERE c.fk_pessoa_cuidador = ?
                     ORDER BY p.pessoa_nome";

    $stmtPacientes = $conexao->prepare($sqlPacientes);
    $stmtPacientes->bind_param("i", $idLogado);
    $stmtPacientes->execute();

    $pacientes = $stmtPacientes->get_result();
} elseif ($tipoLogado !== "paciente") {
    die("Tipo de usuário inválido.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar medicamento | MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php require_once "cabecalho.php"; ?>

    <main class="prescricao-page">
        <section class="prescricao-container">

            <header class="prescricao-header">
                <span class="prescricao-etiqueta">MEDTIME</span>
                <h1>Adicionar medicamento</h1>
                <p>
                    Informe como o medicamento será utilizado para organizar
                    sua rotina de cuidados.
                </p>
            </header>

            <div class="prescricao-medicamento">

                <?php if ($nomeFoto !== ""): ?>

                    <img
                        src="<?= escapar($caminhoFoto) ?>"
                        alt="<?= escapar($medicamento['medicamento_nome']) ?>"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <div class="prescricao-sem-foto" style="display:none;">
                        ✚
                    </div>

                <?php else: ?>

                    <div class="prescricao-sem-foto">
                        ✚
                    </div>

                <?php endif; ?>

                <div>
                    <span>
                        <?= escapar($medicamento['medicamento_categoria']) ?>
                    </span>

                    <h2>
                        <?= escapar($medicamento['medicamento_nome']) ?>
                    </h2>

                    <p>
                        Laboratório:
                        <?= escapar($medicamento['medicamento_laboratorio']) ?>
                    </p>
                </div>

            </div>

            <?php if ($tipoLogado === "cuidador" && $pacientes->num_rows === 0): ?>

                <div class="prescricao-mensagem erro">
                    Você ainda não possui pacientes associados.
                    Acesse a página de associação para adicionar um paciente
                    antes de cadastrar uma prescrição.
                </div>

                <a href="cadastrar_cuidado.php" class="prescricao-botao">
                    Associar paciente
                </a>

            <?php else: ?>

                <form action="salvar_prescricao.php" method="POST"
                    class="prescricao-form" id="formPrescricao">

                    <input
                        type="hidden"
                        name="medicamento_id"
                        value="<?= (int)$medicamento['medicamento_id'] ?>">

                    <?php if ($tipoLogado === "cuidador"): ?>

                        <div class="prescricao-campo">
                            <label for="paciente_id">Paciente</label>

                            <select name="paciente_id" id="paciente_id" required>
                                <option value="">Selecione um paciente</option>

                                <?php while ($paciente = $pacientes->fetch_assoc()): ?>

                                    <option value="<?= (int)$paciente['pessoa_id'] ?>">
                                        <?= escapar($paciente['pessoa_nome']) ?>
                                        - <?= escapar($paciente['pessoa_email']) ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                    <?php endif; ?>

                    <div class="prescricao-campo">
                        <label for="dosagem">Quantidade por dose</label>

                        <input
                            type="number"
                            name="dosagem"
                            id="dosagem"
                            min="0.01"
                            step="0.01"
                            placeholder="Ex.: 1"
                            required>

                        <small>
                            Informe a quantidade numérica. A unidade pode ser
                            detalhada nas observações.
                        </small>
                    </div>

                    <div class="prescricao-campo">
                        <label for="frequencia">Quantidade de tomadas por dia</label>

                        <input
                            type="number"
                            name="frequencia"
                            id="frequencia"
                            min="1"
                            max="6"
                            value="1"
                            required>

                        <small>
                            Informe de 1 a 6 horários por dia.
                        </small>
                    </div>

                    <div class="prescricao-campo">
                        <label>Horários de tomada</label>

                        <div id="horarios-container">
                            <div class="prescricao-horario-item">
                                <input
                                    type="time"
                                    name="horarios[]"
                                    required>
                            </div>
                        </div>

                        <small>
                            A quantidade de horários será ajustada conforme
                            a frequência diária informada.
                        </small>
                    </div>

                    <div class="prescricao-campo">
                        <label for="data_inicio">Data de início</label>

                        <input
                            type="date"
                            name="data_inicio"
                            id="data_inicio"
                            min="<?= date('Y-m-d') ?>"
                            value="<?= date('Y-m-d') ?>"
                            required>
                    </div>

                    <div class="prescricao-campo">
                        <label for="data_fim">
                            Data de término
                            <span>(opcional)</span>
                        </label>

                        <input
                            type="date"
                            name="data_fim"
                            id="data_fim"
                            min="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="prescricao-campo">
                        <label for="observacao">Observações</label>

                        <textarea
                            name="observacao"
                            id="observacao"
                            rows="3"
                            maxlength="255"
                            placeholder="Ex.: tomar com água; dosagem em mg."></textarea>
                    </div>

                    <button type="submit" class="prescricao-botao">
                        Salvar medicamento
                    </button>

                </form>

            <?php endif; ?>

            <a href="listar_med.php" class="prescricao-voltar">
                ← Voltar para os medicamentos
            </a>

        </section>
    </main>

    <footer>
        <div class="container">
            <?php require_once "rodape.php"; ?>
        </div>
    </footer>

    <script>
        const frequencia = document.getElementById("frequencia");
        const horariosContainer = document.getElementById("horarios-container");

        function atualizarHorarios() {
            let quantidade = parseInt(frequencia.value);

            if (isNaN(quantidade) || quantidade < 1) {
                quantidade = 1;
            }

            if (quantidade > 6) {
                quantidade = 6;
                frequencia.value = 6;
            }

            const horariosAtuais = Array.from(
                horariosContainer.querySelectorAll('input[type="time"]')
            ).map(input => input.value);

            horariosContainer.innerHTML = "";

            for (let i = 0; i < quantidade; i++) {
                const item = document.createElement("div");
                item.className = "prescricao-horario-item";

                const label = document.createElement("label");
                label.textContent = "Horário " + (i + 1);

                const input = document.createElement("input");
                input.type = "time";
                input.name = "horarios[]";
                input.required = true;

                input.value = horariosAtuais[i] || "";

                item.appendChild(label);
                item.appendChild(input);

                horariosContainer.appendChild(item);
            }
        }

        frequencia.addEventListener("input", atualizarHorarios);

        document.getElementById("data_inicio").addEventListener("change", function() {
            document.getElementById("data_fim").min = this.value;

            if (
                document.getElementById("data_fim").value &&
                document.getElementById("data_fim").value < this.value
            ) {
                document.getElementById("data_fim").value = "";
            }
        });
    </script>

</body>

</html>

<?php
if (isset($stmtPacientes)) {
    $stmtPacientes->close();
}

$conexao->close();
?>