<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title><?php echo 'Cuisine - '.preg_replace('#<.+>#iU', '', $T['titre']) ?></title>
	
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="Content-Language" content="fr" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="index,follow" />
	
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 600px)" href="style/style.mob.css" />
	<link rel="stylesheet" type="text/css" media="screen and (min-width: 600px)" href="style/style.css" />

	<?php if (isset($T['jquery'])) { ?>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<?php } ?>
	<?php if (isset($T['js_file'])) { ?>
	<script type="text/javascript" src="js/<?php echo $T['js_file'] ?>.js"></script>
	<?php } ?>
</head>
<body class="<?php if (isset($T['body_class'])) echo $T['body_class']; ?>">
	<?php if (!empty($T['success'])) { ?><div id="ok_message"><?php echo (is_array($T['success'])) ? implode('<br/>', $T['success']) : $T['success']; ?></div><?php } ?>
	<?php if (!empty($T['error'])) { ?><div id="error_message"><?php echo (is_array($T['error'])) ? implode('<br/>', $T['error']) : $T['error']; ?></div><?php } ?>
	<?php if (!empty($T['fatal_error'])) { ?><div id="error_message"><?php echo (is_array($T['fatal_error'])) ? implode('<br/>', $T['fatal_error']) : $T['fatal_error']; ?></div><?php } ?>