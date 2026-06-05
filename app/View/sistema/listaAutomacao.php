<?= formTitulo("Lista de Sistemas", true) ?>

<div class="m-2">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<table class="table table-bordered table-striped table-hover table-sm">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Descrição</th>
                <th scope="col">Categoria</th>
                <th scope="col">Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $value): ?>
                <tr>
                    <th scope="row"><?= $value['id'] ?></th>
                    <td><?= $value['descricao'] ?></td>
                    <td><?= $value['nome'] ?></td>
                    <td>
                        <a href="<?= baseUrl() ?>Automacao/form/view/<?= $value['id'] ?>" 
                           title="Visualizar" 
                           class="text-info me-2">
                           <i class="bi bi-eye-fill"></i>
                        </a>

                        <a href="<?= baseUrl() ?>Automacao/form/update/<?= $value['id'] ?>" 
                           title="Alterar" 
                           class="text-warning me-2">
                           <i class="bi bi-pencil-square"></i>
                        </a>

                        <a href="<?= baseUrl() ?>Automacao/form/delete/<?= $value['id'] ?>" 
                           title="Excluir" 
                           class="text-danger">
                           <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
