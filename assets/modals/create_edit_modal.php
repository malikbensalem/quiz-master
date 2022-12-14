<?if (hasAccess('2')){?>
<div class="modal fade" id="create-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h5 class="modal-title" id="cem-modal-title"><i class="fa-solid fa-plus"></i> Questionnaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="cem-alert"></div>
      	<div class="form-group">
      		<div class="input-group">
      			<div class="input-group-prepend">
	      			<div class="input-group-text">Title</div>		
	      		</div>
      			<input id="cem-title" class="form-control" placeholder="Questionnaire title">		
      		</div>
      	</div>
      	<div class="form-group">
      		<div class="input-group">
      			<div class="input-group-prepend">
	      			<div class="input-group-text">Status</div>		
	      		</div>
            <select class="form-control selectpicker" id="cem-status" >
              <option value=1>Publish</option>
              <option value=2>Draft</option>
            </select>
      		</div>
      	</div>
      	<div class="form-group">
      		<div class="input-group">
      			<div class="input-group-prepend">
	      			<div class="input-group-text">Subject</div>
      			</div>	
            <select class="btn btn-outline-dark form-control selectpicker " title="Subject filter" multiple id="cem-cat" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5">
      				<?
      				$qc=getCategories();
      				foreach ($qc as $cat) {
      					echo '<option value='.$cat['id'].'>'.$cat['desc'].'</option>';
      				}
      				?>
      			</select>		
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light mr-auto" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" id="cem-questions"><?if (hasAccess('3')){?>Modify<?}else{?>View<?}?> questions</button>
        <?if (hasAccess('2')){?><button type="button" class="btn btn-success" id="cem-save">Save changes</button><?}?>
      </div>
    </div>
  </div>
</div>

<script>

	$('#cem-questions').click(function(){
    window.location='<?echo $baseURL?>questionnaire/edit_questionnaire?id='+$('#cem-save').data('id')    
	})  
	$('#cem-save').click(function(){
    saveQuestionnaire()
	})
  function clearCem(){
    $('#cem-save').data('id','')
    $('#cem-title').val('')
    $('#cem-status').val('2')
    $('#cem-cat').val('')

    $('.selectpicker').selectpicker('refresh')
  }
	function saveQuestionnaire(){
    if ($('#cem-title').val()!=''){
      ajax({
         method: 'POST',
        url: '<?echo $baseURL?>questionnaire/xhr.php',
        data: {
          action:'save_questionnaire',
          id:$('#cem-save').data('id'),
          title:$('#cem-title').val(),
          sts:$('#cem-status').val(),
          cat:$('#cem-cat').val(),
        },
        dataType: 'json',

      },function(data){
        if (data.result){
            timedAlert('#cem-alert','<div class="alert alert-success">Successfully saved questionnaire</div>')
            setTimeout(function(){
              $('#create-edit-modal').modal('hide')
            }, 2000);
            $('#statuses button[data-btn-status].active').click()

            return true
          }
          else{
            timedAlert('#cem-alert','<div class="alert alert-danger">Could not save questionnaire</div>')
            return false
          }
        })
    }
    else{
      timedAlert('#cem-alert','<div class="alert alert-danger">Cannot save without a title</div>')      
      return false
    }
	}
  $('#create-q').click(function(){
    clearCem()
    $('#cem-questions').hide()
    $('#create-edit-modal').modal('show')
  })
  $('#statuses button[data-btn-status]').click()
  <?if (!hasAccess('3')){?>
    $('#create-edit-modal').find('input,select').attr('disabled','true')
  <?}?>
</script>
<?}?>