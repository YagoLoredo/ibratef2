<?= formTitulo("Cadastro de Contra-Cheque") ?>

<div class="m-2">

    <form method="POST" action="<?= $this->request->formAction() ?>" enctype="multipart/form-data">
    <?php if (!empty($data['arquivo'])): ?>
        <input type="hidden" name="nomeArquivo" value="<?= $data['arquivo'] ?>">
    <?php endif; ?> 
        <input type="hidden" name="id" value="<?= setValor("id") ?>">

        <div class="row">

            <!-- Descrição -->
            <div class="col-md-6 mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control"
                       name="descricao"
                       id="descricao"
                       value="<?= setValor("descricao") ?>"
                       placeholder="Ex: Mensalidade Março"
                       required>
                <?= setMsgFilderError("descricao") ?>
            </div>
            <div class="col-4 mb-3">
                <label for="funcionario_id" class="form-label">Funcionário</label>
                 <select class="form-control" name="funcionario_id" id="funcionario_id" required>
            <option value="" <?= setValor('funcionario_id') == "" ? "SELECTED" : "" ?>>...</option>
                <?php foreach ($dados['aFuncionario'] as $value): ?>
                <option value="<?= $value['id'] ?>" <?= setValor('funcionario_id') == $value['id'] ? "SELECTED" : "" ?>>
                <?= $value['nome'] ?>
            </option>
                <?php endforeach; ?>
             </select>
            </div>
            <!-- Vencimento -->
            <div class="col-md-3 mb-3">
                <label for="data" class="form-label">Data</label>
                <input type="date" class="form-control"
                       name="data"
                       id="data"
                       value="<?= setValor("data") ?>"
                       required>
                <?= setMsgFilderError("data") ?>
            </div>

            <!-- Status -->
            <div class="form-group col-4 mb-3">
                    <?= comboboxBoleto(setValor('statusRegistro')) ?>
                    <?= setMsgFilderError('statusRegistro') ?>
                </div>

            <!-- Upload PDF -->
            <div class="col-md-12 mb-3">
   
            <?php if (in_array($this->request->getAction(), ['insert', 'update'])): ?>
                <label for="arquivo" class="form-label">Arquivo do ContraCheque (PDF)</label>
                <input type="file"
                       class="form-control"
                       name="arquivo"
                       id="arquivo"
                       accept="application/pdf">

                <?= setMsgFilderError("arquivo") ?>
                <?php endif; ?>


                <?php if (!empty($data['arquivo'])): ?>
                    <div class="mt-2">
                        <a href="/contracheque/download/<?= $data['id'] ?>" class="btn btn-sm btn-outline-primary botoes">
                            📄 Ver PDF atual
                        </a>
                    </div>
                <?php endif; ?>
                
            </div>

        </div>

        <?= formButton() ?>

    </form>
</div>