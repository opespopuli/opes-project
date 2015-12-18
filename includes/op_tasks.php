<?php

/* Projets Admin */

function codex_opestasks_init() {

	register_post_type( 'opestasks',
		array(
		  'labels' => array(
			'name' => __( 'Opes Tasks' ),
			'singular_name' => __( 'Opes Task' )
		  ),
		  'public' => true,
		  'has_archive' => false,
		  'rewrite' => array( 'slug' => 'opestasks' ),
		  'supports' => array( 'title', 'editor')
		)
	);
}

function add_meta_tasks(){
	add_meta_box("infos_tasks-meta", "Infos Opes Tasks", "infos_tasks", "opestasks");
}
function save_details_tasks(){
  global $post;
  update_post_meta($post->ID, "ot_project", $_POST["ot_project"]);
  update_post_meta($post->ID, "ot_dated", $_POST["ot_dated"]);
  update_post_meta($post->ID, "ot_datef", $_POST["ot_datef"]);
  update_post_meta($post->ID, "ot_duree", $_POST["ot_duree"]);
  update_post_meta($post->ID, "ot_avancement", $_POST["ot_avancement"]);
  update_post_meta($post->ID, "ot_responsable", $_POST["ot_responsable"]);
  update_post_meta($post->ID, "ot_precedant", $_POST["ot_precedant"]);
  update_post_meta($post->ID, "ot_hierarchie", $_POST["ot_hierarchie"]);
  update_post_meta($post->ID, "ot_lien", $_POST["ot_lien"]);
  update_post_meta($post->ID, "ot_ressources", $_POST["ot_ressources"]);
}

function infos_tasks() {
  global $post;
  $custom = get_post_custom($post->ID);
  $ot_project = $custom["ot_project"][0];
  $ot_dated = $custom["ot_dated"][0];
  $ot_datef = $custom["ot_datef"][0];
  $ot_duree = $custom["ot_duree"][0];
  $ot_avancement = $custom["ot_avancement"][0];
  $ot_responsable = $custom["ot_responsable"][0];
  $ot_precedant = $custom["ot_precedant"][0];
  $ot_hierarchie = $custom["ot_hierarchie"][0];
  $ot_lien = $custom["ot_lien"][0];
  $ot_ressources = $custom["ot_ressources"][0];
  
  if($ot_dated == ''){$ot_dated = date('d/m/Y');}
  if($ot_datef  == ''){$ot_datef  = date('d/m/Y');}
  if($ot_avancement  == ''){$ot_avancement  = 0;}
  ?>
  <span style="width:150px;">opes project: </span><input name="ot_project" value="<?php echo $ot_project; ?>" /><br />
  <span style="width:150px;">date début: </span><input name="ot_dated" value="<?php echo $ot_dated; ?>" /><br />
  <span style="width:150px;">date fin: </span><input name="ot_datef" value="<?php echo $ot_datef; ?>" /><br />
  <span style="width:150px;">durée: </span><input name="ot_duree" value="<?php echo $ot_duree; ?>" /><br />
  <span style="width:150px;">avancement: </span><input name="ot_avancement" value="<?php echo $ot_avancement; ?>" /><br />
  <span style="width:150px;">responsable: </span><input name="ot_responsable" value="<?php echo $ot_responsable; ?>" /><br />
  <span style="width:150px;">precedant: </span><input name="ot_precedant" value="<?php echo $ot_precedant; ?>" /><br />
  <span style="width:150px;">hierarchie: </span><input name="ot_hierarchie" value="<?php echo $ot_hierarchie; ?>" /><br />
  <span style="width:150px;">lien internet: </span><input name="ot_lien" value="<?php echo $ot_lien; ?>" /><br />
  <span style="width:150px;">ressources: </span><input name="ot_ressources" value="<?php echo $ot_ressources; ?>" />
  <?php 
}

?>