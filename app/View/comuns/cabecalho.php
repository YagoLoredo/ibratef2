<?php
use Core\Library\Session;

// Invoca a nossa função recém-corrigida
$perfilHeader = getUsuarioLogado();

if ($perfilHeader) {
    // Alinha o fallback com o nome exato registrado na tabela do seu banco de dados
    $fotoSessao = !empty($perfilHeader['foto']) ? $perfilHeader['foto'] : 'default-avatar.png';
    $nomeExibir = !empty($perfilHeader['nome']) ? $perfilHeader['nome'] : 'Usuário';
} else {
    $fotoSessao = 'default-avatar.png';
    $nomeExibir = 'Visitante';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AtomPHP, microframework">
    <meta name="author" content="Yago Lorêdo">

    <title>IBRATEF | SISTEMAS E CERTIFICAÇAO DIGITAL</title>

    <link href="<?= baseUrl() ?>assets/img/ibratef-icone.png" rel="icon" type="image/png">
    <link href="<?= baseUrl() ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="<?= baseUrl() ?>assets/fontawesome-free-6.7.2-web/css/fontawesome.css" rel="stylesheet" />
    <link href="<?= baseUrl() ?>assets/fontawesome-free-6.7.2-web/css/brands.css" rel="stylesheet" />
    <link href="<?= baseUrl() ?>assets/fontawesome-free-6.7.2-web/css/solid.css" rel="stylesheet" />
    <link href="<?= baseUrl() ?>assets/fontawesome-free-6.7.2-web/css/sharp-thin.css" rel="stylesheet" />
    <link href="<?= baseUrl() ?>assets/fontawesome-free-6.7.2-web/css/duotone-thin.css" rel="stylesheet" />
    <link href="<?= baseUrl() ?>assets/fontawesome-free-6.7.2-web/css/sharp-duotone-thin.css" rel="stylesheet" />
    <link href="<?= baseUrl() ?>assets/bootstrap/css/costumizado.css" rel="stylesheet">

    <script src="<?= baseUrl() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= baseUrl() ?>">
                    <img class="login-img" src="<?= baseUrl() ?>assets/img/ibratef-simbolo.png" alt="Logo" height="90" width="90">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= baseUrl() ?>home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>#sobre">Quem Somos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>#contato">Contato</a>
                        </li>
                        
                        <?php if ((int)Session::get("userNivel") === 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>Sistema">Gerenciamento</a>
                        </li>
                        <?php endif; ?> 
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>#sistemas">Sistemas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>#servicos">Serviços</a>
                        </li>
                    </ul>

                    <?php if (Session::get("userId")): ?>
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item">
                                
                            <li class="nav-item dropdown me-3 position-relative" style="list-style: none;">
                                <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notif-bell-btn" style="cursor: pointer; padding-top: 8px;">
                                    <i class="fa-solid fa-bell fs-5 text-white"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                          id="notif-badge" 
                                          style="display: none; transform: translate(50%, -50%); margin-top: 5px; font-size: 0.75rem;">
                                        0
                                    </span>
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-end notif-dropdown-menu" aria-labelledby="notif-bell-btn">
                                    <li class="dropdown-header d-flex flex-column gap-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold" style="letter-spacing: 0.5px;">Notificações</span>
                                        </div>
                                        <button class="btn-notif-all" onclick="marcarTodasComoLidas(event)">
                                            <i class="fa-solid fa-check-double me-1"></i> Marcar todas como lidas
                                        </button>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider m-0" style="border-color: rgba(255,255,255,0.1);"></li>
                                    
                                    <ul id="notif-list" class="list-unstyled m-0 p-0">
                                        <li class="text-center p-4 text-white-50" id="notif-empty" style="font-size: 0.9rem;">
                                            <i class="fa-solid fa-bell-slash d-block mb-2 fs-4 opacity-50"></i>
                                            Nenhuma notificação nova
                                        </li>
                                    </ul>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle usuario-menu" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user"></i> <?= Session::get("userNome"); ?> <i class="fa-solid fa-chevron-down"></i>
                            </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>agendamento">Agendar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalDesativarConta">
                                            Excluir Conta
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>boleto">Boletos</a></li> 
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>Usuario/formTrocarSenha">Trocar a Senha</a></li>
                                    
                                    <?php if ((int)Session::get("userNivel") === 1 || (int)Session::get("userNivel") === 11): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <?php if ((int)Session::get("userNivel") === 1): ?>
                                            <li><a class="dropdown-item" href="<?= baseUrl() ?>cliente">Clientes</a></li>
                                            <li><a class="dropdown-item" href="<?= baseUrl() ?>funcionario">Funcionários</a></li>
                                        <?php endif; ?> 
                                        
                                        <li><a class="dropdown-item" href="<?= baseUrl() ?>usuario">Usuários</a></li> 
                                        <li><a class="dropdown-item" href="<?= baseUrl() ?>contracheque">Contra Cheques</a></li>   
                                        <li><a class="dropdown-item" href="<?= baseUrl() ?>tiposervico">Serviços</a></li>
                                        <li><a class="dropdown-item" href="<?= baseUrl() ?>automacao">Sistemas</a></li>
                                        <li><a class="dropdown-item" href="<?= baseUrl() ?>categoria">Categoria</a></li>
                                    <?php endif; ?> 
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>login/signOut">Sair</a></li>
                                </ul>
                            </li>
                        </ul>
                    <?php else: ?>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= baseUrl() ?>Login">Login</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container">
        <div class="modal fade" id="modalDesativarConta" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">⚠ Excluir Conta</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal"></button>
                    </div>
                    <form method="POST" action="<?= baseUrl() ?>Usuario/excluirConta">
                        <div class="modal-body">
                            <p>
                                Tem certeza que deseja <b>excluir sua conta</b>?<br>
                                Essa ação irá desativar seu acesso.
                            </p>
                            <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('notif-bell-btn')) {
        carregarNotificacoes();
        setInterval(carregarNotificacoes, 30000); 
    }
});

