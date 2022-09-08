<?
include_once $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<?getHead('500')?>
    </head>
    <body>
		<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>
		<div class="container error">
            <div class="row">
                <div class="col-md-12 text-center">
                	<?breadcrumbs()?>  
                </div>
            </div>
            <?include_once $_SERVER['ROOT_PATH'].'assets/template/500.php';?>
        </div>
		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
    </body>
</html>

