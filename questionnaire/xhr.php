<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';
if (!loggedin()){
	die(json_encode(['result'=>false,'error'=>'401']));
}
if (isPost()){
	$_POST=sanitize($_POST);
	if ($_POST['action']=='save_questionnaire'){
		if (!hasAccess('3')){
			die(json_encode(['result'=>false,'error'=>'403']));			
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
	elseif ($_POST['action']=='save_questionnaire_questions'){
		if (!hasAccess('3')){
			die(json_encode(['result'=>false,'error'=>'403']));	
		}
		$qid=$_POST['id'];
		$title=$_POST['title'];
		$cats=isset($_POST['cats'])?'['.implode(',', $_POST['cats']).']':'[]';
		$sts=$_POST['status'];
		$pass=$_POST['pass'];
		mysqli_query($mysqli,"UPDATE question_header SET qh_title='$title',qh_qc_id='$cats',qh_pass=$pass WHERE qh_id=$qid");

		$questions=$_POST['questions']??[];
		mysqli_query($mysqli,"UPDATE question_line SET ql_qs_id='3' WHERE ql_qh_id=$qid");
		foreach ($questions as $q) {
			$q['id']=isset($q['id'])?$q['id']:'null';

			$q['options']=isset($q['options'])?json_encode( $q['options']):[];

			mysqli_query($mysqli,"INSERT INTO question_line (ql_id,ql_qh_id,ql_title,ql_options,ql_mark) VALUES (".$q['id'].",$qid,'".$q['title']."','".$q['options']."','".$q['mark']."') ON DUPLICATE KEY UPDATE ql_title='".$q['title']."',ql_options='".$q['options']."', ql_mark='".$q['mark']."'  ,ql_qs_id=1") or die(json_encode(['result'=>false, 'error'=>500]));
		}
		die (json_encode(['result'=>true]));
	}
	elseif ($_POST['action']=='submit_questionnaire_answers'){
		$qid=$_POST['id'];
		$quests=$_POST['quests'];

		$conds='';
		foreach ($quests as $q) {
			$conds.=" OR JSON_OVERLAPS(ql_options, '{\"title\": \"".$q['ans']."\",\"correct\": \"true\"}')";
		}
		$conds=substr($conds, 3);
	
		list($score)=mysqli_fetch_row(mysqli_query($mysqli,"SELECT sum(ql_mark) FROM question_line WHERE ql_qh_id=$qid AND ($conds) ")) or die(json_encode(['result'=>false, 'error'=>500]));
		$score=$score??0;
		list($total)=mysqli_fetch_row(mysqli_query($mysqli,"SELECT sum(ql_mark) FROM question_line WHERE ql_qh_id=$qid and ql_qs_id=1")) or die(json_encode(['result'=>false, 'error'=>500]));
		mysqli_query($mysqli,"INSERT INTO users_question_header (uqh_u_id,uqh_qh_id,uqh_score,uqh_total) VALUES (".$_SESSION['user_id'].",$qid,$score,$total)") or die(json_encode(['result'=>false, 'error'=>500]));

		die(json_encode(['result'=>true]));
	}
	elseif ($_POST['action']=='delete_questionnaire'){
		$id=$_POST['id'];
		mysqli_query($mysqli,"UPDATE `question_header` SET `qh_qs_id` = '3' WHERE `question_header`.`qh_id` = $id;") or die(json_encode(['result'=>false, 'error'=>500]));
		
		die(json_encode(['result'=>true]));
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
		$uid=$_SESSION['user_id'];
		$sts=$_GET['sts']??'1';
		$cats=isset($_GET['cats'])?' AND qc_id in ('.implode(',',$_GET['cats']).') ':'';
		$assigned=$_GET['assigned'];
		$complete=$_GET['complete'];

		$qas=[];
		$cond='';

		if ($assigned==1){
			$cond .= " AND (qh_u_id = ".$_SESSION['user_id']." OR qha_u_id = ".$_SESSION['user_id'].") ";
		}
		elseif ($assigned==2){
			$cond .= " AND (u_id!=".$_SESSION['user_id'].") ";
		}
		if (!hasAccess(2)){
			if($complete==1){
				$cond .= " AND (SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=$uid AND uqh_qh_id=qh_id) IS NULL";
			}
			elseif($complete==2){
				$cond .= " AND  ((SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=$uid AND uqh_qh_id=qh_id) < qh_pass OR (SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=$uid AND uqh_qh_id=qh_id) IS NULL)";
			}
			elseif($complete==3){
				$cond .= " AND (SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=$uid AND uqh_qh_id=qh_id) >= qh_pass";			
			}
		}
		$qq=mysqli_query($mysqli,"SELECT qh_id,(SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=$uid AND uqh_qh_id=qh_id) as uqh_score,qh_id,qh_title,qh_pass,(SELECT max(uqh_score) FROM users_question_header WHERE uqh_u_id=$uid AND uqh_qh_id=qh_id) >= qh_pass as qh_passed,qh_qc_id as c_id, (SELECT CONCAT(u_first_name,' ',u_last_name) FROM users LEFT JOIN question_header_assignee ON u_id=qha_u_id WHERE qha_live=1 AND qha_qh_id=qh_id LIMIT 1) as assigned_by, qh_u_id,(SELECT DATE_FORMAT(qh_end_date, '%m/%d/%Y') FROM question_header_assignee WHERE qha_live=1 AND qha_qh_id=qh_id LIMIT 1) as deadline FROM question_header LEFT JOIN question_category ON JSON_CONTAINS(qh_qc_id, CAST(qc_id as JSON), '$') LEFT JOIN users ON u_id=qh_u_id LEFT JOIN question_header_assignee ON qha_qh_id=qh_id LEFT JOIN users_question_header ON u_id=uqh_id WHERE qh_qs_id in ($sts) $cond $cats GROUP BY qh_id");
		
		while ($r=mysqli_fetch_assoc($qq)){	

			if($r['c_id']!='"[]"'){
				$r['c_id']=substr($r['c_id'], 1, -1);
			}
			if ($r['c_id']=='[]'||empty($r['c_id'])){
				$r['c_id']='"[]"';
			}

				$qcdesc=mysqli_query($mysqli,"SELECT qc_desc FROM question_category WHERE qc_id in(".$r['c_id'].")");
				$owned = mysqli_fetch_row(mysqli_query($mysqli,"SELECT CONCAT(u_first_name,' ',u_last_name) FROM users WHERE u_id=".$r['qh_u_id']));
				$cdesc='';
				
				while ($rr=mysqli_fetch_assoc($qcdesc)){
					$cdesc.=$rr['qc_desc'].' ,';
				}
				$cdesc=substr($cdesc, 0, -1);
				$r['c_id'] = str_replace(["'",'"','[',']'],'',$r['c_id']);

				$qas[]=[
					'id'=>$r['qh_id'],
					'title'=>$r['qh_title'],
					'cid'=>$r['c_id'],
					'cat'=>$cdesc==''?'-':$cdesc,
					'pass'=>$r['qh_pass'],
					'passed'=>$r['qh_passed']??'0',
					'assigned'=>$r['f_name']??'-',
					'deadline'=>$r['deadline']=='00/00/0000'?'No deadline':$r['deadline']??'-',
					'owned'=>$owned,
				];
		}
		die(json_encode(['result'=>$qas!=[],'quest'=>$qas]));
	}
	elseif($_GET['action']=='get_questionnaire_and_answers'){
		$qid=$_GET['id'];

		list($title,$pass)=mysqli_fetch_row(mysqli_query($mysqli,"SELECT qh_title,qh_pass FROM `question_header` WHERE qh_id=$qid")) or die(json_encode(['result'=>false, 'error'=>'500']));
		$qql=mysqli_query($mysqli,"SELECT ql_id,ql_title,ql_options,ql_mark FROM `question_line` WHERE ql_qh_id=$qid AND ql_qs_id=1") or die(json_encode(['result'=>false, 'error'=>'500']));
		$ql=[];
		while($r=mysqli_fetch_assoc($qql)){
			$ql[]=[
				'id'=>$r['ql_id'],
				'title'=>$r['ql_title'],
				'options'=>json_decode($r['ql_options']),
				'mark'=>$r['ql_mark'],
			];
		}
		die(json_encode(['result'=>true,'questions'=>$ql,'title'=>$title,'pass'=>$pass]));
	}
	elseif ($_GET['action']=='get_questionnaire'){
		$qid=$_GET['id'];
		list($title)=mysqli_fetch_row(mysqli_query($mysqli,"SELECT qh_title FROM question_header WHERE qh_id=$qid")) or die(json_encode(['result'=>false, 'error'=>500]));
		$qql=mysqli_query($mysqli,"SELECT ql_id,ql_title,ql_options FROM `question_line` WHERE ql_qh_id=$qid AND ql_qs_id=1") or die(json_encode(['result'=>false, 'error'=>500]));

		$ql=[];
		while ($r=mysqli_fetch_assoc($qql)) {
			$opt=[];
			foreach (json_decode($r['ql_options'],true) as $value) {
				$opt[]=$value['title'];
			}

			$ql[]=[
				'id'=>$r['ql_id'],
				'title'=>$r['ql_title'],

				'options'=>$opt,
			];
		}
		die(json_encode(['result'=>true,'title'=>$title,'questions'=>$ql]));
	}
	elseif ($_GET['action']=='get_user_attempts'){

		$quqh='';
		$cond=[];
		if (!hasAccess(2)){
			$cats=isset($_GET['cats'])?' AND qc_id in ('.implode(',',$_GET['cats']).') ':'';

			$cond=$cond==[]?'':'Where '.implode(' AND ',$cond);
			$quqh=mysqli_query($mysqli,"SELECT qh_id,u_first_name,u_last_name,qh_title,DATE_FORMAT(uqh_input_date, '%m/%d/%Y %H:%i') as uqh_input_date,uqh_score,uqh_total,qh_pass FROM `users_question_header` LEFT JOIN question_header ON uqh_qh_id=qh_id LEFT JOIN users ON uqh_u_id=u_id LEFT JOIN question_category ON JSON_CONTAINS(qh_qc_id, CAST(qc_id as JSON), '\$') WHERE u_id=".$_SESSION['user_id']." $cats GROUP BY uqh_id ORDER BY `users_question_header`.`uqh_input_date` DESC");
		}else{
			$usrs=(isset($_GET['users']) && !empty($_GET['users'])) ?' u_id in ('.implode(',', $_GET['users']).') ':'';

			$cats=isset($_GET['cats'])?' qc_id in ('.implode(',',$_GET['cats']).') ':'';

			$cond=[];
			if ($usrs!=''){
			$cond[]=$usrs;
			}
			if ($cats!=''){
			$cond[]=$cats;
			}
			
			$cond=$cond==[]?'':'Where '.implode(' AND ',$cond);


			$quqh=mysqli_query($mysqli,"SELECT qh_id,u_first_name,u_last_name,qh_title,DATE_FORMAT(uqh_input_date, '%m/%d/%Y %H:%i') as uqh_input_date,uqh_score,uqh_total,qh_pass FROM `users_question_header` LEFT JOIN question_header ON uqh_qh_id=qh_id LEFT JOIN users ON uqh_u_id=u_id LEFT JOIN question_category ON JSON_CONTAINS(qh_qc_id, CAST(qc_id as JSON), '\$') $cond GROUP BY uqh_id ORDER BY `users_question_header`.`uqh_input_date` DESC");
		}
		$ugh=[];
		while ($r=mysqli_fetch_assoc($quqh)) {
			$ugh[]=[
				'id'=>$r['qh_id'],
				'name'=>$r['u_first_name'].' '.$r['u_last_name'],
				'quest'=>$r['qh_title'],
				'date'=>$r['uqh_input_date'],
				'score'=>$r['uqh_score'],
				'pass'=>$r['uqh_total'],
				'total'=>$r['uqh_total'],
			];
		}
		die(json_encode(['result'=>$ugh!=[],'outcome'=>$ugh]));
	}
}

die(json_encode(['result'=>false,'error'=>'404']));
