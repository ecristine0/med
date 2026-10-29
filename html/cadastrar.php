<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - MedTime</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="cadastro-page">
        <section class="cadastro-container">

            <header class="cadastro-header">
                <span class="cadastro-etiqueta">MEDTIME</span>
                <h1>Crie sua conta</h1>
                <p>Cuide da sua saúde com mais facilidade. Preencha seus dados para começar.</p>
            </header>

            <form action="salvar_cadastro.php" method="POST" class="cadastro-form" id="formCadastro">

                <div class="cadastro-form-group">
                    <label for="pessoa_nome">Nome completo</label>
                    <input
                        type="text"
                        id="pessoa_nome"
                        name="pessoa_nome"
                        placeholder="Ex.: Maria da Silva"
                        autocomplete="name"
                        required>
                </div>

                <div class="cadastro-form-group">
                    <label for="pessoa_email">E-mail</label>
                    <input
                        type="email"
                        id="pessoa_email"
                        name="pessoa_email"
                        placeholder="Ex.: maria@email.com"
                        autocomplete="email"
                        required>
                </div>

                <div class="cadastro-form-group cadastro-senha">
                    <label for="pessoa_senha">Senha</label>
                    <input
                        type="password"
                        id="pessoa_senha"
                        name="pessoa_senha"
                        placeholder="Crie uma senha"
                        autocomplete="new-password"
                        required>

                    <ul class="regras-senha">
                        <li id="regra_quantidade" class="invalido">No mínimo 8 caracteres</li>
                        <li id="regra_maiuscula" class="invalido">Pelo menos uma letra maiúscula</li>
                    </ul>
                </div>

                <div class="cadastro-form-group">
                    <label for="pessoa_tipo">Tipo de usuário</label>
                    <select id="pessoa_tipo" name="pessoa_tipo" required>
                        <option value="">Selecione uma opção</option>
                        <option value="cuidador">Cuidador</option>
                        <option value="paciente">Paciente</option>
                    </select>
                </div>

                <div class="cadastro-form-group">
                    <label for="pessoa_data_nascimento">Data de nascimento</label>
                    <input
                        type="date"
                        id="pessoa_data_nascimento"
                        name="pessoa_data_nascimento"
                        autocomplete="bday"
                        required>
                </div>

                <div class="cadastro-form-group">
                    <label for="pessoa_peso">Peso <span>(kg)</span></label>
                    <input
                        type="number"
                        id="pessoa_peso"
                        name="pessoa_peso"
                        placeholder="Ex.: 70.5"
                        step="0.1"
                        min="0"
                        required>
                </div>

                <div class="cadastro-form-group">
                    <label for="pessoa_altura">Altura <span>(m)</span></label>
                    <input
                        type="number"
                        id="pessoa_altura"
                        name="pessoa_altura"
                        placeholder="Ex.: 1.70"
                        step="0.01"
                        min="0"
                        required>
                </div>

                <button type="submit" class="cadastro-button">
                    Criar minha conta
                </button>

            </form>

            <div class="cadastro-footer">
                <p>Já possui uma conta? <a href="login.php">Entre aqui</a></p>
                <a href="index.php" class="cadastro-back-link">Voltar para o início</a>
            </div>

        </section>
    </main>

    <script>
        const campoSenha = document.getElementById('pessoa_senha');
        const regraQuantidade = document.getElementById('regra_quantidade');
        const regraMaiuscula = document.getElementById('regra_maiuscula');
        const formulario = document.getElementById('formCadastro');

        function validarRegra(valor, regra) {
            regra.classList.toggle('valido', valor);
            regra.classList.toggle('invalido', !valor);
        }

        function validarSenha() {
            const quantidade = campoSenha.value.length >= 8;
            const maiuscula = /[A-Z]/.test(campoSenha.value);

            validarRegra(quantidade, regraQuantidade);
            validarRegra(maiuscula, regraMaiuscula);

            return quantidade && maiuscula;
        }

        campoSenha.addEventListener('input', validarSenha);

        formulario.addEventListener('submit', function(evento) {
            if (!validarSenha()) {
                evento.preventDefault();
                campoSenha.focus();
            }
        });
    </script>
</body>

</html>