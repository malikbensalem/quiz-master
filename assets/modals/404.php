
<div class="modal fade error" id="404-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h5 class="modal-title">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?include_once $_SERVER['ROOT_PATH'].'assets/template/404.php';?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light mr-auto" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  $('#404-modal').find('h2').text('Request not found');
</script>