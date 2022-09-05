<?
include_once $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?getHead('403')?>
    </head>
    <body >
        <?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?breadcrumbs()?>
                </div>
            </div>
            <?include_once $_SERVER['ROOT_PATH'].'assets/template/403.php';?>
        </div>
        <?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
    </body>
</html>

