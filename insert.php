<?php
	require_once("./includes/db.php");

	$date = new DateTimeImmutable();
	
	if(isset($_POST['confirm'])) {
		sleep(3);
		
		$skid = trim($_POST['skid']);
		$name = trim($_POST['name']);
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
			$imageDate = $date->format('dmYHisu');
			$imageRand = uniqid();
			$imageNewName = $imageDate . $imageRand;
			$target_dir = "images/" . $imageNewName . ".png";
			
			if(in_array($imageExtension, $allowedExtensions) && strpos($imageType, "image") !== false) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$file = finfo_file($finfo, $image_tmp);
				
				if(strpos($file, "image") !== false) {
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
				$sql = "UPDATE scores SET name = :name, wins = :wins, kills = :kills, botKills = :botKills, deaths = :deaths, kdr = :kdr, level = :level, games = :games, image = :image, date = :date WHERE skid = :skid";
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
				$sql = "INSERT INTO scores (skid, name, wins, kills, botKills, deaths, kdr, level, games, image, date) VALUES (:skid, :name, :wins, :kills, :botKills, :deaths, :kdr, :level, :games, :image, :date)";
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
			$sql = "INSERT INTO scores (name, wins, kills, botKills, deaths, kdr, level, games, image, date) VALUES (:name, :wins, :kills, :botKills, :deaths, :kdr, :level, :games, :image, :date)";
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
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href='https://fonts.googleapis.com/css?family=Sigmar One' rel='stylesheet'>
		
		<title>Insert Player</title>
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
			<div class="page" id="start" style="display: block;">
				SKID
				<br>
				<input type="text" name="skid" class="formInput" id="skid" placeholder="SKID">
				<br>
				Name
				<br>
				<input type="text" name="name" class="formInput" id="name" placeholder="Name" required>
				<br>
				Wins
				<br>
				<input type="number" name="wins" class="formInput" id="wins" placeholder="Wins" min = "0" max="99999999" required>
				<br>
				Kills
				<br>
				<input type="number" name="kills" class="formInput" id="kills" placeholder="Kills" min = "0" max="99999999" required><span name="error" id="killsError" class="error"></span>
				<br>
				Bot Kills
				<br>
				<input type="number" name="botKills" class="formInput" id="botKills" placeholder="Bot Kills" min = "0" max="99999999" required>
				<br>
				Deaths
				<br>
				<input type="number" name="deaths" class="formInput" id="deaths" placeholder="Deaths" min = "0" max="99999999" required><span name="error" id="deathsError" class="error"></span>
				<br>
				Level
				<br>
				<input type="number" name="level" class="formInput" id="level" placeholder="Level" min="1" max="80" required>
				<!--Update this as level max increases-->
				<br>
				Games
				<br>
				<input type="number" name="games" class="formInput" id="games" placeholder="Games" min = "0" max="99999999" required>
				<br>
				Screenshot
				<br>
				<input type="file" name="image" class="formInput" id="image" accept="image/*" required><span name="error" id="imageError" class="error"></span>
				<br>
				<br>
				<button type="button" name="submit" id="submit">Submit</button>
			</div>
			<div class="page" id="verify" style="display: none;">
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
		<div class="page" id="done" style="display: none;">
			Thank you! Your stats have been submitted. Taking you back!
		</div>
		<script type="text/javascript" src="includes/script.js"></script>
	</body>
</html>		