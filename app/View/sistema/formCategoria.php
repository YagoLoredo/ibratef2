<?= formTitulo("Categoria") ?>

<div class="m-2">

    <form method="POST" action="<?= $this->request->formAction() ?>">

        <input type="hidden" name="id" id="id" value="<?= setValor("id") ?>">

        <div class="row">
            <div class="col-5 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" 
                    id="nome" 
                    name="nome" 
                    placeholder="Nome"
                    maxlength="30"
                    value="<?= setValor("nome") ?>"
                    required
                    autofocus>
                <?= setMsgFilderError("nome") ?>
            </div>

                <div class="form-group col-4 mb-3">
                    <?= comboboxStatus(setValor('statusRegistro')) ?>
                    <?= setMsgFilderError('statusRegistro') ?>
                </div>
        </div>

        <?= formButton() ?>

    </form>

</div>