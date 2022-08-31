
<div class="modal fade" id="create-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h5 class="modal-title" id="cem-modal-title">Questionnaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="modal-alert"></div>
      	<div class="form-group">
      		<div class="input-group">
      			<div class="input-group-prepend">
	      			<div class="input-group-text">Title</div>		
	      		</div>
      			<input id="cem-title" class="form-control">		
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
	      			<div class="input-group-text">Categories</div>
      			</div>	
            <select class="btn btn-outline-dark form-control selectpicker " title="Subject filter"  multiple id="cem-cat" data-actions-box="true" data-live-search="true" data-selected-text-format="count > 3" data-size="5">
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
        <button type="button" class="btn btn-light float-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" id="cem-questions">Modify Questions</button>
        <button type="button" class="btn btn-success" id="cem-save">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>

	$('#cem-questions').click(function(){
    window.location='edit_questions?id='+$('#cem-save').data('id')    
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
  		$.ajax({
        method: 'POST',
        url: 'xhr.php',
        data: {
          action:'save_questionnaire',
          id:$('#cem-save').data('id'),
          title:$('#cem-title').val(),
          sts:$('#cem-status').val(),
          cat:$('#cem-cat').val(),
        },
        dataType: 'json',
        success: function(data) {
          if (data.result){
            timedAlert('#modal-alert','<div class="alert alert-success">Successfully saved questionnaire</div>')
            setTimeout(function(){
              $('#create-edit-modal').modal('hide')
            }, 2000);
            return true
          }
          else{
            timedAlert('#modal-alert','<div class="alert alert-danger">Could not save questionnaire</div>')
          }
          return false
        }
      }).then(function(res){console.log(res.result);return res.result}) 
    }
    else{
      timedAlert('#modal-alert','<div class="alert alert-danger">Cannot save without a title</div>')      
      return false

    }
	}
  $('#create-q').click(function(){
    clearCem()
    $('#cem-questions').hide()
    $('#create-edit-modal').modal('show')
  })

</script>