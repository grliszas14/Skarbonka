<?php
	session_start();

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0) {
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		$id_rekordu = $_SESSION['id_rekordu'];
		$data_new = $_POST['fdate2'];
		$na_co_new = $_POST['fitem2'];
		$kto_new = $_POST['fwho2'];
		$ile_new = $_POST['fhowmuch2'];

		$sql = "UPDATE wplaty2 SET data='$data_new', na_co='$na_co_new', kto='$kto_new', ile='$ile_new'
			WHERE id='$id_rekordu'";

		@$polaczenie->query($sql);

		header('Location: index.php');
		$polaczenie->close();

	}
?>
