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
                    
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
            </div>
        </div>
		<?include $_SERVER['ROOT_PATH'].'assets/includes/footer.php'?>
    </body>
</html>
