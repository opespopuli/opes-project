<?php

/*
Plugin Name: Opes Project 0.2
Plugin URI: http://opes-project.fr
Description: Une autre façon de suivre vos projets.
Version: 0.2
Author: Opes Populi
Author URI: http://opes-project.fr
License: GPL2
*/

$op_pageList = "/dashboard";
$op_pageEdit = "/edition-de-projet";

/* Shortcode */
add_action('init', 'marguerite_shortcode_init');

function marguerite_shortcode_init() {
	add_shortcode('opesproject_list', 'opesproject_shortcode_list');
	add_shortcode('opesproject_edit', 'opesproject_shortcode_edit');
}

/* Initialisation des données */
function opesproject_shortcode_list(){
		global $post;
		
		$resultatAffiche = '';
		
		if(isset($_POST["op_status"])){
			if($_POST["op_status"] == "delete"){
				wp_delete_post($_POST["op_id"]);
				$resultatAffiche = '<legend>Project deleted.</legend>';
			}
		}
		
		$resultatAffiche .= '<div style="margin:50px;">';
		//$resultatAffiche .= '<h1>Mes projets</h1>';
		
		$current_user = wp_get_current_user();
		/* old si enregistré */
		if ( 0 == $current_user->ID ) {
			$resultatAffiche .= '<p style="line-height:30px; float:left;">Veuillez vous <a href="login/">connecter</a> pour accéder à vos projets.</p>';
		}else{
			//Define your custom post type name in the arguments
			$args = array('post_type' => 'opesproject', 'posts_per_page' => -1, 'author' => get_current_user_id());
			/* old $args = array('post_type' => 'opesproject', 'posts_per_page' => -1);*/
			//Define the loop based on arguments
			$loop = new WP_Query( $args );
			//Display the contents
			
			$resultatAffiche .= '<a style="color:#9EA6A4;" class="op_textAdmin" href="/edition-de-projet"><button class="op_btnMenu" style="border-bottom:#FF8964 5px solid; margin:15px 0px;">Add a project</button></a>';
			$resultatAffiche .= '<ul style="margin-top:10px; padding:0;">';
			while ( $loop->have_posts() ) : $loop->the_post();
				
				$resultatAffiche .= '<li class="op_listMenu">';
				$resultatAffiche .= '<p style="line-height:29px; float:left; min-width:200px; width:60%;" class="op_textMenu">';
				$resultatAffiche .= '<a style="color:#14b9d6;" target="_blank" href="'.$post->post_name.'">'.$post->post_title.'</a>';
				$resultatAffiche .= ' - ';
				$resultatAffiche .= get_post_meta($post->ID, 'op_client', true);
				$resultatAffiche .= '</p>';
				
				//$resultatAffiche .= '<form style="display:inline; float:right;" action="/dashboard" method="post"><input type="hidden" name="op_status" value="delete" id="status"><input type="hidden" name="op_id" value="'.$post->ID.'"><input class="op_btnMenu" type="submit" value="Delete"></form>';
				$resultatAffiche .= '<form style="display:inline; float:right;" action="/edition-de-projet" method="post"><input type="hidden" name="op_status" value="live" id="status"><input type="hidden" name="op_id" value="'.$post->ID.'"><input class="op_btnMenu" type="submit" value="Edit this project"></form>';
				$resultatAffiche .= '<div style="clear:both;"></div>';
				
				$resultatAffiche .= '</li>';
				
				
			endwhile;
			$resultatAffiche .= '</ul>';
			
			
			/* Restore original Post Data */
			wp_reset_postdata();
		}
		
		$resultatAffiche .= '</div>';
		
		
		return $resultatAffiche;
		
}
function opesproject_shortcode_edit(){

		global $post;
		
		/*get_post_meta($post->ID, "client", $_POST["client"]);
		get_post_meta($post->ID, "av_general", $_POST["av_general"]);
		get_post_meta($post->ID, "debut", $_POST["debut"]);
		get_post_meta($post->ID, "jour", $_POST["jour"]);
		get_post_meta($post->ID, "fin", $_POST["fin"]);
		get_post_meta($post->ID, "gantt", $_POST["gantt"]);*/
		
		$resultatAffiche = '';
		if(isset($_POST["op_status"])){
			if($_POST["op_status"] == "add"){
				/* Ajout du projet */
				$add_opesproject = array(
				  'post_title'    => $_POST["op_title"],
				  'post_content'  => $_POST["op_content"],
				  'post_status'   => 'publish',
				  'post_type'     => 'opesproject'
				);
				//wp_insert_post($add_opesproject);
				$addProject_id = wp_insert_post( $add_opesproject, $wp_error );
				
				$resultatAffiche = '<legend>Project added.</legend>';
				
				$id = $addProject_id;
				$queried_post = get_post($addProject_id);
				
				
					$newOrder = $_POST["op_order"];
					$newGantt = $_POST["op_gantt"];
				
					if($_POST["op_gantt"] != ''){
						/* Nouvelles taches */
						$arrayNvltasks = explode("|",$_POST["op_gantt"]);
						for($p=1; $p<count($arrayNvltasks); $p++){
							$arrayNvltasks[$p] = explode(",", $arrayNvltasks[$p]);
						}
						
						/* Mise à jour taches */
						for($o=1; $o<count($arrayNvltasks); $o++){
								if(substr($arrayNvltasks[$o][0],0,1) == 'n'){
									/* Création des nouvelles tâches */
									$add_opestask = array(
									  'post_title'   => $arrayNvltasks[$o][1],
									  'post_content'    => $arrayNvltasks[$o][11],
									  'post_status'   => 'publish',
									  'post_type'     => 'opestasks'
									);
									$addTask_id = wp_insert_post( $add_opestask, $wp_error );
									
									update_post_meta ($addTask_id, 'ot_project', $addProject_id);
									update_post_meta ($addTask_id, 'ot_dated', $arrayNvltasks[$o][2]);
									update_post_meta ($addTask_id, 'ot_datef', $arrayNvltasks[$o][3]);
									update_post_meta ($addTask_id, 'ot_duree', 'a faire');
									update_post_meta ($addTask_id, 'ot_avancement', $arrayNvltasks[$o][5]);
									update_post_meta ($addTask_id, 'ot_responsable', $arrayNvltasks[$o][6]);
									update_post_meta ($addTask_id, 'ot_precedant', $arrayNvltasks[$o][7]);
									update_post_meta ($addTask_id, 'ot_hierarchie', $arrayNvltasks[$o][8]);
									update_post_meta ($addTask_id, 'ot_ressources', $arrayNvltasks[$o][10]);
									$newOrder = str_replace($arrayNvltasks[$o][0],$addTask_id,$newOrder );
									$newGantt = str_replace($arrayNvltasks[$o][0],$addTask_id,$newGantt );
								}
						}
					}
				
				
				$slug = $queried_post->post_name;
				$title = $_POST["op_title"];
				$content = $_POST["op_content"];
				$client = $_POST["op_client"];
				$avancement = $_POST["op_avancement"];
				$op_gantt = $newGantt;
				$op_order = $newOrder;
				
				//$queried_post->post_status = 'publish';
				update_post_meta ($id, 'op_client', $_POST["op_client"]);
				update_post_meta ($id, 'op_avancement', $_POST["op_avancement"]);
				//update_post_meta ($id, 'op_gantt', $_POST["op_gantt"]);
				//update_post_meta ($id, 'op_order', $_POST["op_order"]);
				update_post_meta ($id, 'op_gantt', $newGantt);
				update_post_meta ($id, 'op_order', $newOrder);
				
			}else{
				/* Si enregistrement */
				if($_POST["op_status"] == "edit"){
				
					$newOrder = $_POST["op_order"];
				
					if($_POST["op_gantt"] != ''){
						/* Nouvelles taches */
						$arrayNvltasks = explode("|",$_POST["op_gantt"]);
						for($p=1; $p<count($arrayNvltasks); $p++){
							$arrayNvltasks[$p] = explode(",", $arrayNvltasks[$p]);
						}
						
						/* Mise à jour taches */
						for($o=1; $o<count($arrayNvltasks); $o++){
							if((substr($arrayNvltasks[$o][0],0,1) != 'n') && (substr($arrayNvltasks[$o][0],0,1) != 'd')){
								/* Mise à jour */
								$edit_opestasks = array(
								  'ID'            => $arrayNvltasks[$o][0],
								  'post_title'    => $arrayNvltasks[$o][1],
								  'post_content'    => $arrayNvltasks[$o][11],
								  'post_status'   => 'publish',
								  'post_type'     => 'opestasks',
								);
								wp_update_post($edit_opestasks);
								
								update_post_meta ($arrayNvltasks[$o][0], 'ot_dated', $arrayNvltasks[$o][2]);
								update_post_meta ($arrayNvltasks[$o][0], 'ot_datef', $arrayNvltasks[$o][3]);
								update_post_meta ($arrayNvltasks[$o][0], 'ot_duree', 'a faire');
								update_post_meta ($arrayNvltasks[$o][0], 'ot_avancement', $arrayNvltasks[$o][5]);
								update_post_meta ($arrayNvltasks[$o][0], 'ot_responsable', $arrayNvltasks[$o][6]);
								update_post_meta ($arrayNvltasks[$o][0], 'ot_precedant', $arrayNvltasks[$o][7]);
								update_post_meta ($arrayNvltasks[$o][0], 'ot_hierarchie', $arrayNvltasks[$o][8]);
								update_post_meta ($arrayNvltasks[$o][0], 'ot_ressources', $arrayNvltasks[$o][10]);
							}else{
								if(substr($arrayNvltasks[$o][0],0,1) == 'n'){
									/* Création des nouvelles tâches */
									$add_opestask = array(
									  'post_title'   => $arrayNvltasks[$o][1],
									  'post_content'    => $arrayNvltasks[$o][11],
									  'post_status'   => 'publish',
									  'post_type'     => 'opestasks'
									);
									$addTask_id = wp_insert_post( $add_opestask, $wp_error );
									
									update_post_meta ($addTask_id, 'ot_project', $_POST["op_id"]);
									update_post_meta ($addTask_id, 'ot_dated', $arrayNvltasks[$o][2]);
									update_post_meta ($addTask_id, 'ot_datef', $arrayNvltasks[$o][3]);
									update_post_meta ($addTask_id, 'ot_duree', 'a faire');
									update_post_meta ($addTask_id, 'ot_avancement', $arrayNvltasks[$o][5]);
									update_post_meta ($addTask_id, 'ot_responsable', $arrayNvltasks[$o][6]);
									update_post_meta ($addTask_id, 'ot_precedant', $arrayNvltasks[$o][7]);
									update_post_meta ($addTask_id, 'ot_hierarchie', $arrayNvltasks[$o][8]);
									update_post_meta ($addTask_id, 'ot_ressources', $arrayNvltasks[$o][10]);
									$newOrder = str_replace($arrayNvltasks[$o][0],$addTask_id,$newOrder );
								}else{
									/* Suppression des tâches */
									wp_delete_post(substr($arrayNvltasks[$o][0],1));
								}
							}
						}
					}
					
					$edit_opesproject = array(
					  'ID'            => $_POST["op_id"],
					  'post_title'    => $_POST["op_title"],
					  'post_content'  => $_POST["op_content"],
					  'post_status'   => 'publish',
					  'post_type'     => 'opesproject',
					);
					wp_update_post($edit_opesproject);
					update_post_meta ($_POST["op_id"], 'op_client', $_POST["op_client"]);
					update_post_meta ($_POST["op_id"], 'op_avancement', $_POST["op_avancement"]);
					update_post_meta ($_POST["op_id"], 'op_gantt', $_POST["op_gantt"]);
					update_post_meta ($_POST["op_id"], 'op_order', $newOrder);
					
					$resultatAffiche = '<legend>Project edited.</legend>';
					
					//$id = $_POST["op_id"];
					//$title = $_POST["op_title"];
					//$content = $_POST["op_content"];
					//$client = $_POST["op_client"];
					//$avancement = $_POST["op_avancement"];
					//$op_gantt = $_POST["op_gantt"];
					//$op_order = $_POST["op_order"];
					
					//$op_order = "";
					//$op_gantt = "";
					
					/*$argsTasks = array('post_type' => 'opestasks', 'author' => get_current_user_id());
					$loopTasks = new WP_Query( $argsTasks );
					while ($loopTasks->have_posts()) : $loopTasks->the_post();
						if(get_post_meta($post->ID, 'ot_project', true) == $idProject){
							$op_gantt .= '|' . $post->ID . ',' . $post->post_title . ',' . get_post_meta($post->ID, 'ot_dated', true) . ',' . get_post_meta($post->ID, 'ot_datef', true) . ',' . get_post_meta($post->ID, 'ot_duree', true) . ',' . get_post_meta($post->ID, 'ot_avancement', true) . ',' . get_post_meta($post->ID, 'ot_responsable', true) . ',' . get_post_meta($post->ID, 'ot_precedant', true) . ',' . get_post_meta($post->ID, 'ot_hierarchie', true) . ',' . get_post_meta($post->ID, 'ot_lien', true) . ',' . get_post_meta($post->ID, 'ot_ressources', true);
						}
					endwhile;*/
				}
				
				/* Modification du projet */
				//if($_POST["op_status"] != "edit"){
					$queried_post = get_post($_POST["op_id"]);
					$idProject = $queried_post->ID;
					$id = $idProject;
					$slug = $queried_post->post_name;
					$title = $queried_post->post_title;
					$content = $queried_post->post_content;
					$client = get_post_meta($_POST["op_id"], 'op_client', true);
					$avancement = get_post_meta($_POST["op_id"], 'op_avancement', true);
					//$op_gantt = get_post_meta($_POST["op_id"], 'op_gantt', true);
					$op_order = get_post_meta($_POST["op_id"], 'op_order', true);
					//$op_order = "";
					$op_gantt = "";
					
					$argsTasks = array('post_type' => 'opestasks',  'posts_per_page' => -1, 'author' => get_current_user_id());
					/* old $argsTasks = array('post_type' => 'opestasks',  'posts_per_page' => -1); */
					$loopTasks = new WP_Query( $argsTasks );
					while ($loopTasks->have_posts()) : $loopTasks->the_post();
						if(get_post_meta($post->ID, 'ot_project', true) == $idProject){
							$op_gantt .= '|' . $post->ID . ',' . $post->post_title . ',' . get_post_meta($post->ID, 'ot_dated', true) . ',' . get_post_meta($post->ID, 'ot_datef', true) . ',' . get_post_meta($post->ID, 'ot_duree', true) . ',' . get_post_meta($post->ID, 'ot_avancement', true) . ',' . get_post_meta($post->ID, 'ot_responsable', true) . ',' . get_post_meta($post->ID, 'ot_precedant', true) . ',' . get_post_meta($post->ID, 'ot_hierarchie', true) . ',' . get_post_meta($post->ID, 'ot_lien', true) . ',' . get_post_meta($post->ID, 'ot_ressources', true) . ',' . $post->post_content;
						}
					endwhile;
					/* Restore original Post Data */
					wp_reset_postdata();
				//}
			}
				
			$status = "edit";
			$titre = "Edit a project";
			$txt_voirProjet = '<a style="color:#9EA6A4;" target="_blank" href="/opesproject/'.$slug.'" class="op_textAdmin"><button class="op_btnMenu" style="margin:5px 10px; float:left; border-bottom:#FF8964 5px solid;">See the project</button></a>';
			$txt_bouton = "Save the project";
			$txt_delete = '<form style="display:inline;" action="/dashboard" method="post"><input type="hidden" name="op_status" value="delete" id="status"><input type="hidden" name="op_id" value="'.$id.'"><input class="op_btnMenu op_textAdmin" style="float:right;" type="submit" value="Delete"></form>';
		}else{
			/* Nouveau projet */
			$titre = "Add a project";
			$title = "Project name";
			$content = "Description of this project.";
			$client = "Client name";
			$avancement = "0";
			$op_gantt = "";
			$op_order = "";
			$txt_bouton = "Add the project";
			$status = "add";
			$id = "";
			$txt_voirProjet = '';
			$txt_delete = '';
		}
		
		
		$resultatAffiche .= '<div style="margin:30px;">';
		$resultatAffiche .= '<h1 class="op_textAdmin" style="font-size:25px; margin-top:20px;">'.$titre.'</h1>';
		$resultatAffiche .= $txt_voirProjet;
		$resultatAffiche .= '<a style="color:#9EA6A4;" href="/dashboard" class="op_textAdmin"><button class="op_btnMenu" style="margin:5px 10px; float:left;">View projects list</button></a>';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<form id="op_formProject"  action="#" method="post">';
		$resultatAffiche .= '<input type="hidden" name="op_status" value="'.$status.'" id="status">';
		$resultatAffiche .= '<input type="hidden" name="op_id" value="'.$id.'" id="id">';
		$resultatAffiche .= '<p class="op_textMenu" style="width:130px; float:left;">Project title</p><input class="op_inputMenu op_textMenu" type="text" name="op_title" value="'.$title.'" id="title">';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<textarea class="wp-editor-area op_inputMenu op_textMenu" style="text-align:left; height: 110px; width:100%; min-width:100%; min-height:110px; max-width:100%; max-height:110px; resize:none;" cols="40" name="op_content" id="content">'.$content.'</textarea>';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<p class="op_textMenu" style="width:130px; float:left;">Client name</p><input class="op_inputMenu op_textMenu" type="text" name="op_client" value="'.$client.'" id="client">';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<p class="op_textMenu" style="width:250px; float:left;">Progression</p><input class="op_inputMenu op_textMenu" type="number" min="0" max="100" name="op_avancement" value="'.$avancement.'" id="op_avancement"><p class="op_textMenu" style="float:left; position:relative; left:-15px; padding:6px 0px;">%</p>';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<div style="float:left;">';
		$resultatAffiche .= '<h2 class="op_textAdmin" style="font-size:25px; margin-top:20px;">Tasks of this project</h2>';
		//$resultatAffiche .= '<style onload="op_construireTasks();"></style>';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<input style="text-align:left; width:170px;" class="op_inputMenu op_textMenu" type="text" name="op_new_task" value="New task" id="new_task">';
		$resultatAffiche .= '<button class="op_btnMenu op_textAdmin" id="new_taskBtn" type="button" style="float:left; height:30px; font-size:15px; line-height:15px; padding:5px; border-bottom:#FF8964 5px solid;" onClick="op_addTask()">Add</button>';
		$resultatAffiche .= '</div>';
		$resultatAffiche .= '<div class="op_blank"></div>';
		$resultatAffiche .= '<div id="tasks" style="float:left; width:100%; min-height:50px;"> </div>';
		$resultatAffiche .= '<div style="margin-top:10px;" class="op_blank"></div>';
		$resultatAffiche .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
		$resultatAffiche .= '<script>google.load("visualization", "1.1", {packages:["gantt"]});</script>';
		$resultatAffiche .= '<div style="width:100%; max-width:1100px; margin: 0 auto; left:0; right:0; text-align:center; border-top: solid 1px #eaedf1; float:left; margin-top:40px; box-shadow: 0px -20px 30px -22px rgba(0,0,0,0.10); -webkit-box-shadow: 0px -20px 30px -22px rgba(0,0,0,0.10);">';
			$resultatAffiche .= '<div style="width:100%; text-align:center; font-size:18px; font-family:Open-sans; padding:3px;">Gantt (save to reload it)</div>';
			$resultatAffiche .= '<div style="width:100%; position:relative; padding:0; overflow:hidden; max-width:1100px; margin: 0 auto; left:0; right:0; margin-bottom:25px;" id="chart_Gantt"></div>';
		$resultatAffiche .= '</div>';
		
		$resultatAffiche .= '<textarea class="wp-editor-area" style="height: 120px; display:none; width:100%; max-width:100%; max-height:240px;" cols="40" name="op_gantt" id="gantt">'.$op_gantt.'</textarea>';
		$resultatAffiche .= '<textarea class="wp-editor-area" style="height: 120px; display:none; width:100%; max-width:100%; max-height:240px;" cols="40" name="op_order" id="op_order">'.$op_order.'</textarea>';
		$resultatAffiche .= '<input class="op_btnMenu op_textAdmin" style="float:left; border-bottom: #FF8964 5px solid;" type="submit" value="'.$txt_bouton.'">';
		$resultatAffiche .= '</form>';
		$resultatAffiche .= $txt_delete;
		
		$resultatAffiche .='
		<div id="dialog-form22" class="op_listMenu op_textMenu" title="Task informations">
		  <form id="ot_formTask">
			<fieldset>
			  <input type="hidden" class="inputForm op_inputMenu op_textMenu" name="ot_id" id="ot_id" value="" class="text ui-widget-content ui-corner-all">
			  <label class="labelForm op_textMenu" style="float:left; width:120px;">Title</label>
			  <input type="text" class="inputForm op_inputMenu op_textMenu" style="float:right; width:170px;" name="ot_title" id="ot_title" value="truc" class="text ui-widget-content ui-corner-all">
			  <div class="op_blank"></div>
			  <label class="labelForm op_textMenu" style="float:left; width:120px;">Beginning</label>
			  <input type="text" class="inputForm op_inputMenu op_textMenu" style="float:right; width:170px;" name="ot_dated" id="ot_dated" value="" class="text ui-widget-content ui-corner-all">
			  <div class="op_blank"></div>
			  <label class="labelForm op_textMenu" style="float:left; width:120px;">End</label>
			  <input type="text" class="inputForm op_inputMenu op_textMenu" style="float:right; width:170px;" name="ot_datef" id="ot_datef" value="" class="text ui-widget-content ui-corner-all">
			  <div class="op_blank"></div>
			  <label class="labelForm op_textMenu" style="float:left; width:120px;">Progression</label>
			  <input type="number" class="inputForm op_inputMenu op_textMenu" style="float:right; width:170px;" name="ot_avancement" id="ot_avancement" value="" min="0" max="100" class="text ui-widget-content ui-corner-all"><p class="op_textMenu" style="float:left; padding:6px 0px; position:relative; left:176px;">%</p>
			  <div style="display:none;" class="op_blank"></div>
			  <label class="labelForm op_textMenu" style="display:none; float:left; width:120px;">Person in charge</label>
			  <input type="text" class="inputForm op_inputMenu op_textMenu" style="display:none; float:right; width:170px;" name="ot_responsable" id="ot_responsable" value="" class="text ui-widget-content ui-corner-all">
			  <div class="op_blank"></div>
			  <label class="labelForm op_textMenu" style="float:left; width:120px;">Resources</label>
			  <input list="list_ressources" class="inputForm op_inputMenu op_textMenu" style="float:right; width:170px;" name="ot_ressources" id="ot_ressources" value="" class="text ui-widget-content ui-corner-all">
			  <datalist id="list_ressources">
				<option value="Project management">
				<option value="Development">
				<option value="UX Designer">
				<option value="Design">
				<option value="Integration">
				<option value="Marketing">
				<option value="Communication">
			  </datalist>
			  <div class="op_blank"></div>
			  <label style="display:block; float:left; width:120px;" class="labelForm op_textMenu">Description</label>
			  <textarea class="op_inputMenu op_textMenu" rows="4" cols="29" style="resize:none; text-align:left; float:right; display:block; height:auto;" name="ot_descriptif" id="ot_descriptif" form="ot_formTask"></textarea>
		 
			  <!-- Allow form submission with keyboard without duplicating the dialog button -->
			  <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</fieldset>
		  </form>
		</div>';
		
		/*$resultatAffiche .='
		<div id="dialog-formPw" class="op_listMenu op_textMenu" title="Project password">
		  <form id="ot_formPw">
			<fieldset>
			  <label class="labelForm op_textMenu" style="float:left; width:120px;">Password</label>
			  <input type="text" class="inputForm op_inputMenu op_textMenu" style="float:right; width:170px;" name="ot_pw" id="ot_pw" value="" class="text ui-widget-content ui-corner-all">
			  <label class="labelForm op_textMenu" id="ot_pwmsg" style="float:right; width:200px; color:#FF8964; display:none; text-align:right;">Wrong password</label>
			  
			  <!-- Allow form submission with keyboard without duplicating the dialog button -->
			  <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</fieldset>
		  </form>
		</div>';*/
		
		
		return $resultatAffiche;
}

