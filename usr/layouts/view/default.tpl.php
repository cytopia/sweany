<div class="topbar" style="text-align:right;">
	<?php echo Html::getLanguageSwitcher('Site', 'lang'); ?>
</div>

<div id="whole">
	<div id="wraper">
		<div id="header">
			<div class="topmenu">
				<ul>
					<li onclick="window.location='/'"><a href="/">Home</a></li>
					<li onclick="window.location='/Forums'"><a href="/Forums">Forums</a></li>
					<li onclick="window.location='/Contact'"><a href="/Contact">Contact</a></li>
					<li onclick="window.location='/Test'"><a href="/Test">Test</a></li>
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>

		<div id="content">
			<?php echo $view; ?>
		</div>

		<div id="footer">
			
		</div>

	</div><!--#wraper -->
</div><!--#whole -->
<div style="clear:both;"></div>
