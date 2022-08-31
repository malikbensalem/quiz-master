<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
if (!loggedin()){
	die(json_encode(['result'=>false,'error'=>'401']));
}
if (isPost()){
	$_POST=sanitize($_POST);
	if ($_POST['action']=='save_questionnaire'){
		if ($_SESSION['user_level']<2){
			die(json_encode(['result'=>false]));
		}
		$id=$_POST['id']??0;
		$title=$_POST['title'];
		$cat=$_POST['cat']??'[]';
		$sts=$_POST['sts'];
		if ($id){
			mysqli_query($mysqli,"UPDATE question_header SET qh_title='$title',qh_qc_id='".str_replace('"','',json_encode($cat))."',qh_qs_id=$sts WHERE qh_id=$id") or die (json_encode(['result'=>false]));
		}else{
			mysqli_query($mysqli,"INSERT INTO question_header (qh_title,qh_u_id,qh_qc_id,qh_qs_id) VALUES('$title','".$_SESSION['user_id']."','".json_encode($cat)."',$sts)") or die (json_encode(['result'=>false]));
		}
		die (json_encode(['result'=>true]));
	}
}
elseif (isGet()){
	$_GET=sanitize($_GET);
	if($_GET['action']=='get_questionnaire_categories'){
		$qid=$_GET['id']??'0';
		$qqc=mysqli_query($mysqli,"SELECT qc_id,qc_desc FROM question_category") or die(json_encode(['result'=>false, 'error'=>500]));
		$qc=[];
		while ($r=mysqli_fetch_assoc($qqc)) {
			$qc[]=[
				'id'=>$r['qc_id'],
				'desc'=>$r['qc_desc'],
			];
		}
		$qhc=json_decode(mysqli_fetch_row(mysqli_query($mysqli,"SELECT qh_qc_id FROM question_header WHERE qh_id=$qid"))[0]??'0',true);
		die(json_encode(['result'=>true,'cats'=>$qc,'selCats'=>$qhc]));
	}
	elseif ($_GET['action']=='get_questionnaires'){
		$sts=$_GET['sts'];
		$cats=isset($_GET['cats'])?' AND qc_id in ('.implode(',',$_GET['cats']).') ':'';
		$assigned=$_GET['assigned'];
		$complete=$_GET['complete'];

		$qas=[];

		$cond='';

		$joins='';

		if ($assigned==1){
			$cond .= " AND (qh_u_id = ".$_SESSION['user_id']." OR qha_u_id = ".$_SESSION['user_id'].") ";
		}
		elseif ($assigned==2){
			$cond .= " AND qha_u_id != ".$_SESSION['user_id'];
		}
		if ($_SESSION['user_level']==1){
			if($complete==1){
				$cond .= " AND uqh_score IS NULL";
			}
			elseif($complete==2){
				$cond .= " AND uqh_score < qh_pass";
			}
			elseif($complete==3){
				$cond .= " AND uqh_score >= qh_pass";			
			}
		}

		$qq=mysqli_query($mysqli,"SELECT qh_u_id,qh_id,qh_title,qh_pass,qh_pass>=(SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=".$_SESSION['user_id'].") as qh_passed, CONCAT(u_first_name,' ',u_last_name) as f_name,GROUP_CONCAT(qc_id SEPARATOR ', ') as c_id ,GROUP_CONCAT(qc_desc SEPARATOR ', ') as c_desc FROM question_header LEFT JOIN question_category ON JSON_CONTAINS(qh_qc_id, CAST(qc_id as JSON), '\$') LEFT JOIN question_header_assignee ON qha_qh_id=qh_id LEFT JOIN users ON u_id=qha_u_id LEFT JOIN users_question_header ON u_id=uqh_id WHERE qh_qs_id in ($sts) $cond $cats GROUP BY qh_id") or die(json_encode(['result'=>false, 'error'=>500]));

		while ($r=mysqli_fetch_assoc($qq)){	
			$owned = mysqli_fetch_row(mysqli_query($mysqli,"SELECT u_first_name,u_last_name FROM users WHERE u_id=".$r['qh_u_id']));
			$owned = $owned[0].' '.$owned[1];

			$qas[]=[
				'id'=>$r['qh_id'],
				'title'=>$r['qh_title'],
				'cid'=>$r['c_id'],
				'cat'=>$r['c_desc']??'',
				'pass'=>$r['qh_pass'],
				'passed'=>$r['qh_passed']??'0',
				'assigned'=>$r['f_name']??'',
				'owned'=>$owned??'',
			];
		}

		die(json_encode(['result'=>$qas!=[],'quest'=>$qas]));
	}
}

die(json_encode(['result'=>false,'error'=>'404']));
