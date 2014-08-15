<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <?php // <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css"> ?>
</head>

<body role="document">
  <div class="container">
    <h1 class="content-subhead">Using ZF2 Forms with Slim</h1>
      
      <form method="POST" role="form">
          <div class="form-group">
              <label for="email"><?= $this->formLabel($form->get('email')) ?></label>
              <?= $this->formElement($form->get('email')) ?>
              <?= $this->formElementErrors($form->get('email')) ?>
          </div>
          <button type="submit" class="btn btn-default">Log in</button>
      </form>
        
  </div>
<?php /*
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
*/?>
</body>
</html>
