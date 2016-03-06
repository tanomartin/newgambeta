<?
include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "model/fixture.php";

if (isset ($_POST ['idFecha']) &&  $_POST ['idFecha'] != 0) {
	$oFixture = new Fixture ();
	$partidos = $oFixture->getByFecha ( $_POST ['idFecha']);
	if ($partidos != NULL) {
		foreach ( $partidos as $partido ) {
			$resultado .= "<div class='modal fade' id='myModal".$partido['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal'
												aria-label='Close'>
												<span aria-hidden='true'>&times;</span>
											</button>
											<h4 class='modal-title' id='myModalLabel'>Mas Noticias
												Sobre Gambeta Femenina</h4>
										</div>
										<div class='modal-body'>
											<h3>Gambeta Femenina</h3>
											lorem impsun, breve descripcion. lorem impsun, breve
											descripcion. lorem impsun, breve descripcion. lorem impsun,
											breve descripcion. lorem impsun, breve descripcion. lorem
											impsun, breve descripcion. lorem impsun, breve descripcion.
											lorem impsun, breve descripcion. lorem impsun, breve
											descripcion. lorem impsun, breve descripcion. lorem impsun,
											breve descripcion. lorem impsun, breve descripcion. lorem
											impsun, breve descripcion. lorem impsun, breve descripcion.
											lorem impsun, breve descripcion. lorem impsun, breve
											descripcion. lorem impsun, breve descripcion. lorem impsun,
											breve descripcion.
										</div>
										<div class='modal-footer'>
											<button type='button' class='btn btn-default'
												data-dismiss='modal'>Cerrar</button>
										</div>
									</div>
								</div>
							</div>";
		}
	}
}
echo $resultado;
?>