<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
$_SESSION['user_id']='0';
$_SESSION['user_level']='0';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<?getHead('Login Page')?>

    </head>
    <body>
		
        <header style="background-image: url('<?echo $baseURL?>assets/images/banner.jpg'); background-position: center; padding:10vh">
            <div class="text-center">
                <img class="img-fluid rounded-circle" src="<?echo $baseURL?>assets/images/logo.svg" width="150" alt="logo" />
                <h1 class="text-white fs-3 fw-bolder">WebbiSkools</h1>
                <a href="<?echo $baseURL?>auth/login.php" class="btn btn-info">Click here to login or register</a>
            </div>
        </header>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h2>About</h2>
                    
                    <p> WebbiSkools Ltd provides on-line educational solutions to commercial and government clients, such as universities and training departments of large industrial companies.</p>
                </div>
            </div>
        </div>
		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
    </body>
</html>
