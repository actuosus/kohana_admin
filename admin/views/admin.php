<html>
	<head>
		<title><?php print Kohana::lang('admin.administration_interface'); ?> <?php print $_SERVER['SERVER_NAME']; ?></title>
		<link rel="stylesheet" href="/admin/media/styles/inspector.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		<link rel="stylesheet" href="/admin/media/styles/main.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		<!--[if IE 6]>
			<link rel="stylesheet" href="/admin/media/styles/ie6.css" type="text/css" media="screen" title="IE6 fixes" charset="utf-8" />
		<![endif]-->
		<link rel="stylesheet" href="/public/styles/ui.datepicker.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		<link rel="stylesheet" href="/public/styles/jquery.wysiwyg.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		<script type="text/javascript" charset="utf-8" src="/admin/media/scripts/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="/admin/media/scripts/jquery.ui.all.js"></script>
		<script type="text/javascript" charset="utf-8" src="/admin/media/scripts/jquery.form.js"></script>
		<script type="text/javascript" charset="utf-8" src="/admin/media/scripts/ajaxfileupload.js"></script>
		<script type="text/javascript" charset="utf-8" src="/admin/media/scripts/swfobject1.5.js"></script>
		<script type="text/javascript" charset="utf-8" src="/admin/media/scripts/admin.js"></script>
	</head>
	<body id="main-app" name="main_app">
		<div id="toolbar">
			<?php print $toolbar; ?>
			<!-- <div class="toolbar-item close"><button id="close-button"></button></div> -->
			
			<button class="toolbar-item toggleable add">
				<div class="toolbar-icon"></div>
				<div class="toolbar-label"><?php print Kohana::lang('admin.add'); ?></div>
			</button>
			<button class="toolbar-item toggleable delete">
				<div class="toolbar-icon"></div>
				<div class="toolbar-label"><?php print Kohana::lang('admin.delete'); ?></div>
			</button>
			<button class="toolbar-item toggleable save">
				<div class="toolbar-icon"></div>
				<div class="toolbar-label"><?php print Kohana::lang('admin.save'); ?></div>
			</button>
			
			<div class="toolbar-item flexable-space"></div>
			<div class="toolbar-item hidden" id="search-results-matches"></div>
			<div class="toolbar-item"><input id="search" type="search" incremental results="0"><div id="search-toolbar-label" class="toolbar-label"></div></div>
		</div>
		<div id="main">
			<div id="main-panels" tabindex="0">
				<div class="panel visible">
					<div id="main-container">
						<div id="navigation-sidebar" class="sidebar">
							<?php print $navigation; ?>
						</div>
						<div id="main-container-content">
							<?php print $content; ?>
						</div>
					</div>
				</div>
			</div>
			<!-- <div id="main-status-bar" class="status-bar"><div id="anchored-status-bar-items"><button id="dock-status-bar-item" class="status-bar-item toggled"></button><button id="console-status-bar-item" class="status-bar-item"></button><div id="error-warning-count" class="hidden"></div></div></div> -->
		</div>
		<div id="loader"></div>
	</body>
</html>