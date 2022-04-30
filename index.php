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
			 'Screenshot'];
	
	if(isset($_POST['sorting'])) {
		$sortOrder = $_POST['sorting'];
	} else {
		$sortOrder = 'kills DESC';
	}
	
	echo 'Sort Order: ' . $sortOrder;
		
	$sql = "SELECT name, 
				   wins, 
				   kills, 
				   botKills, 
				   deaths, 
				   kdr, 
				   level, 
				   games, 
				   image 
			  FROM scores 
		  ORDER BY " . $sortOrder;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$player[htmlspecialchars($row['name'])] = [$row['wins'], 
												   $row['kills'],
												   $row['botKills'],
												   $row['deaths'],
												   $row['kdr'],
												   $row['level'],
												   $row['games'],
												   '<img src="images/screenshots/' . $row['image'] . '.png" height="100">'];
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
						<?php
							foreach($stat as $title) {
						?>
								<td>
									<b><?php echo $title; ?></b>
									<?php
										if($title !== 'Screenshot') {
									?>
									<button class="fas fa-caret-square-up arrow" name="sorting" id="sorting" value="<?php echo strtolower($title) . ' ASC'; ?>">
									</button>
									<button class="fas fa-caret-square-down arrow" name="sorting" id="sorting" value="<?php echo $title . ' DESC'; ?>">
									</button>
									<?php
										}
									?>
								</td>
						<?php
							}
						?>
					</tr>
					<?php
						foreach($player as $name => $stat) {
							echo '<tr>';
							echo '<td>' . $name . '</td>';
							foreach($stat as $value) {
								echo '<td>' . $value . '</td>';
							}
							echo '</tr>';
						}
					?>
				</table>
			</form>
		</div>
	</body>
</html>

<script>
    window.onload = function() {
        history.replaceState("", "", "index.php");
    }
</script>