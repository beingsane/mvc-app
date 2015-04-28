<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/layouts/css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="/layouts/css/default_layout.css"/>
		
		<title><?= $this->title ?></title>
		
		<?php
			foreach($this->jsFiles as $file)
			{
				?>
					<script src="<?= $file ?>"></script>
				<?php
			}
			
			foreach($this->cssFiles as $file)
			{
				?>
					<link rel="stylesheet" type="text/css" href="<?= $file ?>"/>
				<?php
			}
		?>
	</head>
	<body>
		<div class="wrapper">
			<div class="content_wrapper">
				<div class="header"></div>
				
				<div class="content">
					<?= $content ?>
				</div>
				
				<div class="footer_placeholder"></div>
			</div>
			
			<div class="footer"></div>
		</div>
	</body>
</html>