/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
      ONCLICK ZOOM-IN
 */

/**
 * @deprecated TODO: onclick zoom-in als objekt
 */
function show_dev_stat_queries(html_node_toggle_zoom){
	$('#id_dev_stats_queries_detail').toggle(500);
	if(html_node_toggle_zoom){
		$(html_node_toggle_zoom).toggleClass('zoom-out');
	}
}

/**
 * TODO(3): onclick zoom-in als objekt
 */
function t2_toggle_detail_zoom(content_id, html_node_toggle_zoom){
	$('#'+content_id).toggle(500);
	$(html_node_toggle_zoom).toggleClass('zoom-out');
}

/*
       AJAX
 */

function t2_ajax_to_func(query,Funktion){
	let xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET",query,true);
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState===4 && xmlhttp.status===200){
			new Function("response",Funktion)(xmlhttp.responseText);
		}
	};
	xmlhttp.send();
}

function t2_ajax_to_id(query,id,add,func_after){
	let func;
	if(add){
		func = "$('#"+id+"').append(response);";
	}else{
		func = "document.getElementById('"+id+"').innerHTML=response;";
	}
	if(func_after){
		func = func + func_after;
	}
	t2_ajax_to_func(query,func);
}

/*
       WAIT SPINNER
 */
function t2_spinner_start(){
	document.getElementById('uploadSpinner').style.display="block";
	scope_disableKeys = true;
}
function t2_spinner_stop(){
	document.getElementById('uploadSpinner').style.display="none";
	scope_disableKeys = false;
}
let scope_disableKeys = false;
window.addEventListener('keydown', function (event) {
	if (scope_disableKeys === true) {
		event.preventDefault();
		return false;
	}
});

function t2_ajax_post(url, Funktion, err_detail, report){
	$.ajax({
		type: 'POST',
		url: url,
		data: {foo:'bar'},
		success: function (data) {
			if(data.ok){
				Funktion(data);
			}else{
				let message;
				if(err_detail){
					message="<h1>Ajax returns error #"+data.error_id+"</h1><pre class='dev'>"+url+'<hr>'+data.error_msg+"</pre>";
				}else{
					message="An error occured. Please report this reference to your administrator: \"Ajax returned: "+data.error_id+"\"";
				}
				t2_error(message);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			let message;
			if (jqXHR.readyState == 0) {
				message="Could not connect to the server. Please check your network connection.";
			}else if(err_detail){
				message='<div class="dev"><h1>'+textStatus+'</h1>'+errorThrown+'<pre>'+url+'<br>Status code: '+jqXHR.status+'</pre><div class="dev ajax_response">'+jqXHR.responseText+'</div></div>';
			}else{
				if(jqXHR.status===404){
					textStatus="This ist 404";
				}
				message="An error occured. Please report this reference to your administrator: \""+textStatus+"\"";
			}
			t2_error(message);
		},
		dataType: 'json'
	});
}

function t2_error(message){
	let msg = $('<div>',{'class': 'message msg_type_error'}).html(message);
	$('#t2_messages').append(msg);
}
