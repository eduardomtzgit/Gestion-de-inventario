<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Index</title>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<style>
		* { font-size: 16px; }
		h1 { font-size: 25px; }
		p { margin: 0; }

		input[type=number], input[type=text] {
			width: 70%;
			height: 20px;
			padding: 3px;
			border-radius: 5px;
			border: 1px solid gray;
		}

		input[type=submit], input[type=button] {
			width: 120px;
			height: 30px;
			margin: 20px 70px 50px 70px;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<h1 align="center">Gestion de inventario</h1>
	<div>
		<form action="" method="post" name="form">
			<div align="center" style="display: flex;">
				<div>
					<p>Id</p>
					<input type="number" name="inpId" id="inpId">
				</div>
				<div>
					<p>Articulo</p>
					<input type="text" name="articulo" id="inpArt">
				</div>
				<div>
					<p>Cantidad</p>
					<input type="number" name="cantidad" id="inpCant">
				</div>
				<div>
					<p>Precio</p>
					<input type="number" name="precio" id="inpPrec">
				</div>
				<div>
					<p>Descuento</p>
					<input type="text" name="descuento" id="inpDesc">
				</div>
				<div>
					<p>Utilidad</p>
					<input type="number" name="utilidad" id="inpUtil">
				</div>
				<div>
					<p>Fecha</p>
					<input type="text" name="fecha" id="inpFech">
				</div>
			</div>
			<div align="center" style="display: flex;">
				<input type="submit" name="agregar" value="Agregar">
				<input type="submit" name="modificar" value="Modificar">
				<input type="submit" name="borrar" value="Borrar">
				<input type="submit" name="borrartodo" value="Borrar todo">
				<input type="button" name="limpiar" value="Limpiar" onclick="cleanInputs()">
			</div>
		</form>
		<table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Id</th>
	                <th>Articulo</th>
	                <th>Cantidad</th>
	                <th>Precio</th>
	                <th>Descuento</th>
	                <th>Utilidad</th>
	                <th>Fecha</th>
	            </tr>
	        </thead>
	        <tbody id="tbody">
	        	<?php 
	        		$archivoLectura = file("productos.txt");

	        		foreach ($archivoLectura as $jsonProducto) {
	        			$decoProductos = json_decode($jsonProducto);

	        			echo "<tr>
	        					<td>$decoProductos->id</td>
	        					<td>$decoProductos->articulo</td>
	        					<td>$decoProductos->cantidad</td>
	        					<td>$$decoProductos->precio</td>
	        					<td>$decoProductos->descuento%</td>
	        					<td>$$decoProductos->utilidad</td>
	        					<td>$decoProductos->fecha</td>
	        				  </tr>";
	        		}
	        	?>
	        </tbody>
	    </table>
	</div>
	<?php 
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$id = $_POST['inpId'];
			$articulo = $_POST['articulo'];
			$cantidad = $_POST['cantidad'];
			$precio = $_POST['precio'];
			$descuento = $_POST['descuento'];
			$utilidad = $_POST['utilidad'];
			$fecha = $_POST['fecha'];

			if(isset($_POST['agregar'])) {
				$producto = array("id"=>$id, "articulo"=>$articulo, "cantidad"=>$cantidad, "precio"=>$precio, "descuento"=>$descuento, "utilidad"=>$utilidad, "fecha"=>$fecha);
				$productoJSON = json_encode($producto);
				$archivo = fopen("productos.txt", "a");

				fwrite($archivo, $productoJSON.PHP_EOL);
				fclose($archivo);

				//Script para agregar item y evitar recargar pagina (Evita colocar header())
				echo "<script>
						var tab = document.getElementById('tbody');
						var trNode = document.createElement('tr');
						var tdId = document.createElement('td');
	        			var tdArt = document.createElement('td');
	        			var tdCant = document.createElement('td');
	        			var tdPre = document.createElement('td');
	        			var tdDesc = document.createElement('td');
	        			var tdUtil = document.createElement('td');
	        			var tdFech = document.createElement('td');

						tdId.innerText = '$id'
	        			tdArt.innerText = '$articulo'
	        			tdCant.innerText = '$cantidad'
	        			tdPre.innerText = '$$precio'
	        			tdDesc.innerText = '$descuento%'
	        			tdUtil.innerText = '$$utilidad'
	        			tdFech.innerText = '$fecha'

						trNode.appendChild(tdId);
						trNode.appendChild(tdArt);
						trNode.appendChild(tdCant);
						trNode.appendChild(tdPre);
						trNode.appendChild(tdDesc);
						trNode.appendChild(tdUtil);
						trNode.appendChild(tdFech);
	        			tab.appendChild(trNode);
					</script>";
			} else if(isset($_POST['modificar'])) {
				$archivoLecturaMod = file("productos.txt");
				$fileJsonUpd = "";
				$fileUpd = "";

				foreach($archivoLecturaMod as $line) {
					$prodts = json_decode($line);

					if($prodts->id != $id) {
	    				$fileJsonUpd .= '{"id":"'.$prodts->id.'","articulo":"'.$prodts->articulo.'","cantidad":"'.$prodts->cantidad.'","precio":"'.$prodts->precio.'","descuento":"'.$prodts->descuento.'","utilidad":"'.$prodts->utilidad.'","fecha":"'.$prodts->fecha.'"}'.PHP_EOL;
	    			} else {
	    				$fileUpd .= '{"id":"'.$id.'","articulo":"'.$articulo.'","cantidad":"'.$cantidad.'","precio":"'.$precio.'","descuento":"'.$descuento.'","utilidad":"'.$utilidad.'","fecha":"'.$fecha.'"}'.PHP_EOL;
	    			}
				}

				$fileWrite = fopen("productos.txt", "w");

				fwrite($fileWrite, $fileJsonUpd);
				fwrite($fileWrite, $fileUpd);
				fclose($fileWrite);
				header("Location: Index.php");
			} else if(isset($_POST['borrar'])) {
				$fileReadDel = file("productos.txt");
				$fileJsonUpd = "";

				foreach($fileReadDel as $line) {
					$prodts = json_decode($line);

					if($prodts->id != $id) {
	    				$fileJsonUpd .= '{"id":"'.$prodts->id.'","articulo":"'.$prodts->articulo.'","cantidad":"'.$prodts->cantidad.'","precio":"'.$prodts->precio.'","descuento":"'.$prodts->descuento.'","utilidad":"'.$prodts->utilidad.'","fecha":"'.$prodts->fecha.'"}'.PHP_EOL;
	    			}
				}

				$fileWrite = fopen("productos.txt", "w");

				fwrite($fileWrite, $fileJsonUpd);
				fclose($fileWrite);
				header("Location: Index.php");
			} else if(isset($_POST['borrartodo'])) {
				$fileDel = fopen('productos.txt', 'w');
				$void = "";

				fwrite($fileDel, $void);
				fclose($fileDel);
				header("Location: Index.php");
			}
		}
	?>
	<script>	
		$(document).ready(function() {
			var table = new DataTable('#example');

			table.on("click", "tbody tr", function () {
	    		let data = table.row(this).data();
	    		let nPre = data[3].replace("$", "");
	    		let nDes = data[4].replace("%", "");
	    		let nUtil = data[5].replace("$", "");

	    		$('#inpId').val(data[0]);
	    		$('#inpArt').val(data[1]);
	    		$('#inpCant').val(data[2]);
	    		$('#inpPrec').val(nPre);
	    		$('#inpDesc').val(nDes);
	    		$('#inpUtil').val(nUtil);
	    		$('#inpFech').val(data[6]);
	    	})
		})

		function cleanInputs() {
			$('#inpId').val('');
    		$('#inpArt').val('');
    		$('#inpCant').val('');
    		$('#inpPrec').val('');
    		$('#inpDesc').val('');
    		$('#inpUtil').val('');
    		$('#inpFech').val('');
		}
	</script>
</body>
</html>