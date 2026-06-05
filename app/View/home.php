<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Início - Ibratef</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= baseUrl('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= baseUrl('assets/bootstrap/css/costumizado.css') ?>">

</head>
<body>

<!-- Banner Principal -->
<div class="img-banner">
    <img src="<?="/assets/img/ibratefbanner.jpg"?>" 
         alt="Bem-vindo à IBRATEF" 
         class="img-banner w-100" 
>
</div>


<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

<div class="container mb-5">

<h3 class="text-center text-secondary mb-4">Entre em Contato</h3>

<div class="row g-4">

<!-- Suporte Técnico -->
<div class="col-md-6 col-xl-3">
<div class="card shadow h-100">

<img src="/assets/img/suporte.png"
class="card-img-top img-servico"
alt="Suporte Técnico">

<div class="card-body text-center">

<h5 class="card-title">Suporte Técnico</h5>

<p class="card-text">
Atendimento especializado para manutenção e assistência técnica.
</p>

<p>
<i class="fa fa-envelope"></i> suporte@ibratef.com.br
</p>

<p>
<i class="fa fa-phone"></i> (32) 99904-0003
</p>

<a href="<?= baseUrl()?>agendamento/form/insert/0" class="btn botoes">
Abrir Chamado
</a>

</div>
</div>
</div>


<!-- Certificado Digital -->
<div class="col-md-6 col-xl-3">
<div class="card shadow h-100 fade-in">

<img src="/assets/img/certificado.png"
class="card-img-top img-servico"
alt="Certificado Digital">

<div class="card-body text-center">
<h5 class="card-title">Certificado Digital</h5>

<p class="card-text">
Emissão e renovação de certificados digitais com segurança.
</p>

<p>
<i class="fa fa-envelope"></i> certificado@ibratef.com.br
</p>

<p>
<i class="fa fa-phone"></i> (32) 99974-0004
</p>

<a href="https://wa.me/5532999740004?text=Olá,%20preciso%20de%20Certificado%20Digital"
class="btn botoes">
Solicitar Certificado
</a>

</div>
</div>
</div>

<!-- Automação Comercial -->
<div class="col-md-6 col-xl-3">
<div class="card shadow h-100">

<img src="/assets/img/automacao.png"
class="card-img-top img-servico"
alt="Automação Comercial">

<div class="card-body text-center">

<h5 class="card-title">Automação Comercial</h5>

<p class="card-text">
Sistemas de vendas, PDV e controle completo para sua empresa.
</p>

<p>
<i class="fa fa-envelope"></i> contato@ibratef.com.br
</p>

<p>
<i class="fa fa-phone"></i> (32) 99974-0004
</p>

<a href="https://wa.me/5532999740004?text=Olá,%20preciso%20de%20um%20Sistema"
    class="btn botoes">
            Falar com Consultor
            </a>

</div>
</div>
</div>


<!-- WhatsApp -->
<div class="col-md-6 col-xl-3">
<div class="card shadow h-100">

<img src="/assets/img/whatsapp2.png"
class="img-servico"
alt="WhatsApp">

<div class="card-body text-center">

<h5 class="card-title">WhatsApp</h5>

<p class="card-text">
Não sabe por onde começar? Fale com a gente!
</p>

<p>
<i class="fa fa-whatsapp"></i> Atendimento rápido
</p>

<p>
<i class="fa fa-phone"></i> (32) 99904-0003
</p>

<a href="https://wa.me/553299040003?text=Olá,%20preciso%20de%20suporte%20técnico%20da%20IBRATEF"
class="btn botoes">
Falar no WhatsApp
</a>

</div>
</div>
</div>

</div>
</div>

<section id="servicos" class="container my-5">

    <h1 class="mt-4 mb-4 text-center">Nossos Serviços</h1>

    <div class="row">
        <?php if (!empty($dados) && is_array($dados)): ?>
            <?php foreach ($dados as $servico): ?>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-primary fade-in">

                        <?php if (!empty($servico['imgservico'])): ?>
                            <img 
                                src="<?= baseUrl() . 'imagem.php?file=tipo_servico/' . htmlspecialchars($servico['imgservico']) ?>" 
                                class="img-thumbnail card-img-top img-sistema"
                                alt="<?= htmlspecialchars($servico['nome']) ?>">
                        <?php else: ?>
                            <img 
                                src="<?= baseUrl('assets/img/sem-imagem.png') ?>" 
                                class="card-img-top">
                        <?php endif; ?>

                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">
                                <?= htmlspecialchars($servico['nome']) ?>
                            </h5>
                        </div>

                        <div class="card-footer text-center">
                            <a href="<?= baseUrl() ?>agendamento/form/insert"
                               class="btn btn-primary botoes"
                               onclick="if(!usuarioLogado){ alert('Você precisa estar logado'); return false; }">
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

</section>
    <section id="sistemas" class="container my-5">

    <h1 class="mt-4 mb-4 text-center">Nossos Sistemas</h1>

    <div class="row">
        <?php if (!empty($automacoes) && is_array($automacoes)): ?>
            <?php foreach ($automacoes as $automacao): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-primary fade-in">

                        <?php if (!empty($automacao['imgautomacao'])): ?>
                            <img 
                                src="<?= baseUrl() . 'imagem.php?file=automacao/' . htmlspecialchars($automacao['imgautomacao']) ?>" 
                                class="img-thumbnail card-img-top img-sistema"
                                alt="<?= htmlspecialchars($automacao['descricao']) ?>">
                        <?php else: ?>
                            <img 
                                src="<?= baseUrl('assets/img/sem-imagem.png') ?>" 
                                class="card-img-sistema"
                                alt="Sem imagem">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <?= htmlspecialchars($automacao['descricao']) ?>
                            </h5>
                        </div>

                        <div class="card-footer bg-transparent border-top-0">
                            <a href="<?= baseUrl() ?>Automacao/detalhe/<?= $automacao['id'] ?>" 
                               class="btn botoes">
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

</section>
<script src="<?= baseUrl('assets/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Servicos e Agendamento -->
<!-- PROBLEMAS -->
<div class="container my-5 text-center">

    <h2 class="fw-bold mb-4">Você está passando por isso?</h2>

    <div class="row justify-content-center mb-4">

        <div class="col-md-3 mb-3">
            <div class="card p-3 shadow rounded bg-light">
                <i class="fa fa-desktop fa-2x text-danger mb-2"></i>
                <p class="mb-0">Computador lento</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-3 shadow rounded bg-light">
                <i class="fa fa-lock fa-2x text-warning mb-2"></i>
                <p class="mb-0">Problemas com certificado</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-3 shadow rounded bg-light">
                <i class="fa fa-cogs fa-2x text-primary mb-2"></i>
                <p class="mb-0">Sistema desorganizado</p>
            </div>
        </div>

    </div>

    <h4 class="fw-bold text-success mb-3">
        A gente resolve tudo isso pra você 🚀
    </h4>

</div>
<div class="d-flex justify-content-center my-4">
    <a href="https://wa.me/5532999740004?text=Olá,%20preciso%20de%20suporte%20técnico%20da%20IBRATEF" class="btn botoes">Fale Conosco</a>
</div>

<section id="sobre" class="vh-100">
      <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">

      <!-- Imagem lateral -->
       <!-- Imagem lateral -->
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="/assets/img/ibratef-icone2.jpg"
          class="img-fluid logopet" alt="Logo PetAmigo" />
      </div>

      <!-- Conteúdo "Sobre Nós" -->
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <div class="p-3">

          <h2 class="fw-bold mb-4 text-center">Sobre Nós</h2>
          <p class="text-center fw-semibold fs-5">Automação Comercial com excelência!</p>

          <p>
            Desde 2019, nos dedicamos a oferecer serviços de qualidade, tanto em automaçao comercial, suporte técnico, e em emissao de Certificados Digitais
          </p>

          <p>
            Hoje a Ibratef é uma das maiores referencias da região, buscando sempre aperfeiçoar nossos serviços em busca da satisfação do cliente.
        </p>

          <div class="text-center mb-3 social_icon icones" style="font-size: 24px;">
            <a href="#" class="me-3" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" class="me-3" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="me-3" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<?php $this->loadHelper("emailHelper"); ?>

<section id="contato" class=" bg-light">

<div class="container">

<div class="row justify-content-center align-items-center g-5">

<!-- IMAGEM -->
<div class="col-lg-5 text-center ">

<img src="<?= baseUrl() ?>assets/img/ibratef-icone2.jpg"
class="img-fluid rounded shadow logopet"
style="max-height:420px"
alt="Logo IBRATEF">

<h4 class="mt-4 fw-bold">
Fale com a IBRATEF
</h4>

<p class="text-muted">
Entre em contato para conhecer nossos sistemas,
automação comercial e certificação digital.
</p>

</div>


<!-- FORMULÁRIO -->
<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-body p-5">

<h3 class="fw-bold mb-4 text-center">
Contato
</h3>

<form action="<?= baseUrl() ?>/Home/contatoEnviaEmail" method="POST" id="contactForm">

<!-- NOME -->
<div class="mb-3">

<label class="form-label">Nome</label>

<input type="text"
name="nome"
class="form-control"
placeholder="Digite seu nome"
required
maxlength="60"
value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">

<?= setMsgFilderError("nome") ?>

</div>


<!-- TELEFONE -->
<div class="mb-3">

<label class="form-label">Telefone</label>

<input type="text"
name="celular"
class="form-control"
placeholder="(00) 00000-0000"
required
value="<?= isset($_POST['celular']) ? htmlspecialchars($_POST['celular']) : '' ?>">

<?= setMsgFilderError("celular") ?>

</div>


<!-- EMAIL -->
<div class="mb-3">

<label class="form-label">E-mail</label>

<input type="email"
name="email"
class="form-control"
placeholder="seu@email.com"
required
value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

<?= setMsgFilderError("email") ?>

</div>


<!-- ASSUNTO -->
<div class="mb-3">

<label class="form-label">Assunto</label>

<input type="text"
name="assunto"
class="form-control"
placeholder="Resumo do assunto"
required
value="<?= isset($_POST['assunto']) ? htmlspecialchars($_POST['assunto']) : '' ?>">

<?= setMsgFilderError("assunto") ?>

</div>


<!-- MENSAGEM -->
<div class="mb-4">

<label class="form-label">Mensagem</label>

<textarea
name="mensagem"
class="form-control"
rows="5"
placeholder="Digite sua mensagem..."
required><?= isset($_POST['mensagem']) ? htmlspecialchars($_POST['mensagem']) : '' ?></textarea>

<?= setMsgFilderError("mensagem") ?>

</div>


<div class="mb-3 text-center">
<?= exibeAlerta() ?>
</div>


<div class="d-grid">

<button type="submit" class="btn btn-dark btn-lg botoes">

<i class="fa-solid fa-paper-plane"></i>
Enviar Mensagem

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</section>


<!-- Bootstrap JS -->
<script src="<?= baseUrl('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script>
const elements = document.querySelectorAll('.fade-in');

window.addEventListener('scroll', () => {
    elements.forEach(el => {
        const position = el.getBoundingClientRect().top;
        const screenHeight = window.innerHeight;

        if(position < screenHeight - 100){
            el.classList.add('show');
        }
    });
});
</script>
<script>
function scrollCarrossel(tipo, direcao) {
    const container = document.getElementById('carrossel-' + tipo);

    container.scrollBy({
        left: direcao * 300,
        behavior: 'smooth'
    });
}
</script>
</body>
</html>
