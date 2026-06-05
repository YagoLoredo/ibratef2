<h1 class="mt-4 mb-4 text-center">Seus ContraCheques</h1>

<?php
$userId    = $_SESSION['userId'] ?? null;
$userNivel = $_SESSION['userNivel'] ?? null;
?>

<div class="m-3">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php if ((int)\Core\Library\Session::get("userNivel") === 1): ?>
    <div class="mb-3 text-end">
        <a href="<?= baseUrl() ?>ContraCheque/form/insert/0" 
           class="btn btn-success botoes">
            ➕ Novo
        </a>
    </div>
<?php endif; ?>

<form method="POST" action="<?= baseUrl() ?>ContraCheque/index" class="row mb-4">

    <div class="col-md-3">
        <input type="text" 
               name="id" 
               class="form-control"
               placeholder="ID"
               value="<?= htmlspecialchars($_POST['id'] ?? '') ?>">
    </div>

    <div class="col-md-3">
        <input type="text" 
               name="descricao" 
               class="form-control"
               placeholder="Descrição"
               value="<?= htmlspecialchars($_POST['descricao'] ?? '') ?>">
    </div>

    <div class="col-md-4">
        <input type="text" 
               name="busca" 
               class="form-control"
               placeholder="Funcionário"
               value="<?= htmlspecialchars($_POST['busca'] ?? '') ?>">
    </div>

    <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">🔍</button>
        <a href="<?= baseUrl() ?>ContraCheque/index" class="btn btn-secondary w-100">❌</a>
    </div>

</form>

<form method="POST" action="<?= baseUrl() ?>ContraCheque/acaoEmMassa">

    <table class="table table-bordered table-hover table-sm tabela">

    <thead class="table-light">
    <tr>
        <th width="40"><input type="checkbox" class="selectAllItem"></th>
        <th width="60">ID</th>
        <th>Descrição</th>
        <th>Data</th>
        <th>Funcionário</th>
        <th width="120" class="text-center">Ações</th>
    </tr>
    </thead>

    <tbody>

    <?php if (!empty($contracheques)): ?>
    <?php foreach ($contracheques as $value): ?>

    <tr>
        <td>
            <input type="checkbox" name="ids[]" value="<?= $value['id'] ?>" class="selectSubItem">
        </td>

        <td class="text-center"><?= $value['id'] ?></td>

        <td><?= htmlspecialchars($value['descricao'] ?? '') ?></td>

        <td><?= !empty($value['data']) ? date('d/m/Y', strtotime($value['data'])) : '-' ?></td>

        <td><?= htmlspecialchars($value['nome_funcionario'] ?? '') ?></td>

        <td class="text-center">

            <?php if ((int)\Core\Library\Session::get("userNivel") === 1): ?>
            <a href="<?= baseUrl() ?>ContraCheque/form/view/<?= $value['id'] ?>"
               title="Visualizar"
               class="text-info me-2">
                <i class="bi bi-eye-fill"></i>
            </a>
            <?php endif; ?>

            <?php if (!empty($value['arquivo'])): ?>
                <a href="<?= baseUrl() ?>ContraCheque/download/<?= $value['id'] ?>"
                   title="Baixar ContraCheque"
                   class="text-primary me-2">
                    <i class="bi bi-download"></i>
                </a>
            <?php endif; ?>

            <?php if ((int)\Core\Library\Session::get("userNivel") === 1): ?>
                <a href="<?= baseUrl() ?>ContraCheque/form/update/<?= $value['id'] ?>" 
                   title="Alterar" 
                   class="text-warning me-2">
                   <i class="bi bi-pencil-square"></i>
                </a>

                <a href="<?= baseUrl() ?>ContraCheque/form/delete/<?= $value['id'] ?>" 
                   title="Excluir" 
                   class="text-danger"
                   onclick="return confirm('Tem certeza que deseja excluir?')">
                   <i class="bi bi-trash-fill"></i>
                </a>
            <?php endif; ?>

        </td>

    </tr>

    <?php endforeach; ?>
    <?php else: ?>

    <tr>
        <td colspan="6" class="text-center text-muted">
            Nenhum Contra Cheque encontrado.
        </td>
    </tr>

    <?php endif; ?>

    </tbody>
    </table>

    <?php if (!empty($contracheques) && (int)\Core\Library\Session::get("userNivel") === 1): ?>
        <div class="mt-3">
            <button type="submit" name="acao" value="excluir" class="btn btn-danger"
                onclick="return confirm('Tem certeza que deseja excluir os contracheques selecionados?')">
                <i class="bi bi-trash-fill"></i> Excluir Selecionados
            </button>
        </div>
    <?php endif; ?>

</form>

</div>

<script>
document.querySelectorAll('.selectAllItem').forEach(headerCheckbox => {
    headerCheckbox.addEventListener('click', function() {
        const form = this.closest('form');
        form.querySelectorAll('.selectSubItem').forEach(rowCheckbox => {
            rowCheckbox.checked = this.checked;
        });
    });
});
</script>