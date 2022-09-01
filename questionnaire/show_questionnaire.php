<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';


$qid=$_GET['id']??'0';
if (!loggedin()){
	header("Location: $baseURL");
}
if (!$qid){
	header("Location: $baseURL".'questionnaire.php');
}

?>

<html>
	<head>
		<?getHead('Questionnaire Page')?>
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
				<button class="btn btn-info" id="submit-ans">Submit</button>	
				</div>
			</div>
		</div>


		<div class="modal fade" id="submit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header modal-header-info">
		        <h5 class="modal-title">Questionnaire Complete</h5>
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
				$(this).siblings().removeClass('active')
				$(this).addClass('active')
			})
			function getQuestionnaire(id){
				$.ajax({
	                method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_questionnaire',
	                    id:id,
	                },
	                dataType: 'json',
	                success: function(data) {
	                	if (data.result){
	                		$('#questionnaire-title').html(data.title)

	                		data.questions.forEach(function(q){
			                	questionMaker(q.id,q.title,q.options)
	                		})
	                	}
	                }
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

				$.ajax({
	                method: 'POST',
	                url: 'xhr.php',
	                data: {
	                    action:'submit_questionnaire_answers',
	                    quests:quests,
	                    id:<?echo $qid?>,
	                },
	                dataType: 'json',
	                success: function(data) {
	                	if (data.result){
	                		$('#questionnaire-title').html(data.title)
	                		timedModal('#submit-modal')
	                		timedRedirect('results.php')
	                	}
	                }
				})

			}		
			
		</script>
	</body>
</html>