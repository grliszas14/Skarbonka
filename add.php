<?php
	session_start();

if($_POST["add"]) {
	
	// Connect to MySQL database
	require_once "connect.php";
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	// Check if connection is successful and
	// insert new record in the database
	if ($polaczenie->connect_errno!=0) {
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		$data = $_POST['fdate'];
		$na_co = $_POST['fitem'];
		$kto = $_POST['fwho'];
		$ile = $_POST['fhowmuch'];
		$wyplata = $_POST['wyplata'];

		if($wyplata == "wplata") {
			$sql = "INSERT INTO wplaty (data, na_co, kto, ile)
				VALUES('$data', '$na_co', '$kto', '$ile')";
		} elseif ($wyplata == "wyplata") {
			$ile = -$ile;
			$sql = "INSERT INTO wplaty (data, na_co, kto, ile)
				VALUES('$data', '$na_co', '$kto', '$ile')";

		}

		@$polaczenie->query($sql);
		header('Location: index.php');

		$polaczenie->close();
	}
}

if($_POST["edit"]){

	// Connect to MySQL database
	require_once "connect.php";
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	// Check if connection is successful and
	// get the proper row from database
	if ($polaczenie->connect_errno!=0) {
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		$data = $_POST['fdate'];
		$na_co = $_POST['fitem'];
		$kto = $_POST['fwho'];
		$ile = $_POST['fhowmuch'];
		$wyplata = $_POST['wyplata'];
		$id = $_POST['fid'];

		// Conversion needed to find in database
		$sql = "SELECT * FROM wplaty WHERE id='$id'";

		if($result = @$polaczenie->query($sql)) {
			$ile_odp = $result->num_rows;
			$rekord = $result->fetch_assoc();
			$_SESSION['id_rekordu'] = $rekord['id'];
			$polaczenie->close();
			if($ile_odp == 1){
				// Display edit panel 
				echo '<form action="edit.php" method="post">',
					'Aktualne dane:<br><br>',
					'Data (yyyy-mm-dd):<br><input type="text" name="fdate" id="fdate" value="',$data,'" disabled><br>',
					'Na co : <br><input type="text" name="fitem" id="fitem" value="',$na_co,'" disabled><br>',
					'Kto :<br><input type="text" name="fwho" id="fwho" value="',$kto,'" disabled><br><br>',
					'Działanie: ',$wyplata,'<br><br>',
					'Ile : <br><input type="text" name="fhowmuch" id="fhowmuch" value="',$ile,'" disabled><br><br><br>',
					'Nowe dane:<br><br>',
					'Data (yyyy-mm-dd):<br><input type="text" name="fdate2" id="fdate2" value="',$data,'"><br>',
					'Na co : <br><input type="text" name="fitem2" id="fitem2" value="',$na_co,'"><br>',
					'Kto : <br><select name="fwho2" id="fwho2">',
							'<option value="qui">qui</option>',
							'<option value="mglinka">mglinka</option>',
							'<option value="pciechomski">pciechomski</option>',
							'<option value="lucek">lucek</option>',
							'<option value="kuban">kuban</option>',
							'<option value="gwojciech">gwojciech</option>',
							'<option value="match">match</option>',
							'<option value="bdrogosiewicz">bdrogosiewicz</option>',
							'<option value="jc">jc</option>',
							'<option value="abarcz">abarcz</option>',
							'<option value="mmorusiewicz">mmorusiewicz</option>',
							'<option value="spychy">spychy</option>',
							'<option value="michalch">michalch</option>',
							'<option value="marta">marta</option>',
							'<option value="jlukomski">jlukomski</option>',
						'</select><br>',
						'<script type="text/javascript">',
						'document.getElementById("fwho2").value = "',$kto,'";',
						'</script>',
					'<input type="radio" id="wyplata" name="wyplata" value="wyplata">Wypłata</input>',
					'<input type="radio" id="wplata" name="wyplata" value="wplata">Wpłata</input><br>',
					'Ile : <br><input type="text" name="fhowmuch2" id="fhowmuch2" value="',$ile,'"><br>',
					'<script type="text/javascript">',
					'if("',$wyplata,'" === "wplata") {',
						'document.getElementById("wplata").checked = true;',
					'} else {',
						'document.getElementById("wyplata").checked = true;',
						'document.getElementById("fhowmuch").value =',$ile,';',
						'document.getElementById("fhowmuch2").value =',$ile,';',
					'}',
					'</script>',

					'<br><input type="submit" value="Edytuj" name="edit_second"/>',
					'</form>';
			}
		}
	}

}
?>
