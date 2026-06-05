<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="background: #2b0d0d; color: white;">
                <div class="card-header border-0 text-center pt-4" style="background: #1f0808;">
                    <h4 class="mb-0 fw-bold" style="letter-spacing: 0.5px;">Meu Perfil</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (isset($_GET['sucesso'])): ?>
                        <div class="alert alert-success text-center py-2 small" role="alert">
                            ✓ Perfil atualizado com sucesso!
                        </div>
                    <?php endif; ?>

                    <form action="<?= baseUrl() ?>usuario/salvarPerfil" method="POST" enctype="multipart/form-data">
                        
                        <div class="text-center mb-4 position-relative">
                            <?php if (\Core\Library\Session::get('userFoto')): ?>
                                <img src="<?= baseUrl() ?>assets/img/perfis/<?= \Core\Library\Session::get('userFoto') ?>?t=<?= time() ?>" 
                                     class="rounded-circle border border-2 border-light shadow-sm" 
                                     width="130" height="130" style="object-fit: cover;" id="preview-foto">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center border border-2 border-light shadow-sm" 
                                     style="width: 130px; height: 130px;" id="avatar-padrao">
                                    <i class="fa-solid fa-user text-white fs-1"></i>
                                </div>
                                <img src="" class="rounded-circle border border-2 border-light shadow-sm d-none" 
                                     width="130" height="130" style="object-fit: cover;" id="preview-foto">
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold opacity-75">Alterar Foto de Perfil</label>
                            <input type="file" name="foto" class="form-control form-control-sm text-dark" accept="image/*" onchange="previewImagem(event)">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold opacity-75">Nome Completo</label>
                            <input type="text" name="nome" class="form-control text-dark fw-normal" value="<?= \Core\Library\Session::get('userNome') ?>" required style="background: #fff;">
                        </div>
                        
                        <div class="mb-5">
                            <label for="email" class="form-label small fw-bold opacity-75">Email</label>
                            <input type="email" class="form-control text-dark fw-normal" id="email" name="email" placeholder="Email do Usuário" maxlength="150" value="<?= setValor('email') ?>" required style="background: #fff;">
                            <?= setMsgFilderError('email') ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white fw-bold border-0" style="background: #e63946;">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Salvar Alterações
                            </button>
                            <a href="<?= baseUrl() ?>home" class="btn btn-sm btn-link text-white-50 text-decoration-none">Voltar para Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para mostrar a imagem na tela assim que o usuário seleciona o arquivo
function previewImagem(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('preview-foto');
        const avatarPadrao = document.getElementById('avatar-padrao');
        
        output.src = reader.result;
        output.classList.remove('d-none');
        
        if (avatarPadrao) {
            avatarPadrao.classList.add('d-none');
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>