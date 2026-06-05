<?= formTitulo("Funcionario") ?>

<div class="m-2">

    <form method="POST" action="/funcionario/<?= $data['form_action'] ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <input type="hidden" name="id" id="id" value="<?= setValor("id") ?>">

        <div class="row">
            <div class="col-5 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" 
                    id="nome" 
                    name="nome" 
                    placeholder="Nome Funcionario"
                    maxlength="100"
                    value="<?= setValor("nome") ?>"
                    required
                    autofocus>
                <?= setMsgFilderError("nome") ?>
            </div>

            <div class="col-3 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" 
                    id="cpf" 
                    name="cpf" 
                    placeholder="CPF"
                    value="<?= setValor("cpf") ?>"
                    required>
                <?= setMsgFilderError("cpf") ?>
            </div>
        </div>
        <div class="col-4 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="E-mail"
                    value="<?= setValor("email") ?>"
                    required>
                <?= setMsgFilderError("email") ?>
            </div>
        </div>
        <div class="col-6 mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" 
                    id="cargo" 
                    name="cargo" 
                    placeholder="cargo"
                    value="<?= setValor("cargo") ?>"
                    required>
                <?= setMsgFilderError("cargo") ?>
            </div>
        </div>

        <?= formButton() ?>
            <script>
            $(document).ready(function(){
            $('#cpf').mask('000.000.000-00');
            });
        </script>

    </form>

</div>