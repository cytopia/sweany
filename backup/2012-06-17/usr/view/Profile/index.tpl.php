<h1>All Members</h1><div>	<div style="border:solid 1px red;">		All Members (27,012,432,454,434)		<?php echo $form->start('form_test'); ?>			<?php echo $form->inputField('search'); ?>			<?php echo $form->submitButton('submit_search', 'search'); ?>			<?php echo $form->selectBox('order', array(array('id' => 0, 'value' =>'Recently Added'), array('id' => 1, 'value' =>'Alphabetical'), array('id' => 2, 'value' =>'Random'))); ?>		<?php echo $form->end(); ?>	</div><br/>		<div style="border:solid 1px red;">		<?php for($i=0; $i<20; $i++): ?>			<div class="borderMe" style="width:210px; height:110px; float:left; margin-right:25px;margin-bottom:25px;">				<div style="float:left; width:100px; height:100px;border:1px solid black;">pic</div>				<div style="float:left; width:100px; height:100px;">					<strong><a href="/Profile/show">Username</a></strong><br/>					comment<br/>					give gift				</div>			</div>		<?php endfor; ?>		<div style="clear:both;"></div>		&lt;-prev 1,2,3,...5923343 next-&gt;	</div></div><div style="clear:both;"></div>