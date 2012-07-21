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
		<?php include($render_element); ?>
		</div>

		<div id="footer">
			&copy; 2012   Created by Berlin Stuff Media.
		</div>

	</div><!--#wraper -->
	</div>
<div style="clear:both;"></div>


<hr />
<center>
<table border="1">
	<thead>
		<tr>
			<th>Framework Frontend</th>
			<th>Framework Backend</th>
			<th>Modules</th>
			<th>Examples</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<a href="/">index (no url)</a><br/>
				<?php echo Html::l('index direct', $GLOBALS['DEFAULT_CONTROLLER'], $GLOBALS['DEFAULT_METHOD']); ?><br/>
				<?php echo Html::l('error', $GLOBALS['ERROR_CONTROLLER'], $GLOBALS['ERROR_METHOD']); ?><br/>
				<a href="/robots.txt">robots.txt</a><br/>
			</td>
			<td>
				<a href="/<?php echo $GLOBALS['BACKEND_URL_PATH'];?>/Login/enter">Login</a><br/>
				<a href="/<?php echo $GLOBALS['BACKEND_URL_PATH'];?>/Login/logout">Logout</a><br/>
				<a href="/<?php echo $GLOBALS['BACKEND_URL_PATH'];?>/Overview/start">Overview</a><br/>
				<a href="/<?php echo $GLOBALS['BACKEND_URL_PATH'];?>/Overview/serverInfo">Server Info</a><br/>
				<a href="/<?php echo $GLOBALS['BACKEND_URL_PATH'];?>/Contact/show">Contact</a><br/>
				<a href="/<?php echo $GLOBALS['BACKEND_URL_PATH'];?>/Visitors/lastVisits">Visitors</a><br/>

			</td>
			<td>
				<?php echo Html::l('Contact', 'Contact', 'add'); ?><br/>
				<?php echo Html::l('Forum', 'Forums', 'show'); ?><br/>
				<?php echo Html::l('Messages', 'Nachrichten', 'overview'); ?><br/>
			</td>
			<td>
				<?php echo Html::l('Login/Register', 'User', 'login'); ?><br/>
				<?php echo Html::l('Logout', 'User', 'logout'); ?><br/>
			</td>
		</tr>
	</tbody>
</table>
</center>
