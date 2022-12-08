<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>BootsChat - Sala</title>
        <link rel="icon" type="image/x-icon" href="<?=BASE?>media/images/icon.png" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous" />
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>

    </head>
    
    <body>
        
        <input type="hidden" id="userId" value="<?=$_SESSION["id"]?>" />
        <input type="hidden" id="welcome" value="<?=$welcome?>" />
        <input type="hidden" id="room" value="<?=$roomId?>" />
        <div class="container-fluid">
            <div class="row text-light">
                <div class="col-12 text-center bg-primary p-2">
                    <span class="fs-5 fw-bold text-light">Bootschat</span> <i class="bi bi-chat-fill fs-3"></i><br />                    
                </div>
            </div>
            <div class="row mt-1" style="min-height: 450px; max-height: 450px;">
                <div class="col-9">
                    <ul class="list-group list-group-flush" id="chat" style="overflow-y: auto; max-height: 450px; font-size: 14px;"></ul>
                </div>
                <div class="col-3 border-2 border-primary border-start">
                    <div class="users">
                        <div id="userList" style="overflow-y: auto; font-size: 13px;"></div>
                    </div>                    
                </div>
            </div>
            <div class="row">
                <div class="col-9">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group me-2" role="group" aria-label="First group" id="chatList"></div>
                    </div>
                    <div class="border border-primary border-2 p-2">
                        <input type="text" id="msg" style="border: none; outline: none; width: 100%;" maxlength="100" placeholder="Digite sua mensagem e pressione ENTER para enviar..." />
                    </div>
                    <div class="progress mt-1" style="display: none;">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>                    
                </div>
                <div class="col-3">
                    <div class="row mt-1"><input type="file" id="photo" style="position: absolute; top: 0; left: -1000px;" />
                        <div class="col-6">
                            <button type="button" class="btn btn-warning btn-sm" id="setPictureBtn">
                                <i class="bi bi-image"></i> Enviar imagem
                            </button>                            
                        </div>
                        <div class="col-6">
                            <button type="button" id="exitBtn" class="btn btn-danger btn-sm" onclick="exit('0');">
                                <i class="bi bi-door-open"></i> Deixar sala
                            </button>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Aviso</h5>                        
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="document.getElementById('msg').focus();">
                            <i class="bi bi-x"></i> Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?=BASE?>js/config.js"></script>        
        <script src="<?=BASE?>js/chat.js"></script>
        <script src="<?=BASE?>js/room.js"></script>
        
    </body>
    
</html>