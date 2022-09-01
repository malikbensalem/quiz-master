<div class="modal fade" id="user-assign-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h5 class="modal-title" id="uam-modal-title">Assign users</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="uam-alert"></div>
      	<div class="form-group">
      		<div class="input-group">
      			<div class="input-group-prepend">
	      			<div class="input-group-text">Person</div>		
	      		</div>
      			<input id="uam-student" class="form-control">		
            <div class="input-group-append">
              <button class="btn btn-outline-dark" id="uam-search"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
      		</div>
      	</div>
        <div class="table-responsive">
        	<table class="table table-striped" id="uam-tbl">
            <thead>
              <tr>
                <th>Name</th><th>Assigned by</th><th>Date Assigned</th><th>Deadline</th><th>Attempts</th><th>Passed</th><th>Best</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light mr-auto" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="uam-save">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  $('#uam-search').click(function(){
    if ($('#uam-student').val()){
      $.ajax({
        method: 'GET',
        url: '<?echo $baseURL?>user/xhr.php',
        data: {
            action:'get_users',
            name:$('#uam-student').val(),
        },
        dataType: 'json',
        success: function(data) {
          if (data.result){
            data.users.forEach(function(u){
              $('#uam-tbl tbody').append('<tr data-uid='+u.id+' ><td data-name>'+u.name+'</td><td data-assign><? echo mysqli_fetch_row(mysqli_query($mysqli,"SELECT CONCAT(u_first_name,' ',u_last_name) FROM users WHERE u_id=".$_SESSION['user_id']))[0]??''?></td><td>-</td><td><input min="<?echo date('Y-m-d')?>" data-date class="form-control" type="date"  placeholder="deadline"></td><td>-</td><td>-</td><td><button data-remove class="btn btn-danger"><i class="fa-regular fa-trash-can"></i></button></td></tr>')
            })
          } 
          else{
            timedAlert('#uam-alert','<div class="alert alert-danger">No user with that email or name</div>')
          }         
          $('#uam-student').val('');
        }
      })
    }
    else{
      timedAlert('#uam-alert','<div class="alert alert-danger">Cannot search for empty user</div>')
    }
  })
  $('#uam-save').click(function(){
    users=[]
    $('#uam-tbl tbody tr').each(function(){
      users.push({
        'uid':$(this).data('uid'),
        'deadline':$(this).find('input[data-date]').val(),
      })
    })
    $.ajax({
      method: 'POST',
      url: '<?echo $baseURL?>user/xhr.php',
      data: {
        action:'user_assignee',
        qid:$('#user-assign-modal').data('qid'),
        users:users
      },
      dataType: 'json',
      success: function(data) {
        if (data.result){
          timedAlert('#uam-alert','<div class="alert alert-success>Successfully modified assignees.</div>"')
        }
      }
    })
  })
  $('#uam-student').keyup(function(e){
    if (e.keyCode === 13) {
      $('#uam-search').click()
    }
  })

  function getAssignees(qid){
    $('#uam-tbl tbody').html('')
    $.ajax({
      method: 'GET',
      url: '<?echo $baseURL?>user/xhr.php',
      data: {
        action:'get_user_assignee',
        qid:qid,
      },
      dataType: 'json',
      success: function(data) {
        if (data.result){
          data.users.forEach(function(u){
            $('#uam-tbl tbody').append('<tr data-uid='+u.uid+'><td>'+u.uname+'</td><td>'+u.aname+'</td><td>'+u.date+'</td><td> <input type="date" class="form-control" data-date value="'+u.deadline+'"></td><td>'+u.attempts+'</td><td>'+u.best+'</td><td>'+(u.passed==1?'<i class="fa-solid fa-check"></i>':'<i class="fa-solid fa-x"></i>')+'</td><td><button data-remove class="btn btn-danger"><i class="fa-regular fa-trash-can" data-remove></i></button></td></tr>')
          })
        }
        else{
          $('#uam-tbl tbody').append('<tr data-empty><td class="text-center" colspan="100%">No users assigned to this questionnaire</td></tr>')

        }
      }
    })
  }
  
  $('#uam-tbl tbody').on('click','button[data-remove]',function(){
    $(this).parents('tr').remove()
  })
</script>
