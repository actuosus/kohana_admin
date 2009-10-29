<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title><?= Kohana::lang('admin.administration_interface') ?> <?= $_SERVER['SERVER_NAME'] ?></title>
		<link rel="stylesheet" href="/admin/media/styles/main.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		<!--[if IE 6]>
			<link rel="stylesheet" href="/admin/media/styles/ie6.css" type="text/css" media="screen" title="IE6 fixes" charset="utf-8" />
		<![endif]-->
		<link rel="stylesheet" href="/public/styles/ui.datepicker.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		<link rel="stylesheet" href="/public/styles/jquery.wysiwyg.css" type="text/css" media="screen" title="Main" charset="utf-8" />
		
		<?php
			print html::script(array
			(
				'scripts/jquery.js',
				'scripts/jquery.ui.all.js',
				'admin/media/scripts/admin.js'
			), TRUE);
		?>
	</head>
	<body id="main-app" name="main_app" class="detached platform-mac-leopard">
		<div id="toolbar">
			<?= $toolbar ?>
			<div class="toolbar-item close"><button id="close-button"></button></div>
			<div class="toolbar-item flexable-space"></div>
			<div class="toolbar-item hidden" id="search-results-matches"></div>
			<div class="toolbar-item"><input id="search" type="search" incremental results="0"><div id="search-toolbar-label" class="toolbar-label"></div></div>
		</div>
		<div id="main">
			<?= $navigation ?>
			<?= $content ?>
			<div id="main-panels" tabindex="0"></div>
			<div id="main-status-bar" class="status-bar"><div id="anchored-status-bar-items"><button id="dock-status-bar-item" class="status-bar-item toggled"></button><button id="console-status-bar-item" class="status-bar-item"></button><div id="error-warning-count" class="hidden"></div></div></div>
		</div>
		<div id="console">
			<div id="console-messages"><div id="console-prompt"><br></div></div>
			<div id="console-status-bar" class="status-bar"><div id="other-console-status-bar-items"><button id="clear-console-status-bar-item" class="status-bar-item"></button></div></div>
		</div>
		<div id="navigation" name="navigation"><?= $navigation ?></div>
		<div id="content" name="content"><?= $content ?></div>
	</body>
</html>