<?php
	require_once("./includes/db.php");
	
	$stat = ['Name', 
			 'Wins', 
			 'Kills', 
			 'Bot Kills', 
			 'Deaths', 
			 'KDR', 
			 'Level', 
			 'Games', 
			 'Screenshot',
			 'Date'];
	
	if(isset($_POST['sorting'])) {
		$sortOrder = $_POST['sorting'];
	} else {
		$sortOrder = 'kills DESC';
	}
	
	echo 'Sort Order: ' . $sortOrder;
		
	$sql = "SELECT skid, 
				   name, 
				   wins, 
				   kills, 
				   botKills, 
				   deaths, 
				   kdr, 
				   level, 
				   games, 
				   image, 
				   date
			  FROM scores 
		  ORDER BY " . $sortOrder;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$filename = $row['image'];
		$date = new DateTime($row['date']);
		
		$player[$row['skid']] = ['Name' => htmlspecialchars($row['name']), 
								 'Wins' => $row['wins'], 
								 'Kills' => $row['kills'],
								 'Bot Kills' => $row['botKills'],
								 'Deaths' => $row['deaths'],
								 'KDR' => $row['kdr'],
								 'Level' => $row['level'],
								 'Games' => $row['games'],
								 'Filename' => $row['image'],
								 'Screenshot' => 'images/screenshots/' . $filename . '.png',
								 'Date' => $date->format('n-j-Y')];
	}
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="includes/style.css">
        <link href='https://fonts.googleapis.com/css?family=Sigmar One' rel='stylesheet'>
		<script src="https://kit.fontawesome.com/ad8eb128d3.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <img src="images/title.png" alt="Smash Karts Ranks" id="title">
		<div id="mainGrid">
			<form action="index.php" method="POST">
				<table border="0">
					<tr>
						<td></td>
						<?php
							foreach($stat as $title) {
								if($title !== 'Screenshot' && $title !== 'Filename') {
						?>
							<td>
								<b><?php echo $title; ?></b>
								<button class="fas fa-caret-square-up arrow" name="sorting" value="<?php echo strtolower($title) . ' ASC'; ?>">
								</button>
								<button class="fas fa-caret-square-down arrow" name="sorting" value="<?php echo $title . ' DESC'; ?>">
								</button>
							</td>
								<?php
									}
							}
								?>
					</tr>
					<?php
						foreach($player as $skid => $attArr) {
					?>
						<tr>
							<td>
								<div class="fas fa-camera screenshot" name="screenshot" id="<?php echo $player[$skid]['Screenshot']; ?>"></div>
								<div id="lightbox" class="lightbox-hide" style="display:none;"></div>
							</td>
					<?php
							foreach($attArr as $attribute => $value) {
								if($attribute !== 'Screenshot' && $attribute !== 'Filename') {
									echo '<td>' . $value . '</td>';
								}
							}
							echo '</tr>';
						}
					?>
				</table>
			</form>
		</div>
	</body>
</html>

<script type="text/javascript" src="includes/script-lightbox.js"></script>
<script>
    window.onload = function() {
        history.replaceState("", "", "index.php");
    }
</script>