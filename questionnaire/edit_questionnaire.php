<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';


$qid=$_GET['id']??'0';
if (!loggedin()||!hasAccess('2')){
	include $baseURL."assets/errors/401.php";
}
$noContent = mysqli_num_rows(mysqli_query($mysqli,"SELECT * FROM `question_header` WHERE qh_id='$qid'"))==0;
if ($noContent){
	include ($_SERVER['ROOT_PATH'].'assets/errors/204.php');
	die();
}

?>

<html>
	<head>
		<?getHead('Edit questionnaire')?>
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
					<?breadcrumbs(['questionnaire'],['questionnaire'=>'Edit Questionnaire'])?>
				</div>
				<div class="col-md-8" id="questionnaire-all">
					<label class="required">Questionnaire title</label><h1 id="questionnaire-title" contenteditable="true"></h1><br>
					<div class="form-group">
			      		<div class="input-group">
			      			
				            
				            <div class="input-group-prepend">
				      			<div class="input-group-text">Pass</div>		
				      		</div>
			      			<input class="form-control" data-inputmask="'alias': 'positive'"  id="questionnaire-pass">
			      			<div class="input-group-prepend">
				      			<div class="input-group-text">Total</div>		
				      		</div>
			      			<input class="form-control" disabled id="questionnaire-total">
				        </div>
				    </div>
				    <div class="form-group">
			      		<div class="input-group">
			      			<div class="input-group-prepend">
				      			<div class="input-group-text">Status</div>		
				      		</div>
				          <select class="form-control selectpicker" id="questionnaire-status">
				              <option value=1>Publish</option>
				              <option value=2>Draft</option>
				            </select>
			      		</div>
			      	</div>
				    <div class="form-group">
			      		<div class="input-group">
				            <div class="input-group-prepend">
				      			<div class="input-group-text">Subjects</div>		
				      		</div>
						<select class="form-control selectpicker" title="Subject filter"  multiple id="questionnaire-cats" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5" ></select>
			      		</div>
			      	</div>
					<div id="questions">
					</div>
					<br>
					<div class="row" id="action-questionnaire">
						<div class="col-md-4">
							<a href="#" class="text-danger" id="delete-questionnaire">Delete questionnaire</a>	
						</div>
						<div class="col-md-4">
							<button class="btn btn-dark  btn-block float-right mt-2" id="add-question">Add question</button>
						</div>
						<div class="col-md-4">
							<button class="btn btn-info btn-block float-right mt-2" id="save-questionnaire">Save changes</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="save-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header modal-header-info">
		        <h5 class="modal-title">Saved questionnaire</h5>
		      </div>
		      <div class="modal-body">
		        <div class="alert alert-success">
		        	Successfully saved questionnaire. You will be redirected shortly.
		        </div>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="quest-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header modal-header-info">
		        <h5 class="modal-title">Cannot save questionnaire</h5>
		      </div>
		      <div class="modal-body">
		      	<div class="alert alert-danger">Unable to save questionnaire as a question is missing a title or there is no correct option. </div>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="del-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header modal-header-info">
		        <h5 class="modal-title">Delete questionnaire</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<div class="alert alert-danger">Are sure you want to delete the questionnaire?</div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-light mr-auto" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-danger" id="dm-delete">Delete</button>
		      </div>
		    </div>
		  </div>
		</div>

		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
			getQuestionnaire(<?echo $qid?>)
			getQuestionnaireCategories(<?echo $qid?>)
			

			function getQuestionnaireCategories(id){
				ajax({
					method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_questionnaire_categories',
	                    id:id,
	                },
	                dataType: 'json',
				},function(data){
					if (data.result){
                		data.cats.forEach(function(cat){
                			selected=''
                			if (data.selCats!='[]'){
	                			data.selCats.forEach(function(sc){
		                			if (sc==cat.id){
			                			selected='selected'
		                			}
		                		})
                			}
	                		$('#questionnaire-cats').append('<option value="'+cat.id+'" '+selected+'>'+cat.desc+'</option>')
                		})
                		$('.selectpicker').selectpicker('refresh')
                	}
				})   
			}

			$('#questionnaire-pass').keyup(function(){
				if ($('#questionnaire-total').val()<$('#questionnaire-pass').val()){
					$('#questionnaire-pass').val($('#questionnaire-total').val())
				}
			})
			$('#delete-questionnaire').click(function(){
				$('#del-modal').modal('show')
			})


			function getOptions(options){
				all=[]

				options.find('div[data-option]').each(function(){
					all.push({							
					'title':$(this).find('input[data-option-desc]').val(),
						'correct':$(this).find('button[data-option-correct]').hasClass('active')
					})
				})
				return all
			}

			$('#save-questionnaire').click(function(){
				questions=[]
				complete=true
				$('#questions div[data-question]').each(function(){
					options=getOptions($(this))
					if ($(this).find('h3[data-title]').text()==''){
						complete=false
			            timedModal('#quest-modal')
						return false
					}
					oneCorrect=false
					options.forEach(function(o){
						if (o.correct==true){
							oneCorrect=true
						}
						return false
					})
					if (!oneCorrect){
						complete=false
			            timedModal('#quest-modal')
					}

					questions.push({
						'id':$(this).data('id'),
						'title':$(this).find('h3[data-title]').text(),
						'mark':$(this).find('input[data-mark]').val(),
						'options':options
					})
				})
				if (complete){
					ajax({
						method: 'POST',
		                url: 'xhr.php',
		                data: {
		                    action:'save_questionnaire_questions',
		                    id:<?echo $qid?>,
		                    title:$('#questionnaire-title').text(),
		                    cats:$('#questionnaire-cats').val(),
		                    status:$('#questionnaire-status').val(),
		                    pass:$('#questionnaire-pass').val(),
		                    questions:questions
		                },
		                dataType: 'json',
					},function(data){
						if (data.result){
	                		$('#save-modal').modal('show')
	                		timedRedirect('index.php')
	                	}
					})      			
				}
			})

			$('#add-question').click(function(){
				$('#questions').append('<div class="card" data-question><div class="card-header"><label class="required">Question title:</label><h3 data-title contenteditable="true"></h3><label>Mark<input data-mark class="form-control form-control-sm"></label></div><div class="card-body"><label>Options:</label><div class="form-group" data-option><label>A.</label><div class="input-group"><input class="form-control" data-option-desc><div class="input-group-append"><button class="btn btn-outline-success" data-option-correct>Correct</button></div></div></div><div class="form-group" data-option><label>B.</label><div class="input-group"><input class="form-control" data-option-desc><div class="input-group-append"><button class="btn btn-outline-success" data-option-correct>Correct</button></div></div></div><div class="form-group" data-option><label>C.</label><div class="input-group"><input class="form-control" data-option-desc><div class="input-group-append"><button class="btn btn-outline-success" data-option-correct>Correct</button></div></div></div></div><div class="card-footer"><button class="btn btn-danger" data-remove>Remove question</button><button class="btn btn-dark float-right" data-add-option>Add options</button></div></div>')

				Inputmask({ alias: 'positive'}).mask('input[data-mark]');
			})

			function resetNewQuestion(){
				$('#new-title').val('')
				$('#new-question card-body').html('')

			}
			function questionAnswerMaker(id,title,options,mark){
				quest='<div class="card" data-question data-id='+id+'><div class="card-header"><label class="required">Question title:</label><h3 data-title contenteditable="true">'+title+'</h3><label>Mark<input value="'+mark+'" data-mark class="form-control form-control-sm"></label></div> <div class="card-body"><label>Options:</label>'
				count=1;
				options.forEach(function(o){
					quest+='<div class="form-group" data-option><label>'+alpha[count]+'.</label><div class="input-group"><input class="form-control" data-option-desc value="'+o.title+'"><div class="input-group-append">'+(count>3?'<button class="btn btn-danger" data-remove>Remove</button>':'')+'<button class="btn btn-outline-success '+(o.correct=='true'?'active':'')+'" data-option-correct>Correct</button></div></div></div>'
					count++
				})
				quest+='</div><div class="card-footer"><button class="btn btn-danger" data-remove>Remove question</button><button class="btn btn-dark float-right" data-add-option>Add options</button></div></div></div>'
				quest+='</div></div>'
				$('#questions').append(quest)
				Inputmask({ alias: 'positive'}).mask('input[data-mark]');
			}

			function getQuestionnaire(id){
				ajax({
					method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_questionnaire_and_answers',
	                    id:id,
	                },
	                dataType: 'json',
				},function(data){
					if (data.result){
                		$('#questionnaire-title').text(data.title)
                		$('#questionnaire-pass').val(data.pass)

                		data.questions.forEach(function(q){
		                	questionAnswerMaker(q.id,q.title,q.options,q.mark)
                		})
						$('#questionnaire-total').val(totalMarks());
						<?if (!hasAccess('3')){?>
							$('#questionnaire-all input').attr('disabled','true')
							$('#questionnaire-all select').attr('disabled','true')
							$('#questionnaire-all *').removeAttr('contenteditable')
							$('#questionnaire-all label').removeClass('required')
							$('#questionnaire-all .card-footer').hide()
							$('#questionnaire-all div[data-option] button.btn-outline-success').attr('disabled','true')
							$('#questionnaire-all div[data-option] button.btn-outline-success.active').addClass('btn-success')
							$('#questionnaire-all div[data-option] button.btn-success.active').removeClass('btn-outline-success')
							$('#action-questionnaire').hide()
						<?}?>
                	}
				})
			}
			function totalMarks(){
				tM=0
        		$('#questions').find('input[data-mark]').each(function(){
        			if (!Number.isInteger(parseInt($(this).val()))){
        				$(this).val(0)
	        		}
            		tM+=parseInt($(this).val())
        		})
        		return tM;

			}

			$('#questions').on('keyup','input[data-mark]',function(){
				$('#questionnaire-total').val(totalMarks());
			})

			$('#questions').on('click','.card .card-body button[data-option-correct] ',function(){
				$(this).parents('.card-body').find('button[data-option-correct]').removeClass('active')
				$(this).addClass('active')
			})
			$('#questions').on('click','.card .card-body button[data-remove]',function(){
				$(this).parents('.card').find('.card-footer button[data-add-option]').removeAttr('disabled')
				let options= $(this).parents('.card')
				$(this).parents('.form-group').remove()
				options.find('.card-body div[data-option]').each(function(e){
					$(this).find('label').text(alpha[e+1]+'.')	
				})
			})
			$('#questions').on('click','.card .card-footer button[data-remove]',function(){
				$(this).parents('.card').remove()
				$('#questionnaire-total').val(totalMarks());
			})
			$('#questions').on('click','.card .card-footer button[data-add-option]',function(){
				let amount=$(this).parents('.card').find('.card-body').find('div[data-option]').length;
				if (amount>3){
					$(this).attr('disabled','true')
				}

				$(this).parents('.card').find('.card-body').append('<div class="form-group" data-option><label>'+alpha[amount+1]+'.</label><div class="input-group"><input class="form-control" data-option-desc><div class="input-group-append"><button class="btn btn-danger" data-remove>Remove</button><button class="btn btn-outline-success" data-option-correct>Correct</button></div></div></div>')
				
			})

			$('button[data-btn-status]').click(function(){
				getQuestionnaires($(this).data('btn-status'))
			})

		</script>
	</body>
</html>