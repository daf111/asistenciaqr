<?php

require_once '../admin/config/config.php';

$mysqli = new mysqli($aConfBD['BD_DEFAULT']['sServerBase'], $aConfBD['BD_DEFAULT']['sUsuarioBase'], $aConfBD['BD_DEFAULT']['sClaveBase'], $aConfBD['BD_DEFAULT']['sNombreBase']);

/* comprobar la conexión */
if ($mysqli->connect_errno) {
    $data = array (
		'content' => 'Error general',
	);
} else {

	if ($resultado = $mysqli->query("SELECT * FROM alumno limit 1", MYSQLI_USE_RESULT)) {
		
		if ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
			$data = array (
				'content' => $myrow['alu_apellido'].' '.$myrow['alu_nombre'].' (#'.$myrow['alu_id'].')',
			);
		}
		
		$resultado->close();

	} else {

		$data = array (
					'content' => 'No se ha identificado el alumno',
				);
				
	}
	
}

$mysqli->close();



header('Content-Type: application/json');
echo json_encode($data);

?>