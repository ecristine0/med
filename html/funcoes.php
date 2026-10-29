<?php
require_once "conexao.php";

//login
function Login($conexao, $email, $senha)
{
    $sql = "SELECT * FROM pessoa WHERE pessoa_email = ? AND pessoa_senha = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $pessoa = $resultado->fetch_assoc();
        $_SESSION['pessoa_id'] = $pessoa['pessoa_id'];
        $_SESSION['pessoa_nome'] = $pessoa['pessoa_nome'];
        $_SESSION['pessoa_tipo'] = $pessoa['pessoa_tipo'];

        return true;
    }

    return false;
}

function VerificarLogin()
{

    return isset($_SESSION['pessoa_id']);
}




//medicamento
function listarmedicamento($conexao)
{
    return $conexao->query("SELECT * FROM banco.medicamento");
}


function inserirmedicamento($conexao, $nome, $laboratorio, $categoria, $observacao, $arquivoImagem)
{
    $caminhoImagem = uploadCapa($arquivoImagem);
    if (!$caminhoImagem) {
        return false;
    }
    $sql = "INSERT INTO banco.medicamento
            (medicamento_nome, medicamento_foto, medicamento_laboratorio,
             medicamento_categoria, medicamento_obervacao)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param(
        "sssss",
        $nome,
        $caminhoImagem,
        $laboratorio,
        $categoria,
        $observacao
    );
    return $stmt->execute();
}

function buscarmedicamentoid($conexao, $id)
{
    $sql = "SELECT * FROM banco.medicamento WHERE medicamento_id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}


function buscarmedicamentopornome($conexao, $nome)
{
    $sql = "SELECT * FROM banco.medicamento where medicamento_nome LIKE ?";
    $stmt = $conexao->prepare($sql);
    $nomebusca = "%" . $nome . "%";
    $stmt->bind_param("s", $nomebusca);
    $stmt->execute();

    return $stmt->get_result();
}

function atualizarmedicamento($conexao, $id, $nome, $laboratorio, $categoria, $observacao, $foto)
{
    $sql = "UPDATE banco.medicamento SET medicamento_nome = ?, medicamento_laboratorio = ?, medicamento_categoria = ?, medicamento_observacao = ?, medicamento_foto = ? WHERE medicamento_id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssi", $nome, $laboratorio, $categoria, $observacao, $foto, $id);

    return $stmt->execute();
}

function deletarmedicamento($conexao, $id)
{
    $sql = "DELETE FROM banco.medicamento WHERE medicamento_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


//idoso
function cadastraridoso($conexao, $nome, $email, $senha, $tipo, $data_nascimento, $peso, $altura, $foto)
{

    if ($data_nascimento > '1966-08-06') {
        return false;
    }

    $sql = "INSERT INTO banco.pessoa
            (pessoa_nome, pessoa_email, pessoa_senha, pessoa_tipo,
             pessoa_data_nascimento, pessoa_peso, pessoa_altura, pessoa_foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssdds", $nome, $email, $senha, $tipo, $data_nascimento, $peso, $altura, $foto);

    return $stmt->execute();
}

function listaridoso($conexao)
{
    return $conexao->query("SELECT * FROM banco.pessoa");
}
function buscaridoso($conexao, $id)
{
    $sql = "SELECT * FROM banco.pessoa WHERE pessoa_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function buscaridosopornome($conexao, $nome)
{
    $sql = "SELECT * FROM banco.pessoa where pessoa_nome LIKE ?";
    $stmt = $conexao->prepare($sql);
    $nomebusca = "%" . $nome . "%";
    $stmt->bind_param("s", $nomebusca);
    $stmt->execute();

    return $stmt->get_result();
}


function atualizaridoso($conexao, $id, $nome, $email, $senha, $tipo, $data_nascimento, $peso, $altura, $foto)
{
    $sql = "UPDATE banco.pessoa SET pessoa_nome = ?, pessoa_email = ?, pessoa_senha = ?, pessoa_tipo = ?, pessoa_data_nascimento = ?, pessoa_peso, pessoa_altura, pessoa_foto WHERE pessoa_id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssddsi", $nome, $email, $senha, $tipo, $data_nascimento, $peso, $altura, $foto, $id);
    return $stmt->execute();
}

function deletaridoso($conexao, $id)
{
    $sql = "DELETE FROM banco.pessoa WHERE pessoa_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

//cuidado
function listarcuidador($conexao)
{
    return $conexao->query("SELECT * FROM banco.cuidado");
}


function inserircuidador($conexao, $nome, $data_nas, $arquivoImagem)
{
    $caminhoImagem = uploadCapa($arquivoImagem);

    if (!$caminhoImagem) {
        return false;
    }

    $sql = "INSERT INTO leitores (cuidador_nome, cuidador_data_nas, arquivo_Imagem) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sss", $nome, $data_nas, $caminhoImagem);
    return $stmt->execute();
}
//foto
function uploadCapa($arquivo)
{
    $diretorio = 'foto/';
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png'];

    if (!in_array($extensao, $permitidas)) {
        return false;
    }

    if ($arquivo['size'] > 1024 * 1024 * 2) { // permite até 2MB
        return false;
    }

    $nomeArquivo = uniqid() . "_" . $arquivo['name'];
    $caminho = $diretorio . $nomeArquivo; // uploads/capas/13516516has5_arvore.png

    if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
        return $caminho;
    }

    return false;
}

function buscarcuidador($conexao, $id)
{
    $sql = "SELECT * FROM banco.cuidadado WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function buscarcuidadopornome($conexao, $nome)
{
    $sql = "SELECT * FROM banco.cuidado WHERE nome LIKE ?";
    $stmt = $conexao->prepare($sql);
    $nomeBusca = "%" . $nome . "%";
    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();
    return $stmt->get_result();
}

function atualizarcuidado($conexao, $nome, $data_nas, $foto)
{
    $sql = "UPDATE leitores SET cuidado_nome= ?, cuidado_data_nas= ?, cuidado_foto = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sss", $nome, $data_nas, $foto);
    return $stmt->execute();
}

function deletarcuidado($conexao, $id)
{
    $sql = "DELETE FROM banco.cuidado WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


















//prescrição

function atualizaprescricao($conexao, $id, $frequencia, $dosagem, $horario_primeira_dose, $data_inicio, $observacao)
{
    $sql = "UPDATE banco.prescricao SET prescricao_frequencia = ?, prescricao_dosagem = ?, prescricao_horario_primeira_dose = ?, prescricao_data_inicio = ?, prescricao_observacao = ? WHERE prescricao_id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("iidsss", $id, $frequencia, $dosagem, $horario_primeira_dose, $data_inicio, $observacao);
    return $stmt->execute();
}
function deletarprescricao($conexao, $id)
{
    $sql = "DELETE FROM banco.prescricao WHERE prescricao_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function listarprescricao($conexao)
{
    return $conexao->query("SELECT * FROM banco.prescricao");
}
