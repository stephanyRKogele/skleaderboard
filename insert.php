<?php
	require_once("./includes/db.php");

	$date = new DateTime();
	
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
	}
?>

<html>
	<head>
		<title>Insert Player</title>
	</head>
	<body>
		<form action="insert.php" method="POST" enctype="multipart/form-data" id="submission">
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
				<input type="number" name="wins" class="formInput" id="wins" placeholder="Wins" required>
				<br>
				Kills
				<br>
				<input type="number" name="kills" class="formInput" id="kills" placeholder="Kills" required><span name="error" id="test" class="error"></span>
				<br>
				Bot Kills
				<br>
				<input type="number" name="botKills" class="formInput" id="botKills" placeholder="Bot Kills" required>
				<br>
				Deaths
				<br>
				<input type="number" name="deaths" class="formInput" id="deaths" placeholder="Deaths" required><span name="error" class="error"></span>
				<br>
				Level
				<br>
				<input type="number" name="level" class="formInput" id="level" placeholder="Level" min="1" max="80" required>
				<!--Update this as level max increases-->
				<br>
				Games
				<br>
				<input type="number" name="games" class="formInput" id="games" placeholder="Games" required>
				<br>
				<!--<input type="file" name="image" class="formInput" id="image">
				<br>-->
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
				<br>
				<button name="confirm" id="confirm" onclick="showHide('verify', 'done')">Yes, looks good</button>&nbsp;
			</form>
				<button type="button" name="return" id="return" onclick="showHide('verify', 'start')">No, go back</button>
		</div>
		<div class="page" id="done" style="display: none;">
			Thank you! Your stats have been submitted. Taking you back!
		</div>
		<script>
			document.getElementById("submit").addEventListener("click", isValid);
				
			function isValid() {
				if(!document.getElementById("submission").checkValidity()) {
					document.getElementById("submission").reportValidity();
				} else {
					checkKdr();
					//showHide('start', 'verify');
				}
			}
			
			function checkKdr() {
				var kills = document.getElementById("kills").value;
				var deaths = document.getElementById("deaths").value;
				var errorSpan = document.getElementsByClassName("error");
				var kdr = kills / deaths;
				kdr = kdr.toFixed(2);
				
				if(kdr > 99.99) {
					for(let i = 0; i < errorSpan.length; i++) {
						errorSpan[i].innerHTML = "&nbsp;Your KDR cannot be higher than 99.99%!";
					}
					
					clearText();
				} else {
					getVariables(kdr);
				}
			}
			
			function clearText() {
				document.getElementById("kills").value = "";
				document.getElementById("deaths").value = "";
			}

			function getVariables(k) {
				var formInput = document.getElementsByClassName("formInput");
				var formValue = [];
				
				for(let i = 0; i < formInput.length; i++) {
					formValue.push(formInput[i].id + "Value");
					
					document.getElementById(formValue[i]).innerHTML = formInput[i].value;
				}
				
				document.getElementById("kdrValue").innerHTML = k;
				
				showHide('start', 'verify');
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
	</body>
</html>		