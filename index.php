<!DOCTYPE html>
<html lang="pl">
	<head>
		<title>Skarbonka Newterm</title>
		<meta charset="utf-8" />

		<style>
			.container {
				overflow: hidden
				}
			.tab {
				float: left; 
				margin-left: 50px
				}
			.tab-2 {
				float: right; 
				margin-left: 50px; 
				margin-right: 50px; 
				position: fixed; 
				right: 50px
				}
			.tab-2 input {
				display: block; 
				margin-bottom: 20px
				}
				tr {
				transition:all .25s ease-in-out
				}
				tr:hover {
				background-color: #a1a0a3; 
				cursor: pointer
				}
			.header img {
				display: block; 
				margin-left: auto; 
				margin-right: auto; 
				margin-bottom: 50px; 
				width: 500px
				}
			.sum {
				float: left; 
				margin-left: 50px; 
				position: fixed; 
				right: 400px
				}
			.leakage {
				background-color: #ff0000;
				}
			.bonus {
				background-color: #00ff00;
				}
			.selected {
				background-color: #ff7178;
				}
			.buttons input {
				display: inline;
				}
			.radiobuttons input {
				display: inline;
				}
		</style>
	</head>
	<body>

		<!-- Logo -->
		<div class="header">
			<img src="public/images/skarbonka.png" />
		</div>

		<div class="container">
			<!-- Table header -->
			<div class="tab tab-1">
				<table id="table" border="1">
					<tr>
						<th>Data</th>
						<th>Na co</th>
						<th>Kto</th>
						<th>Ile [zł]</th>
					</tr>
				</table>
			</div>

			<!-- Balance -->
			<div class="sum">
				<h2>Stan skarbonki: </h2>
				<span id="moneyBoxCondition">0 zł</span>
				<br><br><br><h3>Dodawanie:</h3>
				Wpisz dane w formularzu po<br>
				prawej stronie i kliknij "Dodaj"<br>
				<h3>Edycja:</h3>
				Zaznacz wybrany rekord tak, aby<br>
				podświetlił się na różowo, a<br>
				następnie klinij "Edytuj"
			</div>

			<!-- Form on the right side -->
			<div class="tab tab-2">
				<form action="add.php" method="post">
					Data (yyyy-mm-dd):<input type="text" name="fdate" id="fdate">
					Na co : <input type="text" name="fitem" id="fitem">
					Kto : <select name="fwho" id="fwho">
							<!-- WRITE NEW EMPLOYEE HERE/ also in add.php -->
							<option value="qui">qui</option>
							<option value="mglinka">mglinka</option>
							<option value="pciechomski">pciechomski</option>
							<option value="lucek">lucek</option>
							<option value="kuban">kuban</option>
							<option value="gwojciech">gwojciech</option>
							<option value="match">match</option>
							<option value="jc">jc</option>
							<option value="abarcz">abarcz</option>
							<option value="mmorusiewicz">mmorusiewicz</option>
							<option value="spychy">spychy</option>
							<option value="michalch">michalch</option>
							<option value="marta">marta</option>
						</select><br><br>	
					Ile (xx.xx bez znaku!): <input type="text" name="fhowmuch" id="fhowmuch">

					<div class="radiobuttons">
						<input type="radio" id="wyplata" name="wyplata" value="wyplata" checked="checked">Wypłata</input>
						<input type="radio" id="wplata" name="wyplata" value="wplata">Wpłata</input>
					</div>

					<div class="buttons">
						<input onclick="addHTMLTableRow();" type="submit" value="Dodaj" name="add"/>
						<input onclick="editHTMLTableSelectedRow()" type="submit" value="Edytuj" name="edit"/>
						<!-- deleting records not working now
						<input onclick="removeSelectedRow()" type="submit" value="Usuń"/> -->
					</div>
				</form>
			</div>

		</div>

		<script>
			var rIndex,											// table row index
				table = document.getElementById("table");		// main table with records

			function defaultTodaysDateInInput() {
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; 					// January is 0!
				var yyyy = today.getFullYear();

				if(dd<10) {
					dd = '0' + dd;
				}

				if(mm<10) {
					mm = '0' + mm;
				}

				today = yyyy + '-' + mm + '-' + dd;
				document.getElementById("fdate").value = today;
			}
			defaultTodaysDateInInput();

			// check the empty input in form
			function checkEmptyInput() {
				var isEmpty = false,
					fdate = document.getElementById("fdate").value,
					fitem = document.getElementById("fitem").value,
					fwho = document.getElementById("fwho").value,
					fhowmuch = document.getElementById("fhowmuch").value;

				if (fdate === "") {
					alert("Data nie może być pusta !");
					isEmpty = true;
				}
				if (fitem === "") {
					alert("Wpisz na co poszła kasa ! ;-)");
					isEmpty = true;
				}
				if (fwho === "") {
					alert("Anonim ? ");
					isEmpty = true;
				}
				if (fhowmuch === "") {
					alert("Zapisz ile to kosztowało !");
					isEmpty = true;
				}
				return isEmpty;
			}

			// create new row and cells
			// get values from input text
			// set values into row cell's
			function addHTMLTableRow() {
				if (!checkEmptyInput()) {
					var	newRow = table.insertRow(table.length),
						cellDate = newRow.insertCell(0),
						cellItem = newRow.insertCell(1),
						cellWho = newRow.insertCell(2),
						cellHowMuch = newRow.insertCell(3),
						fdate = document.getElementById("fdate").value,
						fitem = document.getElementById("fitem").value,
						fwho = document.getElementById("fwho").value,
						fhowmuch = document.getElementById("fhowmuch").value;

					cellDate.innerHTML = fdate;
					cellItem.innerHTML = fitem;
					cellWho.innerHTML = fwho;
					cellHowMuch.innerHTML = fhowmuch;

					// call the function to set the event to the new row
					selectedRowToInput();
					calculateActualMoneyBoxState();
				}
			}

			function addHTMLTableRowPhp(data, na_co, kto, ile) {
					var	newRow = table.insertRow(table.length),
						cellDate = newRow.insertCell(0),
						cellItem = newRow.insertCell(1),
						cellWho = newRow.insertCell(2),
						cellHowMuch = newRow.insertCell(3),
						fdate = data,
						fitem = na_co,
						fwho = kto,
						fhowmuch = ile;

					cellDate.innerHTML = fdate;
					cellItem.innerHTML = fitem;
					cellWho.innerHTML = fwho;
					cellHowMuch.innerHTML = fhowmuch;

					if (na_co.match(/manko/i)) {
						newRow.classList.toggle("leakage");
					} else if (na_co.match(/płata/i) || na_co.match(/plata/i)) {
						newRow.classList.toggle("bonus");
					}

					// call the function to set the event to the new row
					selectedRowToInput();
					calculateActualMoneyBoxState();
			}

			// display selected row data into form
			function selectedRowToInput() {

				for (var i = 1; i < table.rows.length; i++) {

					table.rows[i].onclick = function() {

						if(typeof rIndex !== "undefined") {
							table.rows[rIndex].classList.toggle("selected");
						}
						// get the selected row index
						rIndex = this.rowIndex;
						document.getElementById("fdate").value = this.cells[0].innerHTML;
						document.getElementById("fitem").value = this.cells[1].innerHTML;
						document.getElementById("fwho").value = this.cells[2].innerHTML;
						
						checkSign = this.cells[3].innerHTML;
						if(checkSign >= 0){
							document.getElementById("fhowmuch").value = checkSign;
							document.getElementById("wplata").checked = true;
						} else {
							document.getElementById("fhowmuch").value = -checkSign;
							document.getElementById("wyplata").checked = true;
						}
							

						this.classList.toggle("selected");

					};
				}
			}
			selectedRowToInput();

			function editHTMLTableSelectedRow() {
				var fdate = document.getElementById("fdate").value,
					fitem = document.getElementById("fitem").value,
					fwho = document.getElementById("fwho").value,
					fhowmuch = document.getElementById("fhowmuch").value;

				if (!checkEmptyInput()) {
					table.rows[rIndex].cells[0].innerHTML = fdate;
					table.rows[rIndex].cells[1].innerHTML = fitem;
					table.rows[rIndex].cells[2].innerHTML = fwho;
					table.rows[rIndex].cells[3].innerHTML = fhowmuch;
				}
				calculateActualMoneyBoxState();
			}

			function removeSelectedRow() {
				// clear input text
				table.deleteRow(rIndex);
				
				document.getElementById("fdate").value = "";
				document.getElementById("fitem").value = "";
				document.getElementById("fwho").value = "";
				document.getElementById("fhowmuch").value = "";

				calculateActualMoneyBoxState();
			}

			function calculateActualMoneyBoxState() {

				var sumVar = 0;
				for (var i = 1; i < table.rows.length; i++) {
					sumVar = sumVar + parseFloat(table.rows[i].cells[3].innerHTML);
				}

				sumVar = sumVar.toFixed(2);
				document.getElementById("moneyBoxCondition").innerHTML = sumVar+" zł";
			}
			calculateActualMoneyBoxState();

			// TEMPORARILY UNUSED
			// the next 2 functions are for downloading CSV file from the table
			function downloadCSV(csv, filename) {
				var csvFile;
				var downloadLink;

				csvFile = new Blob([csv], {type:"text/csv"});

				downloadLink = document.createElement("a");
				downloadLink.download = filename;
				downloadLink.href = window.URL.createObjectURL(csvFile);
				downloadLink.style.display = "none";

				document.body.appendChild(downloadLink);

				downloadLink.click();
			}

			function exportTableToCSV(filename) {
				var csv = [];
				var rows = document.querySelectorAll("table tr");

				for(var i = 0; i < rows.length; i++) {
					var row = [], cols = rows[i].querySelectorAll("td, th");
					for(var j = 0; j < cols.length; j++) {
						row.push(cols[j].innerText);
					}

					csv.push(row.join(","));
				}

				// download CSV file
				downloadCSV(csv.join("\n"), filename);
			}

		</script>
		<?php
			//Get user logged in
			$user = $_SERVER['PHP_AUTH_USER'];

			// Connect to MySQL database
			require_once "connect.php";
			$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

			// Check if connection is successful and
			// select records to display on the screen
			if ($polaczenie->connect_errno!=0) {
				echo "Error: ".$polaczenie->connect_errno;
			} else {
				$sql = "SELECT * FROM wplaty ORDER BY data DESC";

				if ($result = @$polaczenie->query($sql)) {
					while($row = $result->fetch_assoc()) {
						echo '<script type="text/javascript">',
							'addHTMLTableRowPhp(\'',$row['data'],'\',\'',$row['na_co'],'\',\'',$row['kto'],'\',',$row['ile'],');',
							'</script>'
						;
					}
					$result->free_result();
				}
			}

			$polaczenie->close();
			echo '<script type="text/javascript">',
				'document.getElementById("fwho").value = \'',$user,'\';',
				'</script>'
				;
		?>
	</body>
</html>
