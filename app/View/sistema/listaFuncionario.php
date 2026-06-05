<?= formTitulo("Lista de Funcionários", true) ?>

<?php if (!empty($dados)): ?>
<form method="POST" action="<?= baseUrl() ?>Funcionario/acaoEmMassa">

    <div class="m-2">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th width="40"><input type="checkbox" class="selectAllItem"></th>
                    <th scope="col">Id</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Cargo</th>
                    <th scope="col">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $value): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" value="<?= $value['id'] ?>" class="selectSubItem">
                        </td>
                        
                        <th scope="row"><?= $value['id'] ?></th>
                        
                        <td><?= htmlspecialchars($value['nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($value['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($value['cargo'] ?? '') ?></td>

                        <td>
                            <a href="<?= baseUrl() ?>Funcionario/form/view/<?= $value['id'] ?>" 
                               title="Visualizar" 
                               class="text-info me-2">
                               <i class="bi bi-eye-fill"></i>
                            </a>

                            <a href="<?= baseUrl() ?>Funcionario/form/update/<?= $value['id'] ?>" 
                               title="Alterar" 
                               class="text-warning me-2">
                               <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="<?= baseUrl() ?>Funcionario/form/delete/<?= $value['id'] ?>" 
                               title="Excluir" 
                               class="text-danger"
                               onclick="return confirm('Tem certeza que deseja excluir?')">
                               <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-3">
            <button type="submit" name="acao" value="inativar" class="btn btn-warning">
                <i class="bi bi-pause-fill"></i> Inativar Selecionados
            </button>

            <button type="submit" name="acao" value="excluir" class="btn btn-danger"
                onclick="return confirm('Tem certeza que deseja excluir os funcionários selecionados?')">
                <i class="bi bi-trash-fill"></i> Excluir Selecionados
            </button>
        </div>
    </div>  
</form>

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

<?php else: ?>
    <div class="alert alert-warning mt-5 mb-5" role="alert">Nenhum funcionário encontrado.</div>   
<?php endif; ?>