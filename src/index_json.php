<?php
session_start();

if (isset($_SESSION['id']) AND isset($_SESSION['sub']) AND isset($_SESSION['attrs'])) {
  $id = $_SESSION['id'];
  $sub = $_SESSION['sub'];
  $attrs = $_SESSION['attrs'];
  $jsonResp = $_SESSION['jsonResp'];
} else {
  $_SESSION['return'] = 'index_json.php';
  header('Location: auth.php');
  die();
}

?>

<?php echo $jsonResp; ?>
