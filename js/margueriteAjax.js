jQuery(document).ready(function($){
	
	/*$('#marguerite-title').click(function(e){
		alert('cool3');
	});*/
	
	
	/*$(function() {
		//$( "#datepicker" ).datepicker();
        document.addEventListener('backbutton', backButtonCallback, false);
	});*/
	
	/* Open Menu Header */
	if ($("#demo").length != 0) {
		//testonspw(document.getElementById("op_idProject").innerHTML);
		
		
		//$( "#opMenuTabsA" ).hide();
		//$( "#opMenuTabsB" ).hide();
		//$( "#opOverlay" ).hide();
		
		
		$( "#opMenuTabsA" ).tabs();
		//$( "#opMenuTabsB" ).tabs();
		affichageTabsA = false;
		affichageTabsB = false;
	 
		$("#opIPMenu").click(function(){manageOverlay('a');});
		$(".opIFMenu").click(function(){manageOverlay();});
		$("#opOverlay").click(function(){manageOverlay();});
		
		
		testonsb(document.getElementById("op_idProject").innerHTML,0,'marguerite');
		
		
	}
	
	var updateOutputb = function(e) {
        var listb   = e.length ? e : $(e.target),
            outputb = listb.data('output');
		if (window.JSON) {
            outputb.val(window.JSON.stringify(listb.nestable('serialize')));//, null, 2));
        } else {
            outputb.val('JSON browser support required for this demo.');
        }
		var resb = document.getElementById("op_order").value; 
		resb = resb.replace(/"id":|"children":|{|}|"/gi, '');
		resb = resb.slice(1,-1);
		document.getElementById("op_order").value = resb;
		
		arrayChanges = resb.split(",");
		arrayParent = [0];
		
		//document.getElementById("gantt").value = '';
		//sep = '|';
		arrayTempTask = [0];
		for (c = 0; c < arrayChanges.length; c++) { 
			if(arrayChanges[c].charAt(0) == '['){
				arrayParent.unshift(arrayChanges[c-1].replace(/]|\[/g,''));
			}
			
			for (d = 0; d < op_tTache.length; d++) {
				if(arrayChanges[c].replace(/]|\[/g,'') == op_tTache[d][0]){
					op_tTache[d][7] = arrayParent[0];
					op_tTache[d][8] = c;
					arrayTempTask.unshift(op_tTache[d]);
				}
			}
			
			if(arrayChanges[c].slice(-1) == ']'){
				changesSlice = arrayChanges[c];
				while(changesSlice.slice(-1) == ']'){
					changesSlice = changesSlice.substr(0, changesSlice.length-1); 
					arrayParent.shift();
				}
			}
			//document.getElementById("").value += sep.concat(op_tTache[c]);
		}
		
		op_tTache = arrayTempTask;
		op_majGantt();
    };
	
	
	if ($("#op_formProject").length != 0) {
	
		op_construireTasks();
	
		$('#ot_orderTasks').nestable({
			group: 1
		}).on('change', updateOutputb);
	   updateOutputb($('#ot_orderTasks').data('output', $('#op_order')));
	   
		$(function() {
			$( "#ot_dated" ).datepicker({dateFormat: "dd/mm/yy"});
			$( "#ot_datef" ).datepicker({dateFormat: "dd/mm/yy"});
		});
		
		$('.btnTask').each(function(){
			$(this).button().on( "click", function(){ $( "#dialog-form22" ).dialog( "open" ); op_manageTask($(this).parent()[0].getAttribute("data-id")); });
		});
		
		$("#new_taskBtn").click(function(){
			$('.dd-list:first').append('<li class="dd-item" data-id="n' + (nbrN-1) + '"><div class="btnTask op_btnMenu" style="height:30px;" onclick="op_manageTask(\'n' + (nbrN-1) + '\');">Edit</div><div class="dd-handle op_listMenu" style="font-weight:normal; font-size:15px; background:white;">' + document.getElementById("new_task").value + '</div></li>');
			
			$('.btnTask:last').button().on( "click", function(){ $( "#dialog-form22" ).dialog( "open" ); op_manageTask($(this).parent()[0].getAttribute("data-id")); });
		});
		
		$(function() {
			var dialog, form;
			//var ot_id = $( "#ot_id" ), ot_title = $( "#ot_title" ), ot_dated = $( "#ot_dated" ), ot_datef = $( "#ot_datef" ), ot_avancement = $( "#ot_avancement" );
			
			dialog = $( "#dialog-form22" ).dialog({
			  autoOpen: false,
			  resizable: false,
			  width:350,
			  modal: true,
			  buttons: {
				"Cancel": function() {
				  $( this ).dialog( "close" );
				},
				"Delete": function() {
				  $( this ).dialog( "close" );
				  op_deleteTask();
				},
				"Save task": function() {
				  dialog.dialog( "close" );
				  op_registerTask();
				}
			  }
			});
			
			form = dialog.find( "form" ).on( "submit", function( event ) {
			  event.preventDefault();
			  dialog.dialog( "close" );
			  op_registerTask();
			});
		});
		
		/*$(function() {
			var dialogPw, formPw;
			//var ot_id = $( "#ot_id" ), ot_title = $( "#ot_title" ), ot_dated = $( "#ot_dated" ), ot_datef = $( "#ot_datef" ), ot_avancement = $( "#ot_avancement" );

			dialogPw = $( "#dialog-formPw" ).dialog({
			  autoOpen: true,
			  resizable: false,
			  width:350,
			  modal: true,
			  buttons: {
				"Cancel": function() {
				  $( this ).dialog( "close" );
				},
				"Ok": function() {
					if(testPw()){
						$( this ).dialog( "close" );
					}else{
						document.getElementById('ot_pwmsg').style.display = 'block';
					}
				}
			  }
			});
			
			formPw = dialogPw.find( "form" ).on( "submit", function( event ) {
			  event.preventDefault();
			  dialogPw.dialog( "close" );
			});
		});*/
		
		op_drawGantt();
		
	}
	
	
	//testonsb(0, 'marguerite');
	
	/*function testons2(tache, type) {
		var xmlhttp;
		
		if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("demo").innerHTML=xmlhttp.responseText;
				charta();chartb();chartc();chartz();chartN2a();chartN2b();chartN2c();chartN2z();
			}
		}
		
		xmlhttp.open("GET","truc.php?tacheActuelle="+tache+"&typeAffichage="+type,true);
		xmlhttp.send();
		
	};*/
	
	
	
});
	
	/*function backButtonCallback() {
		alert('ok');
	}*/
	
	function testPw(){
		if(document.getElementById('ot_pw').value == 'test'){
			return true;
		}else{
			return false;
		}
	}
	
	function op_drawGantt(type){
		if ((($("#chart_Gantt").length != 0) && ($("#op_infosGantt").length != 0) && (document.getElementById("op_infosGantt").innerHTML != '')) || (($("#chart_Gantt").length != 1) && ($("#gantt").length != 0) && (document.getElementById("gantt").innerHTML != ''))) {
			
			//google.load("visualization", "1.1", {packages:["gantt"]});
			//google.setOnLoadCallback(drawChart);
			
			drawChart();
			
			 function daysToMilliseconds(days) {
			  return days * 24 * 60 * 60 * 1000;
			}

			function drawChart() {

			  var data = new google.visualization.DataTable();
			  data.addColumn('string', 'Task ID');
			  data.addColumn('string', 'Task Name');
			  if(type != 'marguerite'){
				data.addColumn('string', 'Resource');
			  }
			  data.addColumn('date', 'Start Date');
			  data.addColumn('date', 'End Date');
			  data.addColumn('number', 'Duration');
			  data.addColumn('number', 'Percent Complete');
			  data.addColumn('string', 'Dependencies');
			  
			  //arrayTest = ['Research', 'Find sources', new Date(2015, 0, 1), new Date(2015, 0, 5), null,  100,  null];
			  
				
				//data.setValue(0, 0, 'truc'+0);
				//data.setValue(0, 1, 'Find sources');
				//data.setValue(0, 2, new Date(2015, 0, 1));
				//data.setValue(0, 3, new Date(2015, 0, 5));
				//data.setValue(0, 4, null);
				//data.setValue(0, 5, 100);
				//data.setValue(0, 6, null);
				
				if($("#op_infosGantt").length != 0){
					op_ganttFront = document.getElementById("op_infosGantt").innerHTML;
				}else{
					//if($("#gantt").length != 0){
						//op_ganttFront = document.getElementById("gantt").value;
						op_ganttFront = op_gantt;
						//op_ganttFront = op_ganttFront.slice(1);
					//}
				}
				
				//alert(op_ganttFront);
				//op_ganttFront = '637,Phase 1,25/10/2015,30/10/2015,100,0,1|638,Phase 2,25/10/2015,31/10/2015,20,0,4|';
				op_ganttFront = op_ganttFront.slice(1);
				var arrayGanttFront = op_ganttFront.split("|");
				data.addRows(arrayGanttFront.length);
				
				for(nc = 0; nc < arrayGanttFront.length; nc++){
					var arrayGanttFrontInfos = arrayGanttFront[nc].split(",");
					
					if($("#op_infosGantt").length != 0){
						if(type != 'gantt'){
							data.setValue(nc, 0, 'task'+arrayGanttFrontInfos[0]);
							data.setValue(nc, 1, arrayGanttFrontInfos[1]);
							//data.setValue(nc, 2, arrayGanttFrontInfos[6]);
							
							arrayDateDebut = arrayGanttFrontInfos[2].split("/");
							data.setValue(nc, 2, new Date(arrayDateDebut[2], (arrayDateDebut[1]-1), arrayDateDebut[0], 09, 00));
							
							arrayDateFin = arrayGanttFrontInfos[3].split("/");
							data.setValue(nc, 3, new Date(arrayDateFin[2], (arrayDateFin[1]-1), arrayDateFin[0], 18, 00));
							data.setValue(nc, 4, null);
							data.setValue(nc, 5, arrayGanttFrontInfos[4]);
							if(arrayGanttFrontInfos[5] == -1){
								data.setValue(nc, 6, 'task'+arrayGanttFrontInfos[5]);
							}else{
								data.setValue(nc, 6, null);
							}
						}else{
							data.setValue(nc, 0, 'task'+arrayGanttFrontInfos[0]);
							data.setValue(nc, 1, arrayGanttFrontInfos[1]);
							data.setValue(nc, 2, arrayGanttFrontInfos[6]);
							
							arrayDateDebut = arrayGanttFrontInfos[2].split("/");
							data.setValue(nc, 3, new Date(arrayDateDebut[2], (arrayDateDebut[1]-1), arrayDateDebut[0], 09, 00));
							
							arrayDateFin = arrayGanttFrontInfos[3].split("/");
							data.setValue(nc, 4, new Date(arrayDateFin[2], (arrayDateFin[1]-1), arrayDateFin[0], 18, 00));
							data.setValue(nc, 5, null);
							data.setValue(nc, 6, arrayGanttFrontInfos[4]);
							if(arrayGanttFrontInfos[5] == -1){
								data.setValue(nc, 7, 'task'+arrayGanttFrontInfos[5]);
							}else{
								data.setValue(nc, 7, null);
							}
						}
					}else{
						/*data.setValue(nc, 0, 'task'+arrayGanttFrontInfos[0]);
						data.setValue(nc, 1, arrayGanttFrontInfos[1]);
						data.setValue(nc, 2, new Date (2015, 11, 20));
						data.setValue(nc, 3, new Date (2015, 11, 22));
						data.setValue(nc, 4, null);
						data.setValue(nc, 5, 20);
						data.setValue(nc, 6, null);*/
						data.setValue(nc, 0, 'task'+arrayGanttFrontInfos[0]);
						data.setValue(nc, 1, arrayGanttFrontInfos[1]);
						data.setValue(nc, 2, arrayGanttFrontInfos[10]);
						
						arrayDateDebut = arrayGanttFrontInfos[2].split("/");
						data.setValue(nc, 3, new Date(arrayDateDebut[2], (arrayDateDebut[1]-1), arrayDateDebut[0], 09, 00));
						
						arrayDateFin = arrayGanttFrontInfos[3].split("/");
						data.setValue(nc, 4, new Date(arrayDateFin[2], (arrayDateFin[1]-1), arrayDateFin[0], 18, 00));
						data.setValue(nc, 5, null);
						data.setValue(nc, 6, arrayGanttFrontInfos[5]);
						if(arrayGanttFrontInfos[5] == -1){
							data.setValue(nc, 7, 'task'+arrayGanttFrontInfos[5]);
						}else{
							data.setValue(nc, 7, null);
						}
					}
					
				}
			  
			  hauteur = (arrayGanttFront.length+1)*45;
			  var options = {
				height : hauteur,
				gantt: {
				  innerGridTrack: {fill: '#eaedf1'},
				  innerGridDarkTrack: {fill: '#eaedf1'}
				}
			  };
			  
				var chart = new google.visualization.GanttChart(document.getElementById('chart_Gantt'));

				chart.draw(data, options);
			}
		
		}
	}
	
	function op_manageTask(id_task) {
		if(op_tTache.length>0){
			for(h=0; h<op_tTache.length; h++){
				if(op_tTache[h][0] == id_task){
					/*ot_id.value = op_tTache[h][0];
					//ot_title.value = op_tTache[h][1];
					ot_title.value = op_tTache[h][1];
					//tempDated = op_tTache[h][2].split("/")
					//ot_dated.value = tempDated[2] + '-' + tempDated[1] + '-' + tempDated[0];
					ot_dated.value = op_tTache[h][2];
					//tempDatef = op_tTache[h][3].split("/")
					//ot_datef.value = tempDatef[2] + '-' + tempDatef[1] + '-' + tempDatef[0];
					ot_datef.value = op_tTache[h][3];
					ot_avancement.value = op_tTache[h][5];
					ot_responsable.value = op_tTache[h][6];
					ot_ressources.value = op_tTache[h][10];
					ot_descriptif.value = op_tTache[h][11];*/
					
					document.getElementById('ot_id').value = op_tTache[h][0];
					document.getElementById('ot_title').value = op_tTache[h][1];
					document.getElementById('ot_dated').value = op_tTache[h][2];
					document.getElementById('ot_datef').value = op_tTache[h][3];
					document.getElementById('ot_avancement').value = op_tTache[h][5];
					document.getElementById('ot_responsable').value = op_tTache[h][6];
					document.getElementById('ot_ressources').value = op_tTache[h][10];
					document.getElementById('ot_descriptif').value = op_tTache[h][11];
				}
			}
		}
	}
	
	function op_deleteTask() {
		if(op_tTache.length>0){
			for(h=0; h<op_tTache.length; h++){
				if(op_tTache[h][0] == document.getElementById('ot_id').value){
					op_tTache[h][0] = 'd' + op_tTache[h][0];
				}
			}
		}
		var arrayLi = document.getElementsByTagName("li"); 
		for(w=0; w<arrayLi.length; w++){
			if(arrayLi[w].getAttribute("data-id") == document.getElementById('ot_id').value){
				arrayLi[w].getElementsByClassName("dd-handle")[0].style.background = "#FFE0E6";
				arrayLi[w].getElementsByClassName("btnTask")[0].style.display = "none";
			}
		}
		
		op_order = document.getElementById("op_order").value;
		op_order =	op_order.replace((',[' + document.getElementById('ot_id').value + ']'),'');
		op_order =	op_order.replace((document.getElementById('ot_id').value + ',['),'');
		op_order =	op_order.replace((document.getElementById('ot_id').value + ','),'');
		op_order =	op_order.replace((',' + document.getElementById('ot_id').value),'');
		op_order =	op_order.replace(document.getElementById('ot_id').value,'');
		document.getElementById("op_order").value = op_order;
		
		op_majGantt();
	}
	
	function op_registerTask() {
		//alert(ot_id.value);
		//document.getElementById("gantt").value = '';
		if(op_tTache.length>0){
			for(h=0; h<op_tTache.length; h++){
				if(op_tTache[h][0] == document.getElementById('ot_id').value){
					/*op_tTache[h][1] = ot_title.value;
					//tempDated = ot_dated.value.split("-");
					//op_tTache[h][2] = tempDated[2] + '/' + tempDated[1] + '/' + tempDated[0];
					op_tTache[h][2] = ot_dated.value;
					//tempDatef = ot_datef.value.split("-")
					//op_tTache[h][3] = tempDatef[2] + '/' + tempDatef[1] + '/' + tempDatef[0];
					op_tTache[h][3] = ot_datef.value;
					op_tTache[h][5] = ot_avancement.value;
					op_tTache[h][6] = ot_responsable.value;
					op_tTache[h][10] = ot_ressources.value;
					op_tTache[h][11] = ot_descriptif.value;*/
					
					op_tTache[h][1] = document.getElementById('ot_title').value;
					op_tTache[h][2] = document.getElementById('ot_dated').value;
					op_tTache[h][3] = document.getElementById('ot_datef').value;
					op_tTache[h][5] = document.getElementById('ot_avancement').value;
					op_tTache[h][6] = document.getElementById('ot_responsable').value;
					op_tTache[h][10] = document.getElementById('ot_ressources').value;
					op_tTache[h][11] = document.getElementById('ot_descriptif').value;
				}
				//document.getElementById("gantt").value += '|' + op_tTache[h];
			}
		}
		
		var arrayLi = document.getElementsByTagName("li"); 
		for(w=0; w<arrayLi.length; w++){
			if(arrayLi[w].getAttribute("data-id") == document.getElementById('ot_id').value){
				arrayLi[w].getElementsByClassName("dd-handle")[0].innerHTML = document.getElementById('ot_title').value;
			}
		}
		
		op_majGantt();
		
		//op_construireTasks();
	}
	
	function op_majGantt(){
		document.getElementById("gantt").value = '';
		if(op_tTache.length>0){
			for(z=0; z<op_tTache.length; z++){
				document.getElementById("gantt").value += '|' + op_tTache[z];
			}
		}
		
		//op_drawGantt();
	}
	
	var nbrN = 1;
	function op_addTask() {
		
		newTask = document.getElementById("new_task").value;
		/*var data = {
			'action': 'optasks_add',
			'nomTask': newTask
		};
		
		ajaxurl = document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php';
		$.post(ajaxurl, data, function(response) {
			op_gantt = document.getElementById("gantt").innerHTML + response;
		});*/
		
		var d = new Date();
		day = d.getDate();
		day = day.toString();
		month = d.getMonth() + 1;
		month = month.toString();
		if((d.getMonth()+1)<10){
			month = '0' + month;
		}
		year = d.getFullYear();
		year = year.toString();
		//year = year.slice(2);
		dateD = day + '/' + month + '/' + year;
		op_tTache.push([('n' + nbrN), newTask, dateD, dateD, 1, 0, '', 0, 0, '', '', '']);
		//nvlTache = '|' + 'n' + nbrN + ',' + newTask + ',' + dateD + ',' + dateD + ',1,0,,,1,,,';
		
		
		
		/*op_gantt = document.getElementById("gantt").value + nvlTache;
		document.getElementById("gantt").value = op_gantt;*/
		
		op_majGantt();
		
		op_order = document.getElementById("op_order").value + ',n' + nbrN;
		document.getElementById("op_order").value = op_order;
		
		nbrN ++;
		
		//op_construireTasks();
		//document.getElementById("op_formProject").submit();
	}
	

	
	var op_tTache = [];
	function op_construireTasks() {
		
		if(op_tTache.length < 1){
			op_gantt = document.getElementById("gantt").value;
			var arrayGantt = op_gantt.split("|");
			if(arrayGantt.length > 0){
				for (i = 1; i < arrayGantt.length; i++) {
					arrayInfos = arrayGantt[i].split(",");
					op_tTache.push(arrayInfos);
				}
			}
		}
		//document.getElementById("content").value += op_tTache.length;
		
		var resb = document.getElementById("op_order").value; 
		arrayTasks = resb.split(",");
		if(arrayTasks.length > 0){
			op_tasks = '<div class="dd" id="ot_orderTasks">';
			op_tasks += '<ol class="dd-list">';
			for (c = 0; c < arrayTasks.length; c++) {
				if(arrayTasks[c].charAt(0) == '['){
					for (d = 0; d < op_tTache.length; d++) {
						if(arrayTasks[c].replace(/]|\[/g,'') == op_tTache[d][0]){
							op_tasks += '<ol class="dd-list">';
							op_tasks += '<li class="dd-item" data-id="' + op_tTache[d][0] + '">';
							op_tasks += '<div class="btnTask op_btnMenu" style="height:30px;" onclick="op_manageTask(' + op_tTache[d][0] + ');">Edit</div>';
							op_tasks += '<div class="dd-handle op_listMenu" style="font-weight:normal; font-size:15px; background:white;">' + op_tTache[d][1] + '</div>';
						}
					}
				}else{
					for (d = 0; d < op_tTache.length; d++) {
						if(arrayTasks[c].replace(/]|\[/g,'') == op_tTache[d][0]){
							op_tasks += '</li>';
							op_tasks += '<li class="dd-item" data-id="' + op_tTache[d][0] + '">';
							op_tasks += '<div class="btnTask op_btnMenu" style="height:30px;" >Edit</div>';
							op_tasks += '<div class="dd-handle op_listMenu" style="font-weight:normal; font-size:15px; background:white;">' + op_tTache[d][1] + '</div>';
						}
					}
				}
				
				if(arrayTasks[c].slice(-1) == ']'){
					changesSlice = arrayTasks[c];
					while(changesSlice.slice(-1) == ']'){
						changesSlice = changesSlice.substr(0, changesSlice.length-1); 
						op_tasks += '</li>';
						op_tasks += '</ol>';
						op_tasks += '</li>';
					}
				}
			}
			op_tasks += '</ol>';
			op_tasks += '</div>';
			
			
			document.getElementById("tasks").innerHTML = op_tasks;
			
			/*$('.btnTask').each(function(){
				$(this).button().on( "click", function(){ $( "#dialog-form22" ).dialog( "open" );});
			});*/
			
			//$('.dd').nestable();
			//var nest = document.getElementsByClassName("dd");
			//nest[0].nestable();
			
			//var nest2 = document.getElementById("ot_orderTasks");
			//nest2.nestable();
		}
	}
	

	
	function op_construireGantt() {
		alert('ok');
		//op_tasks = document.getElementById("tasks").innerHTML;
		/*var arrayGantt_li = op_tasks.split("</li>");
		if(arrayGantt_li.length > 0){
			//op_gantt = '|';
			for (i = 0; i < arrayGantt_li.length; i++) {
				var arrayGantt_div = arrayGantt_li[i].split("</div>");
				if(arrayGantt_div.length > 0){
					//op_gantt += arrayGantt_div[4];
					for (u = 4; u < arrayGantt_div.length; u++) {
						//op_gantt += arrayGantt_div[u];
						//op_gantt += '<div class="ui-sortable-handle"><li id="editableRow2" style="margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 16px; height: 32px; " class="ui-state-default"><div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; float:left; width:80%; height:22px;"><span style=" position: absolute; font-size: 18px; margin-left: -1.3em;" class="ui-icon ui-icon-arrowthick-2-n-s"></span><label class="labelId">2</label> - <label class="labelTache">Nom tâche</label> - <label class="labelDebut">16/10/2015</label> - <label class="labelFin">16/10/2015</label> - <label class="labelProg">0</label></div><div style="margin-top:-5px; width:9%; float:right; overflow:hidden;" id="btnSupp"><button type="button" style="width:100%;" onclick="suppRow((this));">X</button></div><div style="margin-top:-5px; width:9%;float:right; overflow:hidden; margin-right:1%;" id="btnSupp"><button type="button" style="width:100%;" onclick="modifRow((this));">Modifier</button></div><div class="editable" style="display:none;"><div class="taskInfosId">2</div><div class="taskInfosNom">Nom tâche</div><div class="taskInfosDesc">Descriptif</div><div class="taskInfosDebut">16/10/2015</div><div class="taskInfosFin">16/10/2015</div><div class="taskInfosProg">0</div><div class="taskInfosParent">0</div><div class="taskInfosLiaisons">ids</div><div class="taskInfosType">Tache</div><div class="taskInfosActif">Actif</div><div class="taskInfosRess">Nom ressource</div></div></li></div>';
					}
				}
			}
			//op_gantt += '</ul>';
		}*/
		//document.getElementById("gantt").innerHTML = op_gantt;
	}
	
	function manageOverlay(nomMenu){
		if(affichageTabsA == true || affichageTabsB == true){
			document.getElementById('opOverlay').style.display = 'none';
			document.getElementById('opMenuTabsA').style.right = '-345px';
			affichageTabsA = false;
			document.getElementById('opMenuTabsB').style.right = '-345px';
			affichageTabsB = false;
		}else{
			if(nomMenu == 'a'){
				document.getElementById('opOverlay').style.display = 'block';
				document.getElementById('opMenuTabsA').style.right = '0';
				affichageTabsA = true;
			}else{
				document.getElementById('opOverlay').style.display = 'block';
				document.getElementById('opMenuTabsB').style.right = '0';
				affichageTabsB = true;
			}
		}
	}

	function progressBar() {
		var progressBarWidth = 60;
		//var progressBarWidth = percent * $element.width() / 100;
		//document.getElementsByClassName('opProgressBar')[0].animate({ width: progressBarWidth }, 500).html(progressBarWidth + "%");
	}

	function testonsb(id, tache, type){
		//$( "#demoCharts" ).fadeOut( 200, function(){});
			//document.getElementById('demoCharts').style.opacity = '0';
		$.post(document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php', {action: 'marguerite_ajax', idProject: id, tacheActuelle: tache, typeAffichage: type}, function(response){
			document.getElementById("demo").innerHTML=response;
			//$( "#demoCharts" ).fadeIn( 1000, function(){});
			//progressBar();
			if(type=="marguerite"){
				charta();chartb();chartc();chartz();
				chartN2a();chartN2b();chartN2c();chartN2z();
			}
				document.getElementById('demoCharts').style.opacity = '1';
			$( "#opMenuTabsB" ).tabs();
			$(".opIFMenuB").click(function(){manageOverlay();});
			
			op_drawGantt(type);
		});
		
		
	}
	
	/*function testonspw(id){
		$.post(document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php', {action: 'marguerite_ajaxwp', idProject: id}, function(response){
			var password;

			var pass1 = response;

			password=prompt('Please enter your password to view this page : ',' ');

			if (password==pass1)
				$( "#opMenuTabsA" ).tabs();
				//$( "#opMenuTabsB" ).tabs();
				affichageTabsA = false;
				affichageTabsB = false;
			 
				$("#opIPMenu").click(function(){manageOverlay('a');});
				$(".opIFMenu").click(function(){manageOverlay();});
				$("#opOverlay").click(function(){manageOverlay();});
				
				
				testonsb(document.getElementById("op_idProject").innerHTML,0,'marguerite');
			else
			{
				testonspw(document.getElementById("op_idProject").innerHTML);
			}
		});
	}*/
			

		function charta() {
			$('.charta').each(function(){
				$(this).easyPieChart({
					barColor: '#1fbba6',
					trackColor: 'transparent',
					lineWidth: '10',
					lineCap: 'butt',
					scaleColor: false,
					size: '110',
					animate: 1000
				});
			});
		}
		function chartb() {
			$('.chartb').each(function(){
				$(this).easyPieChart({
					barColor: '#1fbba6', trackColor: 'transparent', lineWidth: '10', lineCap: 'butt', scaleColor: false, size: '110', animate: 1000
				});
			});
		}
		function chartc() {
			$('.chartc').each(function(){
				$(this).easyPieChart({
					barColor: '#FF8964', trackColor: 'transparent',	lineWidth: '10', lineCap: 'butt', scaleColor: false, size: '110', animate: 1000
				});
			});
		}
		function chartz() {
			$('.chartz').each(function(){
				$(this).easyPieChart({
					barColor: '#9ea7b3', trackColor: 'transparent', lineWidth: '10', lineCap: 'butt', scaleColor: false, size: '110', animate: 1000
				});
			});
		}
		
		function chartN2a() {
			$('.chartN2a').easyPieChart({
				barColor: '#1fbba6', trackColor: 'transparent', lineWidth: '8', lineCap: 'butt', scaleColor: false, size: '90', animate: 1000
			});
		}
		function chartN2b() {
			$('.chartN2b').easyPieChart({
				barColor: '#1fbba6', trackColor: 'transparent', lineWidth: '8', lineCap: 'butt', scaleColor: false, size: '90', animate: 1000
			});
		}
		function chartN2c() {
			$('.chartN2c').easyPieChart({
				barColor: '#FF8964', trackColor: 'transparent',	lineWidth: '8', lineCap: 'butt', scaleColor: false, size: '90', animate: 1000
			});
		}
		function chartN2z() {
			$('.chartN2z').easyPieChart({
				barColor: '#9ea7b3', trackColor: 'transparent', lineWidth: '8', lineCap: 'butt', scaleColor: false, size: '90', animate: 1000
			});
		}