<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
if (!loggedin()){
	die(json_encode(['result'=>false,'error'=>'401']));
}
if (isPost()){
	$_POST=sanitize($_POST);
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

		$joins=' LEFT JOIN question_header_assignee ON qha_qh_id=qh_id ';

		if ($assigned==2){
			$cond .= " AND qha_u_id = ".$_SESSION['user_id']." ";
			$joins .= ' INNER JOIN question_header_assignee ON qha_qh_id=qh_id ';
		}
		elseif ($assigned==3){
			$cond .= " AND qha_u_id != ".$_SESSION['user_id'];
		}
		$joins .= ' LEFT JOIN users ON u_id=qha_u_id ';
		if ($complete==1){
			$joins .= ' LEFT JOIN users_question_header ON u_id=uqh_id ';
			$cond .= " AND uqh_id != ".$_SESSION['user_id'];
		}
		elseif($complete==2){
			$joins .= ' INNER JOIN users_question_header ON u_id=uqh_id ';
			$cond .= " AND uqh_score < qh_pass";
		}
		elseif($complete==3){
			$joins .= ' INNER JOIN users_question_header ON u_id=uqh_id ';
			$cond .= " AND uqh_score >= qh_pass";			
		}

		$qq=mysqli_query($mysqli,"SELECT qh_id,qh_title,qh_pass,qh_pass>=(SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=".$_SESSION['user_id'].") as qh_passed, CONCAT(u_first_name,' ',u_last_name) as f_name,GROUP_CONCAT(qc_id SEPARATOR ', ') as c_id ,GROUP_CONCAT(qc_desc SEPARATOR ', ') as c_desc FROM question_header LEFT JOIN question_category ON JSON_CONTAINS(qh_qc_id, CAST(qc_id as JSON), '\$') $joins  WHERE qh_qs_id in ($sts) $cond $cats GROUP BY qh_id") or die(json_encode(['result'=>false, 'error'=>500]));

		while ($r=mysqli_fetch_assoc($qq)){			
			$qas[]=[
				'id'=>$r['qh_id'],
				'title'=>$r['qh_title'],
				'cid'=>$r['c_id'],
				'cat'=>$r['c_desc']??'',
				'pass'=>$r['qh_pass'],
				'passed'=>$r['qh_passed']??'0',
				'assigned'=>$r['f_name']??'',
			];
		}

		die(json_encode(['result'=>$qas!=[],'quest'=>$qas]));
	}
}

die(json_encode(['result'=>false,'error'=>'404']));
