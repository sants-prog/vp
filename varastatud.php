<?php
	require_once "../config.php";
	//echo $server_host;
	
	$author_name = "Sander varastas selle faili - ";
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_now = date("N");
	//echo $weekday_now;
	$weekdaynames_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekdaynames_et[$weekday_now - 1];
	$hours_now = date("H");
	//echo $hours_now;
	$part_of_day = "suvaline päeva osa";
	//  <   > >=  <=   ==  !=
	if ($weekday_now >= 1 and $weekday_now <= 5) { 
		// esmaspaev kuni reede
		if($hours_now < 7){
			$part_of_day = "uneaeg";
		}
		//   and   or
		else if($hours_now >= 8 and $hours_now < 18){
			$part_of_day = "koolipäev";
		}
		else {
			$part_of_day = "uneaeg";
		}
	}
	else if ($weekday_now == 6){
		// laupaev
		$part_of_day = "pidupäev";
	}
	else {
		// puhapaev
		$part_of_day = "peaaegu koolipäev";
	}
	
	$vanasonad = [
		"Ega leib ole võsast võetud ega kase oksist katkutud.",
		"Hea ei tule ühelt poolt, kui teine ei tee head.",
		"Inimene läheb aasta vanemaks, kaks targemaks.",
		"Julge koer müüb vahel naha.",
		"Kask ei kasva kuuse kännu peale.",
		"Kes tööd teeb, leiba leiab.",
		"Kuidas lükkad, nõnda läheb; kuidas tõmbad, nõnda tuleb."
	];
	$vanasona_loos = mt_rand(0, count($vanasonad)-1);
	
	//uurime semestri kestmist
	$semester_begin = new DateTime("2022-9-5");
	$semester_end = new DateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	//echo $semester_duration;
	$semester_duration_days = $semester_duration->format("%r%a");
	$from_semester_begin = $semester_begin->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	//juhuslik arv
	//küsin massiivi pikkust
	//echo count($weekdaynames_et);
	//echo mt_rand(0, count($weekdaynames_et) -1);
	//echo $weekdaynames_et[mt_rand(0, count($weekdaynames_et) -1)];
	
	// juhuslik foto
	$photo_dir = "photos";
	// loen kataloogi sisu
	$all_files = array_slice(scandir($photo_dir), 2);
	//var_dump($all_files);

	// kontrollin kas on foto
	$allowed_photo_types = ["image/jpeg", "image/png"];

	/*
	for ($i = 0; $i < count($all_files); $i++) {
		echo $all_files[$i];
	}
	*/

	$photo_files = [];
	foreach ($all_files as $filename) {
		$file_info = getimagesize($photo_dir ."/" .$filename);
		//var_dump($file_info);

		//kas on lubatud tyypide nimekirjas
		if (isset($file_info["mime"])) {
			if (in_array($file_info["mime"], $allowed_photo_types)) {
				array_push($photo_files, $filename);
			}
		}
	}

	//var_dump($photo_files);
	// <img src="kataloog/fail" alt="tekst">
	$photo_index = mt_rand(0, count($photo_files)-1);

	//if (isset($_POST["photo_select"]) and !empty($_POST["photo_select"])) {
	if (isset($_POST["photo_select"]) and $_POST["photo_select"] >= 0) {
		$photo_index = $_POST["photo_select"];
	}
	$photo_src = $photo_dir ."/" .$photo_files[$photo_index];
	$photo_html = '<img src="' .$photo_src .'" alt="Tallinna pilt">';
	
	// vaatame, mida vormis sisestati
	//var_dump($_POST);
	$todays_adjective = "pole midagi sisestatud";

	if (isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"]))
	$todays_adjective = $_POST["todays_adjective_input"];


	// $comment_error = null;
	// $grade = 7;
	// //kas klikiti päevakommentaari nuppu?
	// if(isset($_POST["comment_submit"])) {
	// 	if(isset($_POST["comment_input"]) and !empty($_POST["comment_input"])) {
	// 		$comment = $_POST["comment_input"];
	// 	} else {
	// 		$comment_error = "Kommentaar jäi kirjutamata!";
	// 	}
	// 	//$comment = $_POST[comment_input];
	// 	$grade = $_POST[grade_input];
		
	// 	if(empty($comment_error)) {
	// 		//loon andmebaasiga ühenduse
	// 		//server, kasutaja, parool, andmebaas
	// 		$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	// 		//määran suhtlemisel kasutatava kooditabeli
	// 		$conn->set_charset("utf8");
	// 		//valmistame ette andmete saatmise SQL käsu
	// 		$stmt = $conn->prepare("INSERT INTO vp_daycomment(comment, grade) values(?, ?)");
	// 		echo $conn->error;
	// 		//seome SQL käsu õigete andmetega
	// 		//andmetüübid i - integer  d - decimal  s - string
	// 		$stmt->bind_param("si", $comment, $grade);
	// 		if($stmt->execute()) {
	// 			$grade = 7;
	// 			$comment = null;
	// 		}
	// 		$stmt->execute();
	// 		//sulgeme käsu
	// 		$stmt->close();
	// 		//sulgeme andmebaasiühenduse kinni
	// 		$conn->close();
	// 	}
	// }
	
	$comment_error = null;
	$grade = 7;
	//kas klikiti päeva kommentaari nuppu
	if(isset($_POST["comment_submit"])){
		if(isset($_POST["comment_input"]) and !empty($_POST["comment_input"])){
			$comment = $_POST["comment_input"];
		} else {
			$comment_error = "Kommentaar jäi kirjutamata!";
		}
		$grade = $_POST["grade_input"];

		if(empty($comment_error)){

			//loon andmebaasiga ühenduse
			//server, kasutaja, parool, andmebaas
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määran suhtklemisel kasuatatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette andmete saatmise SQL käsu
			$stmt = $conn->prepare("INSERT INTO vp_daycomment_2 (comment, grade) values(?,?)");
			echo $conn->error;
			//seome SQL käsu õigete andmetega
			//andmetüübid  i - integer   d - decimal    s - string
			$stmt->bind_param("si", $comment, $grade);
			if($stmt->execute()){
				$grade = 7;
				$comment = null;
			}
			//sulgeme käsu
			$stmt->close();
			//andmebaasiühenduse kinni
			$conn->close();
		}
	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name;?> programmeerib veebi</title>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="bänner">
	<h1><?php echo $author_name;?> programmeerib veebi</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>
	<p>Lehe avamise hetk: <?php echo $weekdaynames_et[$weekday_now - 1] .", " .$full_time_now;?></p>
	<p>Praegu on <?php echo $part_of_day;?>.</p>
	<p>Vanasõna loos: <?php echo $vanasonad[$vanasona_loos];?></p>
	<p>Semestri pikkus on <?php echo $semester_duration_days;?> päeva. See on kestnud juba <?php echo $from_semester_begin_days; ?> päeva.</p>
	<img src="pics/tlu_15.jpg" alt="Tallinna Ülikooli sisemuse pilt">

	<form method="POST">
		<input type="text" id="todays_adjective_input" name="todays_adjective_input" placeholder="Kirjuta siia omadussõna tänase päeva kohta">
		<input type="submit" id="todays_adjective_submit" name="todays_adjective_submit" value="Saada omaddussõna!">
	</form>

	<p>Omadussõna tänase kohta: <?php echo $todays_adjective; ?></p>
	
	<form  style="margin: 100px 0 100px 0;" method="POST">
		<label for="comment_input">Kommentaar tänase päeva kohta (140 tähte)</label>
		<br>
		<textarea id="comment_input" name="comment_input" cols="35" rows="4" placeholder="kommentaar"></textarea>
		<br>
		<label for="grade_input">Hinne tänasele päevale (0 - 100)</label>
		<input type="number" id="grade_input" name="grade_input" min="0" max="10" step="1" value="7">
		<br>
		
		<input type="submit" id="comment_input" name="comment_input" value="Salvesta">
		<span><?php echo $comment_error; ?></span>
	</form>

	<form method="POST">
		<select id="photo_select" name="photo_select">
			<?php 
				// <option value="0">tlu_5.jpg</option>
				// loome rippmenuu valikud
				$select_html = '<option value="" disabled>Vali pilt</option>';
				for ($i = 0; $i < count($photo_files); $i++) {
					if ($i == $photo_index) {
						$select_html .= '<option value="' .$i .'" selected>' .$photo_files[$i] ."</option>";
					} else {
						$select_html .= '<option value="' .$i .'">' .$photo_files[$i] ."</option>";
					}
				}
				echo $select_html;
			?>
		</select>
		<input type="submit" id="photo_submit" name="photo_submit" value="Määra pilt">
	</form>

	<hr>
		<?php echo $photo_html; ?>
	<hr>
	<h2>Kodutöö</h2>
	<form method="POST">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri">
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912">
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="60" max="600">
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr">
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja">
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör">
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>
</body>
</html>
