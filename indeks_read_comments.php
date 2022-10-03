<?php
	require_once "../config.php";
	
	//loon andmebaasiga ühenduse
	//server, kasutaja, parool, andmebaas
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määran suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	
	//valmistame ette andmete saatmise SQL käsu
	$stmt = $conn->prepare("SELECT comment, grade, added FROM vp_daycomment");
	echo $conn->error;
	//seome saadavad andmed muutujatega
	$stmt->bind_result($comment_from_db, $grade_from_db, $added_from_db);
	//täidame käsu
	$stmt->execute();
	//kui saan ühe kirje
	//if($stmt->fetch()) {
		//mis selle ühe kirjega teha
	//}
	//kui tuleb teadmata arv kirjeid
	$comment_html = null;
	while($stmt->fetch()) {
		//echo comment_from_db;
		//<p>kommentaar, hinne päevale: 6, lisatud xx/xx/xxxx</p>
		$comment_html .="<p>" .$comment_from_db .", hinne päevale: " .$grade_from_db;
		$comment_html .= ", lisatud " .$added_from_db .".</p> \n";
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Page Title</title>
		<link rel="stylesheet" href="styles.css">
	</head>
	<body style="background-color:#D3D3D3;">

		<h1 style="position: auto;margin: 3% 40%;color: red;">This is a Heading</h1>
		<p>This is a paragraph.</p>
		<button onclick=" window.open('www.tlu.ee','_blank')" type="button">www.tlu.ee</button>

		<div class="prooviks-seda-div" style="font-size:18px">
			<h1>List</h1>
			<ul>
				<li style="color:red;">
				proov
				esimene
				</li>
				<li>
				proov
				teine
				</li>
				<li>
				proov
				kolmas
				</li>
			</ul>
		</div>
		<div>
			<p>Järgmine rida koodi</p>	
		<div>
		<element>Sisu</element>
		<h3>Sander programmerib veebi</h3>
		<p>Mõttetu paragraaf</p>
		<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a></p>
		<br>
		<?php echo $comment_html; ?>
	</body>
</html> 

