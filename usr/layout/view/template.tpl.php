<div class="topbar">
</div>



	<?PHP
	$mainMenu['Home'] 			= '/';
	$mainMenu['My Page'] 		= '/Profile/show';
	$mainMenu['Members'] 		= '/Profile/index';
	$mainMenu['Groups'] 		= '/Groups/index';
	$mainMenu['Gallery'] 		= '/Gallery/index';
	$mainMenu['Events'] 		= '/Event/index';
	$mainMenu['Forum'] 			= '/Forums/show';
	$mainMenu['Blogs'] 			= '/Blogs/index';
	$mainMenu['Classifieds'] 	= '/Classified/index';
	$mainMenu['Contact'] 		= '/Contact/index';
	?>
	<div id="whole">
	<div id="wraper">
		<div id="header">
			<div class="topmenu">
				<ul>
				<?PHP foreach($mainMenu as $key => $value):	?>
					<li <?php if(isset($active) && $active==$key)echo 'class="active"';?> onclick="window.location='<?PHP echo $value ?>'"><a href="<?PHP echo $value ?>"><?PHP echo $key ?></a></li>
				<?PHP	endforeach ?>
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>

		<div id="content">
		<?php echo $view; ?>
		</div>

		<div id="footer">
			&copy; 2012   Created by Berlin Stuff Media.
		</div>

	</div><!--#wraper -->
	</div>
<div style="clear:both;"></div>