function carregarNotificacoes() {
    fetch('<?= baseUrl() ?>notificacao/buscarNaoLidas') 
        .then(response => response.json())
        .then(res => {
            if (res.status === 'sucesso') {
                const badge = document.getElementById('notif-badge');
                const lista = document.getElementById('notif-list');
                
                if (res.total > 0) {
                    badge.innerText = res.total;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }

                // Ajustando o menu dropdown principal para ter tamanho fixo e permitir quebras
                const menuDropdown = document.querySelector('.notif-dropdown-menu');
                if (menuDropdown) {
                    menuDropdown.style.setProperty('width', '340px', 'important');
                    menuDropdown.style.setProperty('max-width', '90vw', 'important');
                    menuDropdown.style.setProperty('background-color', '#b91c1c', 'important');
                }

                if (res.dados.length > 0) {
                    lista.innerHTML = ''; 
                    
                    res.dados.forEach(item => {
                        let rota = 'home';
                        
                        switch(item.tipo) {
                            case 'boleto':
                                rota = 'boleto';
                                break;
                            case 'agendamento':
                            case 'agendamento_admin':
                                rota = 'agendamento'; 
                                break;
                            case 'contracheque':
                                rota = 'contracheque';
                                break;
                        }

                        // REESTRUTURADO: Forçando a quebra de texto (white-space: normal) e cores corretas
                        lista.innerHTML += `
                            <li style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <div class="p-3" style="white-space: normal !important; word-wrap: break-word !important; max-width: 100%;">
                                    <a href="<?= baseUrl() ?>${rota}" class="text-decoration-none d-block mb-2">
                                        <p class="m-0 text-white fw-normal" style="font-size: 0.9rem; white-space: normal !important; line-height: 1.4;">
                                            ${item.mensagem}
                                        </p>
                                    </a>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-white-50" style="font-size: 11px;">${formatarData(item.data)}</small>
                                        <button class="btn btn-sm btn-light py-1 px-2 text-danger fw-bold shadow-sm" style="font-size: 11px; border-radius: 4px;" onclick="marcarLida(event, ${item.id})">
                                            ✓ Lida
                                        </button>
                                    </div>
                                </div>
                            </li>
                        `;
                    });
                } else {
                    lista.innerHTML = '<li class="text-center p-4 text-white-50" id="notif-empty" style="font-size: 0.9rem;"><i class="fa-solid fa-bell-slash d-block mb-2 fs-4 opacity-50"></i>Nenhuma notificação nova</li>';
                }
            }
        }).catch(err => console.error("Erro ao carregar notificações:", err));
}

function marcarLida(event, id) {
    event.preventDefault();
    event.stopPropagation(); 

    const formData = new FormData();
    formData.append('id', id);

    fetch('<?= baseUrl() ?>notificacao/marcarComoLida', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'sucesso') {
            carregarNotificacoes(); 
        }
    });
}

function marcarTodasComoLidas(event) {
    event.preventDefault();
    event.stopPropagation(); 

    fetch('<?= baseUrl() ?>notificacao/MarcarTodasComoLida', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Erro na resposta do servidor");
        }
        return response.json();
    })
    .then(res => {
        if (res.status === 'sucesso') {
            carregarNotificacoes(); 
        } else {
            console.error("O servidor retornou um erro:", res.mensagem);
        }
    })
    .catch(err => {
        console.error("Erro na requisição Fetch de marcar todas:", err);
    });
}

function formatarData(dataString) {
    if(!dataString) return '';
    const partes = dataString.split(' ')[0].split('-'); 
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}
</script>