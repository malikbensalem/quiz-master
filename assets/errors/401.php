<?
include_once $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?getHead('401')?>
        <link rel="stylesheet" type="text/css" href="<?echo $baseURL?>assets/errors/errors.css">
        </style>
    </head>
    <body>
        <?include $_SERVER['ROOT_PATH'].'assets/includes/nav.php';?>

        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <img class="pic-header" src="<?echo $baseURL?>assets/images/barrier.png" alt="barrier">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 text-center">
                    <h1>401</h1>
                    <h2>Page not found</h2>
                    <br><hr>
                    <h3 style="font-weight:normal" class="text-center">The barrier is blocking your path due to unauthrised access.</h3>
                </div>
                <div class="col-md-6 text-center">
                    <?breadcrumbs()?>
                </div>
            </div>
        </div>

        <?include $_SERVER['ROOT_PATH'].'assets/includes/footer.php'?>
    </body>
</html>

