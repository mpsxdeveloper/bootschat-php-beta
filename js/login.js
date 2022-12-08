function login() {
    
    $('#messages').css("display", "block");
    if($('#email').val().trim() !== '' && $('#password').val() !== '') {
        $.ajax({
            type: "POST",
            url: BASE + 'login/login',
            data: {email: $('#email').val(), password: $('#password').val(), 
                csrf_token: $('#csrf_token').val()},
            dataType: 'json'
        }).done(function (json) {
            if(json === true) {
                window.location.href = BASE + 'room/index/?w=0';
            }
            else {
                $('#messages').addClass("alert-danger");
                $('#messages').text("E-mail e/ou senha incorretos!");
                $('#email').focus();
            }
        });
    }
    else {
        $('#messages').addClass("alert-danger");
        $('#messages').text("Preencha todos os campos");
        $('#email').focus();
    }
    
}

function add() {
    $('#alerts').css("display", "block");
    if($('#nemail').val().trim() !== '' && $('#nnick').val().trim() !== '' && $('#npassword').val() !== '') {
        $.ajax({
            type: "POST",
            url: BASE + 'user/add',
            data: {email: $('#nemail').val().trim().toLowerCase(), nick: $('#nnick').val().trim(),
                password: $('#npassword').val(), csrf_token: $('#csrf_token').val()},
            dataType: 'json'
        }).done(function (json) {
            if(json === "uninvited") {
                $('#alerts').addClass("alert-danger");
                $('#alerts').text("E-mail informado não recebeu convite");
                $('#nemail').focus();
            }
            else if(json === "already") {
                $('#alerts').addClass("alert-danger");
                $('#alerts').text("Esse e-mail já foi registrado no chat");
                $('#nemail').focus();
            }
            else if(json === "existing") {
                $('#alerts').addClass("alert-danger");
                $('#alerts').text("Esse apelido já está sendo utilizado por alguém");
                $('#nnick').focus();
            }
            else if(json === true) {
                $('#alerts').removeClass("alert-danger");
                $('#alerts').addClass("alert-success");
                $('#alerts').text("Cadastro realizado com sucesso. Você pode fazer login agora");
                $('#email').focus();
            }
        });
    }
    else {
        $('#alerts').addClass("alert-danger");
        $('#alerts').text("Preencha todos os campos");
        $('#nemail').focus();
    }
}