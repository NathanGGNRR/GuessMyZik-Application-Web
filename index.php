<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <?php require './header.php'; ?>
        
        <div class="d-flex flex-column mx-auto w-75" >
            <div class="d-flex flex-column">
                <div class="text-white fs-vw-2">Search Track(s)</div>
                <div class="d-flex flex-row">
                    <div class="d-flex flex-column mr-1 w-100">
                        <input type="text" id="text-search" class="form-control h-vw-2 shadow" aria-label="Search Track(s)" required/>
                        <small id="invalid-search" class="form-text text-danger invisible">
                            Please provide a search.
                        </small>
                    </div>
                    <button type="button" id="confirm-search" class="btn btn-secondary h-vw-2 rounded border border-dark shadow "><i class="fas fa-search fs-vw-1"></i></button>
                </div>
            </div>
            <div class="d-flex flex-column mt-4">
                <div class="text-white fs-vw-1_5 mb-2">Double click for choosing track(s) (Min: 3 / Max: 20)</div>
                <div class="d-flex flex-row">
                    <div  class="d-flex flex-column mr-2 w-50" >
                        <ul class="mr-2 shadow list-track rounded w-100" id="list-search-track" style="height: 500px !important; max-height: 500px; position: relative;">
                            <div class="lds-ring" style="display: none;"><div></div><div></div><div></div><div></div></div>
                            <h4 id="text-empty-search" class="text-empty">EMPTY</h4>
                        </ul>
                        <div class="d-flex flex-row">
                            <div style="height: 25px; width: 25px;" class="bg-white my-auto"></div>
                            <div style="height: 25px; width: 25px;" class="bg-dark my-auto"></div>
                            <p class="text-white my-auto mx-2" style="font-weight: bold;">track never selected</p> 
                            <div style="height: 25px; width: 25px; background: #d1d1d1;" class="my-auto"></div>
                            <div style="height: 25px; width: 25px;" class="bg-info my-auto"></div>
                            <p class="text-white my-auto mx-2" style="font-weight: bold;">track already selected</p> 
                            <i class="fas fa-trash text-white my-auto ml-auto p-2 mr-2" id="trash-search" style="cursor: pointer; font-size:25px; padding:0 !important;"></i>
                        </div>
                    </div>
                    <div  class="d-flex flex-column mr-2 w-50" >
                        <ul class="shadow list-track rounded w-100" id="list-choose-track" style="height: 500px !important; max-height: 500px; position: relative;">
                            <h4 id="text-empty-choose" class="text-empty">EMPTY</h4>
                        </ul>
                        <div class="number-text d-flex flex-row text-light ">Number Of Choosing Track(s): <div class="number-track ml-2 text-danger">0</div><i id="trash-choose" class="fas fa-trash text-white my-auto ml-auto p-2 mr-2" style="cursor: pointer;  font-size:25px; padding:0 !important;"></i></div>
                    </div>


                </div>
            </div>
            <button type="button" id="btn-guess" class="btn btn-success h-vw-3 rounded border border-dark mt-5 d-inline-flex mx-auto" disabled><div class="fs-vw-1_5">Démarrer le Blind Test</div><i class="fas fa-music fs-vw-1_5 ml-2 mt-2"></i></button>

            <form id="formChoose" action="guess.php" method="post" style="display: none;">
                <input name="tracks" id="tracksChoose" type="hidden" value=""/>
            </form>
        </div>
    </body>
</html>