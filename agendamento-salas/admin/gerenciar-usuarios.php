<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../images/logo - Copia.png" type="image/png">
    <style>
        .table td, .table th {
            padding: 0rem 0rem;
        }
        .table tbody tr {
            height: 0.5em;
        }
        .table th:nth-child(5), .table td:nth-child(5) {
            width: 120px;
        }
        .btn-sm {
            font-size: 0.6rem !important;
            padding: 0.05rem 0.1rem !important;
            width: 50px !important;
        }
    </style>
</head>

<body class="body">

    <img src="../images/cabeçalho.png" alt="Cabeçalho" width="1536px" height="45px">
    <header>
        <div class="user-info">

            <div class="menu-container">
                <span class="menu-dots">⋮</span>
                <div class="menu-options">
                    <div id="editarPerfil">Editar Perfil</div>
                </div>
            </div>

            <img src="../images/perfilSemFoto.png" alt="Foto de Perfil" id="profilePic" class="profile-pic">
            <div class="user-details">
                <p id="username">Usuário</p>
                <p id="userEmail">email@gmail.com</p>
            </div>
        </div>
        <div style="position: relative;">
            <input type="text" placeholder="Pesquisar sala..." id="busca" autocomplete="off">
            <div id="resultados-busca"></div>
        </div>
        <nav>
            <a href="../tela-inicial.html" style="text-decoration: none;">Início</a>
            <a href="../salas.html" style="text-decoration: none;">Salas Disponíveis</a>
            <a href="../salas-reservadas.html" style="text-decoration: none;">Salas Reservadas</a>
            <a href="../gerenciamento.html" id="linkGerenciamento" style="text-decoration: none;">Gerenciamento</a>
            <a href="../index.html" style="text-decoration: none;">Sair</a>
        </nav>
    </header>

    <div id="modalPerfil" class="modal">
        <div class="modal-content">
            <h2>Editar Perfil</h2>
            <input type="text" id="novoUsername" placeholder="Novo nome de usuário">
            <input type="text" id="novoEmailNome" placeholder="Parte antes do @">
            <br>
            <button id="salvarPerfil">Salvar</button>
            <button id="cancelarPerfil">Cancelar</button>
        </div>
    </div>

  <!-- Modal de confirmação de saída -->
  <div id="modalSair" class="modal-sair">
    <div class="modal-conteudo">
      <p>Deseja realmente sair?</p>
      <div class="botoes-modal">
        <button id="confirmarSair">Sim</button>
        <button id="cancelarSair">Não</button>
      </div>
    </div>
  </div>

    <div class="container-fluid mt-4">
        <div id="mensagem"></div>

        <form id="formUsuario" class="mb-4" style="background-color: #004aad; padding: 15px; border-radius: 5px;">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="nome" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <input type="email" class="form-control" id="email" placeholder="E-mail" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="senha" placeholder="Senha" required>
                </div>
                <div class="col-md-2">
                    <select id="papel" class="form-select">
                        <option value="gerente">Gerente</option>
                        <option value="usuario">Usuário</option>
                    </select>
                </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Salvar</button>

                    </div>

            </div>
        </form>

        <div style="width: 100%; height: 800px; overflow-y: auto;">
            <table id="tabelaUsuarios" class="table table-bordered table-striped" style="width: 100%;">
                <thead class="table-dark" style="position: sticky; top: 0;">
                    <tr>
                        <th>ID</th><th>Nome</th><th>E-mail</th><th>Senha</th><th>Papel</th><th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>

            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          const usuario = JSON.parse(localStorage.getItem('usuario'));
          if (usuario) {
            document.getElementById("username").textContent = usuario.username;
            document.getElementById("userEmail").textContent = usuario.email;
            if (usuario.profilePic) {
              document.getElementById("profilePic").src = usuario.profilePic;
            }
          }
          document.getElementById('profilePic').addEventListener('click', () => {
            if (!usuario) return alert('Faça login para mudar a foto.');
            const uploadInput = document.createElement('input');
            uploadInput.type = 'file';
            uploadInput.accept = 'image/*';
            uploadInput.click();
            uploadInput.addEventListener('change', () => {
              const file = uploadInput.files[0];
              if (!file) return;
              const reader = new FileReader();
              reader.onload = () => {
                document.getElementById('profilePic').src = reader.result;
                usuario.profilePic = reader.result;
                localStorage.setItem('usuario', JSON.stringify(usuario));
              };
              reader.readAsDataURL(file);
            });
          });
          // Modal de Sair
          const sairLink = document.querySelector('a[href="../index.html"]');
          const modalSair = document.getElementById('modalSair');
          sairLink.addEventListener('click', function (e) {
            e.preventDefault();
            modalSair.style.display = 'block';
          });
          document.getElementById('confirmarSair').addEventListener('click', function () {
            window.location.href = '../index.html';
          });
          document.getElementById('cancelarSair').addEventListener('click', function () {
            modalSair.style.display = 'none';
          });
          window.addEventListener('click', function (event) {
            if (event.target === modalSair) {
              modalSair.style.display = 'none';
            }
          });
        });

        const baseURL = "../../";

        function carregarUsuarios() {
            $.get(baseURL + "api/admin/usuarios_listar.php", function(lista) {
                const tbody = $('#tabelaUsuarios tbody');
                tbody.empty();
                lista.forEach(u => {
                    tbody.append(`
                        <tr style="background-color: #004aad;">
                            <td>${u.id_usuario}</td>
                            <td>${u.nome}</td>
                            <td>${u.email}</td>
                            <td>${u.senha}</td>
                            <td>${u.papel}</td>
                            <td>
                            <button class="btn btn-sm btn-warning" style="display: inline-block; margin: 0 30px;" onclick="editarUsuario(${u.id_usuario}, '${u.nome}', '${u.email}', '${u.senha}', '${u.papel}')">Editar</button>
                            <button class="btn btn-sm btn-danger" style="display: inline-block; margin: 0 30px;" onclick="excluirUsuario(${u.id_usuario})">Excluir</button>
                            </td>
                        </tr>
                    `)
                });
            }, 'json');
        }


        $('#formUsuario').on('submit', function(e)  {
        e.preventDefault();
        $.post(baseURL + 'api/admin/usuarios_adicionar.php', {
            nome: $('#nome').val(),
            email: $('#email').val(),
            senha: $('#senha').val(),
            papel: $('#papel').val()
         }, function(res) {
            $('#mensagem').html(`
                <div class="alert alert-${res.status === 'success' ? 'success' : 'danger'}">${res.mensagem}</div>
            `);
            carregarUsuarios();
            $('#formUsuario')[0].reset();
        }, 'json');
        });

        function excluirUsuario(id) {
            if (confirm('Tem certeza que deseja excluir este usuario?')) {
                $.post(baseURL + 'api/admin/usuarios_excluir.php', {id}, function(res) {
                    $('#mensagem').html(`
                        <div class="alert alert-${res.status === 'success' ? 'success' : 'danger'}">${res.mensagem}</div>
                    `);
                    carregarUsuarios();
                }, 'json');
            }
        }

        function editarUsuario(id, nome, email, papel) {
             $('#nome').val(nome);
             $('#email').val(email);
             $('#senha').val('').removeAttr('required').attr('type', 'text');
             $('#papel').val(papel);
             $('#formUsuario').off('submit').on('submit', function(e) {
                e.preventDefault();
             $.post(baseURL + 'api/admin/usuarios_editar.php', {
                 id,
                 nome: $('#nome').val(),
                 email: $('#email').val(),
                 senha: $('#senha').val(),
                 papel: $('#papel').val()
             }, function(res) {
                $('#mensagem').html(`
                    <div class="alert alert-${res.status === 'success'?'success':'danger'}">${res.mensagem}</div>
                `);
                 carregarUsuarios();
                // Update localStorage if the current user was edited
                if (res.status === 'success' && res.updated_name) {
                    const usuario = JSON.parse(localStorage.getItem('usuario'));
                    if (usuario && usuario.email === $('#email').val()) {
                        usuario.username = res.updated_name;
                        localStorage.setItem('usuario', JSON.stringify(usuario));
                        document.getElementById("username").textContent = usuario.username;
                    }
                }
             }, 'json');

            });

        }

        $(document).ready(() => carregarUsuarios());

    </script>
</body> 
</html>