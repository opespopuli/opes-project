<?php

/* Projets Admin */

function codex_opesproject_init() {

	register_post_type( 'opesproject',
		array(
		  'labels' => array(
			'name' => __( 'Opes Projects' ),
			'singular_name' => __( 'Opes Project' )
		  ),
		  'public' => true,
		  'has_archive' => false,
		  'rewrite' => array( 'slug' => 'opesproject' ),
		  'supports' => array( 'title', 'editor')
		)
	);
	register_taxonomy('ressources', 'opesproject',
		array(
		  'labels' => array(
			'name' => __( 'Ressources' ),
			'singular_name' => __( 'Ressources' )
		  ),
		  'rewrite' => array( 'slug' => 'ressources' )
		)
	);
	
	/*
	$labels = array(
		'name'               => 'Opes Projects',
		'singular_name'      => 'Opes Project',
		'all_items'          => 'Tous les projets',
		'add_new_item'       => 'Ajouter un projet',
		'new_item'           => 'Nouveau projet',
		'edit_item'          => 'Editer un projet',
		'view_item'          => 'Voir un projet',
		'search_items'       => 'Rechercher un projet',
		'not_found'          => 'Aucun projet trouvé.',
		'not_found_in_trash' => 'Aucon projet trouvé.'
	);
	$args = array(
		'label'             => 'Opes Projects',
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'opesproject' ),
		'capability_type'     => array('opesproject','opesprojects'),
        'map_meta_cap'        => true,
		//'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor' , 'custom-fields' 'author', 'thumbnail', 'excerpt', 'comments')
	);
	register_post_type( 'opesproject', $args );
	
	$labelsB = array(
		'name'              => 'ressources',
		'singular_name'     => 'ressource',
		'search_items'      => 'Rechercher une ressource',
		'all_items'         => 'Toutes les ressources',
		'edit_item'         => 'Editer une ressource',
		'view_item'         => 'Voir une ressource',
		'update_item'       => 'Mettre à jour une ressource',
		'add_new_item'      => 'Ajouter une ressource',
		'new_item_name'     => 'Nouveau nom de ressource',
		'popular_items'     => 'Ressources les plus utilisées'
	);
	$argsB = array(
		'label'             => 'ressources',
		'labels'            => $labelsB,
		'rewrite' 			=> array('slug' => 'ressources'),
		'hierarchical'      => true
	);*/
	//register_taxonomy( 'ressources', 'opesproject', $argsB);
	//register_taxonomy_for_object_type( 'ressources', 'opesproject', $argsB);
	//register_taxonomy('ressources', 'opesproject', 'show_tagcloud=1&hierarchical=true');
	
	//add_action('add_meta_boxes', 'add_meta_infos');
	//add_action('save_post', 'save_details_projects');
	/* Filter the single_template with our custom function*/
	//add_filter('single_template', 'my_custom_template');
}

function add_meta_infos(){
	add_meta_box("infos_tache-meta", "Infos Opes Project", "infos_project", "opesproject");
}
function save_details_projects(){
	//echo '<script type="text/javascript">saveGantt();</script>'; 
  global $post;
  update_post_meta($post->ID, "op_client", $_POST["op_client"]);
  update_post_meta($post->ID, "op_avancement", $_POST["op_avancement"]);
  update_post_meta($post->ID, "debut", $_POST["debut"]);
  update_post_meta($post->ID, "jour", $_POST["jour"]);
  update_post_meta($post->ID, "fin", $_POST["fin"]);
  update_post_meta($post->ID, "op_gantt", $_POST["op_gantt"]);
  update_post_meta($post->ID, "op_order", $_POST["op_order"]);
}

