<!DOCTYPE html>
<html>
	<head>
		<meta name="keywords" content="<?php echo $keywords;?>">
		<meta name="description" content="<?php echo $description;?>">
		<meta charset="utf-8" />
		
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		
		<link href="assets/css/styles.css" rel="stylesheet" type="text/css" />
		
		<title><?php echo $title;?></title>
	</head>
	<body>
	
	<header>
	</header>
		
	<div id="page">
	
		<?php if($flashMessage != ""){?>
			<div class="flashMessage"><?php echo $flashMessage;?></div><?php
		}?>
		
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	
	</div><!-- page -->
	
	<footer>
		Martignier Stéphane - Projet PRW2
	</footer>
	
	</body>
</html>