<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
if (isPost()){
	if ($_POST['action']=='login'){
		$em=$_POST['em'];
		$pw=hashPW($em,$_POST['pw']);
		$noUser=mysqli_query($mysqli,"SELECT u_id,u_us_id,u_ut_id FROM users WHERE u_email='$em' AND u_password='$pw'");
		if (mysqli_num_rows($noUser)==0){
			die(json_encode(['result'=>false,'alert'=>'<div class="alert alert-danger">Wrong email or password.</div>']));
		}
		list($_SESSION['user_id'],$sts,$_SESSION['user_level'])=mysqli_fetch_row($noUser);
		if ($sts!=1){
			die(json_encode(['result'=>false,'alert'=>'<div class="alert alert-danger">This user has been removed.</div>']));
		}
		die(json_encode(['result'=>true,'alert'=>'<div class="alert alert-success">Successfully logged in. Redirecting to questionnaires page</div>']));

	}
	elseif ($_POST['action']=='register'){
		$em=$_POST['em'];
		$pw=hashPW($em,$_POST['pw']);
		$fn=$_POST['fn'];
		$ln=$_POST['ln'];

		mysqli_query($mysqli,"INSERT INTO users (u_email,u_password,u_first_name,u_last_name) VALUES('$em','$pw','$fn','$ln')") or die(json_encode(['result'=>false,'alert'=>'<div class="alert alert-danger"> This email is already registered with us.</div>']));

		die(json_encode(['result'=>true,'alert'=>'<div class="alert alert-success">Successfully registered.</div>']));
	}
}