<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
if (!loggedin()){
	die(json_encode(['result'=>false,'error'=>'401']));
}
if (isPost()){
	$_POST=sanitize($_POST);
	if ($_POST['action']=='remove_user'){
		$uid=$_POST['id'];
		mysqli_query($mysqli,"UPDATE `users` SET `u_us_id` = '3' WHERE `u_id` = $uid;");
	}
	elseif ($_POST['action']=='change_user_type'){
		if ($_SESSION['user_level']<3){
			die(json_encode(['result'=>false]));
		}
		$uid=$_POST['id'];
		$type=$_POST['type'];
		if ($uid==$_SESSION['user_id']){
			$_SESSION['user_level']=$type;
		}
		mysqli_query($mysqli,"UPDATE `users` SET `u_ut_id` = '$type' WHERE `u_id` = $uid;");
		die(json_encode(['result'=>true]));
	}
	elseif ($_POST['action']=='change_password'){
		list($email)=mysqli_fetch_row(mysqli_query($mysqli,"SELECT u_email FROM users WHERE u_id=".$_SESSION['user_id']));
		$old=hashPW($email,$_POST['old']);
		$new=hashPW($email,$_POST['new']);
		$matches=mysqli_query($mysqli,"SELECT u_id FROM users WHERE u_password='$old' AND u_id=".$_SESSION['user_id']) or die(json_encode(['result'=>false,'error'=>'500']));
		if (mysqli_num_rows($matches)!=1){
			die(json_encode(['result'=>false,'alert'=>'The current password is incorrect.']));
		}
		mysqli_query($mysqli,"UPDATE `users` SET `u_password` = '$new' WHERE `u_id` =".$_SESSION['user_id']) or die(json_encode(['result'=>false,'error'=>'500']));
		die(json_encode(['result'=>true]));
	}
}
elseif(isGet()){
	$_GET=sanitize($_GET);
	if($_GET['action']=='get_user_type'){
		$qut=mysqli_query($mysqli,"SELECT ut_id,ut_desc FROM `users_type`") or die(json_encode(['result'=>false,'error'=>'500']));
		$ut=[];
		while ($r=mysqli_fetch_assoc($qut)) {
			$ut[]=[
				'id'=>$r['ut_id'],
				'desc'=>$r['ut_desc'],
			];
		}
		die(json_encode(['result'=>true,'types'=>$ut]));
	}
	elseif ($_GET['action']=='get_users'){
		if (!hasAccess('2')){
			die(json_encode(['result'=>false,'error'=>'401']));	
		}
		$sts=$_GET['sts']??'(SELECT ut_id FROM `users_type`)';
		$id=isset($_GET['id'])?' AND u_id='.$_GET['id']:'';
		$name=isset($_GET['name'])?' AND (CONCAT(u_first_name," ",u_last_name) LIKE "%'.$_GET['name'].'%" OR u_email LIKE "%'.$_GET['name'].'%") LIMIT 1':'';
		$qu=mysqli_query($mysqli,"SELECT u_id,u_first_name,u_last_name,ut_desc,u_email FROM `users` LEFT JOIN users_type ON ut_id=u_ut_id WHERE u_us_id=1 AND u_ut_id in($sts) $name $id") or die(json_encode(['result'=>false,'error'=>'500']));
		$u=[];

		while ($r=mysqli_fetch_assoc($qu)) {
			$u[]=[
				'id'=>$r['u_id'],
				'name'=>$r['u_first_name'].' '.$r['u_last_name'],
				'email'=>$r['u_email'],
				'type'=>$r['ut_desc'],
			];
		}
		die(json_encode(['result'=>$u!=[],'users'=>$u]));
	}
}
die(json_encode(['result'=>false,'error'=>'404']));