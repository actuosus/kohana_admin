<div class="database-view table visible">
	<div class="data-grid" tabindex="0">
		<table class="header">
			<colgroup span="1">
				<col style="width: 100%; ">
			</colgroup>
			<tbody>
				<tr>
					<th class="name-column">
						<div><?php Kohana::lang('admin:title')?></div>
					</th>
					<th class="corner"></th>
				</tr>
			</tbody>
		</table>
		<div class="data-container">
			<table class="data">
				<colgroup span="1">
					<col style="width: 100%; ">
				</colgroup>
				<tbody>
					<?php if (isset($items)): ?>
						<?php foreach($items as $item): ?>
							<tr class="item revealed">
									<td class="name-column">
										<div>
											<a href="/admin/<?= $item->table_name ?>/edit/<?= $item->id ?>/"><?= (string) $item ?></a>
										</div>
									</td>
							</tr>
						<? endforeach; ?>
					<?php endif ?>
					<tr class="filler">
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>