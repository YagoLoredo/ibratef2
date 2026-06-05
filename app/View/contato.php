<?php $this->loadHelper("emailHelper"); ?>

<section class=" bg-light">

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
