<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Installation Wizard</title>

    <!-- Bootstrap -->
    <link href="/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/js/vendor/jquery.min.js"></script>
    <script src="/js/vendor/form-validator/jquery.form-validator.min.js"></script>
</head>
<style>
    body {
        background-color: #FEFEFE;
        color: #444;
        font-family: "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    #install-form {
        margin: 5% auto;
        background-color: #fff;
    }
</style>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div id="install-form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>UstaScriptci.com SMM Panel Installation Wizard</h5>
                    </div>
                    <div class="panel-body">
                        <p>
                            Before getting started, we need some information on the database. You will need to know the following items before proceeding.
                        </p>
                        <ol>
                            <li>Database name</li>
                            <li>Database username</li>
                            <li>Database password</li>
                            <li>MySQL Database host</li>
                        </ol>
                        <p>
                            When you have available all of above, then click on proceed to get started.
                        </p>
                    </div>
                    <div class="panel-footer">
                        <a href="{{ url('/install/step1') }}" class="btn btn-default">Proceed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>