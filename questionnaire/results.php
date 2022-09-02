<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';

if (!loggedin()){
	include ($_SERVER['ROOT_PATH'].'assets/errors/401.php');
	die();
}
?>

<html>
	<head>
		<?getHead('Results')?>
	</head>
	<body>
		<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>
		
		<div class="container">
			<h1>Results</h1><br>
			<div class="row">
				<div class="col-md-4" >
					<?breadcrumbs(['results'])?>
					<div class="btn-group-vertical btn-block">
						<a class="btn btn-lg btn-block btn-outline-info active">Filters</a>
						<select class="form-control selectpicker" title="Subject filter"  multiple id="category-filter" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5" data-style="btn-outline-dark"></select>
					<?if (hasAccess(2)){?>
						<select class="form-control selectpicker" title="Filter by users"  multiple id="user-filter" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5" data-style="btn-outline-dark"></select>						
					<?}?>
					</div>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-striped " id="tbl-user-attempts">
							<thead><th>Name</th><th>Questionnaire</th><th>Date taken</th><th>Score</th><th>Pass</th><th>total</th></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
			
			getQuestionnaireCategories()
			getUsersAttempts()
			function getUsersAttempts(){
				$('#tbl-user-attempts tbody').html('')
				ajax({
					method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_user_attempts',
	                    users:$('#user-filter').val(),
	                    cats:$('#category-filter').val()
	                },
	                dataType: 'json',
	                
				},function(data){
					if (data.result){
            			data.outcome.forEach(function(o){
                			$('#tbl-user-attempts tbody').append('<tr><td>'+o.name+'</td><td><a href="show_questionnaire?id='+o.id+'">'+o.quest+'</a></td><td>'+o.date+'</td><td>'+o.score+'</td><td>'+o.pass+'</td><td>'+o.total+'</td></tr>')
                		})
                	}
            		else{
            			emptyTableMsg('#tbl-user-attempts tbody','No questionnaires taken yet')
            		}
				})
			}
			function getQuestionnaireCategories(){
				ajax({
					method: 'GET',
			        url: 'xhr.php',
			        data: {
			            action:'get_questionnaire_categories',
			        },
			        dataType: 'json',
				},function(data){
					if (data.result){
		        		data.cats.forEach(function(c){
		            		$('#category-filter').append($('<option>', {
							    value: c.id,
							    text: c.desc,
							}));
		        		})
		        	}
				})
			}
			$('#category-filter').change(function(){
				getUsersAttempts()
			})

			$('#user-filter').change(function(){getUsersAttempts()})

			ajax({
				method: 'GET',
                url: '<?echo $baseURL?>user/xhr.php',
                data: {
                    action:'get_users',
                },
                dataType: 'json',
			},function(data){
				if (data.result){
            		data.users.forEach(function(u){
            			$('#user-filter').append('<option value="'+u.id+'">'+u.name+'</option>')	
            		})
            		$('.selectpicker').selectpicker('refresh')
            	}
			})
	
			
		</script>
	</body>
</html>