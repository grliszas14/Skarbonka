<!DOCTYPE html>
<html lang="pl">
	<head>
		<title>Skarbonka Newterm</title>
		<meta charset="utf-8" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="js/scripts.js"></script>

		<style>
			.container{overflow: hidden}
			.tab{float: left; margin-left: 100px}
			.tab-2{float: right; margin-left: 50px; margin-right: 50px; position: fixed; right: 50px}
			.tab-2 input{display: block; margin-bottom: 20px}
			tr{transition:all .25s ease-in-out}
			tr:hover{background-color: #a1a0a3; cursor: pointer}
			.header img{display: block; margin-left: auto; margin-right: auto; margin-bottom: 50px; width: 500px}
			.sum {float: left; margin-left: 50px; position: fixed; right: 450px}
			.selected {background-color: #ff7178;}
		</style>

	</head>
	<body>

		<div class="header">
			<img src="public/images/skarbonka.png" />
		</div>

		<div class="container">
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

			<div class="sum">
				<h2>Stan skarbonki: </h2>
				<span id="moneyBoxCondition">0 zł</span>
			</div>


			<div class="tab tab-2">
				<form action="add.php" method="post">
					Data (yyyy-mm-dd):<input type="text" name="fdate" id="fdate">
					Na co : <input type="text" name="fitem" id="fitem">
					Kto :<input type="text" name="fwho" id="fwho">
					Ile : <input type="text" name="fhowmuch" id="fhowmuch">

					<input onclick="addHTMLTableRow();" type="submit" value="Dodaj" name="add"/>
					<input onclick="editHTMLTableSelectedRow()" type="submit" value="Edytuj" name="edit"/>
					<!--
					<input onclick="removeSelectedRow()" type="submit" value="Usuń"/>
					-->
				</form>
			</div>

		</div>

		<script>
			var rIndex,
				table = document.getElementById("table");

			// check the empty input
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

					// call the function to set the event to the new row
					selectedRowToInput();
					calculateActualMoneyBoxState();
			}
			// display selected row data into input text
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
						document.getElementById("fhowmuch").value = this.cells[3].innerHTML;

						this.classList.toggle("selected");

					};
				}
				//exportTableToCSV('092017.csv');
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
				table.deleteRow(rIndex);
				// clear input text
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
			require_once "connect.php";

			$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

			if ($polaczenie->connect_errno!=0) {
				echo "Error: ".$polaczenie->connect_errno;
			} else {
				$sql = "SELECT * FROM wplaty2";

				if ($result = @$polaczenie->query($sql)) {
					while($row = $result->fetch_assoc()) {
						echo '<script type="text/javascript">',
							'addHTMLTableRowPhp(\'',$row['data'],'\',\'',$row['na_co'],'\',\'',$row['kto'],'\',',$row['ile'],');',
							'</script>'
						;
					}
					$result_free();
				}
			}

			$polaczenie->close();
		?>
	</body>
</html>
