<?php
	require_once("./includes/db.php");
	$pdo = makeConnectionLocal();

	$date = new DateTimeImmutable();
	
	if(isset($_POST['confirm'])) {
		sleep(3);
		
		$skid = preg_replace('[^a-zA-Z0-9]', '', $_POST['skid']);
		$name = preg_replace('[^0-9A-Za-z_\\.\' -]', '', $_POST['name']);
		$wins = preg_replace('[\D]', '', $_POST['wins']);
		$kills = preg_replace('[\D]', '', $_POST['kills']);
		$botKills = preg_replace('[\D]', '', $_POST['botKills']);
		$deaths = preg_replace('[\D]', '', $_POST['deaths']);
		$kdr = $kills / $deaths;
		$kdr = round($kdr, 2);
		$kdr = number_format($kdr, 2, '.', '');
		$level = preg_replace('[\D]', '', $_POST['level']);
		$games = preg_replace('[\D]', '', $_POST['games']);
		
		if(isset($_FILES['image'])) {
			$image = $_FILES['image']['name'];
			$image_tmp = $_FILES['image']['tmp_name'];
			$imageType = mime_content_type($image_tmp);
			$imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$allowedExtensions = ["jpg", "jpeg", "bmp", "png"];
			
			if(in_array($imageExtension, $allowedExtensions) && strpos($imageType, "image") !== false) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$file = finfo_file($finfo, $image_tmp);
				$imageDate = $date->format('dmYHisu');
				$imageRand = uniqid();
				$imageNewName = $imageDate . $imageRand;
				$target_dir = "images/screenshots/" . $imageNewName . ".png";
				$count = 0;
				
				if(strpos($file, "image") !== false) {
					while(file_exists($target_dir)) {
						$imageDate = $date->format('dmYHisu');
						$imageRand = uniqid();
						$imageNewName = $imageDate . $imageRand;
						$target_dir = "images/screenshots/" . $imageNewName . ".png";
						$count++;
					}
			
					move_uploaded_file($image_tmp, $target_dir);
				}
			}
		}
	
		if(isset($skid)) {
			$sql = "SELECT skid FROM scores WHERE skid = :skid";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				':skid' => $skid
			]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($result)) {
				$sql1 = "SELECT image
						  FROM scores
						 WHERE skid = :skid";
				$stmt1 = $pdo->prepare($sql1);
				$stmt1->execute([
					':skid' => $skid
				]);
				$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
				$imageDelete = $result1['image'];
				
				unlink("images/screenshots/" . $imageDelete . ".png");
				
				$sql = "UPDATE scores 
						   SET name = :name, 
							   wins = :wins, 
							   kills = :kills, 
							   botKills = :botKills, 
							   deaths = :deaths, 
							   kdr = :kdr, 
							   level = :level, 
							   games = :games, 
							   image = :image, 
							   date = :date 
						 WHERE skid = :skid";
				$stmt = $pdo->prepare($sql);
				$stmt->execute([
					':skid' => $skid,
					':name' => $name,
					':wins' => $wins,
					':kills' => $kills,
					':botKills' => $botKills,
					':deaths' => $deaths,
					':kdr' => $kdr,
					':level' => $level,
					':games' => $games,
					':image' => $imageNewName,
					':date' => $date->format('Y-m-d')
				]);
			} else {
				$sql = "INSERT INTO scores (skid, 
											name, 
											wins, 
											kills, 
											botKills, 
											deaths, 
											kdr, 
											level, 
											games, 
											image, 
											date) 
									VALUES (:skid, 
											:name, 
											:wins, 
											:kills, 
											:botKills, 
											:deaths, 
											:kdr, 
											:level, 
											:games, 
											:image, 
											:date)";
				$stmt = $pdo->prepare($sql);
				$stmt->execute([
					':skid' => $skid,
					':name' => $name,
					':wins' => $wins,
					':kills' => $kills,
					':botKills' => $botKills,
					':deaths' => $deaths,
					':kdr' => $kdr,
					':level' => $level,
					':games' => $games,
					':image' => $imageNewName,
					':date' => $date->format('Y-m-d')
				]);
			}
		} else { //The following query will be removed once all initial entries without SKIDS get purged. At that point, SKIDS will be mandatory.
			$sql = "INSERT INTO scores (name, 
										wins, 
										kills, 
										botKills, 
										deaths, 
										kdr, 
										level, 
										games, 
										image, 
										date) 
								VALUES (:name, 
										:wins, 
										:kills, 
										:botKills, 
										:deaths, 
										:kdr, 
										:level, 
										:games, 
										:image, 
										:date)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				':name' => $name,
				':wins' => $wins,
				':kills' => $kills,
				':botKills' => $botKills,
				':deaths' => $deaths,
				':kdr' => $kdr,
				':level' => $level,
				':games' => $games,
				':image' => $imageNewName,
				':date' => $date->format('Y-m-d')
			]);
		}
	}
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="includes/style.css">
		<link rel="stylesheet" type="text/css" href="includes/style-form.css">
        <link href='https://fonts.googleapis.com/css?family=Sigmar One' rel='stylesheet'>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;800&display=swap" rel="stylesheet">
		
		<title>Add New Entry</title>
		<noscript>
		  <style>
			.js-disabled {
				display: none;
			}
		  </style>
		</noscript>
	</head>
	<body>
		<noscript>
			Please enable JavaScript!
		</noscript>
		
		<form action="insert.php" method="POST" enctype="multipart/form-data" id="submission" class="js-disabled">
			<div class="page" id="start">
				<h1 class="title">Add New Entry</h1>
				<input type="text" name="skid" class="formInput text" id="skid" pattern="[a-zA-Z0-9]+" minlength="28" maxlength="28" placeholder="SKID"><span name="skidError" id="skidError" class="error"></span>
				<br>
				<input type="text" name="name" class="formInput text" id="name" pattern="[-0-9A-Za-z_\\.' ]+" maxlength="30" placeholder="Name" required><span name="nameError" id="nameError" class="error"></span>
				<br>
				<input type="tel" name="wins" class="formInput" id="wins" placeholder="Wins" pattern="[0-9]+" required>
				<br>
				<input type="tel" name="kills" class="formInput" id="kills" placeholder="Kills" pattern="[0-9]+" required><span name="killsError" id="killsError" class="error kdr"></span>
				<br>
				<input type="tel" name="botKills" class="formInput" id="botKills" placeholder="Bot Kills" pattern="[0-9]+" required>
				<br>
				<input type="tel" name="deaths" class="formInput" id="deaths" placeholder="Deaths" pattern="[0-9]+" required><span name="deathsError" id="deathsError" class="error kdr"></span>
				<br>
				<input type="tel" name="level" class="formInput" id="level" placeholder="Level" pattern="[0-9]+" required><span name="levelError" id="levelError" class="error"></span>
				<!--Update this as level max increases-->
				<br>
				<input type="tel" name="games" class="formInput" id="games" placeholder="Games" pattern="[0-9]+" required>
				<br>
				<input type="file" name="image" class="formInput file hidden" id="image" accept="image/*" required>
				<div id="image-label">
					<span name="screenText" id="screenText">Screenshot:</span>
					<label for="image" class="button">Select File</label>
				</div>
				<span name="imageError" id="imageError" class="error"></span>
				<br>
				<div name="submit" id="submit" class="button">Submit</div>
			</div>
			<div class="page hidden" id="verify">
				Please verify your stats are correct:
				<br>
				<br>
				SKID: <span class="value" id="skidValue"></span>
				<br>
				Name: <span class="value" id="nameValue"></span>
				<br>
				Wins: <span class="value" id="winsValue"></span>
				<br>
				Kills <span class="value" id="killsValue"></span>
				<br>
				Bot Kills: <span class="value" id="botKillsValue"></span>
				<br>
				Deaths: <span class="value" id="deathsValue"></span>
				<br>
				KDR: <span class="value" id="kdrValue"></span>
				<br>
				Level: <span class="value" id="levelValue"></span>
				<br>
				Games: <span class="value" id="gamesValue"></span>
				<br>
				Screenshot: <span class="value" id="imageValue"></span>
				<br>
				<br>
				<button name="confirm" id="confirm" onclick="showHide('verify', 'done')">Yes, looks good</button>&nbsp;
			</form>
				<button type="button" name="return" id="return" onclick="showHide('verify', 'start')">No, go back</button>
		</div>
		<div class="page hidden" id="done">
			Thank you! Your stats have been submitted. Taking you back!
		</div>
		<script type="text/javascript" src="includes/script.js"></script>
	</body>
</html>

<script>
    window.onload = function() {
        history.replaceState("", "", "insert.php");
    }
</script>