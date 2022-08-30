<?
function loggedin(){
	global $mysqli;
	return mysqli_num_rows(mysqli_query($mysqli,"SELECT u_id FROM user WHERE u_us_id=1 AND u_ut_id!=0 AND u_id='".$_SESSION['user_id']."'"))>0;
}
function sanitize($value) {
  global $mysqli;
  return is_array($value) ?array_map('sanitize', $value) :mysqli_real_escape_string($mysqli,strip_tags(htmlspecialchars($value)));
}
function isGet(){
	return $_SERVER['REQUEST_METHOD'] === 'GET';
}
function isPost(){
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}
function getHead($title){
	global $baseURL;
	$style_css_v=md5_file($_SERVER['ROOT_PATH']."assets/css/style.css");

	echo "<meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'><meta name='description' content='questionnare page.'><meta name='author' content='Malik Bensalem'><meta name='docsearch:language' content='en'>";
	echo "<title>$title</title>";
	echo "<link rel='stylesheet' href='".$baseURL."assets/css/bootstrap4.css'>";
	echo "<link rel='stylesheet' href='".$baseURL."assets/css/font_awsome6.css' />";
	echo '<link rel="stylesheet" href="'.$baseURL.'assets/css/bootstrap_select.css">';
	echo '<script src="'.$baseURL.'assets/js/jquery.js"></script>';
	echo "<script src='".$baseURL."assets/js/popper.js'></script>";
	echo '<script src="'.$baseURL.'assets/js/bootstrap_select.js"></script>';

	echo "<link rel='stylesheet' href='".$baseURL."assets/css/style.css?v=".$style_css_v."'>";
}
function breadcrumbs($active=[],$text=[]){
	global $baseURL;

	echo '<div class="btn-group-vertical btn-block" id="breadcrumbs">
		<a class="btn btn-lg btn-block btn-outline-info active">Links</a>
		<a class="btn btn-lg btn-block btn-outline-dark " href="javascript:history.back()">Go Back</a>';
	if (loggedin()){
		echo '<a data-questionnaire href="'.$baseURL.'questionnaire.php" class="btn btn-lg btn-block btn-outline-dark" >Questionnaires</a>
		<a data-results href="'.$baseURL.'results.php" class="btn btn-lg btn-block btn-outline-dark">See Results</a>
		'.($_SESSION['user_level']>1?'<a data-users href="'.$baseURL.'users.php" class="btn btn-lg btn-block btn-outline-dark">'.($_SESSION['user_level']==2?"View":'Manage').' users</a>':'');
	}
	else{
		echo '<a data-questionnaire href="'.$baseURL.'" class="btn btn-lg btn-block btn-outline-dark" >Home</a>
		<a data-questionnaire href="'.$baseURL.'login.php" class="btn btn-lg btn-block btn-outline-dark" >Login / Register</a>';
	}
	echo '</div>';

	foreach ($active as $a) {
	?><script class="temp">
		$('#breadcrumbs').find("[data-<?echo $a?>]").addClass('active');
	</script><?

	}
	foreach ($text as $key => $value) {
		?>
		<script class="temp">
		$('#breadcrumbs').find("[data-<?echo $key?>]").text("<?echo $value?>");
		</script>
		<?
	}
}