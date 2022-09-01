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

function questionMaker(id,title,options){
	quest='<div class="card" data-id='+id+'><div class="card-header"><h3>'+title+'</h3></div><div class="card-body">'
	count=1
	options.forEach(function(o){
		quest+='<button class="btn btn-outline-dark btn-block" data-option>'+alpha[count]+'. '+o+'</button>'
		count++
	})
	quest+='</div></div>'
	$('#questions').append(quest)
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