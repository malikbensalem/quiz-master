function timedAlert(ele,ale,time=2500){
	$(ele).html(ale)
	setTimeout(function(){
		$(ele).html('')
	}, time);
}
function timedModal(modal,time=2500){
	$(modal).modal('show')
	setTimeout(function(){
		$(modal).modal('hide')
	}, time);	
}
function timedRedirect(link,time=2500){
	setTimeout(function(){
	 	window.location=link;
	}, 2500);
}
function emptyTableMsg(ele,msg){
	$(ele).html('<tr><td class="text-center" colspan="100%">'+msg+'</td></tr>')
}
function getQuestionnaireCategories(){
	$.ajax({
        method: 'GET',
        url: 'xhr.php',
        data: {
            action:'get_questionnaire_categories',
        },
        dataType: 'json',
        success: function(data) {
        	if (data.result){
        		data.cats.forEach(function(c){
            		$('#category-filter').append($('<option>', {
					    value: c.id,
					    text: c.desc,
					}));
        		})
        	}
    	}
    })
}

function loader(loading){
	if (loading){
		$('body').append('<div class="loading "><i class="fa-solid fa-spinner fa-spin fa-10x "></i></div>')
	}
	else{
		$('.loading').remove();
	}

}
function isEmail(email){
	let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  	if (email.match(regex)) 
    	return true; 
   	else 
    	return false; 
}

$('.disabled,.active').click(function(e){
	e.preventDefault();
	e.stopPropagation();
	return
})
$('.table-responsive').on('show.bs.select', function () { 
	$('.table-responsive').css( "overflow", "inherit" );
	$('.bootstrap-table').css( "overflow", "inherit" ); 
	$('.fixed-table-body').css( "overflow", "inherit" );     
});
$('#statuses').on('click','button[data-btn-status]',function(){
	$('#statuses button[data-btn-status]').removeClass('active')
	$(this).addClass('active')
	loader(true)
})
$(document).ready(function() {
	$('#statuses button[data-btn-status=1]').addClass('active')
});
