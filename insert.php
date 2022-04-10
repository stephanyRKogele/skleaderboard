<?php
	require_once("./includes/db.php");

	$date = new DateTime();
	$confirm = '<button name="confirm" id="confirm">Yes, looks good</button>';
	$return = '<button name="return" id="return">No, go back</button>';
	
	if(isset($_POST['submit'])) {
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
		
		if(isset($skid)) { //This line is only for initial input to add all current entries without a SKID, until they get purged.
			echo "Please verify your stats are correct: ";
			echo "<br>";
			echo "<br>";
			echo "SKID: " . htmlspecialchars($skid);
			echo "<br>";
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
			// echo "Last Updated: " . $date->format('n-j-Y');
			echo "<br>";
			echo '<form action="insert.php" method="POST" enctype="multipart/form-data">';
			echo $confirm . " " . $return;
			echo "</form>";
		} else {
			echo "Please verify your stats are correct: ";
			echo "<br>";
			echo "<br>";
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
			// echo "Last Updated: " . $date->format('n-j-Y');
			echo "<br>";
			echo '<form action="insert.php" method="POST" enctype="multipart/form-data">';
			echo $confirm . " " . $return;
			echo "</form>";
		}
	}
		
	if(isset($_POST['confirm'])) {
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
		<p id="demo"></p>
		<form action="insert.php" method="POST" enctype="multipart/form-data">
			<?php
				if(!isset($_POST['submit']) || isset($_POST['return'])) {
			?>
					SKID
					<br>
					<input type="text" name="skid" id="skid" <?php echo (isset($skid) ? 'value="' . $skid . '"' : 'placeholder="SKID"'); ?>">
					<br>
					Name
					<br>
					<input type="text" name="name" id="name" placeholder="Name" required>
					<br>
					Wins
					<br>
					<input type="number" name="wins" id="wins" placeholder="Wins" required>
					<br>
					Kills
					<br>
					<input type="number" name="kills" id="kills" placeholder="Kills" required>
					<br>
					Bot Kills
					<br>
					<input type="number" name="botKills" id="botKills" placeholder="Bot Kills" required>
					<br>
					Deaths
					<br>
					<input type="number" name="deaths" id="deaths" placeholder="Deaths" required>
					<br>
					Level
					<br>
					<input type="number" name="level" id="level" placeholder="Level">
					<br>
					Games
					<br>
					<input type="number" name="games" id="games" placeholder="Games" required>
					<br>
					<!--<input type="file" name="image" id="image">
					<br>-->
					<button name="submit" id="submit">Submit</button>
			<?php
				} else if(isset($_POST['confirm'])) {
					echo "Thank you! Your stats have been submitted.";
					header("Location: insert.php", 3);
				}
			?>
		</form>
	</body>
</html>