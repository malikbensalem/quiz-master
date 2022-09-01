<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';

if (!loggedin()){
	include ($_SERVER['ROOT_PATH'].'assets/errors/401.php');
	die();
}

if (!hasAccess('5')){
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
					<?

					?>
					<?if(hasAccess('2')){?>
					<div class="btn-group-vertical btn-block" id="statuses">
						<a class="btn btn-lg btn-block btn-outline-info active">Status</a>
						<button class="btn btn-lg btn-block btn-outline-dark" data-btn-status="1">Published</button>
						<button class="btn btn-lg btn-block btn-outline-dark"data-btn-status="2">Drafts</button>
					</div>
					<?}?>
					<div class="btn-group-vertical btn-block">
						<a class="btn btn-lg btn-block btn-outline-info active">Filters</a>
						<select class="form-control form-select-lg selectpicker " title="Assign filter" id="assigned-filter" data-style="btn-outline-dark">
							<option value="1" selected>Assigned <?if(hasAccess('2')){?>/ owned<?}?></option>
							<option value="2">Not assigned</option>
						</select>
						<?if($_SESSION['user_level']==1){?>
						<select class="form-control form-select-lg selectpicker" title="Complete filter" id="complete-filter" data-style="btn-outline-dark">
							<option value="1" selected>Not attempted</option>
							<option value="2">Not passed</option>
							<option value="3">Passed</option>
						</select>
						<?}?>

						<select class="form-control selectpicker" title="Subject filter"  multiple id="category-filter" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5" data-style="btn-outline-dark"></select>
					</div>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-striped " id="tbl-questionnaire">
							<thead><th>Title</th><th>Subject</th><?if($_SESSION['user_level']==1){?><th>Passed</th><?}?><th>Assigned by</th><th>Deadline</th><?if(hasAccess('2')){?><th>Owned by</th><?}?><th>Actions</th></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
				loader(true)
				$.ajax({
	                method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'action',
	                    sts:sts,
	                    cats:cat,
	                    assigned:assign,
	                    complete:complete,
	                },
	                dataType: 'json',
	                success: function(data) {
                		if (data.result){
	                	}
	                	else{
	                		emptyTableMsg('#tbl-questionnaire tbody','There are no questionnaires')
	                	}
						loader(false)
	                }
				})

		</script>
	</body>
</html>