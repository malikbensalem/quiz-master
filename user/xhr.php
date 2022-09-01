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
		$sts=$_GET['sts']??'(SELECT ut_id FROM `user_type`)';
		$name=isset($_GET['name'])?' AND (CONCAT(u_first_name," ",u_last_name) LIKE "%'.$_GET['name'].'%" OR u_email LIKE "%'.$_GET['name'].'%") LIMIT 1':'';
		$qu=mysqli_query($mysqli,"SELECT u_id,u_first_name,u_last_name FROM `users` WHERE u_us_id=1 AND u_ut_id in($sts) $name") or die(json_encode(['result'=>false,'error'=>'500']));
		$u=[];
		while ($r=mysqli_fetch_assoc($qu)) {
			$u[]=[
				'id'=>$r['u_id'],
				'name'=>$r['u_first_name'].' '.$r['u_last_name'],
			];
		}
		die(json_encode(['result'=>$u!=[],'users'=>$u]));
	}
}
die(json_encode(['result'=>false,'error'=>'404']));