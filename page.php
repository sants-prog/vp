<?php
	$author_name = "Sander Nõlvak";
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_now = date("N");
	//echo $weekday_now;
	$weekdaynames_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo weekdaynames_et[$weekday_now - 1];
    $hours_now = date("H");
    $part_of_day = "suvaline päeva osa";
    if($hours_now < 7) {
        echo $part_of_day = "uneaeg";
    }
    if($hours_now >= 8 and $hours_now <= 18) {
        echo $part_of_day = "koolipäev";
    }
    //uurime semestri kestmist
    $semester_begin = new DateTime("2022-9-5");
    $semester_end = new DateTime("2022-12-18");
    $semester_duration = $semester_begin->diff($semester_end);
    //echo $semester_duration;
    $semester_duration_days = $semester_duration->format("%r%a");
    $from_semester_begin = $semester_begin->diff(new DateTime("now"));
    $from_semester_begin_days = $from_semester_begin->format("%r%a");

    //juhguslik arv
    //küsin massiivi pikkust
    //echo count($weekdaynames_et);
    //echo mt_rand(0, count($weekdaynames_et) - 1);
    echo $weekdaynames_et[mt_rand(0, count($weekdaynames_et) - 1)];

    //juhuslik foto
    $photo_dir = "photos";
    //loen kataloogi sisu
    $all_files = scandir($photo_dir);
    //var_dump($all_files);
    $all_real_files = array_slice($all_files, 2);
    //kontrollin kas on foto
    $allowed_photo_types = ["image/jpeg", "image/png"];
    //tsükkel
    /*for($i = 0; $i < count($all_files); $i++) {
        echo $all_files[$i];
    }*/
    //teeb täpselt sama asja!!
    $photo_files = [];
    foreach($all_files as $filename){
        //echo $filename;
        $file_info = getimagesize($photo_dir ."/" .$filename);
        //var_dump($file_info);
        //kas on lubatud tüüpide nimekirjas
        if(isset($file_info["mime"])) {
            if(in_array($file_info["mime"],  $allowed_photo_types)) {
                array_push($photo_files, $filename);
            }
        }
    }


    var_dump($photo_files);
    //   <img src="kataloog/fail" alt="tekst">
    $photo_html = '<img src="' .$photo_dir . "/" .$photo_files[mt_rand(0, count($photo_files) -1)] .'"';
    //niimodi lisan juurde eelmisele väärtusele
    $photo_html .= ' alt="tallinna pilt">';
    //echo $photo_html

    //vaatame mida formis sisestati
    //var_dump($_POST)
    //echo $_POST["$todays_adjective_input"];
    $todays_adjective = "pole midagi siestatud";
    if(isset($_POST["$todays_adjective_input"]) and !empty($_POST["$todays_adjective_input"])) {
        $todays_adjective = $_POST["$todays_adjective_input"];
    }

    //loome rippmenüü valikud
    //<option value="0">tlu_27.png</option>
    //<option value="1">tlu_10.png</option>
    //<option vlaue="2">tln_21.png</option>
    $select_html = '<option value="" selected disabled>Vali pilt</option>';
    for($i = 0; $i < count($photo_files); $i++) {
        $select_html .= '<option value="' .$i . '">';
        $select_html .= $photo_files[$i];
        $select_html .= "</option>";
    }

    if(isset($_POST["photo_select"]) and !empty($_POST["photo_select"])) {
        echo "Valiti foto: " .$_POST["photo_select"];
    }

    $photo_html = "Täna on ilus ilm {$hours_now}";


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $author_name;?> LIN serveri veebileht</title>
</head>
<body>
    <img src="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png" alt="bänner">
    <h1 style="text-align: center;">Esimene veebileht</h1>
    <div style="margin: 0 3%;">
        <h2>List element</h2>
        <ul>
            <li>Esimene</li>
            <li style="color: red">Teine</li>
            <li style="font-size: 18px;">Kolmas</li>
        </ul>
    </div>
    <div>
        <h3>Teadustekst</h3>
        <p style="color: green; width: 75%; margin: 0 15%; text-align: center;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <br>
        <hr>
        <br>
        <p style="width: 75%; margin: 0 15%; text-align: center;">Lorem ipsum dolor sit <span>amet</span>, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco <b>laboris</b> nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>
    <div class="kooli-kirjeldav-pilt" style="text-align: center; margin-top: 100px;">
        <img src="tlu_terra_maja.jpg" width="400px" height="300px" alt="Kooli Terra hoone">
    </div>
    <div style="text-align: center;">
        <h4 style="margin: 40px 0 -15px 0">Sander Nõlvak</h4>
        <p>Mina olen Sander, olen 21. aastane.</p>
    </div>
    <hr style="margin: 30px 0">
    <div class="siin-on-php">
        <h3 style="margin-top: -10px;">Siin on <italic>PHP</italic></h3>
        <p>Lehe avamise hetk: <?php echo $weekdaynames_et[$weekday_now - 1] .", " .$full_time_now;?></p>
        <p>Praegu on <?php echo $part_of_day;?></p>
        <p>Semestri pikkus on <?php echo $semester_duration_days;?> päeva. See on kestnud juba <?php echo $from_semester_begin_days;?> päeva!</p>
        <hr>
        <?php echo $photo_html?>
    </div>
    <div class="siin-on-form" style="margin: 100px 100px;">
        <form method="POST">
            <input type="text" placeholder="kirjuta siia omadussõna tänase päeva kohta" id="todays_adjective_input" name="todays_adjective_input">
            <input type="submit" id="todays_adjective_submit" name="todays_adjective_submit" value="Saada omadussõna!">
        </form>
        <p>Omadussõna tänase kohta: <?php echo $todays_adjective; ?></p>
        <hr>
        <form method="POST">
            <select id="photo_select" name="photo_select">
                <?php echo $select_html; ?>
            </select>
    	    <input type="submit" id="photo_submit" name="photo_submit" value="Määra foto">
        </form>
    </div>
    <p><?php echo $photo_html;?></p>

</body>
</html>
