<?php
use Core\Library\Session;

// Pega erros da sessão, limpa a sessão e joga no $_POST para setMsgFilderError funcionar
$formErrors = Session::get('formError', []);
Session::set('formError', null);

if (!empty($formErrors)) {
    $_POST['formErrors'] = $formErrors;
}
?>

<?= formTitulo("Cliente") ?>

<div class="m-2">

    <!-- Opcional: mostrar mensagem geral de erro (se existir) -->
    <?php if (isset($_POST['formErrors']['geral'])): ?>
        <div class="alert alert-danger">
            <?= $_POST['formErrors']['geral'] ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $this->request->formAction() ?>">

        <!-- jQuery e Máscaras -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <input type="hidden" name="id" id="id" value="<?= setValor("id") ?>">

        <div class="row">
            <div class="col-6 mb-2">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" 
                       class="form-control" 
                       id="nome" 
                       name="nome" 
                       placeholder="Nome do Cliente"
                       maxlength="100"
                       value="<?= setValor("nome") ?>"
                       required
                       autofocus>
                <?= setMsgFilderError("nome") ?>
            </div>

            <div class="col-3 mb-2">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" 
                       class="form-control" 
                       id="telefone" 
                       name="telefone" 
                       placeholder="Telefone"
                       value="<?= setValor("telefone") ?>"
                       required>
                <?= setMsgFilderError("telefone") ?>
            </div>

            <div class="col-6 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="E-mail"
                       value="<?= setValor("email") ?>"
                       required>
                <?= setMsgFilderError("email") ?>
            </div>

            <div class="col-3 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" 
                       class="form-control" 
                       id="cpf" 
                       name="cpf" 
                       placeholder="CPF"
                       value="<?= setValor("cpf") ?>"
                       required>
                <?= setMsgFilderError("cpf") ?>
            </div>

            <div class="col-9 mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" 
                       class="form-control" 
                       id="endereco" 
                       name="endereco" 
                       placeholder="Endereço"
                       value="<?= setValor("endereco") ?>"
                       required>
                <?= setMsgFilderError("endereco") ?>
            </div>
        </div>

        <?= formButton() ?>

        <script>
            $(document).ready(function() {
                $('#cpf').mask('000.000.000-00');
                $('#telefone').mask('(00) 00000-0000');
            });
        </script>

    </form>

</div>
