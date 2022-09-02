<?
include_once $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?getHead('204')?>
        <link rel="stylesheet" type="text/css" href="<?echo $baseURL?>assets/errors/errors.css">
        </style>
    </head>
    <body>
        <?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>

        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <img class="pic-header" src="<?echo $baseURL?>assets/images/tumbleweed.png" alt="Tumbleweed">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 text-center">
                    <h1>204</h1>
                    <h2>No content</h2>
                    <br><hr>
                    <h3 style="font-weight:normal" class="text-center">A tumbleweed blew by. There is nothing here.</h3>
                </div>
                <div class="col-md-6 text-center">
                    <?breadcrumbs()?>
                </div>
            </div>
        </div>

        <?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
    </body>
</html>

