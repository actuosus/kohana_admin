<ol class="sidebar-tree">
<?php
	$item_groups = Kohana::config('admin.navigation');
	
	foreach ($item_groups as $item_group_name => $items)
	{
		print '<li class="parent sidebar-tree-section expanded">';
		print $item_group_name;
		print '</li>';
		print '<ol class="children expanded">';
		foreach ($items as $item)
		{
			$item = ORM::factory($item);
			if ( property_exists($item, 'verbose_plural') )
			{
				$label = $item->verbose_plural;
			}
			else
			{
				$label = preg_replace( '/_/', ' ', ucfirst($item->object_plural) );
			}
			
			print '<li class="sidebar-tree-item"><div class="status"></div>';
			print '<div class="titles no-subtitle">';
			print '<a href="/admin/'.$item->object_plural.'/" target="content">'.$label.'</a>';
			print '<div class="subtitle"></div></div>';
			print '</li>';
		}
		print '</ol>';
	}
?>
	<li class="parent sidebar-tree-section expanded">
		Всякие приблуды
	</li>
	<ol class="children expanded">
		<li class="sidebar-tree-item"><div class="status"></div>
			<div class="titles no-subtitle">
				<a class="wavvy" target="content">Google Wave</a>
			<div class="subtitle"></div></div>
		</li>
</ol>