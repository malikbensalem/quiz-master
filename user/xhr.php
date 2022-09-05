<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
if (!loggedin()){
	die(json_encode(['result'=>false,'error'=>'401']));
}

if (isPost()){
	$_POST=sanitize($_POST);
	if ($_POST['action']=='remove_user'){
		$uid=$_POST['id'];
		if (!hasAccess(4)){
			die(json_encode(['result'=>false,'error'=>'403']));			
		}
		mysqli_query($mysqli,"UPDATE `users` SET `u_us_id` = '3' WHERE `u_id` = $uid;");
	}
	elseif ($_POST['action']=='change_user_type'){
		if (!hasAccess(4)){
			die(json_encode(['result'=>false,'error'=>'403']));			
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
	elseif ($_POST['action']=='user_assignee'){	
		if (!hasAccess(3)){
				die(json_encode(['result'=>false,'error'=>'403']));
		}
		$qid=$_POST['qid'];
		$users=$_POST['users']??[];
		mysqli_query($mysqli,"UPDATE question_header_assignee SET qha_live=0 WHERE qha_qh_id=$qid") or die();
		foreach ($users as $u) {
			mysqli_query($mysqli,"INSERT INTO question_header_assignee (qha_qh_id,qha_u_id,qh_end_date,qh_assigned_by) VALUES($qid,".$u['uid'].",'".$u['deadline']."',".$_SESSION['user_id'].") ON DUPLICATE KEY UPDATE qha_live=1, qh_end_date='".$u['deadline']."'") or die(json_encode(['result'=>false,'error'=>'500']));
		}
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
	elseif ($_GET['action']=='get_user_assignee'){
		if (!hasAccess(2)){
			die(json_encode(['result'=>false,'error'=>'403']));				
		}
		$qid=$_GET['qid'];
		$qqha=mysqli_query($mysqli,"SELECT qha_qh_id,qha_u_id,DATE_FORMAT(qh_input_date, '%Y-%m-%d') as qh_input_dates,qh_end_date,CONCAT(u_first_name,' ',u_last_name) as qha_u_name, (select CONCAT(u_first_name,' ',u_last_name) FROM users WHERE u_id=qh_assigned_by) as qh_a_name FROM question_header_assignee LEFT JOIN users ON u_id = qha_u_id WHERE qha_qh_id=$qid AND qha_live=1") or die(json_encode(['result'=>false,'error'=>'500']));
		$qha=[];
		while ($r=mysqli_fetch_assoc($qqha)) {
			list($attempts) = mysqli_fetch_row(mysqli_query($mysqli,"SELECT count(uqh_id) FROM `users_question_header` WHERE uqh_u_id = ".$r['qha_u_id']." AND uqh_qh_id=".$r['qha_qh_id'])) or die(json_encode(['result'=>false,'error'=>'500']));
			list($best,$passed) = mysqli_fetch_row(mysqli_query($mysqli,"SELECT uqh_score,uqh_total<=uqh_score FROM `users_question_header` WHERE uqh_u_id = ".$r['qha_u_id']." AND uqh_qh_id=".$r['qha_qh_id']." ORDER BY uqh_score LIMIT 1")) or die(json_encode(['result'=>false,'error'=>'500']));


			$qha[]=[
				'uid'=>$r['qha_u_id'],
				'aname'=>$r['qh_a_name'],

				'uname'=>$r['qha_u_name'],
				'aname'=>$r['qh_a_name'],
				'date'=>$r['qh_input_dates'],
				'deadline'=>$r['qh_end_date'],
				'attempts'=> $attempts,
				'best'=>$best,
				'passed'=>$passed,
			];
		}
		die(json_encode(['result'=>$qha!=[],'users'=>$qha]));
	}
	elseif ($_GET['action']=='get_users'){
		$id=isset($_GET['id'])?' AND u_id='.$_GET['id']:'';
		// if ($id==''){
		// 	if (!hasAccess('2')){
		// 		die(json_encode(['result'=>false,'error'=>'403']));	
		// 	}
		// }
		$sts=$_GET['sts']??'(SELECT ut_id FROM `users_type`)';
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
	elseif ($_POST['action']=='remove_user'){
		$uid=$_POST['id'];
		mysqli_query($mysqli,"UPDATE `user` SET `u_us_id` = '3' WHERE `user`.`u_id` = $uid;");
	}
}
die(json_encode(['result'=>false,'error'=>'404']));