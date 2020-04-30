<!DOCTYPE html>
<html>

<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <title><?php echo $title ?></title>
      <link href="public/css/styles.css" rel="stylesheet" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" , shrink-to-fit=no>

      <link rel="stylesheet" href="public/css/bootstrap.css" />
      <link rel="stylesheet" href="public/css/fontawesome.all.css" />

      <link href='public/fullcalendar/core/main.css' rel='stylesheet' />
      <link href='public/fullcalendar/daygrid/main.css' rel='stylesheet' />
      <link href='public/fullcalendar/timegrid/main.css' rel='stylesheet' />
      <link href='public/fullcalendar/bootstrap/main.css' rel='stylesheet' />
      <link rel="stylesheet" href="public/css/tempusdominus-bootstrap-4.min.css" />
</head>

<body>
      <script src="public/js/jquery-3.4.1.js"></script>
      <script src="public/js/MDL.js"></script>

      <script src="public/js/moment.min.js"></script>
      <script src="public/js/moment.locale.fr.js"></script>
      <script src="public/js/bootstrap.js"></script>
      
      <script type="text/javascript" src="public/js/tempusdominus-bootstrap-4.min.js"></script>
      <script src='public/fullcalendar/core/main.js'></script>
      <script src='public/fullcalendar/core/locales/fr.js'></script>
      <script src='public/fullcalendar/daygrid/main.js'></script>
      <script src='public/fullcalendar/timegrid/main.js'></script>
      <script src='public/fullcalendar/interaction/main.js'></script>
      <script src='public/fullcalendar/bootstrap/main.js'></script>

      <div id="page">
            <?php echo $content ?>
      </div>
</body>

</html>
