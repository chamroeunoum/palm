<html>
<head>
    <title>Error Service</title>
</head>
<body>
<div style="padding: 2%; font-size: 16px; "><?php echo "ERROR: Function \"".$this->request->getAction()."\" of \"". Basic::getMVCName($this->request->getService()) . "Service\" not founded." ; ?></div>
</body>
</html>