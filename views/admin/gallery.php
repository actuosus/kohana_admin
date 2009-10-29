<div class="gallery-view visible">
	<?php if (isset($items)): ?>
		<?php foreach($items as $item): ?>
			<?php $file_info = pathinfo($item->file); ?>
			<div class="item">
				<a href="/admin/<?= $item->table_name ?>/edit/<?= $item->id ?>/" title="<?= $item->album->description ?>">
					<?php if (!empty($item->file)): ?>
						<?php $file_info = pathinfo($item->file); ?>
						<div class="picture-box">
							<img src="/public/files/<?= $file_info['filename'] ?>_small.<?= $file_info['extension'] ?>" alt="<?= $item->album->description ?>" />
						</div>
					<?php else: ?>
						<div class="picture-box empty-image">
						</div>
					<?php endif ?>
					<span class="description"><?= $item->title ?></span>
				</a>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>