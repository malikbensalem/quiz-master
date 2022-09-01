<hr>
<div class="container">
	
	<div class="row ">
		<div class="col-sm-8">© 2022 WebbiSkools Ltd.</div>
		<div class="col-sm-4" ><img src="<?echo $baseURL?>assets/images/logo.svg" width="100" class="float-right" ></div>
	</div>
</div>

<?

$functions_js_v=md5_file($_SERVER['ROOT_PATH'].'assets/js/functions.js');
?>

<script src="<?echo $baseURL?>assets/js/inputmask.js"></script>
<script src='<?echo $baseURL?>assets/js/functions.js?v=<?echo $functions_js_v?>'></script>

