<head>
    <title>Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style type="text/css">
        .brand{
            background: #800404 !important;
        }
        .brand-text{
            color: #800404 !important;
        }
        form{
            max-width: 460px;
            margin: 20px auto;
            padding: 20px;
        }
    </style>
</head>
    <body class="grey lighten-4">
        <nav class="white">
            <div class="container">
                <a class="left brand-logo brand-text">Risi's Calendar App</a>
                <ul id="nav-mobile" class="right hide-on-small-and-down">
                    <li><a class="brand-text"><?php echo "Welcome $username" ?></a></li>
                    <li><a href="index.php" class="btn brand">log out</a></li>
                </ul>
            </div>
        </nav>