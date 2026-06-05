
<h1 class="mt-4 mb-4 text-center">Nossos Sistemas</h1>

<div class="row">
    <?php if (!empty($dados) && is_array($dados)): ?>
        <?php foreach ($dados as $automacao): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-primary">
                    <!-- Imagem do Automacao -->
<?php if (!empty($automacao['imgautomacao'])): ?>
    <img 
        src="<?= baseUrl() . 'imagem.php?file=automacao/' . htmlspecialchars($automacao['imgautomacao']) ?>" 
        class="img-thumbnail card-img-top img-sistema" 
        alt="<?= htmlspecialchars($automacao['descricao']) ?>" 
    >
    <input type="hidden" name="nomeImagem" id="nomeImagem" value="<?= htmlspecialchars($automacao['imgautomacao']) ?>">

        <?php else: ?>
                       <img src="<?= baseUrl('assets/img/sem-imagem.png') ?>" 
                             class="card-img-sistema" 
                             alt="Sem imagem" >
                    <?php endif; ?>

                    <!-- Corpo do card -->
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= htmlspecialchars($automacao['descricao']) ?></h5>   

                    </div>

                    <!-- Rodapé -->
                    <div class="card-footer bg-transparent border-top-0">
                    <a href="/Automacao/detalhe/<?= $automacao['id'] ?>" class="btn botoes">
                    Ver Detalhes
                    </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Nenhum sistema encontrado.
            </div>
        </div>
    <?php endif; ?>
</div>
