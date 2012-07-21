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

<hr/>
<?php include($render_element); ?>
<div style="clear:both;"></div>
<hr/>


