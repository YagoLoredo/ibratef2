<section class="vh-100">
  <script type="text/javascript" src="<?= baseUrl(); ?>assets/js/usuario.js"></script>
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">

       <!-- Imagem lateral -->
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="/assets/img/ibratef-icone2.jpg" class="img-fluid logoibratef" alt="Imagem de login" />

      </div>

      <!-- Formulário de Cadastro -->
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form action="<?= baseUrl() ?>Usuario/registraUsuario" method="post">

          <h2 class="fw-bold mb-4 text-center">Cadastro</h2>

          <!-- Nome -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" id="register-name" name="register-name" class="form-control form-control-lg" placeholder="Informe seu nome" required autofocus />
            <label class="form-label" for="register-name">Nome</label>
          </div>

          <!-- Email -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="register-email" name="register-email" class="form-control form-control-lg" placeholder="Informe um e-mail válido" required />
            <label class="form-label" for="register-email">E-mail</label>
          </div>

          <!-- Senha -->
        <div data-mdb-input-init class="form-outline mb-4">
         <input type="password"
            id="register-password"
            name="register-password"
            class="form-control form-control-lg"
            placeholder="Informe a senha"
            required
            onkeyup="checa_segur_senha('register-password', 'msgSenha', 'btnRegister')" />
          <label class="form-label" for="register-password">Senha</label>
          <div id="msgSenha" class="mt-2"></div>
        </div>

<!-- Confirmar Senha -->
<div data-mdb-input-init class="form-outline mb-4">
  <input type="password"
         id="confirm-register-password"
         name="confirm-register-password"
         class="form-control form-control-lg"
         placeholder="Confirme a senha"
         required
         onkeyup="checa_segur_senha('confirm-register-password', 'msgConfSenha', 'btnRegister')" />
  
  <label class="form-label" for="confirm-register-password">Confirmar Senha</label>
  <div id="msgConfSenha" class="mt-2"></div>
</div>

          <!-- Link para Login -->
           <!-- LGPD -->
<div class="form-check mb-3">
  <input class="form-check-input" type="checkbox" name="lgpd" id="lgpd" required>
  <label class="form-check-label" for="lgpd">
    Eu li e concordo com a 
    <a href="politica" target="_blank">Política de Privacidade</a> 
    e os 
    <a href="termos" target="_blank">Termos de Uso</a>
  </label>
</div>

<!-- Botão -->

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Registrar</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">
              Já tem uma conta? <a href="<?= baseUrl() ?>Login" class="link-danger">Entrar</a>
            </p>
          </div>

        </form>
      </div>
    </div>
  </div>

 
  </div>
</section>
