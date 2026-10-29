 <!DOCTYPE html>
 <html lang="pt-BR">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Cadastrar medicamento | MedTime</title>
     <link rel="stylesheet" href="style.css">
 </head>

 <body>

     <?php require_once "cabecalho.php"; ?>

     <main class="med-cadastro-page">
         <section class="med-cadastro-container">
             <div class="med-cadastro-header">
                 <span>MEDTIME</span>
                 <h1>Cadastre um medicamento</h1>
                 <p>Preencha as informações para adicionar um medicamento ao sistema.</p>
             </div>

             <?php if (isset($_GET['status']) && $_GET['status'] === 'sucesso'): ?>
                 <p class="med-mensagem sucesso">Medicamento cadastrado com sucesso!</p>
             <?php elseif (isset($_GET['status']) && $_GET['status'] === 'erro'): ?>
                 <p class="med-mensagem erro">Não foi possível cadastrar. Confira os dados e a imagem e tente novamente.</p>
             <?php endif; ?>

             <form action="salvar_med.php" method="post" enctype="multipart/form-data" class="med-cadastro-form">
                 <div class="med-campo">
                     <label for="medicamento_nome">Nome do medicamento</label>
                     <input type="text" id="medicamento_nome" name="medicamento_nome" required maxlength="255" placeholder="Ex.: Paracetamol">
                 </div>

                 <div class="med-campo">
                     <label for="medicamento_laboratorio">Laboratório</label>
                     <input type="text" id="medicamento_laboratorio" name="medicamento_laboratorio" required maxlength="255" placeholder="Nome do laboratório">
                 </div>

                 <div class="med-campo">
                     <label for="medicamento_categoria">Categoria</label>
                     <input type="text" id="medicamento_categoria" name="medicamento_categoria" required maxlength="255" placeholder="Ex.: Analgésico">
                 </div>

                 <div class="med-campo">
                     <label for="medicamento_observacao">Observações</label>
                     <textarea id="medicamento_observacao" name="medicamento_observacao" required maxlength="255" rows="4" placeholder="Informações adicionais"></textarea>
                 </div>
                 <div class="med-campo">
                     <label for="medicamento_foto">Foto do medicamento</label>
                     <input type="file" id="medicamento_foto" name="medicamento_foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required>
                     <small>Formatos permitidos: JPG e PNG. Tamanho máximo: 2 MB.</small>
                 </div>
                 <button type="submit" class="med-cadastro-button">Salvar medicamento</button>
                 <a href="index.php" class="med-voltar">Voltar para o início</a>
             </form>
         </section>
     </main>
     <?php require_once "rodape.php"; ?>
 </body>

 </html>