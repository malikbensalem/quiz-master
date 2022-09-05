<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';


$qid=$_GET['id']??'0';
if (!loggedin()){
	include ($_SERVER['ROOT_PATH'].'assets/errors/401.php');
	die();
}
list($title) = mysqli_fetch_row(mysqli_query($mysqli,"SELECT qh_title FROM `question_header` WHERE qh_id='$qid' AND qh_qs_id!=3"));
if ($title==[]){
	include ($_SERVER['ROOT_PATH'].'assets/errors/204.php');
	die();
}

?>

<html>
	<head>
		<?getHead($title)?>
		<style type="text/css">
			.card{
				margin-bottom: 20px;
			}
		</style>
	</head>
	<body>
		<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>
		
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<?breadcrumbs(['questionnaire'],['questionnaire'=>'Questionnaire'])?>
				</div>
				<div class="col-md-8">
					
				<h1 id="questionnaire-title">Questionnaire title</h1><br>
				<div id="questions">
				</div>
				<button class="btn btn-info float-right" id="submit-ans">Submit</button>	
				</div>
			</div>
		</div>


		<div class="modal fade" id="submit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header modal-header-info">
		        <h5 class="modal-title"><i class="fa-solid fa-clipboard-check"></i> Questionnaire Complete</h5>
		      </div>
		      <div class="modal-body">
		      	<div class="alert alert-success">Go to the results page to see how you did.</div>
		      </div>
		    </div>
		  </div>
		</div>		

		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
			getQuestionnaire(<?echo $qid?>)
			$('#questions').on('click','button[data-option]',function(){
				$('#questions').find('button[data-option]').removeClass('active')
				$(this).addClass('active')
			})
			function questionMaker(id,title,options){
				quest='<div class="card" data-id='+id+'><div class="card-header"><h3>'+title+'</h3></div><div class="card-body">'
				count=1
				options.forEach(function(o){
					quest+='<div class="form-group"><div class="input-group">'
					quest+='<div class="input-group-prepend"><div class="input-group-text">'+alpha[count]+'</div></div>'
					quest+='<button class="btn btn-outline-dark form-control" data-option>'+o+'</button>'
					quest+='</div></div>'
					count++
				})
				quest+='</div></div>'
				$('#questions').append(quest)
			}
			function getQuestionnaire(id){
				ajax({
					method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_questionnaire',
	                    id:id,
	                },
	                dataType: 'json',
				},function(data){
					data.questions.forEach(function(q){
	                	questionMaker(q.id,q.title,q.options)
            		})
				})
			}
			$('#submit-ans').click(function(){
				submitQuestionnaireAnswers()				
			})

			function submitQuestionnaireAnswers(){
				quests=[]

				$('#questions').find('div[data-id]').each(function(){
					quests.push({
						'id':$(this).data('id'),
						'ans':$(this).find('button.active').text()
					})
				})
				ajax({
					method: 'POST',
	                url: 'xhr.php',
	                data: {
	                    action:'submit_questionnaire_answers',
	                    quests:quests,
	                    id:<?echo $qid?>,
	                },
	                dataType: 'json',
				},function(data){
					if (data.result){
                		$('#questionnaire-title').html(data.title)
                		timedModal('#submit-modal')
                		timedRedirect('<?echo $baseURL?>questionnaire/results.php')
                	}
                })
			}	
			
		</script>
	</body>
</html>