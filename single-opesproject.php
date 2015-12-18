
<?php
/**
 * Template Name: Opes Project
 **/
 
  global $post;
  $custom = get_post_custom($post->ID);
  $client = $custom["op_client"][0];
  $av_general = $custom["op_avancement"][0];
  $debut = $custom["debut"][0];
  $jour = $custom["jour"][0];
  $fin = $custom["fin"][0];
  $gantt = $custom["op_gantt"][0];

get_header(); ?>

	<?php if ( have_posts() ) : ?>  
		<?php while ( have_posts() ) : the_post(); ?>
			<!--<style onload="testonsb(<?php //echo the_ID(); ?>, 0, 'marguerite');"></style>-->
			<div style="display:none;" id="op_idProject"><?php echo the_ID(); ?></div>
			<!-- Display header app -->
			<div class="opHeader">
				<h1 class="opHeaderTitle"><?php the_title(); ?></h1>
			</div>
			<div class="opHeaderMenu">
				<!--<button class="opITMenu" id="opITMenu"><img src="<?php //echo plugins_url('opesproject_wplugin/images/menuInfosTache.png'); ?>" alt="Opes Project : Infos Tache" height="50" width="50"></button>-->
				<button class="opIPMenu" id="opIPMenu"><img src="<?php echo plugins_url('opesproject_wplugin/images/menuInfosProject.png'); ?>" alt="Opes Project : Infos Project" height="50" width="50"></button>
			</div>
			<!--<h2><?php// echo $client;?></h2>-->
			<!--<p><?php //the_content(); ?></p>
			<p id='postID'><?php //the_ID(); ?></p>
			-->
			
			<!--<div style="width:33%; margin-top:0px; float: left; text-align:left;">Date de début</div><div style="width:33%; margin-top:0px; float: left; text-align:center;">Date du jour</div><div style="width:33%; margin-top:0px; float: left; text-align:right;">Date de fin</div>
			<div style="width:33%; margin-top:0px; float: left; text-align:left;"><?php //echo $debut; ?></div>
			<div style="width:33%; margin-top:0px; float: left; text-align:center;"><?php //echo $jour; ?></div>
			<div style="width:33%; margin-top:0px; float: left; text-align:right;"><?php //echo $fin; ?></div>
			<div style="width:100%; margin-top:0px; float: left; text-align:center;"><progress style="width:100%; height:20px;" class="proN2" value="<?php //echo $av_general; ?>" max="100"><?php //echo $av_general; ?>%</progress></div>
			-->
			
			
			
			<div id="demo" style="width:100%; position:relative; max-width:1100px; padding-top:104px; padding-bottom:0px; min-height:120px; overflow:hidden; margin: 0 auto; left:0; right:0;"><div id="demoCharts"> </div></div>
			
			<div style="width:100%; max-width:1100px; margin: 0 auto; left:0; right:0; text-align:center; border-top: solid 1px #eaedf1; margin-bottom:55px; margin-top:-3px; box-shadow: 0px -20px 30px -22px rgba(0,0,0,0.10); -webkit-box-shadow: 0px -20px 30px -22px rgba(0,0,0,0.10);">
				<div style="width:100%; text-align:center; font-size:18px; font-family:Open-sans; padding:3px;">Gantt</div>
				<div style="width:100%; position:relative; padding:0; overflow:hidden; max-width:1100px; margin: 0 auto; left:0; right:0;" id="chart_Gantt"></div>
			</div>
			
			<!--<div style="width:100%; height:500px;"><?php //echo afficherProjet('marguerite', '0'); ?></div>-->
			
		<?php endwhile; ?>
	<?php else : ?>  
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>  

	
	<div style="position:fixed; padding:0; border:0; width:80%; max-width:345px; height:100%; right:-345px; top:0px; display:block; box-sizing:initial; background:none; background-color:#eaedf1; z-index:9999; transition:all 0.8s ease;" id="opMenuTabsA">
		<button class="opICMenu opIFMenu"><img src="<?php echo plugins_url('opesproject_wplugin/images/menuInfosClose.png'); ?>" alt="Opes Project : Infos Project" height="34" width="34"></button>
		<ul style="background:none; border:none; padding:0; margin:0; border-radius:0;">
			<li style="background:none; border:none; padding:0; margin:0; border-radius:0; border-right:1px solid #eaedf1; background-color:#fff;"><a href="#tabs-1">Informations</a></li>
			<!--<li style="background:none; border:none; padding:0; margin:0; border-radius:0; border-right:1px solid #eaedf1; background-color:#fff;"><a href="#tabs-2">Activité</a></li>-->
			<!--<li style="background:none; border:none; padding:0; margin:0; border-radius:0; border-right:1px solid #eaedf1; background-color:#fff;"><a href="#tabs-3">Commentaires</a></li>-->
		</ul>
		<div class="opTabsContent" id="tabs-1">
			<h2 style="color:#5e6d81; margin-bottom:20px;"><?php echo $client;?></h2>
			<?php the_content(); ?>
			<h3 class="opTabsTitle">Views</h3>
			<ul class="opTabsList">
				<li class="opIFMenu" style="cursor:pointer; text-decoration:underline;" onclick="testonsb(<?php echo the_ID(); ?>, 0, 'marguerite')">Opes view</li>
				<li class="opIFMenu" style="cursor:pointer; text-decoration:underline;" onclick="testonsb(<?php echo the_ID(); ?>, 0, 'gantt')">Gantt</li>
			</ul>
		</div>
		<!--<div class="opTabsContent" id="tabs-2">
			<ul class="opTabsList">
				<li>Infos 1</li>
				<li>Infos 2</li>
				<li>Infos 3</li>
			</ul>
		</div>-->
		<!--<div class="opTabsContent" id="tabs-3">
			<ul class="opTabsList">
				<li>Commentaire 1</li>
				<li>Commentaire 2</li>
				<li>Commentaire 3</li>
			</ul>
		</div>-->
	</div>
	
	<div id="opOverlay" class="opOverlay"></div>
	<div style="display:none;"><?php get_footer(); ?></div>
	<div style="display:none;" id="op_infosGantt">1,20/10/2015,30/10/2015,task1,20,0|2,20/10/2015,30/10/2015,task2,50,0|</div>
	
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>google.load("visualization", "1.1", {packages:["gantt"]});</script>