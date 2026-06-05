<script>
    const usuarioLogado = <?= $logado ? 'true' : 'false' ?>;
</script>

<h1 class="mt-4 mb-4 text-center">Nossos Serviços</h1>

<div class="row">
    <?php if (!empty($dados) && is_array($dados)): ?>
        <?php foreach ($dados as $servico): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-primary">
                    <!-- Imagem do Serviço -->
                   <?php if (!empty($servico['imgservico'])): ?>
                        <img 
                            src="<?= baseUrl() . 'imagem.php?file=tipo_servico/' . htmlspecialchars($servico['imgservico']) ?>" 
                            class="img-thumbnail card-img-top img_padrao" 
                            alt="<?= htmlspecialchars($servico['nome']) ?>" 
                            style="object-fit: cover; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
                        >
                        <input type="hidden" name="nomeImagem" id="nomeImagem" value="<?= htmlspecialchars($servico['imgservico']) ?>">
                    <?php else: ?>
                        <img 
                            src="<?= baseUrl('assets/img/sem-imagem.png') ?>" 
                            class="card-img-top" 
                            alt="Sem imagem" 
                            style="height: 200px; object-fit: cover;"
                        >
                    <?php endif; ?>

                    <!-- Corpo do card -->
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= htmlspecialchars($servico['nome']) ?></h5>
                        <p class="card-text">
                            <strong>Preço:</strong> 
                            R$ <?= number_format(floatval($servico['preco']), 2, ',', '.') ?>
                        </p>
                    </div>

                    <!-- Rodapé -->
                    <div class="card-footer bg-transparent border-top-0 d-flex flex-column gap-2">
                        <?php $usuarioLogado = \Core\Library\Session::get("userId") ? true : false; ?>

                        <a href="<?= baseUrl() ?>agendamento/form/insert" 
                        class="btn btn-primary botoes"
                        onclick="if(!<?= $usuarioLogado ? 'true' : 'false' ?>){ alert('Você precisa estar logado para agendar um serviço.'); return false; }">
                        Agendar Serviço
                        </a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Nenhum serviço encontrado.
            </div>
        </div>
    <?php endif; ?>
    
</div>
