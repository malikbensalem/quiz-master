<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<?getHead('404')?>
        <link rel="stylesheet" type="text/css" href="<?echo $baseURL?>assets/errors/errors.css">
    </head>
    <body>

		<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>

		<div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                	<img class="pic-header" src="<?echo $baseURL?>assets/images/hole.png" alt="hole">
                </div>
            </div>

            <div class="row">


                <div class="col-md-6 text-center">
                    <h1>404</h1>
                    <h2>Page not found</h2>
                    <br><hr>
                    <h3 class="text-center">You fell into a hole and could not be found.</h3>
                </div>
                <div class="col-md-6 text-center">
        			<?breadcrumbs()?>
                  
                </div>
            </div>
        </div>

        
		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
    </body>
</html>

