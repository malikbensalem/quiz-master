<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';

if (!loggedin()||!hasAccess('5')){
	include ($_SERVER['ROOT_PATH'].'assets/errors/401.php');
	die();
}

?>

<html>
	<head>
		<?getHead('Title')?>
	</head>
	<body>
			<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>
		<div class="container">
			<h1>title</h1><br>
			
			<div class="row">
				<div class="col-md-4" >
					<?breadcrumbs([''])?>

					
					<div class="btn-group-vertical btn-block" id="statuses">
						<a class="btn btn-lg btn-block btn-outline-info active">Status</a>
						<button class="btn btn-lg btn-block btn-outline-dark" data-btn-status="1">.</button>
						<button class="btn btn-lg btn-block btn-outline-dark"data-btn-status="2">.</button>
					</div>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-striped " id="tbl-">
							<thead><th></th></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
				ajax({
					 method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'action',
	                    
	                },
	                dataType: 'json',
				},function(data){
					if (data.result){
	                	}
	                	else{
	                		emptyTableMsg('#tbl-','There are no ...')
	                	}
				})
		</script>
	</body>
</html>