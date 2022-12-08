<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>BootsChat - Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>        
    </head>
    
    <?php
        if(!isset($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = md5(time() . rand(0, 9999));
        }
        if(!isset($_SESSION["owner"])) {
            $addr = filter_input(INPUT_SERVER, "REMOTE_ADDR");
            $agent = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
            $_SESSION["owner"] = md5($addr . $agent);
        }
    ?>
    
    <body class="bg-light">
        
        <div class="container w-75">
            <input type="hidden" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
            <div class="row mt-3 fs-5 border shadow-lg p-3">
                <div class="col-12 text-primary text-center">
                    <span class="fs-1 fw-bolder pb-0">BootsChat</span><i class="bi bi-chat-fill fs-3"></i><br />
                    <div id="nickHelp" class="form-text mt-0">A Bootstrap/JQuery chat</div>                    
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6 border-primary border-end border-2 mt-5">
                    <h6 class="text-center fw-bold mb-3">Login</h6>
                    <div class="mb-3">
                        <input type="email" class="form-control" id="email" placeholder="E-mail" maxlength="45" />
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Senha" maxlength="60" />
                    </div>
                    <div class="mb-3 text-center">
                        <button type="button" class="btn btn-primary" onclick="login();">
                            <i class="bi bi-check-lg"></i> Login
                        </button>
                    </div>
                    <div class="row mt-1 ms-1 me-1">
                        <div class="alert mt-1" role="alert" style="display: none;" id="messages"></div>
                    </div>
                </div>
                <div class="col-6 mt-5">                    
                    <h6 class="text-center fw-bold mb-3">Recebeu convite? Registre-se aqui</h6>
                    <div class="mb-3">
                        <input type="nemail" class="form-control" id="nemail" placeholder="Informe o e-mail" maxlength="45" />                        
                    </div>
                    <div class="mb-3">                        
                        <input type="text" class="form-control" id="nnick" placeholder="Informe um apelido" maxlength="17" />                        
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="npassword" placeholder="Escolha uma senha" maxlength="60" />
                    </div>
                    <div class="mb-3 text-center">
                        <button type="button" class="btn btn-success" onclick="add();">
                            <i class="bi bi-person-plus-fill"></i> Registrar
                        </button>
                    </div>
                    <div class="row mt-1 ms-1">
                        <div class="alert mt-1" role="alert" style="display: none;" id="alerts"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                
            </div>
        </div>
        
        <script src="<?=BASE?>js/config.js"></script>
        <script src="<?=BASE?>js/login.js"></script>
        
    </body>
    
</html>
