<?php
	//require("../../../config.php");
	require("functions.php");
	//echo $serverHost;
	
	//kui on sisseloginud, siis pealehele
	if(isset($_SESSION["userId"])){
		login($_GET["id"]);
		header("Location: login.php");
		exit();
	}
	
	
	$signupFirstName = "";
	$signupFamilyName = "";
	$gender = "";
	$signupEmail = "";

	$loginEmail = "";
	$notice = "";
	
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	
	$loginEmailError ="";
	
	//kas klõpsati sisselogimise nuppu
	if(isset($_POST["signinButton"])){
	
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Sisselogimiseks on vajalik kasutajatunnus (e-posti aadress)!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
		//echo "Logime sisse!";
		$notice = signIn($loginEmail, $_POST["loginPassword"]);
	}
	}//kas sisselogimine lõppeb
	
	//kas luuakse uut kasutajat, vajutati nuppu
	if(isset($_POST["signupButton"])){
		
	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = test_input($_POST["signupFirstName"]);
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
		}
	}
	
	

	
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {
			$signupEmail = test_input($_POST["signupEmail"]);
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
			$gender = intval($_POST["gender"]);
		} else {
			$signupGenderError = " (Palun vali sobiv!) Määramata!";
	}
	
	//UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas	
	if (empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupEmailError) and empty($signupPasswordError)){
		echo "Hakkan salvestama!";
		//krüpteerin parooli
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		//echo "\n Parooli " .$_POST["signupPassword"] ." räsi on: " .$signupPassword;
		
		signUp($signupFirstName, $signupFamilyName, $signupEmail, $signupPassword);
		
	}
	
	}//uue kasutaja loomise lõpp
	


?>
<!DOCTYPE html>
<html lang="et">
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<head>
	<meta charset="utf-8">
	<title>Sisselogimine</title>
	<style>
		html,
		body {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
		}
		#content1 {
			position:fixed;
			top: 50%;
			left: 50%;
			text-align: center;
			transform: translate(-50%, -50%);
			font-family: 'Cousine';
			font-size: 20px;
			border: 32px solid white;
			border-radius: 24px;
		}
		#bgnBtn {
			background-color: white;
			color: black;
			border: 2px solid #555555;
			width: 160px;
			font-family: Cousine;
			font-size: 28px;
			border-radius: 2px;
		}
	</style>
</head>
<body>
<div id="content1">
	<h1>Logi sisse!</h1>
	<h3 class="title"><a href="http://greeny.cs.tlu.ee/~valevale/java/test/">Esilehele tagasi</a></h3>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	
		<input id="bgnBtn" name="loginEmail" placeholder="E-post" type="email" value="<?php echo $loginEmail; ?>"><span><?php echo $loginEmailError; ?></span>
		<br><br>
		<input id="bgnBtn" name="loginPassword" placeholder="Salasõna" type="password"><span></span>
		<br><br>
		<input id="bgnBtn" name=signinButton type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
		<h2><a href="login1.php">Loo kasutaja siin </a> <h2>
	</form>
	
</div>
</body>
</html>