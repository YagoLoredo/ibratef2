<?php
$userId    = $_SESSION['userId'] ?? null;
$userNivel = $_SESSION['userNivel'] ?? null;
?>

<?= formTitulo("Lista de Agendamentos", true) ?>

<?php if (!empty($agendamentos)): ?>
<form method="POST" action="<?= baseUrl() ?>Agendamento/acaoEmMassa">

    <div class="m-2">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th width="40"><input type="checkbox" class="selectAllItem"></th>
                    <th scope="col">Id</th>
                    <th scope="col">Data</th>
                    <th scope="col">Horário</th>
                    <th scope="col">Serviço</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Observações</th>
                    <th scope="col">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendamentos as $value): 
                    $podeEditarExcluir = (
                        $userNivel == 1 || 
                        ($userNivel == 21 && $value['usuario_id'] == $userId)
                    );
                ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" value="<?= $value['id'] ?>" class="selectSubItem">
                        </td>

                        <th scope="row"><?= $value['id'] ?></th>
                        
                        <td><?= !empty($value['data']) ? date('d/m/Y', strtotime($value['data'])) : '-' ?></td>
                        <td><?= !empty($value['horario']) ? substr($value['horario'], 0, 5) : '-' ?></td>
                        <td><?= htmlspecialchars($value['nome_servico'] ?? '') ?></td>
                        <td><?= htmlspecialchars($value['nome_usuario'] ?? '') ?></td>
                        <td><?= htmlspecialchars($value['observacoes'] ?? '-') ?></td>
                        
                        <td>
                            <a href="<?= baseUrl() ?>Agendamento/form/view/<?= $value['id'] ?>"
                               title="Visualizar"
                               class="text-info me-2">
                               <i class="bi bi-eye-fill"></i>
                            </a>
                            
                            <?php if ($podeEditarExcluir): ?>
                                <a href="<?= baseUrl() ?>Agendamento/form/update/<?= $value['id'] ?>"
                                   title="Alterar"
                                   class="text-warning me-2">
                                   <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?= baseUrl() ?>Agendamento/form/delete/<?= $value['id'] ?>"
                                   title="Excluir"
                                   class="text-danger me-2"
                                   onclick="return confirm('Tem certeza que deseja excluir?')">
                                   <i class="bi bi-trash-fill"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!empty($boletos)): ?>
        <div class="mt-3">
            <button type="submit" name="acao" value="excluir" class="btn btn-danger"
                onclick="return confirm('Tem certeza que deseja excluir os boletos selecionados?')">
                <i class="bi bi-trash-fill"></i> Excluir Selecionados
            </button>
        </div>
    <?php endif; ?>

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

<div class="alert alert-warning mt-5 mb-5" role="alert">
    Não foram localizados registros...
</div>

<?php endif; ?>