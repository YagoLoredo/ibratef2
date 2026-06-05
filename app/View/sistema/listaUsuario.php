<?php

$aNivel  = ["1" => "Super Administrador", "11" => "Administrador", "21" => "Usuário"];
$aStatus = ["1" => "Ativo", "2" => "Inativo", "3" => "Bloqueado"];

$userId    = $_SESSION['userId'] ?? null;
$userNivel = $_SESSION['userNivel'] ?? null;

?>

<?= formTitulo("Lista de Usuários", true) ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php if (count($dados) > 0): ?>

<form method="POST" action="<?= baseUrl() ?>Usuario/acaoEmMassa">

    <div class="m-2">

        <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th scope="col">Id</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Nível</th>
                    <th scope="col">Status</th>
                    <th scope="col">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $value): ?>

                    <?php
                        // Usuário comum só vê a si mesmo
                        if ($userNivel == 21 && $value['id'] != $userId) {
                            continue;
                        }
                    ?>

                    <tr>
                        <td>
                            <input type="checkbox" name="usuarios[]" value="<?= $value['id'] ?>" class="selectItem">
                        </td>

                        <th scope="row"><?= $value['id'] ?></th>
                        <td><?= htmlspecialchars($value['nome']) ?></td>
                        <td><?= htmlspecialchars($value['email']) ?></td>
                        <td><?= $aNivel[$value['nivel']] ?? '-' ?></td>
                        <td><?= $aStatus[$value['statusRegistro']] ?? '-' ?></td>

                        <td>
                            <a href="<?= baseUrl() ?>Usuario/form/view/<?= $value['id'] ?>" 
                               title="Visualizar" 
                               class="text-info me-2">
                               <i class="bi bi-eye-fill"></i>
                            </a>

                            <?php if ($userNivel <= 21 || $value['id'] == $userId): ?>
                                <a href="<?= baseUrl() ?>Usuario/form/update/<?= $value['id'] ?>" 
                                   title="Alterar" 
                                   class="text-warning me-2">
                                   <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= baseUrl() ?>Usuario/form/delete/<?= $value['id'] ?>" 
                                   title="Excluir" 
                                   class="text-danger"
                                   onclick="return confirm('Tem certeza que deseja excluir?')">
                                   <i class="bi bi-trash-fill"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-3">
            <button type="submit" name="acao" value="inativar" class="btn btn-warning">
                Inativar Selecionados
            </button>

            <button type="submit" name="acao" value="excluir" class="btn btn-danger"
                onclick="return confirm('Tem certeza que deseja excluir os selecionados?')">
                Excluir Selecionados
            </button>
        </div>

    </div>

</form>

<script>
document.getElementById('selectAll').addEventListener('click', function() {
    let checkboxes = document.querySelectorAll('.selectItem');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

<?php else: ?>

    <div class="alert alert-warning mt-5 mb-5" role="alert">
        Não foram localizados registros...
    </div>

<?php endif; ?>