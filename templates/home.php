<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
</head>

<body role="document">
  <div class="container">
    <h1 class="content-subhead">Using ZF2 Forms with Slim</h1>
      
    <form method="POST" role="form">
        <div class="form-group">
            <?= $this->formRow($form->get('email')) ?>
        </div>
        <?= $this->formElement($form->get('submit')) ?>
    </form>
        
  </div>
</body>
</html>
