<div style="border:solid 1px red;background-color:gray;">
	<h1>Missing View</h1>

	<p>This has one of the following reasons:</p>
	<ul>
		<li>You have not set $this->view('name_of_the_view');</li>
		<li>The name of the view in $this->view(); is incorrect</li>
		<li>If you do not want a view to be displayed, you will have to set $this->render</li>
	</ul>
	<p>Enable $VALIDATION_MODE (if not done already) in config.php to find out more via Syslog output</p>
</div>
