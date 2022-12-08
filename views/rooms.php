<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>BootsChat - Salas</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous" />
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    
    <body class="bg-light">
        
        <div class="container">            
            <div class="mt-3 row border p-3 shadow-lg">
                <div class="col-4"></div>
                <div class="col-4 text-primary text-center">
                    <span class="fs-1 fw-bolder pb-0">BootsChat</span><i class="bi bi-chat-fill fs-3"></i><br />
                    <div id="nickHelp" class="form-text mt-0">A Bootstrap/JQuery chat</div>                    
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 fw-bold text-end">
                            <i class="fas fa-comments text-primary fw-bold"></i>
                            Olá, <?=$_SESSION["nickname"]?>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-danger mt-5 ms-3 float-end" href="<?=BASE?>login/logout">
                                <i class="bi bi-door-open-fill"></i> Sair
                            </a>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr class="bg-primary" />
                    <h6 class="text-center mb-3">Escolha uma sala <i class="fas fa-comments text-primary"></i></h6>
                    <?php foreach ($info as $room): ?>
                        <?php if($room["total"] == 0): ?>
                        <a type="button" class="btn btn-primary mb-3" style="min-width: 270px;" href="<?= BASE ?>room/show/<?=$room["id"]?>">
                            <?= $room["description"] ?>&nbsp;<span class="badge bg-success"><?= $room["total"] ?></span>
                        </a>
                        <?php elseif($room["total"] < 30 && ($room["total"]) > 0): ?>
                        <a type="button" class="btn btn-primary mb-3" style="min-width: 270px;" href="<?= BASE ?>room/show/<?=$room["id"]?>">
                            <?= $room["description"] ?>&nbsp;<span class="badge bg-warning"><?= $room["total"] ?></span>
                        </a>
                        <?php else: ?>
                        <a type="button" class="btn btn-primary disabled mb-3" style="min-width: 270px;" href="<?= BASE ?>room/show/<?=$room["id"]?>">
                            <?= $room["description"] ?>&nbsp;<span class="badge bg-danger"><?= $room["total"] ?></span>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
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
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>                        
                    </div>
                </div>
            </div>
        </div>
        
    <script src="<?=BASE?>js/config.js"></script>
    
    <?php if($warning == "1"): ?>
        <script>
            $(document).ready(function() {
                $('#warningModal').find(".modal-body").html("Você ficou muito tempo sem enviar mensagens e por isso foi desconectado da sala");
                $('#warningModal').modal('show');
            });            
        </script>
    <?php endif; ?>
        
    </body>
    
</html>