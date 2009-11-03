<form name="admin-form" id="admin-form" action="/admin/<?= $item->table_name ?>/save/<?= $item->id ?>/" method="post" enctype="multipart/form-data">
	<div id="content">
		<ul class="rows">
			<?php
				// print Kohana::debug($item);
				print form_generator::rows($item);
				
				foreach( $item->has_and_belongs_to_many as $related_model_name )
				{
					print form_generator::related_rows($related_model_name, $item);
				}
				
				foreach( $item->has_one as $related_model_name )
				{
					print form_generator::related_one2one($related_model_name, $item);
				}
				
				foreach( $item->has_many as $related_model_name )
				{
					print form_generator::related_rows($related_model_name, $item);
				}
			?>
		</ul>
	</div>
</form>