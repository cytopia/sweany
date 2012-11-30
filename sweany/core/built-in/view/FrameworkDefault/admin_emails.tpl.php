<?php
/*
This file must be here for the admin panel
*/
?>
<div class="sweany_admin sweany_admin_emails">
	<h1>Emails</h1>

	<?php if (count($emails)):?>
		<table style="border:1px solid black;margin-left:auto; margin-right:auto;">
			<thead>
				<tr>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Id</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Recipient</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Headers</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Subject</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Message</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Created</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($emails as $email):?>
				<tr>
					<td style="border:1px solid black;text-align:center;"><?php echo $email->id;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $email->recipient;?></td>
					<td style="border:1px solid black;text-align:left;"><?php echo str_replace("\n", '<br/>', $email->headers);?></td>
					<td style="border:1px solid black;text-align:left;"><?php echo $email->subject;?></td>
					<td style="border:1px solid black;text-align:left;"><?php echo $email->message;?></td>
					<td style="border:1px solid black;text-align:center;padding:5px;"><?php echo $email->created ? TimeHelper::date('Y-m-d H:i', $email->created) : '';?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>
</div>