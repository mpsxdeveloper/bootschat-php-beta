function invite() {
    
    $('#messages').css("display", "block");
    if($('#email').val().trim() !== '' || $('#nome').val() !== '') {
        $.ajax({
            type: "POST",
            url: BASE + 'invite/invite',
            data: {nome: $('#nome').val().trim(), email: $('#email').val().trim().toLowerCase(), 
                csrf_token: $('#csrf_token').val()},
            dataType: 'json'
        }).done(function (json) {
            if(json === true) {
                $('#messages').addClass("alert-success");
                $('#messages').text("Convite realizado com sucesso");              
            }
            else if(json === "already") {
                $('#messages').addClass("alert-danger");
                $('#messages').text("Esse e-mail já recebeu convite");
                $('#email').focus();
            }
            else if(json === false) {
                $('#messages').addClass("alert-danger");
                $('#messages').text("Erro ao enviar convite");
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