<?  include_once "include/config.inc.php";
include_once "model/fixture.php";

if(!isset($_SESSION['equipo'])){
	header("Location: index.php");
	exit;
}

$oPartido = new Fixture();
$idPartido = $_POST['idPartido'];
$oPartido -> confirmarPartido($idPartido, $_SESSION['equipo']);

include_once "reservas.php";

?>