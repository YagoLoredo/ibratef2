<?= formTitulo("Sistema") ?>

<div class="m-2">

    <form method="POST" action="<?= $this->request->formAction() ?>" enctype="multipart/form-data">

        <input type="hidden" name="id" id="id" value="<?= setValor("id") ?>">

        <div class="row">

            <div class="col-8 mb-3">
                <label for="descricao" class="form-label">Descriçao</label>
                <input type="text" class="form-control" 
                    id="descricao" 
                    name="descricao" 
                    placeholder="Descrição do Sistema"
                    maxlength="50"
                    value="<?= setValor("descricao") ?>"
                    required
                    autofocus>
                <?= setMsgFilderError("descricao") ?>
            </div>
                <div class="form-group col-4 mb-3">
                    <?= comboboxStatus(setValor('statusRegistro')) ?>
                    <?= setMsgFilderError('statusRegistro') ?>
                </div>
            <div class="col-4 mb-3">
                <label for="cate_id" class="form-label">Categoria</label>
                 <select class="form-control" name="cate_id" id="cate_id" required>
            <option value="" <?= setValor('cate_id') == "" ? "SELECTED" : "" ?>>...</option>
                <?php foreach ($dados['aCategoria'] as $value): ?>
                <option value="<?= $value['id'] ?>" <?= setValor('cate_id') == $value['id'] ? "SELECTED" : "" ?>>
                <?= $value['nome'] ?>
            </option>
                <?php endforeach; ?>
             </select>
            </div>
                         
            </div>
            </div>
            <div class="row">
            <?php if (in_array($this->request->getAction(), ['insert', 'update'])): ?>
                <div class="mb-3 col-12">
                    <label for="imgautomacao" class="form-label">Imagem do Automacao</label>
                    <input type="file" class="form-control" id="imgautomacao" name="imgautomacao" placeholder="Anexar a Imagem do Automacao" maxlength="100" value="<?= setValor('imgautomacao') ?>">
                    <?= setMsgFilderError('imgautomacao') ?>
                </div>
            <?php endif; ?>
            <!-- Observações -->
            <div class="col-md-12 mb-3">
                <label for="detalhes" class="form-label">Detalhes</label>
                <textarea class="form-control" 
                          name="detalhes" 
                          id="detalhes" 
                          rows="3" 
                          maxlength="255"
                          placeholder="Detalhes do sistema"><?= setValor("detalhes") ?></textarea>
                <?= setMsgFilderError("detalhes") ?>
            </div>
<div class="col-md-12 mb-3">
    <label for="video_url" class="form-label">Link do vídeo</label>
    <input type="url" 
           class="form-control"
           name="video_url" 
           id="video_url"
           placeholder="https://www.youtube.com/watch?v=..."
           value="<?= setValor('video_url') ?>">
    
    <?= setMsgFilderError('video_url') ?>
</div>
        </div>

            <?php if (!empty(setValor("imgautomacao"))): ?>
                <div class="mb-3 col-12">
                    <h5>Imagem</h5>
                    <img src="<?= baseUrl() . 'imagem.php?file=automacao/' . setValor("imgautomacao") ?>" class="img-thumbnail" height="120" width="240" alt="Imagem do Automacao">
                    <input type="hidden" name="nomeImagem" id="nomeImagem" value="<?= setValor("imgautomacao") ?>">
                </div>
            <?php endif; ?>
        </div>
        <?= formButton() ?>

    </form>

</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('detalhes');
</script>