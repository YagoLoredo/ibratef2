<script>
    const usuarioLogado = <?= $logado ? 'true' : 'false' ?>;
</script>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Início - ibratef</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= baseUrl('assets/css/bootstrap.min.css') ?>">
</head>
<body>

<!-- Banner Principal -->
<div class="container-fluid p-0 mb-5">
    <img src="<?="/assets/img/ibratefbanner.jpg"?>" 
         alt="Bem-vindo à Ibratef" 
         class="img-fluid w-100 img-banner">
</div>


            <?php $usuarioLogado = \Core\Library\Session::get("userId") ? true : false; ?>

        <div class="row justify-content-center text-center">
            <div class="col-md-4 mx-2 mb-2">
            <a href="<?= baseUrl()?>agendamento/form/insert" class="btn btn-primary botoes" style="width: 100%;">Agendar Serviço</a>
            </div>
            <div class="col-md-4 mx-2 mb-2">
            <a href="<?= baseUrl()?>boleto" class="btn btn-primary botoes" style="width: 100%;">Visualizar Boletos</a>
            </div>
        </div>



<!-- Bootstrap JS -->
<script src="<?= baseUrl('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
