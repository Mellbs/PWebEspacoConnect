<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logo - Copia.png" type="image/png">
</head>
<img src="../agendamento-salas/images/cabeçalho.png" alt="Cabeçalho" width="1536px" height="45px">
<img src="../agendamento-salas/images/cabeçalho-azul.png" alt="Cabeçalho" width="1536px" height="45px">
<body>
    <a href="gerenciamento.html" style="position: fixed; top: 90px; left: 30px; font-size: 48px; font-weight: bold; cursor: pointer; text-decoration: none; color: #004aad;">&#8592;</a>
    <!-- Menu de navegação -->
    <?php include '../agendamento-salas/admin/menu_admin.php'; ?>

    <div class="container mt-4">
        <h2 style="color: #004aad; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-weight: bold;">Gestão de Usuários</h2>


        <form id="formUsuario" class="mb-4" style="background-color: #004aad; padding: 15px; border-radius: 5px;">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="nome" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <input type="email" class="form-control" id="email" placeholder="E-mail" required>
                </div>
                <div class="col-md-3">
                    <input type="password" class="form-control" id="senha" placeholder="Senha" required>
                </div>
                <div class="col-md-2">
                    <select id="papel" class="form-select">
                        <option value="admin">Gerente</option>
                        <option value="usuario">Usuário</option>
                    </select>
                </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Salvar</button>
                
                    </div>
            
            </div>       
        </form> 
        <div id="mensagem"></div>

        <table id="tabelaUsuarios" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Nome</th><th>E-mail</th><th>Papel</th><th>Ações</th>
                </tr>
            </thead>
            <tbody></tbody>
             
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const baseURL = "/ProjetoUniCorp/";

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
                            <td>${u.papel}</td>
                            <td>
                            <button class="btn btn-sm btn-warning" onclick="editarUsuarios(${u.id_usuario}, '${u.nome}', '${u.email}', '${u.papel}')">Editar</button>
                            <button class="btn btn-sm btn-danger" onclick="excluirUsuario(${u.id_usuario})">Excluir</button>
                            </td>
                        </tr>;
                    `)
                });
            }, 'json');
        }


        $('#formUsuario').on('submit', function(e)  {
        e.preventDefault();
        $.post(baseURL + 'api/admin/usuarios_salvar.php', {
            nome: $('#nome').val(),
            email: $('#email').val(),
            senha: $('#senha').val(),
            papel: $('#papel').val()
         }, function(res) {
            $('#mensagem').html(`
                <div class="alert alert-${res.status === 'success' ? 'success' : 'danger'}">  ${res.mensagem} </div>
                  carregarUsuarios();
                  $('#formUsuario')[0].reset();
            `);
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
             $('#senha').val('');
             $('#papel').val(papel);
             $('#formUsuarioi').off('submit').on('submit', function(e) {
                e.preventDefalt();
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
                $('#formUsuario')[0].reset();
                // Restaura o comportamento original do formulário
                $('#formUsuario').off('submit').on('submit', function (e) {
                   e.preventDefault();
                   $post(baseURL = 'api/admin/usuarios_adicionar.php', {nome, email, senha, papel});
                });
             }, 'json');

            });
    
        }

        $(document).ready(() => carregarUsuarios());

    </script>
</body> 
</html>