/* Ajax */
add_action('wp', 'marguerite_ajax_init');
add_action('wp_ajax_marguerite_ajax', 'marguerite_ajax_process');
add_action('wp_ajax_nopriv_marguerite_ajax', 'marguerite_ajax_process');

/*add_action('wp_ajax_marguerite_ajaxwp', 'marguerite_ajaxwp_process');
add_action('wp_ajax_nopriv_marguerite_ajaxwp', 'marguerite_ajaxwp_process');*/

function marguerite_ajax_init(){
	wp_register_script('margueriteAjax', plugins_url('js/margueriteAjax.js', __FILE__), array('jquery'));
	wp_enqueue_script( 'my-plugin1', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js', array('jquery'));
	wp_enqueue_script('custom-script',plugins_url('js/jquery.easypiechart.js', __FILE__), array('jquery'));
	wp_register_style( 'my-plugin3', plugins_url('opesproject_wplugin/Css/marguerite.css' ) );
	
	wp_enqueue_script('margueriteAjax');
	wp_enqueue_style('my-plugin3');
	
	wp_register_style( 'custom_wp_admin_css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'custom_wp_admin_css' );
		
	wp_register_script( 'custom_wp_admin_script', '//code.jquery.com/ui/1.11.4/jquery-ui.js' );
    wp_enqueue_script('custom_wp_admin_script' );
	
	wp_register_script('op_jqueryList_script', plugins_url('/js/jquery.nestable.js', __FILE__), array('jquery'));
	wp_enqueue_script('op_jqueryList_script' );
	
	wp_register_style( 'op_jqueryList_css', plugins_url('opesproject_wplugin/Css/nestable.css'));
    wp_enqueue_style( 'op_jqueryList_css' );
	
	/*wp_register_script('margueriteAjaxAdmin', plugins_url('/js/margueriteAjaxAdmin.js', __FILE__), array('jquery'));
	wp_enqueue_script('margueriteAjaxAdmin' );*/
	
	/*wp_register_script('margueriteAjaxGantt', 'https://www.google.com/jsapi', array('jquery'));
	wp_enqueue_script('margueriteAjaxGantt' );*/
}


/* Administration */
add_action('admin_menu', 'opesproject_admin_menu');
add_action('admin_init', 'opesproject_admin_init');
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_script' );

function load_custom_wp_admin_script() {
		wp_register_style( 'custom_wp_admin_css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'custom_wp_admin_css' );
		
		wp_register_script( 'custom_wp_admin_script', '//code.jquery.com/ui/1.11.4/jquery-ui.js' );
        wp_enqueue_script('custom_wp_admin_script' );
		
		wp_register_script('margueriteAjaxAdmin', plugins_url('/js/margueriteAjaxAdmin.js', __FILE__), array('jquery'));
		wp_enqueue_script('margueriteAjaxAdmin' );
}

/* -- Admin Menu -- */
function opesproject_admin_menu(){
	add_menu_page('Opes Project Options', 'Opes Project', 'manage_options', 'opesproject', 'opesproject_admin_options');
}
function opesproject_admin_options(){
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Opes Project options</h2>
		<form action="options.php" method="post">
			<?php settings_fields('opesproject-group'); ?>
			<?php @do_settings_fields('opesproject-group'); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="opesproject_dashboard_title">Dashboard Title</label></th>
					<td>
						<input type="text" name="opesproject_dashboard_title" id="opesproject_dashboard_title" value="<?php echo get_option('opesproject_dashboard_title'); ?>" />
						<br /><small>le titre à afficher</small>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="opesproject_test">Test</label></th>
					<td>
						<div name="opesproject_test" id="opesproject_test" value="cool de ouf">sisi c'est ouf !</div>
					</td>
				</tr>
			</table>
			<?php @submit_button(); ?>
		</form>
	</div>
	<?php
}
/* -- Admin register setting -- */
function opesproject_admin_init(){
	register_setting('opesproject-group', 'opesproject_dashboard_title');
	register_setting('opesproject-group', 'opesproject_test');
}

/* Projets Admin */

add_action( 'init', 'codex_opesproject_init' );
add_action('add_meta_boxes', 'add_meta_infos');
add_action('save_post', 'save_details_projects');
add_filter('single_template', 'my_custom_template');
function my_custom_template($single) {
    global $wp_query, $post;

	/* Checks for single template by post type */
	if ($post->post_type == "opesproject"){
		$single = dirname( __FILE__ ) . '/single-opesproject.php';;
	}
	return $single;
}
include_once plugin_dir_path(__FILE__)."includes/op_projects.php";

/* Projets Tasks */
add_action( 'init', 'codex_opestasks_init' );
add_action('add_meta_boxes', 'add_meta_tasks');
add_action('save_post', 'save_details_tasks');
include_once plugin_dir_path(__FILE__)."includes/op_tasks.php";

add_action( 'wp_ajax_optasks_add', 'optasks_add_callback' );
add_action( 'wp_ajax_nopriv_optasks_add', 'optasks_add_callback' );
function optasks_add_callback(){
	$nomTask = $_POST["nomTask"];
	/* Ajout d'une tache */
				/*$add_opestask = array(
				  'post_title'    => $nomTask,
				  'post_status'   => 'publish',
				  'post_type'     => 'opestasks',
				);
				wp_insert_post($add_opestask);
				
				$argsTask = array('post_type' => 'opestasks', 'author' => get_current_user_id());
				$loopTask = new WP_Query( $argsTask );
				$x = 0;
				while ($loopTask->have_posts()) : $loopTask->the_post();
					if($x == 0){
						$idTask = $post->ID;
					}
					$x++;
				endwhile;
				
				echo $nomTask;*/
}


//add_action('admin_init_roles','opesproject_add_role_caps',999);
function opesproject_add_role_caps() {

	// Add the roles you'd like to administer the custom post types
	$roles = array('opesproject','subscriber','administrator');
		
	// Loop through each role and assign capabilities
	foreach($roles as $the_role) { 

		$role = get_role($the_role);
			
	    $role->add_cap( 'read' );
	    $role->add_cap( 'read_opesproject');
	    $role->add_cap( 'read_private_opesprojects' );
	    $role->add_cap( 'edit_opesproject' );
	    $role->add_cap( 'edit_opesprojects' );
		$role->add_cap( 'edit_private_opesprojects' );
	    $role->add_cap( 'edit_others_opesprojects' );
	    $role->add_cap( 'edit_published_opesprojects' );
	    $role->add_cap( 'publish_opesprojects' );
	    $role->add_cap( 'delete_others_opesprojects' );
	    $role->add_cap( 'delete_private_opesprojects' );
	    $role->add_cap( 'delete_published_opesprojects' );
	}
}


/* Ajax suite */

function marguerite_ajax_process_test(){
	/*$resultat = "truc";
		//Define your custom post type name in the arguments
		$args = array('post_type' => 'opesproject', 'posts_per_page' => 20);
		//Define the loop based on arguments
		$loop = new WP_Query( $args );
		//Display the contents
		global $post;
		$tDonnees2 = array(array('N°', 'Actif', 'Type', 'Nom', 'Duree', 'Debut', 'Fin', 'Parent', 'Avancement', 'Infos'));
		while ( $loop->have_posts() ) {
			$loop->the_post();			
			$tTemp2 = array($post->ID, get_post_meta($post->ID, 'actif', true), get_post_meta($post->ID, 'type', true), get_post_meta($post->ID, 'nom', true), get_post_meta($post->ID, 'duree', true), get_post_meta($post->ID, 'debut', true), get_post_meta($post->ID, 'fin', true), get_post_meta($post->ID, 'parent', true), get_post_meta($post->ID, 'avancement', true), $post->post_content);
			array_push($tDonnees2, $tTemp2);
		}
		wp_reset_postdata();*/
		echo 'ok';
		echo get_the_ID();
	echo 'ok';
}
/*
function afficherProjet($projectAffichage, $projectTache){
	displayProject($projectAffichage, $projectTache);
}*/

function marguerite_ajax_process(){
	$idProject = $_POST["idProject"];
	$typeAffichage = $_POST["typeAffichage"];
	$tacheActuelle = $_POST["tacheActuelle"];
	displayProject($idProject, $typeAffichage, $tacheActuelle);
}

function marguerite_ajaxwp_process(){
	$idProject = $_POST["idProject"];
	//Display wp
	$queried_post = get_post($idProject);
	$wp = $queried_post->post_title;
	
	echo 'test';
}

function displayProject($idProject, $projectAffichage, $projectTache){
	$projectId = $idProject;
	$typeAffichage = $projectAffichage;
	$tacheActuelle = $projectTache;
	$tDonnees2 = array(array('N°', 'Actif', 'Type', 'Nom', 'Duree', 'Debut', 'Fin', 'Parent', 'Avancement', 'Infos', 'Hierarchie', 'Ressources'));
	
	/* array('N°', 'Actif', 'Type', 'Nom', 'Duree', 'Debut', 'Fin', 'Parent', 'Avancement', 'Infos', 'Dependances', 'Ressources', 'Etat', 'Diff depart', 'Diff fin', 'Sous taches', 'Sous taches 100%', 'Sous taches warn') */
	$tDonneesAffichage = array();
	$tDonneesAffichageN2 = array();
	
	global $post;
	$nomProjet = 'Tous projets';
	$avProjet = 50;
	$debutProjet = 'date début';
	$jourProjet = 'date du jour';
	$finProjet = 'date de fin';
	
	/*  Récupération des données */
		if($projectId=='0'){
			//Define your custom post type name in the arguments
			$args = array('post_type' => 'Tache', 'posts_per_page' => 20);
			//Define the loop based on arguments
			$loop = new WP_Query( $args );
			
			//Display the contents
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$tTemp2 = array($post->ID, get_post_meta($post->ID, 'actif', true), get_post_meta($post->ID, 'type', true), get_post_meta($post->ID, 'nom', true), get_post_meta($post->ID, 'duree', true), get_post_meta($post->ID, 'debut', true), get_post_meta($post->ID, 'fin', true), get_post_meta($post->ID, 'parent', true), get_post_meta($post->ID, 'avancement', true), $post->post_content);
				array_push($tDonnees2, $tTemp2);
			}
			wp_reset_postdata();
		}else{
		
			$queried_post = get_post($projectId);
			//$idProject = $queried_post->ID;
			$nomProjet = $queried_post->post_title;
			$avProjet = '12';
			$debutProjet = date('d/m/Y');
			$jourProjet = date('d/m/Y');
			$finProjet = date('d/m/Y');

			$op_order = get_post_meta($projectId, 'op_order', true);
			$op_order = str_replace("[","",$op_order);
			$op_order = str_replace("]","",$op_order);
			$op_order = preg_split('/,/', $op_order, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			
			//Display tasks
			$args = array('post_type' => 'opestasks', 'posts_per_page' => -1);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) {
				$loop->the_post();
				//$tTemp2 = array($post->ID, get_post_meta($post->ID, 'actif', true), get_post_meta($post->ID, 'type', true), get_post_meta($post->ID, 'nom', true), get_post_meta($post->ID, 'duree', true), get_post_meta($post->ID, 'debut', true), get_post_meta($post->ID, 'fin', true), get_post_meta($post->ID, 'parent', true), get_post_meta($post->ID, 'avancement', true), $post->post_content);
				//array_push($tDonnees2, $tTemp2);
				if(get_post_meta($post->ID, 'ot_project', true) == $projectId){
					$tTemp2 = array($post->ID, "Actif", "Tache", $post->post_title, "wait", get_post_meta($post->ID, 'ot_dated', true), get_post_meta($post->ID, 'ot_datef', true), get_post_meta($post->ID, 'ot_precedant', true), get_post_meta($post->ID, 'ot_avancement', true), $post->post_content, get_post_meta($post->ID, 'ot_hierarchie', true), get_post_meta($post->ID, 'ot_ressources', true));
					array_push($tDonnees2, $tTemp2);
				}
			}
			wp_reset_postdata();
			
			
			$arrayTempTask = array(array('N°', 'Actif', 'Type', 'Nom', 'Duree', 'Debut', 'Fin', 'Parent', 'Avancement', 'Infos', 'Hierarchie', 'Ressources'));
			
			for ($bg=0; $bg<count($op_order); $bg++){
				for ($bf=1; $bf<count($tDonnees2); $bf++){
					if($op_order[$bg] == $tDonnees2[$bf][0]){
						array_push($arrayTempTask, $tDonnees2[$bf]);
					}
				}
			}
			$tDonnees2 = $arrayTempTask;
			//$tDonnees2 = array_reverse($tDonnees2);
			
			/*$input1 = get_post_meta($projectId, 'op_gantt', true);
			
			
			$input1 = explode("|",$input1);
			if(count($input1) > 0){
				for ($nn=1; $nn<count($input1); $nn++) {
					$input2 = explode(",",$input1[$nn]);
					
					$tTemp2 = array($input2[0], "Actif", "Tache", $input2[1], "wait", $input2[2], $input2[3], $input2[7], $input2[5], $input2[11], $input2[8], $input2[10]);
					array_push($tDonnees2, $tTemp2);
				}
			}*/
			
			/*$input2 = preg_split('/<[^>]*[^\/]>/i', $input1, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			
			
			$input2 = array_slice($input2, 11);
			
			$input3 = array();
			for($bv=0; $bv<(count($input2)/21); $bv++){
				$ligne = $bv*22;
				
				if($input2[$ligne+19] != ''){$tempType = $input2[$ligne+19];}else{$tempType = 'Tache';}
				if($input2[$ligne+12] != ''){$tempNom = $input2[$ligne+12];}else{$tempNom = ' ';}
				if($input2[$ligne+17] != ''){$tempParent = $input2[$ligne+17];}else{$tempParent = '0';}
				if($input2[$ligne+16] != ''){$tempAvancement = $input2[$ligne+16];}else{$tempAvancement = '0';}
				if($input2[$ligne+13] != ''){$tempInfos = $input2[$ligne+13];}else{$tempInfos = ' ';}
				if($input2[$ligne+21] == ''){$tempRessources = 'nul';}else{$tempRessources = $input2[$ligne+21];}
				
				$tTemp2 = array($input2[$ligne+11], $input2[$ligne+20], $input2[$ligne+19], $input2[$ligne+12], 'wait', $input2[$ligne+14], $input2[$ligne+15], $input2[$ligne+17], $input2[$ligne+16], $input2[$ligne+13], $input2[$ligne+18], $tempRessources);
				array_push($tDonnees2, $tTemp2);
			}*/
		}
		
		
		/* -- Creation des tableaux à afficher -- */
		$dateJourTemp = explode("/", $jourProjet);
		$dateJourProjet = new DateTime($dateJourTemp[2].'-'.$dateJourTemp[1].'-'.$dateJourTemp[0]);
		
		$dateDebutProjetTemp = explode("/", $debutProjet);
		$dateDebutProjet = new DateTime($dateDebutProjetTemp[2].'-'.$dateDebutProjetTemp[1].'-'.$dateDebutProjetTemp[0]);
		$dateFinProjetTemp = explode("/", $finProjet);
		$dateFinProjet = new DateTime($dateFinProjetTemp[2].'-'.$dateFinProjetTemp[1].'-'.$dateFinProjetTemp[0]);
		
		
		echo '<div style="display:none;" id="op_infosGantt">'; /* Gantt */
		for ($ba=1; $ba<count($tDonnees2); $ba++){
			if(($tacheActuelle == 0 && $tDonnees2[$ba][7] == $tacheActuelle) || ($tacheActuelle != 0 && $tDonnees2[$ba][0] == $tacheActuelle) || ($tacheActuelle != 0 && $tDonnees2[$ba][7] == $tacheActuelle) || ($typeAffichage == 'gantt')){
				/* Gantt */
				echo '|'.$tDonnees2[$ba][0].','.$tDonnees2[$ba][3].','.$tDonnees2[$ba][5].','.$tDonnees2[$ba][6].','.$tDonnees2[$ba][8].','.$tDonnees2[$ba][7].','.$tDonnees2[$ba][11].',';
				
				/* Calcul Durée */
				$dateDebutTemp = explode("/", $tDonnees2[$ba][5]);
				$dateDebut = new DateTime($dateDebutTemp[2].'-'.$dateDebutTemp[1].'-'.$dateDebutTemp[0]);
				$dateFinTemp = explode("/", $tDonnees2[$ba][6]);
				$dateFin = new DateTime($dateFinTemp[2].'-'.$dateFinTemp[1].'-'.$dateFinTemp[0]);
				$diff = $dateDebut->diff($dateFin);
				
				/* Affichage temps */
				if($dateDebut<$dateDebutProjet){
					$dateDebutProjet = $dateDebut;
				}
				if($dateFin>$dateFinProjet){
					$dateFinProjet = $dateFin;
				}
				
				/* Modification des données */
				$tDonnees2[$ba][4] = ($diff->days) +1;
				$tDonnees2[$ba][5] = $dateDebut;
				$tDonnees2[$ba][6] = $dateFin;
				
				/* Calcul Diff depart + fin */
				$diffDepart = $dateDebut->diff($dateJourProjet);
				$diffFin = $dateFin->diff($dateJourProjet);
				
				/* Calcul Etat de la tache */
				$etatTache = 0;
				$etatTacheGantt = "en attente";
				if($tDonnees2[$ba][8]!=100){
					if($dateJourProjet >= $dateDebut){
						if($dateJourProjet < $dateFin){
							$etatTache = 2;
							$etatTacheGantt = 'en cours';
						}else{
							if($dateJourProjet == $dateFin){
								$etatTache = 3;
								$etatTacheGantt = 'en cours';
							}else{
								if($dateJourProjet > $dateFin){
									$etatTache = 4;
									$etatTacheGantt = 'en retard';
								}
							}
						}
					}
				}else{
					$etatTache = 1;
					$etatTacheGantt = 'achevé';
				}
				/* Gantt */
				echo $etatTache;
				
				/* Calcul des sous taches */
				$nbrSsTache = 0;
				$nbrSsTacheFini = 0;
				for ($bb=1; $bb<count($tDonnees2); $bb++){
					if($tDonnees2[$bb][7] == $tDonnees2[$ba][0]){
						$nbrSsTache += 1;
						if($tDonnees2[$bb][8] == 100){
							$nbrSsTacheFini += 1;
						}
					}
				}
				
				/*  Creation du tableau à afficher */
				array_push($tDonnees2[$ba], $etatTache); /* 12 */
				array_push($tDonnees2[$ba], ($diffDepart->days) +1); /* 13 */
				array_push($tDonnees2[$ba], ($diffFin->days) +1); /* 14 */
				array_push($tDonnees2[$ba], $nbrSsTache); /* 15 */
				array_push($tDonnees2[$ba], $nbrSsTacheFini); /* 16 */
				array_push($tDonnees2[$ba], ''); /* 17 */
				
				if(($tacheActuelle == '0' && $tDonnees2[$ba][7] == $tacheActuelle) || ($tacheActuelle != '0' && $tDonnees2[$ba][0] == $tacheActuelle) || ($typeAffichage == 'gantt')){
					array_push($tDonneesAffichage, $tDonnees2[$ba]);
				}else{
					array_push($tDonneesAffichageN2, $tDonnees2[$ba]);
				}
				
			}
		}
		echo '</div>';
		
		/* Selection de la tache actuelle dans le tableau */
		for ($e=1; $e<count($tDonnees2); $e++){
			if($tDonnees2[$e][0] == $tacheActuelle){
				$tacheTableauB = $e;
			}
		}
		
		/* Affichage Barre de progression */
		if($tacheActuelle == 0){
			//echo'<div class="opProgressCanvas" style="padding-top:5px; top:56px;"><progress class="opProgress" value="'.$avProjet.'" max="100"></progress><div class="opProgressText">'.$avProjet.'%</div></div>';
		}else{
			//echo'<div class="opProgressCanvas" style="padding-top:5px; top:56px;"><progress class="opProgress" value="'.($tDonnees2[$tacheTableauB][8]*100).'" max="100"></progress><div class="opProgressText">'.($tDonnees2[$tacheTableauB][8]*100).'%</div></div>';
		}
		
		/* Affichage Temps */
		$tempsProjet = $dateDebutProjet->diff($dateFinProjet);
		$diffDebutProjet = $dateDebutProjet->diff($dateJourProjet);
		
		if($tacheActuelle == 0){
			echo'<div class="opProgressCanvas" style="padding:10px 20px 0px 20px; top:0px;"><div class="opProgress" value="'.$diffDebutProjet->days.'" max="'.$tempsProjet->days.'"><div class="opProgressBar" style="width:'.(($diffDebutProjet->days/$tempsProjet->days)*100).'%;"></div></div><div class="opProgressTempsDebut">'.$dateDebutProjet->format('j M y').'</div><div class="opProgressTempsFin">'.$dateFinProjet->format('j M y').'</div></div>';
		}else{
			if($tDonneesAffichage[0][12] != 0){
				$tpsValue = $tDonneesAffichage[0][13];
				$couleurTache = '#1fbba6';
				if($tDonneesAffichage[0][8]==100 || $tDonneesAffichage[0][12] == 4){
					$tpsValue = $tDonneesAffichage[0][4];
					if($tDonneesAffichage[0][12] == 4){
						$couleurTache = '#FF8964';
					}
				}
			}else{
				$tpsValue = 0;
				$couleurTache = '#9ea7b3';
			}
			echo'<div class="opProgressCanvas" style="padding:10px 20px 0px 20px; top:0px;"><div class="opProgress" value="'.$tDonneesAffichage[0][13].'" max="'.$tDonneesAffichage[0][4].'"><div class="opProgressBar" style="background-color:'.$couleurTache.'; width:'.(($tpsValue/$tDonneesAffichage[0][4])*100).'%;"></div></div><div class="opProgressTempsDebut">'.$tDonneesAffichage[0][5]->format('j M y').'</div><div class="opProgressTempsFin">'.$tDonneesAffichage[0][6]->format('j M y').'</div></div>';
		}
		
		/* Affichage Fil d'Ariane */
		if($tacheActuelle == -1){
		echo '<div class="opProgressCanvas" style="color:#5e6d81; font-size:16px; padding:10px 0; height:64px; top:40px;">';
		}else{
		echo '<div class="opProgressCanvas" style="color:#5e6d81; font-size:16px; padding:10px 0; height:64px; top:40px;">';
		}
			echo '<div style="margin-left:3%; float:left; text-decoration:underline; cursor:pointer; margin-top:2px; width:8%; overflow:hidden; text-overflow:ellipsis; line-height:20px; height:45px; min-width:65px; max-width:120px;" onclick="testonsb(\''.$projectId.'\', \'0\', \''.$typeAffichage.'\')">'.$nomProjet.'</div><div style="margin-left:1%; float:left;"> ></div>';
			if($tacheActuelle != 0){
				$tacheAriane = $tacheTableauB;
				$tArianeTemp = array();
				while ($tDonnees2[$tacheAriane][7] != 0){
					for ($k=1; $k<count($tDonnees2); $k++){
						if($tDonnees2[$k][0] == $tDonnees2[$tacheAriane][7]){
							$tacheAriane = $k;
							array_push($tArianeTemp, $tDonnees2[$tacheAriane]);
							//echo '<div style="margin-left:2%;" onclick="testonsb('.($tDonneesTemp[$tacheAriane][0]).', \'marguerite\')" class="qbutton">'.$tDonneesTemp[$tacheAriane][3].'</div>';
						}
					}
				}
				$kt = count($tArianeTemp);
				$tDonneesAffichageAriane = array();
				while ($kt != 0){
					$kt --;
					echo '<div style="margin-left:1%; float:left; text-decoration:underline; cursor:pointer; margin-top:2px; width:8%; overflow:hidden; text-overflow:ellipsis; line-height:20px; height:45px; min-width:65px; max-width:120px;" onclick="testonsb(\''.$projectId.'\', \''.($tArianeTemp[$kt][0]).'\', \''.$typeAffichage.'\')">'.$tArianeTemp[$kt][3].'</div><div style="margin-left:1%; float:left;"> ></div>';
				}
				
				echo '<div style="margin-left:1%; float:left; text-decoration:underline; cursor:pointer; margin-top:2px; width:8%; overflow:hidden; text-overflow:ellipsis; line-height:20px; height:45px; min-width:65px; max-width:120px;" onclick="testonsb(\''.$projectId.'\', \''.$tDonnees2[$tacheTableauB][0].'\', \''.$typeAffichage.'\')">'.$tDonnees2[$tacheTableauB][3].'</div><div style="margin-left:1%; float:left;"> ></div>';
			}
		echo '</div>';
			
		/* Affichage menu */
		/*print_r('<div style="width:100%; margin-top:0px; background-color:#eee; float:left; min-height:35px; text-align:center;">');
			print_r('<div style="margin-left:0%;" onclick="testonsb('.$projectId.', '.$tacheActuelle.', \'marguerite\')" class="qbutton">Affichage Marguerite</div>');
			print_r('<div style="margin-left:2%;" onclick="testonsb('.$projectId.', '.$tacheActuelle.', \'gantt\')" class="qbutton">Affichage Gantt</div>');
		print_r('</div>');*/
		
		/* Ajout des données dans un tableau */
		/*foreach ($lignes as $colonnes) {
			$tTemp = array();
			for ($i=0; $i<$colonnes->getElementsByTagName('Data')->length; $i++){
				array_push($tTemp, $colonnes->getElementsByTagName('Data')->item($i)->nodeValue);
			}
			array_push($tDonnees, $tTemp);
			//echo($tDonnees[8][4]);
		}*/
		
		/* Affiche de la Marguerite */
		function affichageMarguerite($idTemp, $tacheTemp, $tDonneesTemp, $tDonneesTempN2){
			
			$jourProjet = date('d/m/Y');
			$dateDuJourTemp = explode("/", $jourProjet);
			$dateDuJour = new DateTime($dateDuJourTemp[2].'-'.$dateDuJourTemp[1].'-'.$dateDuJourTemp[0]);
			
			$tDonneesChart = $tDonneesTemp;
			$tDonneesChartN2 = $tDonneesTempN2;
			
			/* Selection de la tache actuelle dans le tableau */
			/*for ($e=1; $e<count($tDonneesTemp); $e++){
				if($tDonneesTemp[$e][0] == $tacheTemp){
					$tacheTableau = $e;
				}
			}*/
							/* Creation des tableaux à afficher */
							/*
							if($tacheTemp == '0'){
									for ($a=1; $a<count($tDonneesTemp); $a++){
											if($tDonneesTemp[$a][7] == $tacheTemp){
													array_push($tDonneesChart, $tDonneesTemp[$a]);
											}
									}
							}else{
									for ($e=1; $e<count($tDonneesTemp); $e++){
										if($tDonneesTemp[$e][0] == $tacheTemp){
											array_push($tDonneesChart, $tDonneesTemp[$e]);
										}
									}*/
									/* Changement */ 
									/*
									//array_push($tDonneesChart, $tDonneesTemp[$tacheTemp]);
									//if($tacheTemp<(count($tDonneesTemp))-1){
											//$b = $tacheTemp+1;
											//while ($tDonneesTemp[$b][7] > $tDonneesTemp[$tacheTemp][7]){
											for ($b=1; $b<count($tDonneesTemp); $b++){
													if($tDonneesTemp[$b][7] == $tacheTemp){
														array_push($tDonneesChartN2, $tDonneesTemp[$b]);
													}
													// $b++;
											}
									//}
							}*/
							
							if($tacheTemp == 0){
								print_r('<div id="demoCharts" style="width:100%; position:relative; z-index:50; text-align:center; top:0px; margin:0 auto; max-width:1200px; opacity:0; transition: all 0.4s ease;">');
							}else{
								print_r('<div id="demoCharts" style="width:100%; position:relative; z-index:50; text-align:center; top:0px; margin:0 auto; max-width:1200px; opacity:0; transition: all 0.4s ease">');
							}
									/* Affichage infos tâche */
									/*if($tacheTemp != 0){
										$infosDebut = explode("/", $tDonneesTemp[$tacheTableau][5]);
										$infosDateDebut = new DateTime($infosDebut[2].'-'.$infosDebut[1].'-'.$infosDebut[0]);
										$infosFin = explode("/", $tDonneesTemp[$tacheTableau][6]);
										$infosDateFin = new DateTime($infosFin[2].'-'.$infosFin[1].'-'.$infosFin[0]);
										$infosDateDiff = $infosDateDebut->diff($infosDateFin);
										$infosNombreJours = ($infosDateDiff->days)+1;
										echo '<div style="width:25%; text-align:right; right:4%; position:absolute; background-color:#eee; top: 40px;">';
											echo '<strong>Informations sur la tâche</strong>';
											echo '<br />';
											echo '<strong>durée :</strong> '.$infosNombreJours.' jours';
											echo '<br />';
											echo '<strong>début :</strong> '.date_format($infosDateDebut, 'l j F Y');
											echo '<br />';
											echo '<strong>fin :</strong> '.date_format($infosDateFin, 'l j F Y');
											echo '<br />';
											echo $tDonneesTemp[$tacheTableau][9];
										echo '</div>';
									}*/
									/* Affichage des taches de niveau 0 */
									print_r('<div style="width:100%; text-align:center; margin-bottom:45px;">');
											for ($q=0; $q<count($tDonneesChart); $q++){
												$pourcentageLargeur = 100/count($tDonneesChart);
													
													if($tDonneesChart[$q][4] != 0){
														$etatTache2 = "days: 0/".($tDonneesChart[$q][4]);
													}
													$etatTacheCouleur = "z";
													
													if($tDonneesChart[$q][12] != 0){
														if($tDonneesChart[$q][8]!=100){
															//$diffFin = $dateFin->diff($dateDuJour);
																if($tDonneesChart[$q][12] == 2){
																	$etatTache = $tDonneesChart[$q][14]." jours restants";
																	$etatTache2 = "days: ".($tDonneesChart[$q][13])."/".($tDonneesChart[$q][4]);
																	$pourcentageTemp = (($tDonneesChart[$q][13])/($tDonneesChart[$q][4]))*100;
																	$etatTacheTemps = $tDonneesChart[$q][13];
																	$etatTacheTempsUtilise = $tDonneesChart[$q][4];
																	$etatTacheCouleur = "a";
																	$etatJalon = "a";
																	$etatJalonTexte = "GO";
																}else{
																	if($tDonneesChart[$q][12] == 3){
																		$etatTache = "Dernier jour";
																		$etatTache2 = "days: ".($tDonneesChart[$q][13])."/".($tDonneesChart[$q][4]);
																		$pourcentageTemp = (($tDonneesChart[$q][13])/($tDonneesChart[$q][4]))*100;
																		$etatTacheTemps = $tDonneesChart[$q][4];
																		$etatTacheTempsUtilise = $etatTacheTemps;
																		$etatTacheCouleur = "b";
																		$etatJalon = "b";
																		$etatJalonTexte = "Waiting";
																	}else{
																		$etatTache = $tDonneesChart[$q][14]." jours de retard";
																		$etatTache2 = "days: ".($tDonneesChart[$q][13])."/".($tDonneesChart[$q][4]);
																		$pourcentageTemp = (($tDonneesChart[$q][13])/($tDonneesChart[$q][4]))*100;
																		$etatTacheTemps = $tDonneesChart[$q][13];
																		$etatTacheTempsUtilise = $tDonneesChart[$q][13];
																		$etatTacheCouleur = "c";
																		$etatJalon = "c";
																		$etatJalonTexte = "NoGO";
																	}
																}
														}else{
															$etatTache = "Tâche achevée";
															$pourcentageTemp = 100;
															$etatTacheTemps = 100;
															$etatTacheTempsUtilise = 100;
															$etatTache2 = "days: ".($tDonneesChart[$q][4])."/".($tDonneesChart[$q][4]);
															$etatTacheCouleur = "a";
															$etatJalon = "a";
															$etatJalonTexte = "GO";
														}
													}else{
														$etatTache = "wait";
														$pourcentageTemp = 0;
														$etatTacheTemps = 0;
														$etatTacheTempsUtilise = 0;
														$etatTache2 = "days: 0/".($tDonneesChart[$q][4]);
														$etatTacheCouleur = "z";
														$etatJalon = "z";
														$etatJalonTexte = "Wait";
													}
													if($tDonneesChart[$q][2]!="Jalon"){
														if($tacheTemp==0){
															print_r('<div class="chart chart'.$etatTacheCouleur.'" data-percent="'.($tDonneesChart[$q][8]).'"  onclick="testonsb('.$idTemp.', '.$tDonneesChart[$q][0].', \'marguerite\')" style="cursor:pointer; background-image: url(\''.plugins_url('opesproject_wplugin/images/taskBackground.png').'\'); background-repeat:no-repeat; background-position: 50%; background-size:91px; min-width:250px; background-color:#blue; width:'.$pourcentageLargeur.'%;">');
														}else{
															print_r('<div class="chart chart'.$etatTacheCouleur.'" data-percent="'.($tDonneesChart[$q][8]).'"  onclick="manageOverlay(\'b\')" style="cursor:pointer; background-image: url(\''.plugins_url('opesproject_wplugin/images/taskBackground.png').'\'); background-repeat:no-repeat; background-position: 50% 10px; background-size:91px; min-width:250px; background-color:#blue; width:'.$pourcentageLargeur.'%;">');
														}
																print_r('<div class="percentColor'.$etatTacheCouleur.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:-5px; font-size:11px; margin-left:50%; padding-left:60px; line-height:12px; color:#9ea7b3; font-family:open sans;">'.$etatTache2.'<br /><!--<span style="font-size:10px;">'.$etatTache2.'</span>--><progress class="progressBar'.$etatTacheCouleur.'" style="-webkit-progress-value {background: #afa;}" value="'.$etatTacheTemps.'" max="'.$etatTacheTempsUtilise.'">'.($tDonneesChart[$q][8]).'%</progress></div>');
																if($tDonneesChart[$q][15] != 0){
																	print_r('<div class="percentColor'.$etatTacheCouleur.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:72px; font-size:11px; margin-left:50%; padding-left:60px; line-height:12px; color:#9ea7b3; font-family:open sans;">tasks: '.$tDonneesChart[$q][16].'/'.$tDonneesChart[$q][15].'<br /><!--<span style="font-size:10px;">Tâches: '.$tDonneesChart[$q][16].'/'.$tDonneesChart[$q][15].'</span>--><progress class="progressBar'.$etatTacheCouleur.'" value="'.$tDonneesChart[$q][16].'" max="'.$tDonneesChart[$q][15].'">'.($tDonneesChart[$q][8]).'%</progress></div>');
																}
																print_r('<div class="percentB">'.($tDonneesChart[$q][8]).'</div>');
																print_r('<div class="tache percentColor'.$etatTacheCouleur.'">'.$tDonneesChart[$q][3].'</div>');
														print_r('</div>');
													}else{
														if($tacheTemp==0){
															print_r('<div class="chart" data-percent="'.($pourcentageTemp).'"  onclick="testonsb('.$idTemp.', '.$tDonneesChart[$q][0].', \'marguerite\')" style="cursor:pointer; min-width:250px; width:'.$pourcentageLargeur.'%; background-image:url(\''.plugins_url('opesproject_wplugin/images/jalonBackground'.$etatJalon.'.png').'\'); background-repeat:no-repeat; background-position:60%; background-size:110px;">');
														}else{
															print_r('<div class="chart" data-percent="'.($pourcentageTemp).'"  onclick="manageOverlay(\'b\')" style="cursor:pointer; min-width:250px; width:'.$pourcentageLargeur.'%; background-image:url(\''.plugins_url('opesproject_wplugin/images/jalonBackground'.$etatJalon.'.png').'\'); background-repeat:no-repeat; background-position:50%; background-size:110px; padding-right:27px;">');
														}
																print_r('<div class="percentColor'.$etatTacheCouleur.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:10px; font-size:12px; margin-left:50%; padding-left:55px; line-height:12px; color:#9ea7b3; font-family:open sans;">Jalon<br /></div>');
																print_r('<div class="percentColor'.$etatTacheCouleur.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:85px; font-size:12px; margin-left:50%; padding-left:55px; line-height:12px; color:#9ea7b3; font-family:open sans; text-transform:lowercase;">'.$tDonneesChart[$q][5]->format('j M Y').'</div>');
																print_r('<div style=" color:#fff; display:inline-block; line-height:110px; z-index:2; padding-top:2px; padding-left:28px; font-size:23px; font-weight:normal; font-family:open sans;" class="percentColor'.$etatTacheCouleur.'">'.$etatJalonTexte.'</div>');
																print_r('<div class="tache percentColor'.$etatTacheCouleur.'">'.$tDonneesChart[$q][3].'</div>');
														print_r('</div>');
													}
											}
									print_r('</div>');

									/* Affichage des taches N2 */
									if(count($tDonneesChartN2) != 0){
											print_r('<!--<div style="color:#D8DBDE; color:white; font-size:16px;">Tâches</div>--><div style="width:100%; text-align:center; border-top: solid 1px #eaedf1; margin-bottom:50px; margin-top:-3px; box-shadow: 0px -20px 30px -22px rgba(0,0,0,0.10); -webkit-box-shadow: 0px -20px 30px -22px rgba(0,0,0,0.10);">');
													print_r('<div style="width:100%; text-align:center; border-bottom: solid 1px #eaedf1; font-size:18px; font-family:Open-sans; padding:3px;">subtasks</div>');
													for ($q=0; $q<count($tDonneesChartN2); $q++){
														$pourcentageLargeur = 100/count($tDonneesChartN2);
														$pourcentagePI = -(($q+1)*(1/(count($tDonneesChartN2)+1)))+1;
														
														if($tDonneesChartN2[$q][12] != 0){
															if($tDonneesChartN2[$q][8]!=100){
																//$diffFinN2 = $dateFinN2->diff($dateDuJour);
																if($tDonneesChartN2[$q][12] == 2){
																	$etatTacheN2 = $tDonneesChartN2[$q][14]." jours restants";
																	$etatTache2N2 = "days: ".($tDonneesChartN2[$q][13])."/".($tDonneesChartN2[$q][4]);
																	$pourcentageTempN2 = (($tDonneesChartN2[$q][13])/($tDonneesChartN2[$q][4]))*100;
																	$etatTacheN2Temps = $tDonneesChartN2[$q][13];
																	$etatTacheN2TempsUtilise = $tDonneesChartN2[$q][4];
																	$etatTacheCouleurN2 = "a";
																	$etatJalonN2 = "a";
																	$etatJalonN2Texte = "GO";
																}else{
																	if($tDonneesChartN2[$q][12] == 3){
																		$etatTacheN2 = "Dernier jour";
																		$etatTache2N2 = "days: ".($tDonneesChartN2[$q][13])."/".($tDonneesChartN2[$q][4]);
																		$pourcentageTempN2 = (($tDonneesChartN2[$q][13])/($tDonneesChartN2[$q][4]))*100;
																		$etatTacheN2Temps = $tDonneesChartN2[$q][4];
																		$etatTacheN2TempsUtilise = $tDonneesChartN2[$q][4];
																		$etatTacheCouleurN2 = "b";
																		$etatJalonN2 = "b";
																		$etatJalonN2Texte = "Waiting";
																	}else{
																		$etatTacheN2 = $diffFinN2->days." jours de retard";
																		$etatTache2N2 = "days: ".($tDonneesChartN2[$q][13])."/".($tDonneesChartN2[$q][4]);
																		$pourcentageTempN2 = (($tDonneesChartN2[$q][4])/($tDonneesChartN2[$q][13]))*100;
																		$etatTacheN2Temps = $tDonneesChartN2[$q][4];
																		$etatTacheN2TempsUtilise = $tDonneesChartN2[$q][4];
																		$etatTacheCouleurN2 = "c";
																		$etatJalonN2 = "c";
																		$etatJalonN2Texte = "NoGO";
																	}
																}
															}else{
																$etatTacheN2 = "Tâche achevée";
																$etatTache2N2 = "days: ".($tDonneesChartN2[$q][4])."/".($tDonneesChartN2[$q][4]);
																$etatTacheN2Temps = 100;
																$pourcentageTempN2 = 100;
																$etatTacheN2TempsUtilise = 100;
																$etatTacheCouleurN2 = "a";
																$etatJalonN2 = "a";
																$etatJalonN2Texte = "GO";
															}
														}else{
															$etatTacheN2 = "Wait";
															$etatTache2N2 = "days: 0/".($tDonneesChartN2[$q][4]);
															$etatTacheN2Temps = 0;
															$pourcentageTempN2 = 0;
															$etatTacheN2TempsUtilise = 0;
															$etatTacheCouleurN2 = "z";
															$etatJalonN2 = "z";
															$etatJalonN2Texte = "Wait";
														}
														if($tDonneesChartN2[$q][2]!="Jalon"){
															print_r('<div class="chartN2 chartN2'.$etatTacheCouleurN2.'" data-percent="'.($tDonneesChartN2[$q][8]).'"  onclick="testonsb('.$idTemp.', '.$tDonneesChartN2[$q][0].', \'marguerite\')" style="cursor:pointer; background-image: url(\''.plugins_url('opesproject_wplugin/images/taskBackground.png').'\'); background-repeat:no-repeat; background-position: 50%; background-size:74px; min-width:180px; background-color:#blue; width:'.$pourcentageLargeur.'%;">');
																print_r('<div class="percentColor'.$etatTacheCouleurN2.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:-15px; font-size:11px; margin-left:50%; padding-left:60px; line-height:12px; color:#9ea7b3; font-family:open sans;">'.$etatTache2N2.'<br /><!--<span style="font-size:10px;">'.$etatTache2N2.'</span>--><progress class="progressBar'.$etatTacheCouleurN2.'" style="-webkit-progress-value {background: #afa;}" value="'.$etatTacheN2Temps.'" max="'.$etatTacheN2TempsUtilise.'">'.($tDonneesChartN2[$q][8]).'%</progress></div>');
																if($tDonneesChartN2[$q][15] != 0){
																	print_r('<div class="percentColor'.$etatTacheCouleurN2.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:50px; font-size:11px; margin-left:50%; padding-left:60px; line-height:12px; color:#9ea7b3; font-family:open sans;">tasks: '.$tDonneesChartN2[$q][16].'/'.$tDonneesChartN2[$q][15].'<br /><!--<span style="font-size:10px;">Tâches: '.$tDonneesChartN2[$q][16].'/'.$tDonneesChartN2[$q][15].'</span>--><progress class="progressBar'.$etatTacheCouleurN2.'" value="'.$tDonneesChartN2[$q][16].'" max="'.$tDonneesChartN2[$q][15].'">'.($tDonneesChartN2[$q][8]).'%</progress></div>');
																}
																print_r('<div class="percentBN2">'.($tDonneesChartN2[$q][8]).'</div>');
																print_r('<div class="tache percentColor'.$etatTacheCouleurN2.'">'.$tDonneesChartN2[$q][3].'</div>');
															print_r('</div>');
															
															//print_r('<div class="chartN2 chartN2'.$etatTacheCouleurN2.'" data-percent="'.$pourcentageTempN2.'" data-percent2="'.($tDonneesChartN2[$q][8]*100).'" onclick="testonsb('.$idTemp.', '.$tDonneesChartN2[$q][0].', \'marguerite\');" style="cursor:pointer; position:relative; /*top:'.((30*sin(M_PI*$pourcentagePI))+20).'%; left:'.((20*cos(M_PI*$pourcentagePI))+48).'%; width:110px;*//* width:100%; min-width:250px; max-width:350px;">');
																	//print_r('<div class="percentColor'.$etatTacheCouleurN2.'" style="font-weight:bold; position:absolute; text-align:center; width:100%; top:-30px; font-size:11px; line-height:12px;">'.$etatTacheN2.'<br /><!--<span style="font-size:10px;">'.$etatTache2N2.'</span>--><progress style="height:8px;" class="proN2, progressBar'.$etatTacheCouleurN2.'" value="'.$etatTacheN2Temps.'" max="'.$etatTacheN2TempsUtilise.'">'.($tDonneesChart[$q][8]*100).'%</progress></div>');
																	//print_r('<div class="percentBN2 percentColor'.$etatTacheCouleurN2.'">'.($tDonneesChartN2[$q][8]*100).'</div>');
																	//print_r('<div class="tacheN2 percentColor'.$etatTacheCouleurN2.'">'.$tDonneesChartN2[$q][3].'</div>');
															//print_r('</div>');
														}else{
															print_r('<div class="chartN2 data-percent="'.$pourcentageTempN2.'" data-percent2="'.($tDonneesChartN2[$q][8]).'" onclick="testonsb('.$idTemp.', '.$tDonneesChartN2[$q][0].', \'marguerite\');" style="cursor:pointer; background-image:url(\''.plugins_url('opesproject_wplugin/images/jalonBackground'.$etatJalonN2.'.png').'\'); background-repeat:no-repeat; background-position:50%; background-size:110px; position:relative; /*top:'.((30*sin(M_PI*$pourcentagePI))+20).'%; left:'.((20*cos(M_PI*$pourcentagePI))+48).'%; width:110px;*//* width:100%; min-width:100px; max-width:180px;">');
																	print_r('<div class="percentColor'.$etatTacheCouleurN2.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:10px; font-size:11px; margin-left:50%; padding-left:55px; line-height:12px; color:#9ea7b3; font-family:open sans;">Jalon</span></div>');
																	print_r('<div class="percentColor'.$etatTacheCouleurN2.'" style="font-weight:normal; position:absolute; text-align:left; width:50%; top:85px; font-size:11px; margin-left:50%; padding-left:55px; line-height:12px; color:#9ea7b3; font-family:open sans; text-transform:lowercase;">'.$tDonneesChartN2[$q][5]->format('j M Y').'</span></div>');
																	print_r('<div style=" color:#fff; display:inline-block; line-height:110px; z-index:2; padding-top:2px; font-size:23px; font-weight:normal; font-family:open sans;" class="percentColor'.$etatTacheCouleur.'">'.$etatJalonTexte.'</div>');
																	print_r('<div class="tache percentColor'.$etatTacheCouleurN2.'">'.$tDonneesChartN2[$q][3].'</div>');
															print_r('</div>');
														}
													}
											print_r('</div>');
									}
									
					print_r('</div>');
					
		}
			
			function affichageGantt($idTemp, $tacheTemp, $tDonneesChart, $dateDebutProjet, $dateFinProjet, $dateJourProjet){
			
			/* Affichage Calendrier */
			$periodeDebut = $dateDebutProjet->format('m');
			$periodeFin = $dateFinProjet->format('m');
			$year = 2015;
			$nbrJoursAffiches = 0;
			$dateDuJourGantt = new DateTime('2015-08-30');
			$tDates = array();
			$date = new DateTime($year.'-'.$periodeDebut.'-01');
			while ($date->format('n') <= $periodeFin){
				$y = $date->format('Y');
				$m = $date->format('n');
				$d = $date->format('j');
				$w = str_replace('0', '7', $date->format('w'));
				$tDates[$y][$m][$d] = $w;
				$date->add(new DateInterval('P1D'));
			}
			
			/* Année */
			/*print_r('<div style="width:100%; text-align:center; padding-top:30px;">');
				print_r($year);
			print_r('</div>');
			print_r('<br />');*/
			
			/* Mois */
			print_r('<div style="width:100%; text-align:center; position:absolute; top:104px;">');
				for($gmu=0; $gmu<count($tDates[$year]); $gmu++){
					$indexgmu = array_keys($tDates[$year]);
					$gmEcartM = 100/count($tDates[$year]);
					print_r('<div style="width:'.$gmEcartM.'%; float:left; color:#5e6d81; background-color:#eaedf1; border-left:1px solid #fff; font-family: open sans; font-size:16px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">');
						//print_r($indexgmu[$gmu]);
						echo date_format(new DateTime('2014-'.$indexgmu[$gmu].'-01'), 'F');
					print_r('</div>');
				}
			print_r('</div>');
			print_r('<br />');
			
			/* Jours */
			print_r('<div style="width:100%; text-align:center; top:128px; position:absolute;">');
				/* Pour chaque mois à afficher */
				for($gju=0; $gju<count($tDates[$year]); $gju++){
					$indexgju = array_keys($tDates[$year]);
					$gjEcartM = 100/count($tDates[$year]);
					print_r('<div style="width:'.$gjEcartM.'%; float:left;">');
					/* Pour chaque jour de chaque mois */
					for($gjy=0; $gjy<count($tDates[$year][$indexgju[$gju]]); $gjy++){
						$nbrJoursAffiches ++;
						$indexgjy = array_keys($tDates[$year][$indexgju[$gju]]);
						$gjEcartJ = 100/count($tDates[$year][$indexgju[$gju]]);
						$jourDuMois = date_format(new DateTime($year.'-'.$indexgju[$gju].'-'.($gjy+1)), 'N');
						$semaineAnnee = date_format(new DateTime($year.'-'.$indexgju[$gju].'-'.($gjy+1)), 'W');
						if($jourDuMois == 1 || $jourDuMois == 2 || $jourDuMois == 3 || $jourDuMois == 4 || $jourDuMois == 5){$jourDuMoisLettre = "&nbsp;"; $JourDuMoisCouleur = "transparent";}
						if($jourDuMois == 6){$jourDuMoisLettre = "&nbsp;"; $JourDuMoisCouleur = "#eaedf1";}
						if($jourDuMois == 7){$jourDuMoisLettre = "&nbsp;"; $JourDuMoisCouleur = "#eaedf1";}
						if(($year == $dateJourProjet->format('Y')) && ($semaineAnnee == $dateJourProjet->format('W')) && ($jourDuMois == $dateJourProjet->format('N'))){$JourDuMoisCouleur = "#CEE0E9";}
						print_r('<div style="width:'.($gjEcartJ/count($tDates[$year])).'%; margin-left:'.((($gjEcartJ*$gjy)/count($tDates[$year]))+($gjEcartM*$gju)).'%; float:left;  background-color:'.$JourDuMoisCouleur.'; border-left: solid 1px #fff; position:absolute; min-height:400px;">');
							/*if($jourDuMois == 2){
								echo "<div style='position:absolute;'>S. ".$semaineAnnee."</div>";
							}*/
							echo $jourDuMoisLettre;
						print_r('</div>');
						/*if($gjy==0){
							//print_r('<div style="width:'.$gjEcartJ.'%; float:left;">');
							print_r('<div style="width:50%; float:left; text-align:left;">');
								print_r($indexgjy[$gjy]);
							print_r('</div>');
						}
						if($gjy==count($tDates[$year][$indexgju[$gju]])-1){
							//print_r('<div style="width:'.$gjEcartJ.'%; float:left;">');
							print_r('<div style="width:50%; float:left; text-align:right;">');
								print_r($indexgjy[$gjy]);
							print_r('</div>');
						}*/
					}
					print_r('</div>');
				}
			print_r('</div>');
			print_r('<br />');
			
			/* Selection de la tache actuelle dans le tableau */
			for ($ee=1; $ee<count($tDonneesTemp); $ee++){
				if($tDonneesTemp[$ee][0] == $tacheTemp){
					$tacheTableau = $ee;
				}
			}
			
			/* Affichage Fil d'Ariane */
			/*if($tacheTemp != 0){
				$tacheAriane = $tacheTableau;	
				$tArianeTemp = array();
				while ($tDonneesTemp[$tacheAriane][7] != 0){
					for ($k=1; $k<count($tDonneesTemp); $k++){
						if($tDonneesTemp[$k][0] == $tDonneesTemp[$tacheAriane][7]){
							$tacheAriane = $k;
							array_push($tArianeTemp, $tDonneesTemp[$tacheAriane]);
						}
					}
				}
				$tArianeTemp = array_reverse($tArianeTemp);
			}*/
			
			/* Creation des tableaux à afficher */
			/*$tDonneesChart = array();
			$tDonneesChartN2 = array();
			if($tacheTemp == '0'){
				for ($a=1; $a<count($tDonneesTemp); $a++){
					if($tDonneesTemp[$a][7] == $tacheTemp){
						array_push($tDonneesChart, $tDonneesTemp[$a]);
					}
				}
			}else{
			
				/* Changement */ 
				/*for ($b=1; $b<count($tDonneesTemp); $b++){
					
					if($tDonneesTemp[$b][7] == 0 || $tDonneesTemp[$b][0] == $tacheTemp || $tDonneesTemp[$b][7] == $tacheTemp || $tDonneesTemp[$b][7] == $tDonneesTemp[$tacheTableau][7]){
						array_push($tDonneesChart, $tDonneesTemp[$b]);
					}else{
						
						for ($c=0; $c<count($tArianeTemp); $c++){
							
							if($tArianeTemp[$c][0] == $tDonneesTemp[$b][0]){
								//array_push($tDonneesChart, $tArianeTemp[$c]);
							}
								
							if($tDonneesTemp[$b][7] == $tArianeTemp[$c][0]){
								array_push($tDonneesChart, $tDonneesTemp[$b]);
							}
						}
					}
				}*/
			
			
			
				/*for ($c=0; $c<count($tArianeTemp); $c++){
					//if($tArianeTemp[$c][7] == $tacheTemp){
						array_push($tDonneesChart, $tArianeTemp[$c]);
					//}
				}
				for ($e=1; $e<count($tDonneesTemp); $e++){
					if($tDonneesTemp[$e][0] == $tacheTemp){
						array_push($tDonneesChart, $tDonneesTemp[$e]);
					}
				}
				for ($b=1; $b<count($tDonneesTemp); $b++){
					if($tDonneesTemp[$b][7] == $tacheTemp){
						array_push($tDonneesChart, $tDonneesTemp[$b]);
					}
				}*/
			/*}*/
			
			/* Tâches */
			print_r('<div id="demoCharts" style="width:100%; text-align:left;">');
				/* Pour chaque tâche à afficher */
				for($gta=0; $gta<count($tDonneesChart); $gta++){
				
					/* Infos date tache */
					//$dateDebutTemp = explode(" ", $tDonneesChart[$gta][5]);
					$dateDebutTemp = array($tDonneesChart[$gta][5]->format('j'), $tDonneesChart[$gta][5]->format('n'), $tDonneesChart[$gta][5]->format('Y'));
					//$dateDebutTache = new DateTime($dateDebutTemp[2].'-'.$dateDebutTemp[1].'-'.$dateDebutTemp[0]);
					//$dateFinTemp = explode(" ", $tDonneesChart[$gta][6]);
					$dateFinTemp = array($tDonneesChart[$gta][6]->format('j'), $tDonneesChart[$gta][6]->format('n'), $tDonneesChart[$gta][6]->format('Y'));
					//$dateFinTache = new DateTime($dateFinTemp[2].'-'.$dateFinTemp[1].'-'.$dateFinTemp[0]);
					$nbrJoursComptes = 0;
					//$nbrJoursTache = (($dateFinTemp[0]+1)-$dateDebutTemp[0]);
					//$diffTache = $dateDebutTache->diff($dateFinTache);
					//$nombreJoursTache = ($diffTache->days)+1;
					$nombreJoursTache = $tDonneesChart[$gta][4];
					/* Couleur de la tâche */
					if($tDonneesChart[$gta][12] != 0){
						if($tDonneesChart[$gta][8] != 100){
							if($tDonneesChart[$q][12] == 2){
								$etatTacheCouleurGantt = "a";
							}else{
								if($tDonneesChart[$q][12] == 3){
									$etatTacheCouleurGantt = "b";
								}else{
									$etatTacheCouleurGantt = "c";
								}
							}
						}else{
							$etatTacheCouleurGantt = "a";
						}
					}else{
						$etatTacheCouleurGantt = "z";
					}
					
					/* Niveau de la tâche */
					if($tDonneesChart[$gta][7]==0 || $gta==0){
						$tailleTacheGantt = 20;
					}else{
						$tailleTacheGantt = 18;
					}
					
					if($tDonneesChart[$gta][7]==$tacheTemp || $tacheTemp==0){
						print_r('<div onclick="testonsb('.$idTemp.', '.$tDonneesChart[$gta][0].', \'gantt\');" style="width:100%; float:left; height:25px; margin-bottom:20px; cursor:pointer;">');
					}else{
						print_r('<div onclick="manageOverlay(\'b\');" style="width:100%; float:left; height:25px; margin-bottom:20px; cursor:pointer;">');
					}
					/* Pour chaque mois à afficher */
					for($gtu=0; $gtu<count($tDates[$year]); $gtu++){
						$indexgtu = array_keys($tDates[$year]);
						$gtEcartM = 100/count($tDates[$year]);
						//print_r('<div style="width:'.$gtEcartM.'%; float:left;">');
						/* Pour chaque jour de chaque mois on affiche la tâche correspondante */
						for($gty=0; $gty<count($tDates[$year][$indexgtu[$gtu]]); $gty++){
							$indexgty = array_keys($tDates[$year][$indexgtu[$gtu]]);
							$gtEcartJ = 100/count($tDates[$year][$indexgtu[$gtu]]);
							/* Si une tâche correspond à cette date, on l'indique */
							if(($dateDebutTemp[1] == $indexgtu[$gtu]) && ($dateDebutTemp[0] == $indexgty[$gty])){
								if($tDonneesChart[$gta][2] == "Tache"){
									echo '<progress class="progressGantt, progressBar'.$etatTacheCouleurGantt.'" style="cursor:pointer; position:absolute; height:'.$tailleTacheGantt.'px; margin-left:'.(($nbrJoursComptes/($nbrJoursAffiches+0.3))*100).'%; width:'.((($nombreJoursTache)/($nbrJoursAffiches+0.3))*100).'%" value="'.($tDonneesChart[$gta][8]).'" max="100">100%</progress>';
									echo '<div style="text-overflow:ellipsis; white-space:nowrap; overflow:hidden; position:absolute; width:'.(($nombreJoursTache/($nbrJoursAffiches+0.3))*100).'%; text-align:left; left:'.((($nbrJoursComptes+0.3)/($nbrJoursAffiches+0.3))*100).'%; color:#5e6d81; margin-top:-17px; font-size:16px; font-weight:normal; font-family:open sans;">'.$tDonneesChart[$gta][3].'</div>';
									echo '<div style="text-overflow:ellipsis; white-space:nowrap; overflow:hidden; position:absolute; width:'.(($nombreJoursTache/($nbrJoursAffiches+0.3))*100).'%; text-align:center; left:'.((($nbrJoursComptes)/($nbrJoursAffiches+0.3))*100).'%; color:#5e6d81; margin-top:2px; font-size:14px; font-weight:normal; font-family:open sans;"><strong>'.($tDonneesChart[$gta][8]).'</strong>%</div>';
									//echo $tDonneesChart[$gta][3].'  -  '.date_format($dateDebutTache, 'j M Y').' / '.date_format($dateFinTache, 'j M Y');
								}else{
									echo '<div class="progressGantt, progressBar'.$etatTacheCouleurGantt.'" style="background-image:url(\''.plugins_url('opesproject_wplugin/images/jalonBackground'.$etatTacheCouleurGantt.'.png').'\'); background-repeat:no-repeat; background-position:0px; background-color:transparent; background-size:'.$tailleTacheGantt.'px; cursor:pointer; position:absolute; height:'.$tailleTacheGantt.'px; margin-left:'.((($nbrJoursComptes-0.6)/($nbrJoursAffiches+0.3))*100).'%; width:'.$tailleTacheGantt.'px">&nbsp;</div>';
									echo '<div style="text-overflow:ellipsis; white-space:nowrap; overflow:hidden; position:absolute; width:'.(($nombreJoursTache/($nbrJoursAffiches+0.3))*100).'%; text-align:left; left:'.((($nbrJoursComptes+0.3)/($nbrJoursAffiches+0.3))*100).'%; color:#5e6d81; margin-top:-17px; font-size:16px; font-weight:normal; font-family:open sans;">'.$tDonneesChart[$gta][3].'</div>';
								}
							}
							$nbrJoursComptes ++;
							/*if($dateDebutTemp[1] == $dateFinTemp[1]){
								if((($dateDebutTemp[1] == $indexgtu[$gtu]) && ($dateDebutTemp[0] <= $indexgty[$gty])) && (($dateFinTemp[1] == $indexgtu[$gtu]) && ($dateFinTemp[0] >= $indexgty[$gty]))){
									print_r('<div style="width:'.$gtEcartJ.'%; float:left; display:block; opacity:0.7; background-color:'.$couleurTache.'">');
										print_r("&nbsp;");
									print_r('</div>');
								}else{
									print_r('<div style="width:'.$gtEcartJ.'%; float:left; display:block;">');
										// print_r($indexgty[$gty]);
										print_r("&nbsp;");
									print_r('</div>');
								}
							}else{
								if((($dateDebutTemp[1] == $indexgtu[$gtu]) && ($dateDebutTemp[0] <= $indexgty[$gty])) || (($dateDebutTemp[1] < $indexgtu[$gtu]) && ($dateFinTemp[1] > $indexgtu[$gtu]) && ($dateFinTemp[0] == $indexgty[$gty])) || (($dateFinTemp[1] == $indexgtu[$gtu]) && ($dateFinTemp[0] >= $indexgty[$gty]))){
									print_r('<div style="width:'.$gtEcartJ.'%; float:left; display:block; opacity:0.7; background-color:'.$couleurTache.';">');
										print_r("&nbsp;");
									print_r('</div>');
								}else{
									print_r('<div style="width:'.$gtEcartJ.'%; float:left; display:block;">');
										// print_r($indexgty[$gty]);
										print_r("&nbsp;");
									print_r('</div>');
								}
							}
							if($dateFinTemp[1] == $indexgtu[$gtu] && $dateFinTemp[0] == $indexgty[$gty]){
								//print_r('<div style="position:absolute; margin-left:'.($gtEcartJ*$dateFinTemp[0])/count($tDates[$year]).'%;">'.$tDonneesTemp[$gta][3].'</div>');
								//echo '<progress style="width:'.($dateFinTemp[0]-$dateDebutTemp[0]).'" value="100" max="100">100%</progress>';
							}*/
						}
						//print_r('</div>');
					}
					print_r('</div>');
				}
			print_r('</div>');
			}
			
			function affichageVoletInfos($tDonneesAffichage, $tDonneesAffichageN2){
				$etatTache = "Non débuté";
				if($tDonneesAffichage[0][12] == 1){
					$etatTache = "Achevé";
				}else{
					if($tDonneesAffichage[0][12] == 2 || $tDonneesAffichage[0][12] == 3){
						$etatTache = "En cours";
					}else{
						if($tDonneesAffichage[0][12] == 4){
							$etatTache = "En retard";
						}
					}
				}
			
				echo '<div style="position:fixed; padding:0; border:0; width:80%; max-width:345px; height:100%; right:-345px; top:0px; display:block; box-sizing:initial; background:none; background-color:#eaedf1; z-index:9999; transition:all 0.8s ease;" id="opMenuTabsB">';
					echo '<button class="opICMenu opIFMenuB"><img src="'.plugins_url('opesproject_wplugin/images/menuInfosClose.png').'" alt="Opes Project : Infos Project" height="34" width="34"></button>';
					echo '<ul style="background:none; border:none; padding:0; margin:0; border-radius:0;">';
						echo '<li style="background:none; border:none; padding:0; margin:0; border-radius:0; border-right:1px solid #eaedf1; background-color:#fff;"><a href="#tabs-4">Task</a></li>';
						echo '<!--<li style="background:none; border:none; padding:0; margin:0; border-radius:0; border-right:1px solid #eaedf1; background-color:#fff;"><a href="#tabs-5">Alertes</a></li>-->';
						echo '<!--<li style="background:none; border:none; padding:0; margin:0; border-radius:0; border-right:1px solid #eaedf1; background-color:#fff;"><a href="#tabs-6">Dépendances</a></li>-->';
					echo '</ul>';
					echo '<div class="opTabsContent" id="tabs-4">';
						echo '<ul class="opTabsList">';
							echo '<li><h2 style="color:#5e6d81;">'.$tDonneesAffichage[0][3].'</h2></li>';
							echo '<li>'.$tDonneesAffichage[0][9].'</li>';
							echo '<br />';
							echo '<li><strong>Beginning : </strong>'.$tDonneesAffichage[0][5]->format('j M Y').'</li>';
							echo '<li><strong>End : </strong>'.$tDonneesAffichage[0][6]->format('j M Y').'</li>';
							echo '<li><strong>Duration : </strong>'.$tDonneesAffichage[0][4].' day(s)</li>';
							echo '<li><strong>Type : </strong>'.$tDonneesAffichage[0][2].'</li>';
							echo '<li><strong>Status : </strong>'.$etatTache.'</li>';
						echo '</ul>';
						echo '<h3 class="opTabsTitle">Resource</h3>';
						echo '<ul class="opTabsList">';
							echo '<li>'.$tDonneesAffichage[0][11].'</li>';
						echo '</ul>';
					echo '</div>';
					echo '<!--<div class="opTabsContent" id="tabs-5">';
						echo '<ul class="opTabsList">';
							echo '<li>Tâche 1 - 2 jours de retard</li>';
							echo '<li>Tâche 2 - 7 jours de retard</li>';
						echo '</ul>';
					echo '</div>-->';
					echo '<!--<div class="opTabsContent" id="tabs-6">';
						echo '<ul class="opTabsList">';
							echo '<li>Démarre lorsque "Tâche 1" commence</li>';
							echo '<li>Démarre lorsque "Tâche 1" commence</li>';
							echo '<li>Lorsque terminée la tâche "Tâche 3" commence</li>';
						echo '</ul>';
					echo '</div>-->';
				echo '</div>';
			}
			
			if($typeAffichage == 'marguerite'){
				affichageMarguerite($projectId, $tacheActuelle, $tDonneesAffichage, $tDonneesAffichageN2);
				affichageVoletInfos($tDonneesAffichage, $tDonneesAffichageN2);
			}else{
				if($typeAffichage == 'gantt'){
					//$tDonneesAffichage = array_merge($tDonneesAffichage, $tDonneesAffichageN2);
					//affichageGantt($projectId, $tacheActuelle, $tDonneesAffichage, $dateDebutProjet, $dateFinProjet, $dateJourProjet);
					print_r('<div id="demoCharts" style="width:100%; position:relative; z-index:50; text-align:center; top:0px; margin:0 auto; max-width:1200px; opacity:0; transition: all 0.4s ease;">');
					print_r('</div>');
					//affichageVoletInfos($tDonneesAffichage, $tDonneesAffichageN2);
					
					//echo '<div id="chart_Gantt"></div>';
				}
			}
	//}
	
	
	//echo 'ce post est le: '.$_POST['typeAffichage'];
	exit();
}


?>