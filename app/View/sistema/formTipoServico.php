<?= formTitulo("Tipo de Serviço") ?>

<div class="m-2">
    <form method="POST" action="<?= $this->request->formAction() ?>" enctype="multipart/form-data">

        <input type="hidden" name="id" id="id" value="<?= setValor("id") ?>">

        <div class="row">

            <!-- Nome do serviço -->
            <div class="col-md-8 mb-3">
                <label for="nome" class="form-label">Nome do Serviço</label>
                <input type="text" class="form-control" 
                       id="nome" 
                       name="nome" 
                       placeholder="Ex: Certificado, formataçao..." 
                       maxlength="100"
                       value="<?= setValor("nome") ?>" 
                       required autofocus>
                <?= setMsgFilderError("nome") ?>
            </div>

            <!-- Preço -->
            <div class="col-md-4 mb-3">
                <label for="preco" class="form-label">Preço</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" class="form-control" 
                           id="preco" 
                           name="preco" 
                           placeholder="0,00"
                           value="<?= setValor("preco") ?>" 
                           required>
                </div>
                <?= setMsgFilderError("preco") ?>
            </div>

        </div>

        <div class="row">
            <?php if (in_array($this->request->getAction(), ['insert', 'update'])): ?>
                <div class="mb-3 col-12">
                    <label for="imgservico" class="form-label">Imagem do Serviço</label>
                    <input type="file" class="form-control" id="imgservico" name="imgservico">
                    <?= setMsgFilderError('imgservico') ?>
                </div>
            <?php endif; ?>

            <?php if (!empty(setValor("imgservico"))): ?>
                <div class="mb-3 col-12">
                    <h5>Imagem</h5>
                <img src="<?= baseUrl() . 'imagem.php?file=tipo_servico/' . setValor("imgservico") ?>" class="img-thumbnail" height="120" width="240" alt="Imagem do Serviço">
                    <input type="hidden" name="nomeImagem" id="nomeImagem" value="<?= setValor("imgservico") ?>">
                </div>
            <?php endif; ?>
        </div>

        <?= formButton() ?>

    </form>
</div>
