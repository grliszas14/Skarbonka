<?php
	session_start();

if($_POST["add"]) {
	require_once "connect.php";

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0) {
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		$data = $_POST['fdate'];
		$na_co = $_POST['fitem'];
		$kto = $_POST['fwho'];
		$ile = $_POST['fhowmuch'];

		$sql = "INSERT INTO wplaty2 (data, na_co, kto, ile)
			VALUES('$data', '$na_co', '$kto', '$ile')";

		@$polaczenie->query($sql);
		header('Location: index.php');

		$polaczenie->close();
	}
}

if($_POST["edit"]){

	require_once "connect.php";

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0) {
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		$data = $_POST['fdate'];
		$na_co = $_POST['fitem'];
		$kto = $_POST['fwho'];
		$ile = $_POST['fhowmuch'];

		$sql = "SELECT * FROM wplaty2 WHERE data='$data' AND na_co='$na_co'
			AND kto='$kto' AND ile='$ile'";

		if($result = @$polaczenie->query($sql)) {
			$ile_odp = $result->num_rows;
			$rekord = $result->fetch_assoc();
			$_SESSION['id_rekordu'] = $rekord['id'];
			$polaczenie->close();
			if($ile_odp == 1){
				echo '<form action="edit.php" method="post">',
					'Aktualne dane:<br><br>',
					'Data (yyyy-mm-dd):<br><input type="text" name="fdate" id="fdate" value="',$data,'" disabled><br>',
					'Na co : <br><input type="text" name="fitem" id="fitem" value="',$na_co,'" disabled><br>',
					'Kto :<br><input type="text" name="fwho" id="fwho" value="',$kto,'" disabled><br>',
					'Ile : <br><input type="text" name="fhowmuch" id="fhowmuch" value="',$ile,'" disabled><br><br><br>',
					'Nowe dane:<br><br>',
					'Data (yyyy-mm-dd):<br><input type="text" name="fdate2" id="fdate2" value="',$data,'"><br>',
					'Na co : <br><input type="text" name="fitem2" id="fitem2" value="',$na_co,'"><br>',
					'Kto :<br><input type="text" name="fwho2" id="fwho2" value="',$kto,'"><br>',
					'Ile : <br><input type="text" name="fhowmuch2" id="fhowmuch2" value="',$ile,'"><br>',


					'<br><input type="submit" value="Edytuj" name="edit_second"/>',
					'</form>';
			}
		}
	}

}
?>
