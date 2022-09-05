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

function ajax(ajax,callback){
	loader(true)
	return $.ajax({
	    method: ajax['method'],
	    url: ajax['url'],
	    data: ajax['data'],
	    dataType: ajax['dataType'],
	    success: function(data) {
	    	if (data.error){
	    		if (data.error=='500'){
					$('#500-modal').modal('show');
	    		}
	    		else if (data.error=='404'){
					$('#404-modal').modal('show');
	    		}
	    		else if (data.error=='403'){
					$('#403-modal').modal('show');
	    		}
	    		loader(false)
	    		return false;
	    	}
	    	callback(data);
			loader(false)
	    },
	    error: function (request, status, error) {
			$('#500-modal').modal('show');
			loader(false)
	    	return false;
	    }
	})
}
$(document).ready(function() {
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
	})
	$('#statuses button[data-btn-status=1]').addClass('active')
});

alpha={
	'1':'A',
	'2':'B',
	'3':'C',
	'4':'D',
	'5':'E',
	'6':'F',
	'7':'G',
	'8':'H',
	'9':'I',
	'10':'J',
	'11':'K',
	'12':'L',
	'13':'M',
	'14':'N',
	'15':'O',
	'16':'P',
	'17':'Q',
	'18':'R',
	'19':'S',
	'20':'T',
	'21':'U',
	'22':'V',
	'23':'W',
	'24':'X',
	'25':'Y',
	'26':'Z',
}