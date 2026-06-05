<section class="vh-100 d-flex align-items-center">

<div class="container">

<div class="row justify-content-center align-items-center g-5">

<!-- IMAGEM -->
<div class="col-lg-6 text-center">

<img src="<?= baseUrl() ?>assets/img/ibratef-icone2.jpg"
class="img-fluid rounded shadow logopet"
alt="Login IBRATEF">

</div>


<!-- FORMULÁRIO -->
<div class="col-lg-5">

<div class="card shadow border-0">

<div class="card-body p-5">

<h3 class="fw-bold text-center mb-4">
Acessar Sistema
</h3>

<form action="<?= baseUrl() ?>login/signIn" method="POST">

<!-- EMAIL -->
<div class="mb-3">

<label class="form-label">E-mail</label>

<input
type="email"
name="email"
class="form-control form-control-lg"
placeholder="Informe seu e-mail"
required
autofocus>

</div>


<!-- SENHA -->
<div class="mb-3">

<label class="form-label">Senha</label>

<input
type="password"
name="senha"
class="form-control form-control-lg"
placeholder="Informe sua senha"
required>

</div>


<div class="d-flex justify-content-between align-items-center mb-4">

<div class="form-check">

<input
class="form-check-input"
type="checkbox"
name="lembrar"
id="lembrar">

<label class="form-check-label" for="lembrar">
Lembrar-me
</label>

</div>

<a href="<?= baseUrl() ?>Login/esqueciASenha" class="small">
Esqueceu a senha?
</a>

</div>


<div class="mb-3 text-center">
<?= exibeAlerta() ?>
</div>


<div class="d-grid">

<button type="submit" class="btn btn-dark btn-lg botoes">

<i class="fa-solid fa-right-to-bracket "></i>
Entrar

</button>

</div>


<p class="text-center mt-4 small">

Não tem uma conta?

<a href="<?= baseUrl() ?>Home/Cadastro" class="fw-bold">
Registrar
</a>

</p>

</form>

</div>

</div>

</div>

</div>

</div>

</section>
