<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <?php 
            require './header.php'; 
            if(!isset($_POST["tracks"])){
                header('Location: /');
            }
        
        ?>
        
        <div class="modal fade" id="modal-start" tabindex="-1" role="dialog" aria-labelledby="modal-start-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-start-title">Party will begin</h5>
                </div>
                <div class="modal-body">
                    To start the game press the start button otherwise you can go back with the return button.
                </div>
                <div class="d-flex flex-row my-2 w-100">
                    <form action="/" id="formReturn" class="mr-3 w-25 mx-auto" method="post">
                        <button class="back-party btn btn-secondary w-100" data-dismiss="modal">Return</button>
                    </form>
                    <button type="button" id="start-party" class="btn btn-primary ml-3 w-25 mx-auto">Start !</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-next" tabindex="-1" role="dialog" aria-labelledby="modal-next-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-next-title">Next track</h5>
                </div>
                <div class="modal-body">
                    Are you ready for the next track ?
                </div>
                    <button type="button" id="next-party" class="btn btn-primary w-25 mx-auto my-3">Yes, next !</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-end" tabindex="-1" role="dialog" aria-labelledby="modal-start-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-end-title">Finished party</h5>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row">
                    Congratulations you have <div id="point-guess" class="text-info mx-2" style="font-weight: bold;"></div> points. If you want restart, go home.
                </div>
                </div>
                <div class="d-flex flex-row my-2 w-100">
                    <form action="/" id="formReturn" class="mr-3 w-25 mx-auto" method="post">
                        <button class="back-party btn btn-secondary w-100" data-dismiss="modal">Home</button>
                    </form>
                
                </div>
                </div>
            </div>
        </div>


        <div class="d-flex flex-column mx-auto w-75 shadow rounded border border-dark bg-white mt-3" style="border-width: 3px !important; height: 750px;">
            <div class="d-flex flex-row h-100">
                <div class="d-flex flex-column w-75 left-guess" style="position: relative;">
                    <div class="sound-guess d-flex flex-column" >
                        <i class="fas fa-volume-up text-info"></i>
                        <input id="volume-audio" type="range" min="0" max="1" step="0.1" value="0.5">
                    </div>
                    <div class="timer-guess text-dark mt-2">
                        30
                    </div>
                    <div class="d-flex flex-row mx-auto text-number-guess">
                            <div>Track n°</div><div id="number-track-guess">1</div>
                    </div>
                    <div class="mx-auto w-75 my-auto mt-3 border-info rounded shadow image-guess" >
                        <img src="img/dance.gif" alt="Dance" class="img-fluid w-100">
                    </div>
                    <div class="d-flex flex-row mt-3 mx-auto w-75 mb-5">
                        <input type="text" id="text-check" class="form-control shadow mr-2" aria-label="Check Result" required/>
                        <button type="button" id="confirm-check" class="btn btn-info rounded border border-dark shadow " style="height: 38px;" disabled><i class="fas fa-check "></i></button>
                    </div>
                </div>
                <div class="d-flex flex-column right-guess h-100 mt-3" >
                    <div class="h-25 d-flex flex-column title-guess-right">
                        <div class="text-info mb-3">
                            Elements to found:
                        </div>
                        <div class="d-flex flex-row text-danger element-guess">
                            <i class="fas fa-music mt-1 element-front-guess"></i>
                            <div class="mr-2 pl-2 element-front-guess">Name of track:</div>
                            <div style="position: relative;"><div id="track-name-id">NOT YET FOUND</div></div>
                        </div>
                        <div class="d-flex flex-row text-danger element-guess">
                            <i class="fas fa-user mt-1 element-front-guess"></i>
                            <div class="mr-2 pl-2 element-front-guess">Name of artist:</div>
                            <div style="position: relative;"><div id="artist-name-id">NOT YET FOUND</div></div>
                        </div>
                        <div class="d-flex flex-row text-danger element-guess">
                            <i class="fas fa-file-audio mt-1 element-front-guess"></i>
                            <div class="mr-2 pl-2 element-front-guess">Title of album:</div>
                            <div style="position: relative;"><div id="album-name-id">NOT YET FOUND</div></div>
                        </div>
                    </div>
                    <div class="d-flex flex-column h-75 mt-3">
                        <div class="text-info title-guess-right mb-3">
                            Summary of party:
                        </div>        
                        <ul class="mr-2 shadow list-track ul-summary w-100 h-100 border-dark" id="list-previous-track" style="position: relative;">
                            <h4 id="text-empty-previous" class="text-empty" style="display: block;">EMPTY</h4>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <audio id="valid-audio" style="display:none;" controls > 
            <source src="sound/valid.mp3"  type="audio/mpeg">
        </audio>
        <audio id="track-audio" style="display:none;" controls > 
            <source id="preview-audio" src=""  type="audio/mpeg">
        </audio>
        <input id="tracks" type="hidden" value="<?php echo str_replace ("\"","&quot;",$_POST["tracks"]); ?>"/>
    </body>
    <script type="text/javascript" src="js/guess.js"></script>
</html>