<?php
/*
This file must be here for the admin panel
*/
?>

<div class="sweany_admin sweany_admin_visitors">
	<h1>Visitors</h1>

	<?php if (count($visitors)):?>
		<table style="border:1px solid black;margin-left:auto; margin-right:auto;">
			<thead>
				<tr>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Id</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">URL</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Referer</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">IP</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Host</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Created</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($visitors as $visitor):?>
				<?php
					$url = str_replace('/', '/ ', $visitor->url);
					$url = str_replace('&', '& ', $url);

					$referer = str_replace('/', '/ ', $visitor->referer);
					$referer = str_replace('&', '& ', $referer);
				?>
				<tr>
					<td style="border:1px solid black;text-align:center;"><?php echo $visitor->id;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $url;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $referer;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $visitor->ip;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $visitor->host;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $visitor->created ? TimeHelper::date('Y-m-d H:i', $visitor->created) : '';?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>
	<br/>
</div>