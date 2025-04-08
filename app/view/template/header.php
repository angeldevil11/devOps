<!DOCTYPE html>
<html>
<head>
	<title><?php echo $data['title']; ?></title>
	<link href='https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <div class="container">
	    <a class="navbar-brand" href="<?php echo APP_PATH; ?>"><b>AI</b></a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
	      <div class="navbar-nav">
	        <a class="nav-link active" aria-current="page" href="<?php echo APP_PATH; ?>/home/">Home</a>
	        <a class="nav-link" href="<?php echo APP_PATH; ?>/home/page/">Page</a>
	        <a class="nav-link" href="<?php echo APP_PATH; ?>/project/">Project</a>
	      </div>
	    </div>
	  </div>
	</nav>