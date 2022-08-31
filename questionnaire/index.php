<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';

if (!loggedin()){
	include ($_SERVER['ROOT_PATH'].'assets/errors/401.php');
	die();
}
?>

<html>
	<head>
		<?getHead('Questionnaire Page')?>
	</head>
	<body>
			<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>
		<div class="container">
			<h1>Questionnaires</h1><br>
			
			<div class="row">
				<div class="col-md-4" >
					<?breadcrumbs(['questionnaire'])?>

					<div class="btn-group-vertical btn-block">
						<a class="btn btn-lg btn-block btn-outline-info active">Action</a>
						<button class="btn btn-lg btn-block btn-outline-dark" id="create-q">Create questionnaire</button>
					</div>

					<div class="btn-group-vertical btn-block" id="statuses">
						<a class="btn btn-lg btn-block btn-outline-info active">Status</a>
						<button class="btn btn-lg btn-block btn-outline-dark" data-btn-status="1">Published</button>
						
						<?if($_SESSION['user_level']==1){?><button class="btn btn-lg btn-block btn-outline-dark" data-btn-status="complete">Complete</button><?}?>
						<?if($_SESSION['user_level']>1){?><button class="btn btn-lg btn-block btn-outline-dark"data-btn-status="2">Drafts</button><?}?>
					</div>
					<div class="btn-group-vertical btn-block">
						<a class="btn btn-lg btn-block btn-outline-info active">Filters</a>
						<select class="form-control form-select-lg selectpicker " title="Assign filter" id="assigned-filter" data-style="btn-outline-dark">
							<option value="1" selected>Assigned</option>
							<option value="2">Not assigned</option>
						</select>
						<select class="form-control form-select-lg selectpicker" title="Complete filter" id="complete-filter" data-style="btn-outline-dark">
							<option value="1" selected>Not attempted</option>
							<option value="2">Not passed</option>
							<option value="3">Passed</option>
						</select>

						<select class="form-control selectpicker" title="Subject filter"  multiple id="category-filter" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5" data-style="btn-outline-dark"></select>
					</div>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-striped " id="tbl-questionnaire">
							<thead><th>Title</th><th>Subject</th><th>Pass</th><?if($_SESSION['user_level']==1){?><th>Passed</th><?}?><th>Assigned by</th><th>Actions</th></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<?if ($_SESSION['user_level']>1){
			include $_SERVER['ROOT_PATH']."assets/modals/user_assign_modal.php";
			include $_SERVER['ROOT_PATH']."assets/modals/create_edit_modal.php";
		}?>
		<!-- Modal -->
		
		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
			getQuestionnaires(1)
			getQuestionnaireCategories()
			function getQuestionnaires(sts,cat=[],assign=1,complete=1){
				$.ajax({
	                method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_questionnaires',
	                    sts:sts,
	                    cats:cat,
	                    assigned:assign,
	                    complete:complete,
	                },
	                dataType: 'json',
	                success: function(data) {
                		$('#tbl-questionnaire tbody').html('')
	                	if (data.result){
	                		
	                		data.quest.forEach(function(q){
								$('#tbl-questionnaire tbody').append('<tr data-id='+q.id+'><td data-title>'+q.title+'</td><td data-cat="'+q.cid+'">'+q.cat+'</td><td>'+q.pass+'</td><?if ($_SESSION['user_level']==1){?><td>'+(q.passed==1?'<i class="fa-solid fa-check"></i>':'<i class="fa-solid fa-x"></i>')+'</td><td>'+q.assigned+'</td><?}?><td><div class="btn-group"><?if ($_SESSION['user_level']>1){?><button class="btn btn-light" data-edit title="Edit"><i class="fa-regular fa-pen-to-square"></i></button><button class="btn btn-dark" data-users title="Assignees"><i class="fa-solid fa-users"></i></button><?}?> <a href="show_questions?id='+q.id+'" class="btn btn-primary" title="Start"><i class="fa-solid fa-play"></i></a></div></td></tr>')
	                		})
	                	}
	                	else{
	                		emptyTableMsg('#tbl-questionnaire tbody','There are no questionnaires')
	                	}
	                }
				})
			}
			
			$('#tbl-questionnaire tbody').on('click','button[data-users]',function(){
				getAssignees($(this).parents('tr').data('id'))
			})

			$('#category-filter,#assigned-filter,#complete-filter').change(function(){
				getQuestionnaires($('#statuses button[data-btn-status]').data('btn-status'),$('#category-filter').val(),$('#assigned-filter').val(),$('#complete-filter').val())
			})
			$('#statuses button[data-btn-status]').click(function(){
				getQuestionnaires($('#statuses button[data-btn-status]').data('btn-status'),$('#category-filter').val(),$('#assigned-filter').val(),$('#complete-filter').val())
			})
			<?if ($_SESSION['user_level']>1){?>
			$('#tbl-questionnaire tbody').on('click','button[data-users]',function(){
				$('#user-assign-modal').data('qid',$(this).parents('tr').data('id'))
				$('#user-assign-modal').modal('show')
			})
			
			$('#tbl-questionnaire tbody').on('click','button[data-edit]',function(){
				self=$(this).parents('tr')
				$('#cem-title').val(self.find('td[data-title]').text())

				$('#cem-status').val($('#statuses').find('button.active').data('btn-status'))
				cats=self.find('td[data-cat]').data('cat')?self.find('td[data-cat]').data('cat').replaceAll(' ','').split(','):''
				$('#cem-cat').val(cats)
			
				$('#cem-save').data('id',self.data('id'))

			    $('.selectpicker').selectpicker('refresh')

				$('#cem-questions').show()
				$('#create-edit-modal').modal('show')
			})
			<?}?>
			
		</script>
	</body>
</html>