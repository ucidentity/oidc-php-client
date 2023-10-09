<?php
session_start();

if (isset($_SESSION['id']) AND isset($_SESSION['sub']) AND isset($_SESSION['attrs'])) {
  $id = $_SESSION['id'];
  $sub = $_SESSION['sub'];
  $attrs = $_SESSION['attrs'];
} else {
  $_SESSION['return'] = 'index.php';
  header('Location: auth.php');
  die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>OpenID Connect: Released Attributes</title>

</head>

<body>
  <p>Hello,
    <?php echo $id; ?>
  </p>

  <!-- Intro -->
  <div class="banner">
    <div class="container">
      <h1 class="section-heading">Claims</h1>

      <h3>
        Claims sent back from OpenID Connect
      </h3>
      <br />
    </div>
  </div>

  <!-- Claims -->
  <div class="content-section-a" id="claims">
    <div class="container">
      <div class="row">

        <table class="table" style="width:80%;" border="1">
          <?php foreach ($attrs as $key=>$value): ?>
          <tr>
            <td data-toggle="tooltip" title=<?php echo $key; ?>>
              <?php echo $key; ?>
            </td>
            <td data-toggle="tooltip" title=<?php echo $value; ?>>
              <?php echo $value; ?>
            </td>
          </tr>
          <?php endforeach; ?>

        </table>
      </div>
    </div>
  </div>

</body>

</html>