function infos_project() {
  global $post;
  $custom = get_post_custom($post->ID);
  $op_client = $custom["op_client"][0];
  $op_avancement = $custom["op_avancement"][0];
  
  $debut = $custom["debut"][0];
  if($debut == ''){$debut = date('d/m/Y');}
  
  $jour = $custom["jour"][0];
  if($jour == ''){$jour = ' ';}
  
  $fin = $custom["fin"][0];
  if($fin == ''){$fin = date('d/m/Y');}
  
  $op_gantt = $custom["op_gantt"][0];
  $op_order = $custom["op_order"][0];
  ?>
  
  <h2 style="margin-bottom:0px;"><label>Informations du projet :</label><br /></h2>
  <div><label>Nom du client :</label>
  <input style="width:225px; text-align:right;" name="op_client" value="<?php echo $op_client; ?>" /></div>
  <h2 style="margin-bottom:0px;"><label>Avancement du projet :</label><br /></h2>
  <div style="margin-bottom:10px;"><label>Accomplissement général (%) :</label><input style="width:50px; margin-left:10px; text-align:right;" id="avancement" name="op_avancement" value="<?php echo $op_avancement; ?>" />%
  <div style="height:15px; font-size:20px; width:255px; margin-top:10px; margin-bottom:20px;" id="slider-range-min"></div></div>
  <div><label>Dates du projet :</label><br />
  <p style="display:inline; margin-right:35px;"><label>Début du projet :</label>
  <input readonly style="width:120px; text-align:center;" id="datepickerA" name="debut" value="<?php echo $debut; ?>" /></p><br />
  <p style="display:none; margin-right:35px;"><label>Date du jour :</label>
  <input readonly style="width:120px; text-align:center;" id="datepickerB" name="jour" value="<?php echo $jour; ?>" /></p>
  <p style="display:inline; margin-right:35px;"><label>Fin du projet :</label>
  <input readonly style="width:120px; text-align:center;" id="datepickerC" name="fin" value="<?php echo $fin; ?>" /></p></div>
  <textarea style="display:block;" name="op_gantt" id="gantt" rows="10" cols="30"><?php echo $op_gantt; ?></textarea>
  <textarea style="display:block;" name="op_order" id="order" rows="10" cols="30"><?php echo $op_order; ?></textarea>
  
  <h2 style="margin-bottom:0px;"><label>Tâches du projet :</label><br /></h2>
  <div id="editableMenu" style="display:none; position:fixed; width:30%; min-width:250px; max-width:500px; right:11%; top:8%; background-color:#ddd; z-index:9999; padding:20px;">
	<ul>
		<li style="display:none;"><label style="float:left;">Id : </label><div contenteditable='true' style='margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableId'>0</div></li>
		<li><label style="float:left;">Nom : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap; margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableNom'>Task 1</div></li>
		<li><label style="float:left;">Descriptif : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableDesc'>Descriptif 1</div></li>
		<li><label style="float:left;">Date de début : </label><input readonly style='margin-left:33px; width:100px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableDebut' class='editableDatepicker' value='jj/mm/aaaa'></li>
		<li><label style="float:left;">Date de fin : </label><input readonly style='margin-left:53px; width:100px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableFin' class='editableDatepicker' value='jj/mm/aaaa'></li>
		<li><label style="float:left;">Progression (%) : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableProg'>0</div></li>
		<li><label style="float:left;">Tâche parente (id) : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableParent'>id</div></li>
		<li style="display:none;"><label style="float:left;">Tâches liées : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableTasks'>ids</div></li>
		<!--<li><label style="float:left;">Type (Tache/Jalon) : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableType'>Tache</div></li>-->
		<li><label style="float:left;">Type : </label><input contenteditable='true' list="typeTache" name="typeTache" value="Tache" style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap; margin-left:90px; padding:0px 0px 0px 1px; width:100px; border:1px inset #DDD; font-size:14px; height:20px; background-color:#fff;' id='editableType'></li>
		<li style="display:none;"><label style="float:left;">Actif : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableActif'>Actif</div></li>
		<li><label style="float:left;">Ressources : </label><div contenteditable='true' style='text-overflow:ellipsis; overflow:hidden; white-space:nowrap;margin-left:125px; border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;' id='editableRess'>Ressources</div></li>
		<datalist id="typeTache">
		  <option value="Tache">
		  <option value="Jalon">
		</datalist>
	</ul>
	<button type="button" onClick="saveRowInfos();" style="width:100px; margin-left:35%;">Save</button>
	</div>
	<div id="opOverlay" onClick="saveRowInfos();" style="top:0; left:0;	width:100%;	height:100%; opacity:1; z-index:9998;	position:fixed;	margin:0; padding:0; background-color:rgba(0, 0, 0, 0.5); display:none;"></div>
  <?php if($op_gantt!=''){ ?>
	<div id="tacheList" style="margin-bottom:30px;">
		<?php echo $op_gantt; ?>
	</div>
	<?php }else{ ?>
  <?php $nvlTache2 = "<div style=\'width:100%; float:left;\'><div contenteditable=\'true\' style=\'float:left;width:15%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'nomb\'>Nom</div><input class=\'datePicker\' id=\'datepickr\' style=\'float:left;width:15%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'debutb\'>Debut</input><input class=\'datePicker\' id=\'datepicker\' style=\'float:left;width:15%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'finb\'>Fin</input><div contenteditable=\'true\' style=\'float:left;width:15%;border:1px inset #DDD; font-size:15px; height:20px; background-color:#fff;\' id=\'avancementb\'>%</div></div>";
  $nvlTache = '<div><li id="editableRow1" style="margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 16px; height: 18px; " class="ui-state-default"><div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; float:left; width:80%; height:22px;"><span style=" position: absolute; font-size: 18px; margin-left: -1.3em;" class="ui-icon ui-icon-arrowthick-2-n-s"></span><label class=\'labelId\'>1</label> - <label class=\'labelTache\'>Nom tâche</label> - <label class=\'labelDebut\'>'.date('d/m/Y').'</label> - <label class=\'labelFin\'>'.date('d/m/Y').'</label> - <label class=\'labelProg\'>0</label></div><div style=\'margin-top:-5px; width:9%;float:right; overflow:hidden;\' id=\'btnSupp\'><button type=\'button\' style=\'width:100%;\' onClick=\'suppRow((this));\'>X</button></div><div style=\'margin-top:-5px; width:9%; float:right; overflow:hidden; margin-right:1%;\' id=\'btnSupp\'><button type=\'button\' style=\'width:100%;\' onClick=\'modifRow((this));\'>Modifier</button></div><div class="editable" style="display:none;"><div class=\'taskInfosId\'>1</div><div class=\'taskInfosNom\'>Nom tâche</div><div class=\'taskInfosDesc\'>Descriptif</div><div class=\'taskInfosDebut\'>'.date('d/m/Y').'</div><div class=\'taskInfosFin\'>'.date('d/m/Y').'</div><div class=\'taskInfosProg\'>0</div><div class=\'taskInfosParent\'>0</div><div class=\'taskInfosLiaisons\'>ids</div><div class=\'taskInfosType\'>Tache</div><div class=\'taskInfosActif\'>Actif</div><div class=\'taskInfosRess\'>Ressource</div></div></li></div>'; ?>
	<div id="tacheList" style="margin-bottom:30px;">
		<div id="menub">
			<button type="button" onClick="addRow();">Add Row</button>
			<button type="button" onClick="resetGantt();">Reset</button>
			<input style="visibility:hidden;" type="button" id="nbrRowb" value='0'/>
			<button style="visibility:hidden;" type="button" onClick="getElementById('tacheGantt').innerHTML='<div id=\'tacheList\'>'+getElementById('tacheList').innerHTML; getElementById('tacheGantt').innerHTML+='</div>'">Save</button>
		</div>
		<div id="tacheListTasks"><ul id="sortable" style="list-style-type: none; margin: 0; padding: 0; width: 100%;"><?php //echo $nvlTache; ?></ul></div>
	</div>
	<style onload="addRow();"></style>
	<?php } ?>
	
  <p>&nbsp;</p>
  
  <?php 
}

?>