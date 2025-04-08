<!doctype html>
<html>
<head>
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel="icon" href="https://www.unklab.ac.id/wp-content/uploads/2022/08/cropped-cropped-fav-32x32.png" sizes="32x32" />
	<link rel="icon" href="https://www.unklab.ac.id/wp-content/uploads/2022/08/cropped-cropped-fav-192x192.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="https://www.unklab.ac.id/wp-content/uploads/2022/08/cropped-cropped-fav-180x180.png" />
	<title>Universitas Klabat &#8211; Pathway to Exellence</title>
	<link href='https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css' rel='stylesheet'>
	<link href='https://use.fontawesome.com/releases/v5.7.2/css/all.css' rel='stylesheet'>
	<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
	<script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js'></script>

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH; ?>/css/login-style.css">
</head>
<body oncontextmenu='return false' class='snippet-body'>
<div class="container wrapper-center">
	<div class="wrapper bg-white">
		<div class="h2 text-center">Universitas Klabat</div>
		<form action="<?= APP_PATH;?>/login/process" method="post" class="pt-3">
			<div class="form-group py-1 pb-2">
				<div class="input-field">
					<select id="select-role" name="select-role" class="select">
						<option value="admin">Admin</option>
                        <option value="rektor">Rektor</option>
                        <option value="warek1">Wakil Rektor 1</option>
                        <option value="warek2">Wakil Rektor 2</option>
                        <option value="warek3">Wakil Rektor 3</option>
                        <option value="registrar">Registrar</option>
                        <option value="staff">Staff</option>
                        <option value="dosen" selected>Dosen</option>
                    </select>
				</div>
			</div>
			<div class="form-group py-2">
				<div class="input-field"> <span class="far fa-user p-2"></span> 
					<input id="email" name= "email" type="text" placeholder="Email Address" required class=""> 
				</div>
			</div>
			<div class="form-group py-1 pb-2">
				<div class="input-field"> 
					<span class="fas fa-lock p-2"></span> 
					<input id="password" name="password" type="password" placeholder="Password" required class=""> 
					<button type="button" class="btn bg-white text-muted"> 
					<span class="far fa-eye-slash"></span> </button> 
				</div>
			</div>
            <?php 
                if(isset($data['error-message'])){
                    echo "<div id='error-mes-login' style='font-size: 12px;color:red'>".$data['error-message']."</div>";
                }; 
            
            ?>
			<button type="submit" class="btn btn-block text-center mt-3">Log in</button>
		</form>
	</div>
</div>
<script src="<?php echo APP_PATH; ?>/js/jquery-3.3.1.slim.min.js"></script>
<script type='text/javascript' src="<?php echo APP_PATH; ?>/js/login-js-handling.js"></script>
</body>
</html>