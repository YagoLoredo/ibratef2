<?php
function getYoutubeId($url)
{
    if (empty($url)) return null;

    $patterns = [
        '/youtube\.com\/watch\?v=([^\&]+)/',
        '/youtu\.be\/([^\?]+)/',
        '/youtube\.com\/shorts\/([^\?]+)/'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }

    return null;
}

$videoId = getYoutubeId($automacao['video_url'] ?? '');
?>

<div class="container mt-5">   

<?php if (!empty($automacao)):?>
        
    <div class="row">
        
        <!-- IMAGEM + VÍDEO -->
        <div class="col-md-6">
            <?php if (!empty($automacao['imgautomacao'])): ?>
                <img 
                    src="<?= baseUrl() . 'imagem.php?file=automacao/' . $automacao['imgautomacao'] ?>" 
                    class="img-fluid rounded shadow logoibratef"
                >
            <?php else: ?>
                <img src="<?= baseUrl() . 'assets/img/sem-imagem.png' ?>" class="img-fluid">
            <?php endif; ?>
        </div>
                
        <!-- DETALHES -->
        <div class="col-md-6 texto">
            <h2><?= htmlspecialchars($automacao['descricao']) ?></h2>

            <?= $automacao['detalhes'] ?>

            <!-- 🎥 VÍDEO EMBAIXO DOS DETALHES -->
            <?php if ($videoId): ?>
                <div class="mt-3 video-logo">
                    <iframe
                        src="https://www.youtube.com/embed/<?= $videoId ?>"
                        frameborder="0"
                        allowfullscreen>
                    </iframe>
                </div>
                
            <?php endif; ?>

            <a href="https://wa.me/5532999740004?text=Olá,%20preciso%20de%20um%20Sistema"
                class="btn btn-danger botoes">
                Solicitar
            </a>
        </div>

    </div>

    <hr class="mt-5">

    <h4>Comentários</h4>

    <?php 
    $usuarioLogado = \Core\Library\Session::get("userId") ? true : false; 
    ?>

    <!-- SE ESTIVER LOGADO -->
    <?php if ($usuarioLogado): ?>

        <form method="POST" action="/automacao/comentar">                
            <input type="hidden" name="automacao_id" value="<?= $automacao['id'] ?>">

            <div class="mb-3">
                <textarea 
                    name="comentario" 
                    class="form-control" 
                    placeholder="Digite seu comentário..." 
                    required></textarea>
            </div>

            <button class="btn btn-primary">Comentar</button>
        </form>

        <hr>

    <?php else: ?>

        <p>Você precisa estar logado para comentar.</p>

    <?php endif; ?>

    <!-- LISTA DE COMENTÁRIOS -->
    <?php if (!empty($comentarios)): ?>
        <?php foreach ($comentarios as $c): ?>

            <?php 
            $usuarioIdLogado = \Core\Library\Session::get("userId"); 
            $UserNivel = \Core\Library\Session::get("userNivel");
            ?>

            <div class="card mb-3">
                <div class="card-body">
                    <strong><?= htmlspecialchars($c['usuario_nome'] ?? 'Usuário') ?></strong>
                    <p class="mb-2"><?= htmlspecialchars($c['comentario']) ?></p>

                    <?php if ($usuarioIdLogado == $c['usuario_id'] || $UserNivel == 1): ?>
                        <form method="POST" action="/automacao/excluirComentario" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">

                            <button 
                                class="btn btn-danger"
                                onclick="return confirm('Deseja excluir este comentário?')">
                                Excluir
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum comentário ainda.</p>
    <?php endif; ?>

    <!-- MENSAGENS -->
    <?php if ($msg = \Core\Library\Session::getDestroy('msgSucesso')): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <?php if ($msg = \Core\Library\Session::getDestroy('msgErro')): ?>
        <div class="alert alert-danger"><?= $msg ?></div>
    <?php endif; ?>

    <a href="<?= ('/Home/Automacao') ?>" class="btn btn-secondary mt-3 botoes">
        Voltar
    </a>

<?php else: ?>

    <div class="alert alert-warning">
        Sistema não encontrado.
    </div>

<?php endif; ?>

</div>