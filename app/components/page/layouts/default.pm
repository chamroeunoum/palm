<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="icon" href="<?php echo Import::link("img/logo.png");?>" type="image/jpg" /><?php
	echo Import::css("");
	echo Import::js("");
?><title>TEMPLATE</title>
</head>
<body>
Default of controller's view.
<?php echo $this->element("default"); ?>
</body>
</html>