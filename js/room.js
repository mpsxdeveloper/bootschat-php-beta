var room = {
  
    userID : '',
    userTabs : [],
    currentTab : '',
    myNick : '',    
    myId : '',
    users : [],
      
    loadUserList : function() {
        userID = $('#userId').val();
        $('#userList').html("");
        $.ajax({
            url: BASE + 'room/list/'+$('#room').val(),
            type: 'POST',
            dataType: 'json',
            data: {room: 1},
            success: function(json) { 
                for(let i in json.info) {
                    let div = document.createElement("div");
                    div.classList.add("card", "p-1", "ps-3", "fw-bold", "text-light");
                    var spanNick = document.createElement("span");                    
                    spanNick.innerHTML = json.info[i].nickname;
                    $(div).append(spanNick);
                    $(div).css("cursor", "pointer");
                    div.classList.add("divClass");
                    $(div).attr("user-id", json.info[i].id);              
                    if(json.info[i].id != userID) {
                        div.classList.add("bg-primary");                        
                        $(div).on("click", function() {
                            room.addUserTab($(div).attr("user-id"), $(div).html());
                        });
                        let user = {
                            'id':json.info[i].id,
                            'nickname':json.info[i].nickname
                        };
                        room.users.push(user);
                    }
                    else {        
                        room.myNick = json.info[i].nickname;
                        room.myId = json.info[i].id;
                        div.classList.add("bg-info", "text-dark");
                        var spanIcons = document.createElement("span");                     
                        $(spanIcons).append('&nbsp;&nbsp;&nbsp;&nbsp;<i class="bi bi-person-circle"></i>');
                        $(spanIcons).append(" (Meu nick) ");
                        $(spanNick).append($(spanIcons));
                        let user = {
                            'id':json.info[i].id,
                            'nickname':json.info[i].nickname
                        };
                        room.users.push(user);
                    }
                    div.classList.add("mb-1");
                    $('#userList').append(div);                    
                }
            }
        });        
    },
    
    updateUser : function() {
        $('#userList').html("");
        this.loadUserList();
    },

    setUserTab : function(divUser) {
        var divs = document.getElementsByClassName("divUserClass");
        for(var i = 0; i < divs.length; i++) {
            divs[i].classList.remove("active");
            divs[i].classList.remove("bg-primary");
            divs[i].classList.add("bg-dark");
        }
        divUser.classList.add("active");
        divUser.classList.remove("bg-dark");
        divUser.classList.add("bg-primary");        
        this.currentTab = divUser.getAttribute("id");        
        $('#msg').focus();
        
    },
    
    removeUserTab : function(div) {
        
        $('#msg').val("");
        var divUser = document.getElementsByClassName("divUserClass")[0];
        divUser.classList.add("active");
        divUser.classList.remove("bg-dark");
        divUser.classList.add("bg-primary");        
        this.currentTab = divUser.getAttribute("id");
        
        var id = $(div).attr("id");
        let index = this.userTabs.indexOf(id);
        this.userTabs.splice(index, 1);
        $(div).remove();
        divUser.click();
         
    },

    addUserTab : function(id, name) {
        if(this.userTabs.indexOf(id) == -1) {
            this.userTabs.push(id);            
            var divUser = document.createElement("div");
            var spanName = document.createElement("span");
            divUser.classList.add("bg-primary","text-light","p-2","pe-3","ps-3","active");            
            $(divUser).attr("id", id);
            divUser.classList.add("divUserClass");
            spanName.innerHTML = name;            
            $(divUser).css("cursor", "pointer");
            $(divUser).css("min-width", "170px");
            $(divUser).css("max-height", "40px");
            $(divUser).append($(spanName));
            var spanX = document.createElement("span");
            spanX.classList.add("badge","bg-danger");
            spanX.title = "Fechar aba";
            spanX.innerHTML = "X";
            spanX.style.float = "right";
            spanX.style.zIndex = "100";
            spanX.style.position = "relative";
            spanX.style.top = "-10px";
            spanX.style.right = '-10px';
            $(divUser).append($(spanX));
            $('#chatList').append($(divUser));
            this.setUserTab(divUser);
            $(spanX).on("click", function() {
                room.removeUserTab(divUser);              
            });
            $(divUser).on("click", function(e) {
                room.setUserTab(this);
            });
        }        
    }
    
};

$(document).ready(function() {    
        
    chat.chatActivity();    
    setTimeout(function() {
        chat.sendMessage(0, "Entrei na sala...", "welcome");
    }, 1000);

    setInterval(function() {
        chat.userActivity();
    }, 1000 * 7);
    
    room.currentTab = 0;    
    room.loadUserList();   
    room.userTabs.push(0);
    var div = document.createElement("div");
    div.classList.add("bg-primary","text-light","p-2","pe-3","ps-3","active");
    div.innerHTML = "Todos";
    div.id = 0;
    div.classList.add("divUserClass");
    $(div).css("cursor", "pointer");
    $(div).css("border-radius", 0);
    $(div).css("min-width", "170px");
    $(div).css("max-height", "40px");
    $('#chatList').append(div);
    $(div).on("click", function(e) {
        room.setUserTab(div);
        $('#msg').focus(); 
    }); 
    $('#msg').focus();
    
    $('#setPictureBtn').on("click", function() {
        if(room.currentTab == 0) {
            $('#warningModal').find(".modal-body").html("Imagens não podem ser enviadas para todos os usuários.\nSelecione um usuário antes de enviar a imagem");
            $('#warningModal').modal('show');
        }
        else {
            $('#photo').trigger("click");
        }
    });
    
    $('#photo').on("change", function(e){
        chat.sendPhoto(room.currentTab, e.target.files[0]);
    });
    
    $('#msg').on("keyup", function(e) {
        var msg = $('#msg').val().trim();
        if(e.keyCode == 13) {
            if(msg != '') {
                $('#msg').val("");
                $("#msg").focus();
                chat.sendMessage(room.currentTab, msg, "text");
            }
        }        
    });
    
    setInterval(function() {
        
        $.ajax({
            url: BASE + 'ajax/disconnect_users',
            type: 'POST',
            dataType: 'json',
            success: function(userID) {                
            }
        });
    }, 10000);
    
});

function exit(warning) {
    
    chat.sendMessage(0, "Saindo da sala...", "bye");
    setTimeout(function() {
        window.location.href= BASE + 'room/exit/'+$('#room').val()+'/'+warning;
    }, 250);

}