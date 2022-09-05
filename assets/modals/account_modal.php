
<div class="modal fade" id="account-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h5 class="modal-title"><i class="fa-solid fa-user"></i> My Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="am-alert"></div>
      	<div class="row">
          <div class="col-md-12">
    				<label class="btn-block">Full name
            <input id="am-name" class="form-control" disabled></label>
          </div>
      	</div>
        <div class="row">
          <div class="col-md-12">
            <label class="btn-block">Email address
            <input class="form-control" disabled id="am-email"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
	      		<label class="btn-block">Type
            <input class="form-control" disabled id="am-type"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label class="required" for="am-password-old">Current password</label>
            <input class="form-control btn-block" type="password" id="am-password-old" placeholder="Old password">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label class="required" for="am-password-new" >New password</label>
            <input class="form-control btn-block" type="password" id="am-password-new" placeholder="New password">
          </div>
        </div>
      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-light mr-auto" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" id="am-save">Change password</button>
      </div>
    </div>
  </div>
</div>

<script>
  ajax({
    method: 'GET',
      url: '<?echo $baseURL?>user/xhr.php',
      data: {
        action:'get_users',
        id:<?echo $_SESSION['user_id']?>,
      },
      dataType: 'json',
  },function(data){
    if (data.result){
      me=data.users[0]
      $('#am-name').val(me.name)
      $('#am-email').val(me.email)
      $('#am-type').val(me.type)
    }
  })
  $('#am-save').click(function(){
    resetpassword();
  })
  function clearAm(){
    $('#am-password-new').val('')
    $('#am-password-old').val('')
  }
	function resetpassword(){
    if ($('#am-password-old').val()=='' || $('#am-password-new').val().length<5 ){
      timedAlert('#am-alert','<div class="alert alert-danger">Could not change password. Make sure the passwords you have entered are atleast 6 characters long.</div>')
      return false
    }
    ajax({
      method: 'POST',
      url: '<?echo $baseURL?>user/xhr.php',
      data: {
        action:'change_password',
        new:$('#am-password-new').val(),
        old:$('#am-password-old').val(),
      },
      dataType: 'json',
    },function(data){
      if (data.result){
          timedAlert('#am-alert','<div class="alert alert-success">Successfully changed password.</div>')
        }
        else{
          timedAlert('#am-alert','<div class="alert alert-danger">The current password entered is wrong. Please try again.</div>')
        }
      })
  }
</script>