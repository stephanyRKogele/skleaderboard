<?php
	require_once("./includes/db.php");
?>

<script>
	function getVariables() {
		document.getElementById("skidValue").innerHTML = document.getElementById("skid").value;
		document.getElementById("nameValue").innerHTML = document.getElementById("name").value;
		document.getElementById("winsValue").innerHTML = document.getElementById("wins").value;
		document.getElementById("killsValue").innerHTML = document.getElementById("kills").value;
		document.getElementById("botKillsValue").innerHTML = document.getElementById("botKills").value;
		document.getElementById("deathsValue").innerHTML = document.getElementById("deaths").value;
		document.getElementById("levelValue").innerHTML = document.getElementById("level").value;
		document.getElementById("gamesValue").innerHTML = document.getElementById("games").value;
	}
	
	function showHide(currId, nextId) {
		var cId = currId;
		var nId = nextId;
		
		if(document.getElementById(cId).style.display == "block") {
			document.getElementById(cId).style.display = "none";
		} else if(document.getElementById(cId).style.display == "none") {
			document.getElementById(cId).style.display = "block";
		}
		
		if(document.getElementById(nId).style.display == "block") {
			document.getElementById(nId).style.display = "none";
		} else if(document.getElementById(nId).style.display == "none") {
			document.getElementById(nId).style.display = "block";
		}
	}
</script>

<?php
	$date = new DateTime();
	
	if(isset($_POST['confirm'])) {
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
		$target_dir = "images/";
		
		// if(isset($_FILES['image'])) {
		// $image = $_FILES['image']['name'];
		// $image_tmp = $_FILES['image']['tmp_name'];
		// move_uploaded_file($image_tmp, $target_dir);
		// }
	
	if(isset($skid)) {
			$sql = "SELECT skid FROM scores WHERE skid = :skid";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				':skid' => $skid
			]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($result)) {
				$sql = "UPDATE scores SET name = :name, wins = :wins, kills = :kills, botKills = :botKills, deaths = :deaths, kdr = :kdr, level = :level, games = :games, date = :date WHERE skid = :skid";
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
					':date' => $date->format('Y-m-d')
				]);
			} else {
				$sql = "INSERT INTO scores (skid, name, wins, kills, botKills, deaths, kdr, level, games, date) VALUES (:skid, :name, :wins, :kills, :botKills, :deaths, :kdr, :level, :games, :date)";
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
					':date' => $date->format('Y-m-d')
				]);
			}
		} else { //The following query will be removed once all initial entries without SKIDS get purged. At that point, SKIDS will be mandatory.
			echo "Name: " . htmlspecialchars($name);
			echo "<br>";
			echo "Wins: " . $wins;
			echo "<br>";
			echo "Kills " . $kills;
			echo "<br>";
			echo "Bot Kills: " . $botKills;
			echo "<br>";
			echo "Deaths: " . $deaths;
			echo "<br>";
			echo "KDR: " . $kdr;
			echo "<br>";
			echo "Level: " . $level;
			echo "<br>";
			echo "Games: " . $games;
			echo "<br>";
			echo "Last Updated: " . $date->format('m-d-Y');
			
			$sql = "INSERT INTO scores (name, wins, kills, botKills, deaths, kdr, level, games, date) VALUES (:name, :wins, :kills, :botKills, :deaths, :kdr, :level, :games, :date)";
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
				':date' => $date->format('Y-m-d')
			]);
		}
		
		header("Refresh: 3; Location: insert.php");
	}
?>

<html>
	<head>
		<title>Insert Player</title>
	</head>
	<body>
		<form action="insert.php" method="POST" enctype="multipart/form-data">
			<div class="page" id="start" style="display: block;">
				SKID
				<br>
				<input type="text" name="skid" id="skid" placeholder="SKID" oninput="getVariables()">
				<br>
				Name
				<br>
				<input type="text" name="name" id="name" placeholder="Name" oninput="getVariables()" required>
				<br>
				Wins
				<br>
				<input type="number" name="wins" id="wins" placeholder="Wins" oninput="getVariables()" required>
				<br>
				Kills
				<br>
				<input type="number" name="kills" id="kills" placeholder="Kills" oninput="getVariables()" required>
				<br>
				Bot Kills
				<br>
				<input type="number" name="botKills" id="botKills" placeholder="Bot Kills" oninput="getVariables()" required>
				<br>
				Deaths
				<br>
				<input type="number" name="deaths" id="deaths" placeholder="Deaths" oninput="getVariables()" required>
				<br>
				Level
				<br>
				<input type="number" name="level" id="level" placeholder="Level" min="1" max="99" oninput="getVariables()" required>
				<br>
				Games
				<br>
				<input type="number" name="games" id="games" placeholder="Games" oninput="getVariables()" required>
				<br>
				<!--<input type="file" name="image" id="image">
				<br>-->
				<button type="button" name="submit" id="submit" onClick="showHide('start', 'verify')">Submit</button>
			</div>
			<div class="page" id="verify" style="display: none;">
				Please verify your stats are correct:
				<br>
				<br>
				SKID: <span id="skidValue"></span>
				<br>
				Name: <span id="nameValue"></span>
				<br>
				Wins: <span id="winsValue"></span>
				<br>
				Kills <span id="killsValue"></span>
				<br>
				Bot Kills: <span id="botKillsValue"></span>
				<br>
				Deaths: <span id="deathsValue"></span>
				<br>
				KDR: <?php //echo $kdr; ?>
				<br>
				Level: <span id="levelValue"></span>
				<br>
				Games: <span id="gamesValue"></span>
				<br>
				<br>
				<button type="button" name="confirm" id="confirm" onclick="showHide('verify', 'done')">Yes, looks good</button>&nbsp;
			</form>
				<button type="button" name="return" id="return" onclick="showHide('verify', 'start')">No, go back</button>
		</div>
		<div class="page" id="done" style="display: none;">
			Thank you! Your stats have been submitted.
		</div>
	</body>
</html>		