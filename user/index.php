<?
include $_SERVER['ROOT_PATH'].'assets/connection/dbc.php';

if (!loggedin()){
	include $_SERVER['ROOT_PATH']."assets/errors/401.php";
}
if (!hasAccess('2')){
	include $_SERVER['ROOT_PATH']."assets/errors/403.php";
	die();
}
?>

<html>
	<head>
		<?getHead('Users')?>
	</head>
	<body>
		<?include $_SERVER['ROOT_PATH'].'assets/running/nav.php';?>

		<div class="container">
			<h1>Users</h1><br>
			<div class="row">
				<div class="col-md-4" >
					<?breadcrumbs(['users'])?>
					<div class="btn-group-vertical btn-block" id="statuses">
						<a class="btn btn-lg btn-block btn-outline-info active">Type <i class="fa-solid fa-graduation-cap"></i></a>
					</div>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-striped " id="tbl-users">
							<thead><th>Full name</th><th>Type</th><th>Action</th></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<?include $_SERVER['ROOT_PATH'].'assets/running/footer.php'?>
		<script type="text/javascript">
			
			function getUsers(sts){
        		$('#tbl-users tbody').html('')
        		ajax({
        			method: 'GET',
	                url: 'xhr.php',
	                data: {
	                    action:'get_users',
	                    sts:sts
	                },
	                dataType: 'json',
        		},function(data){
        			if (data.result && data.users.length){
	                		sel='<select class="form-control selectpicker" <? echo hasAccess('4')?'':'disabled'?>>'
	                		$('#statuses button[data-btn-status]').each(function(){
	                			sel+='<option value='+$(this).data('btn-status')+' '+(sts==$(this).data('btn-status')?'selected':'')+'>'+$(this).text()+'</option>'
	                		})
	                		sel+='</select>'
	                		data.users.forEach(function(u){
								$('#tbl-users tbody').append('<tr data-id='+u.id+'><td data-name>'+u.name+'</td><td data-type>'+sel+'</td><td><button data-remove class="btn btn-danger" <? echo hasAccess('4')?'':'disabled'?>><i class="fa-solid fa-ban"></i></button></td></tr>')
	                		})
	                	}
	                	else{
	                		emptyTableMsg('#tbl-users tbody','There are no users that match this criteria')
	                	}
                		$('.selectpicker').selectpicker('refresh')
        		})
			}

			$('#tbl-users tbody').on('change','tr td[data-type] select',function(){
				ajax({
					method: 'POST',
	                url: 'xhr.php',
	                data: {
	                    action:'change_user_type',
	                    id:$(this).parents('tr').data('id'),
	                    type:$(this).val(),
	                },
	                dataType: 'json',
				},function(data){
					if (data.result){
                		$('#statuses button[data-btn-status].active').click()
                	}
				})
			})
            ajax({
            	method:'GET',
            	url: 'xhr.php',
                data: {
                    action:'get_user_type',
                },
                dataType: 'json',

            },function(data){
            	if (data.result){
            		data.types.forEach(function(ut){
            			$('#statuses').append('<button class="btn btn-lg btn-block btn-outline-dark" data-btn-status="'+ut.id+'">'+ut.desc+'</button>')	
            		})
					$('#statuses button[data-btn-status=1]').addClass('active')
            		getUsers(1)
            	}            	
            });

			$('#tbl-users tbody').on('click','button[data-remove]',function(){
				ajax({
					method: 'POST',
	                url: 'xhr.php',
	                data: {
	                    action:'remove_user',
	                    id:$(this).parents('tr').data('id'),
	                },
	                dataType: 'json',
				},function(data){
					if (data.result){
                		$('#statuses button[data-btn-status].active').click()	                		
                	}	
				})
				$(this).parents('tr').remove()
            })
			
			$('#statuses').on('click','button[data-btn-status]',function(){
				getUsers($(this).data('btn-status'))
			})
						
		</script>
	</body>
</html>