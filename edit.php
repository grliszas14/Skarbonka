<?php
	session_start();

	// Connect to MySQL database
	require_once "connect.php";
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	// Check if connection is successful and
	// edit row
	if ($polaczenie->connect_errno!=0) {
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		$id_rekordu = $_SESSION['id_rekordu'];
		$data_new = $_POST['fdate2'];
		$na_co_new = $_POST['fitem2'];
		$kto_new = $_POST['fwho2'];
		$ile_new = $_POST['fhowmuch2'];
		$wyplata_new = $_POST['wyplata'];

		if($wyplata_new == "wplata") {
			$sql = "UPDATE wplaty SET data='$data_new', na_co='$na_co_new', kto='$kto_new', ile='$ile_new'
				WHERE id='$id_rekordu'";
		} elseif($wyplata_new == "wyplata") {
			$ile_new = -$ile_new;
			$sql = "UPDATE wplaty SET data='$data_new', na_co='$na_co_new', kto='$kto_new', ile='$ile_new'
				WHERE id='$id_rekordu'";
			$ile_new = -$ile_new;
		}

		@$polaczenie->query($sql);

		header('Location: index.php');
		$polaczenie->close();
	}
?>
