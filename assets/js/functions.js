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
