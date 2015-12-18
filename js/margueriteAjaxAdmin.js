jQuery(document).ready(function($){
	initPicker($);
});

function initPicker($){
	/*$(function() {
		var x = document.getElementsByClassName("datePicker");
		var i;
		for (i = 0; i < x.length; i++) {
			$(x[i]).datepicker();
		}
	});*/
	
	$('.datePicker').each(function(){
		$(this).datepicker({dateFormat: "dd/mm/yy"});
	});
	$('.datePicker2').each(function(){
		$(this).datepicker({dateFormat: "dd/mm/yy"});
	});
	$(function() {
		$( "#datepickerA" ).datepicker({dateFormat: "dd/mm/yy"});
	});
	$(function() {
		$( "#datepickerB" ).datepicker({dateFormat: "dd/mm/yy"});
	});
	$(function() {
		$( "#datepickerC" ).datepicker({dateFormat: "dd/mm/yy"});
	});
	$('.editableDatepicker').each(function(){
		$(this).datepicker({dateFormat: "dd/mm/yy"});
	});
	
	$(function() {
		//$( "#tacheListTasks" ).onchange(saveGantt(););
		
		$( "#slider-range-min" ).slider({
		  range: "min",
		  value: $( "#avancement" ).val(),
		  min: 0,
		  max: 100,
		  slide: function( event, ui ) {
			$( "#avancement" ).val(ui.value);
		  }
		});
		$( "#avancement" ).val( $( "#slider-range-min" ).slider( "value" ) );
	});
	
	$(function() {
		$( "#sortable" ).sortable();
		$( "#sortable" ).disableSelection();
		$( "#sortable" ).sortable({
			connectWith: ".ui-sortable-handle",
			stop: function( event, ui ) {/*alert('ok');*/ //op_construireGantt();/*alert("New position: " + ui.item.index());*/}
		});
		
	});
	
	$(function() {
		/*var editable = document.getElementsByClassName("editable");
		for (i = 0; i < editable.length; i++) {
			$( editable[i] ).hide();
		}*/
	});
	
};

function saveGantt(){
	document.getElementById('gantt').innerHTML=document.getElementById('tacheList').innerHTML;
};

function addRow(){
	//alert('ok');
	var now = new Date();
	//var annee   = now.getFullYear();
	//var mois    = now.getMonth() + 1;
	//var jour    = now.getDate();
	var dateJour = (('0'+now.getDate()   ).slice(-2))+'/'+(('0'+(now.getMonth()+1)).slice(-2))+'/'+now.getFullYear();
	var nbrRow = parseInt(document.getElementById("nbrRowb").value)+1;
	/*var nvltache1 = "<div style=\'width:100%; float:left;\'><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'nomb";
	var nvltache2 = "\'>Nom</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'descrb";
	var nvltache3 = "\'>Descriptif</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'debutb";
	var nvltache4 = "\'>jj/mm/aaaa</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'finb";
	var nvltache5 = "\'>jj/mm/aaaa</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'avancementb";
	var nvltache6 = "\'>0</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'parentb";
	var nvltache7 = "\'>id</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'liaisonb";
	var nvltache8 = "\'>ids</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'typeb";
	var nvltache9 = "\'>Tache</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'actifb";
	var nvltache10 = "\'>Actif</div><div onkeyup=\'saveGantt();\' contenteditable=\'true\' style=\'float:left;width:9%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'ressourceb";
	var nvltache11 = "\'>Ressource</div><div style=\'float:left;width:8%; height:20px; background-color:#fff;\' id=\'btnSupp\'><button type=\'button\' onClick=\'suppRow((this));\'>Supprimer</button></div></div>";
	var nvltache = nvltache1.concat(nbrRow, nvltache2, nbrRow, nvltache3, nbrRow, nvltache4, nbrRow, nvltache5, nbrRow, nvltache6, nbrRow, nvltache7, nbrRow, nvltache8, nbrRow, nvltache9, nbrRow, nvltache10, nbrRow, nvltache11);
	*/
	
	var nvlTache = '<li id="editableRow'+nbrRow+'" style="margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 16px; height: 18px; " class="ui-state-default"><div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; float:left; width:80%; height:22px;"><span style=" position: absolute; font-size: 18px; margin-left: -1.3em;" class="ui-icon ui-icon-arrowthick-2-n-s"></span><label class=\'labelId\'>'+nbrRow+'</label> - <label class=\'labelTache\'>Nom tâche</label> - <label class=\'labelDebut\'>'+dateJour+'</label> - <label class=\'labelFin\'>'+dateJour+'</label> - <label class=\'labelProg\'>0</label></div><div style=\'margin-top:-5px; width:9%; float:right; overflow:hidden;\' id=\'btnSupp\'><button type=\'button\' style=\'width:100%;\' onClick=\'suppRow((this));\'>X</button></div><div style=\'margin-top:-5px; width:9%;float:right; overflow:hidden; margin-right:1%;\' id=\'btnSupp\'><button type=\'button\' style=\'width:100%;\' onClick=\'modifRow((this));\'>Modifier</button></div><div class="editable" style="display:none;"><div class=\'taskInfosId\'>'+nbrRow+'</div><div class=\'taskInfosNom\'>Nom tâche</div><div class=\'taskInfosDesc\'>Descriptif</div><div class=\'taskInfosDebut\'>'+dateJour+'</div><div class=\'taskInfosFin\'>'+dateJour+'</div><div class=\'taskInfosProg\'>0</div><div class=\'taskInfosParent\'>0</div><div class=\'taskInfosLiaisons\'>ids</div><div class=\'taskInfosType\'>Tache</div><div class=\'taskInfosActif\'>Actif</div><div class=\'taskInfosRess\'>Nom ressource</div></div></li>';
	//var nvlTache = nvlTache1.concat(nbrRow, nvlTache2);
	
	document.getElementById("nbrRowb").value = nbrRow;
	var t = document.createTextNode(nvlTache);
	var divt = document.createElement("div");
	divt.innerHTML = nvlTache;
	document.getElementById("sortable").appendChild(divt);
	
	saveGantt();
};

function modifRow(row){
	
	document.getElementById("editableMenu").style.display = "block";
	document.getElementById('opOverlay').style.display = 'block';
	
	document.getElementById("editableId").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosId")[0].innerHTML;
	document.getElementById("editableNom").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosNom")[0].innerHTML;
	document.getElementById("editableDesc").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosDesc")[0].innerHTML;
	document.getElementById("editableDebut").value = row.parentNode.parentNode.getElementsByClassName("taskInfosDebut")[0].innerHTML;
	document.getElementById("editableFin").value = row.parentNode.parentNode.getElementsByClassName("taskInfosFin")[0].innerHTML;
	document.getElementById("editableProg").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosProg")[0].innerHTML;
	document.getElementById("editableParent").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosParent")[0].innerHTML;
	document.getElementById("editableTasks").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosLiaisons")[0].innerHTML;
	document.getElementById("editableType").value = row.parentNode.parentNode.getElementsByClassName("taskInfosType")[0].innerHTML;
	document.getElementById("editableActif").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosActif")[0].innerHTML;
	document.getElementById("editableRess").innerHTML = row.parentNode.parentNode.getElementsByClassName("taskInfosRess")[0].innerHTML;
};


function resetGantt(){
	document.getElementById("tacheGantt").innerHTML='';
};

function suppRow(row){
	//document.getElementById("nbrRowb").value=parseInt(document.getElementById("nbrRowb").value)-1;
	row.parentNode.parentNode.parentNode.innerHTML='';
	saveGantt();
};

function saveRowInfos(){
	
	document.getElementById("editableMenu").style.display = "none";
	document.getElementById('opOverlay').style.display = 'none';
	
	var rowNumber = document.getElementById("editableId").innerHTML;
	var editableRowNumber = "editableRow"+rowNumber;
	
	//document.getElementById(editableRowNumber).getElementsByClassName("labelId")[0].innerHTML = "test";
	document.getElementById(editableRowNumber).getElementsByClassName("labelTache")[0].innerHTML = document.getElementById("editableNom").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("labelDebut")[0].innerHTML = document.getElementById("editableDebut").value;
	document.getElementById(editableRowNumber).getElementsByClassName("labelFin")[0].innerHTML = document.getElementById("editableFin").value;
	document.getElementById(editableRowNumber).getElementsByClassName("labelProg")[0].innerHTML = document.getElementById("editableProg").innerHTML;
	
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosNom")[0].innerHTML = document.getElementById("editableNom").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosDesc")[0].innerHTML = document.getElementById("editableDesc").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosDebut")[0].innerHTML = document.getElementById("editableDebut").value;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosFin")[0].innerHTML = document.getElementById("editableFin").value;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosProg")[0].innerHTML = document.getElementById("editableProg").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosParent")[0].innerHTML = document.getElementById("editableParent").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosLiaisons")[0].innerHTML = document.getElementById("editableTasks").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosType")[0].innerHTML = document.getElementById("editableType").value;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosActif")[0].innerHTML = document.getElementById("editableActif").innerHTML;
	document.getElementById(editableRowNumber).getElementsByClassName("taskInfosRess")[0].innerHTML = document.getElementById("editableRess").innerHTML;
	
	saveGantt();
	
	//row.getElementsByClassName("taskInfosNom")[0].innerHTML = "editableRow"+rowNumber;
	
	
	//document.getElementById("labelTache").innerHTML = document.getElementById("editableNom").innerHTML;
	//document.getElementById("labelDebut").innerHTML = document.getElementById("editableDebut").innerHTML;
	//document.getElementById("labelFin").innerHTML = document.getElementById("editableFin").innerHTML;
	//document.getElementById("labelProg").innerHTML = document.getElementById("editableProg").innerHTML;
};