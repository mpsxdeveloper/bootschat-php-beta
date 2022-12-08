var chat = {

    msgRequest:null,
    userRequest:null,
    lastTime:null,

    updateLastTime:function(last_time) {
        this.lastTime = last_time;
    },

    sendMessage: function(currentTab, msg, type) {
        $.ajax({
            url: BASE + 'ajax/send_message',
            type: 'POST',
            data: {
                receiver_id: currentTab, msg: msg, type:type
            },
            dataType: 'json',
            success: function(json) {
                chat.updateLastTime(json.datemsg);
            }
        });
    },
    
    sendPhoto:function(currentTab, photo) {
		
        var formData = new FormData();
        formData.append('receiver_id', currentTab);
        formData.append('photo', photo);        
        $.ajax({
            url: BASE + 'ajax/send_photo',
            type: 'POST',
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            success: function(json) {
                if(json.status == '1') {
                    if(json.error == '1') {
                        alert(json.errorMsg);
                    }
                }
            },
            xhr: function () {
                var xhrPadrao = $.ajaxSettings.xhr();
                if(xhrPadrao.upload) {
                    xhrPadrao.upload.addEventListener('progress', function (p) {
                        var total = p.total;
                        var loaded = p.loaded;
                        var pct = (total / loaded) * 100;
                        if(pct > 0) {
                            $('.progressbar').css('width', pct + '%');
                            $('.progress').show();
                        }
                        if(pct >= 100) {
                            $('.progressbar').css('width', '0%');
                            $('.progress').hide();
                        }
                    }, false);
                }
                return xhrPadrao;
            }
        });       
    },
    
    insertMessage : function(msg) {
        if(msg.type=="welcome" || msg.type=="bye") {
            if(msg.type=="bye") {
                if(msg.sender_id == $('#userId').val()) {
                    window.location.href = BASE + 'room/exit/'+$('#room').val()+'/1';
                }
                else {
                    var div = document.getElementById(msg.sender_id);
                    room.removeUserTab(div);
                }                
            }
            room.updateUser();
        }
        if(msg.sender_id == room.myId || msg.receiver_id == room.myId || msg.receiver_id == 0) {
            var sender = msg.sender_id;
            var receiver = msg.receiver_id;
            var hour = msg.datemsg.split(" ");
            var small = '<small class="form-text text-muted">' + hour[1] + '</small>';
            var li = document.createElement("li");
            li.classList.add("list-group-item");
            li.classList.add("mb-1", "fw-bold");
            $(li).append(small);
            if(receiver == 0) {
                li.style.backgroundColor = "rgb(173,216,230)";
                $(li).append("<strong>" + " " + msg.nicksender + "</strong>" + " para todos:<br />" + msg.msg);
            }
            else {
                if(msg.type=="text") {
                    if(msg.type != "bye") {
                        li.style.backgroundColor = "rgb(135,206,250)";
                    }
                    else {
                        li.style.backgroundColor = "rgb(220,53,69)";
                    }
                    $(li).append("<strong>" + " " + msg.nicksender + "</strong> (reservadamente) para " + msg.nickreceiver + ":<br />" + msg.msg);
                }
                else if(msg.type=="image") {
                    li.style.backgroundColor = "rgb(0,191,255)";
                    $(li).append("<strong>" + " " + msg.nicksender + "</strong> (reservadamente) para " + msg.nickreceiver + ":<br />");
                    $(li).append('<img src='+BASE+'media/uploads/'+msg.msg+' style="width: auto;" height="300" />');                                        
                }                
            }
            $('#chat').append($(li));
            if($('#chat li').length > 30) {
                $('#chat li').eq(0).remove();
            }
            var objDiv = document.getElementById("chat");
            objDiv.scrollTop = objDiv.scrollHeight;
            $('#msg').focus();           
        }
               
    },
    
    chatActivity:function() {        
        this.msgRequest = $.ajax({
            url:BASE + 'ajax/get_messages',
            type: 'GET',
            data:{last_time: this.lastTime},
            dataType:'json',
            success:function(json) {    
                chat.updateLastTime(json.last_time);
                for(var i in json.msgs) {
                    chat.insertMessage(json.msgs[i]);
		        }
            },
            complete:function() {
                chat.chatActivity();
            }
        });
    },
    
    userActivity : function() {

        this.userRequest = $.ajax({
            url: BASE + 'ajax/get_minutes/',
            type: 'GET',
            data:{ room_id: $('#room').val() },
            dataType:'json',
            success:function(json) {                
                if(json.length > 0) {
                    for(var i = 0; i < json.length; i++) {
                        if(json[i] == $('#userId').val()) {
                            window.location.href = BASE + 'room/exit/'+$('#room').val()+'/1';
                        }
                        else {
                            let div = document.getElementById(json[i]);
                            room.removeUserTab(div);
                        }

                    }
                }
            }
        });
    
    }
    
};
