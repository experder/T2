/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

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
 * TODO: onclick zoom-in als objekt
 */
function t2_toggle_detail_zoom(content_id, html_node_toggle_zoom){
	$('#'+content_id).toggle(500);
	$(html_node_toggle_zoom).toggleClass('zoom-out');
}

function t2_ajax_to_func(query,Funktion){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET",query,true);
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState===4 && xmlhttp.status===200){
			new Function("response",Funktion)(xmlhttp.responseText);
		}
	};
	xmlhttp.send();
}

function t2_ajax_to_id(query,id,add){
	if(add){
		func = "$('#"+id+"').append(response);";
	}else{
		func = "document.getElementById('"+id+"').innerHTML=response;";
	}
	t2_ajax_to_func(query,func);
}
