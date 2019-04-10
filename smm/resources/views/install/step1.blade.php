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

        input {
            border-radius: 0 !important;
            color: #000 !important;
        }

        .form-control{
            padding-left:2px;
        }
        .form-control:focus {
            border-color: #4d90fe;
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

    </style>
</head>
<body>
<div class="loading hide">Loading&#8230;</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div id="install-form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>UstaScriptci.com SMM Panel Installation Wizard</h5>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('error'))
                            <p style="color:red">{{ Session::get('error') }}</p>
                        @endif
                        <p>Below you should enter your database connection details. If you’re not sure about these, contact your host.</p>
                        <form class="form-horizontal"
                              method="post"
                              action="{{ url('/install/step1') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('database_name') ? ' has-error' : '' }}">
                                <label for="database_name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" data-validation="required" id="database_name" value="{{ old('database_name') }}" name="database_name"placeholder="Database Name">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('database_user') ? ' has-error' : '' }}">
                                <label for="database_user" class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="database_user" name="database_user" value="{{ old('database_user') }}" placeholder="Database Username">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('database_password') ? ' has-error' : '' }}">
                                <label for="database_password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="database_password"  name="database_password" value="{{ old('database_password') }}" placeholder="Database Password">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('database_host') ? ' has-error' : '' }}">
                                <label for="database_host" class="col-sm-2 control-label">Host</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="database_host" name="database_host" value="localhost" placeholder="Database Host">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group{{ $errors->has('site_name') ? ' has-error' : '' }}">
                                <label for="site_name" class="col-sm-2 control-label">Site Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="site_name" name="site_name"  placeholder="Website Name" value="{{ old('site_name') }}">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('admin_email') ? ' has-error' : '' }}">
                                <label for="admin_email" class="col-sm-2 control-label">Admin Email</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="admin_email" name="admin_email" value="{{ old('admin_email') }}"  placeholder="Your Email">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('admin_email') ? ' has-error' : '' }}">
                                <label for="admin_password" class="col-sm-2 control-label">Admin Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="admin_password" value="{{ old('admin_password') }}" name="admin_password"  placeholder="Password">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group{{ $errors->has('envato_username') ? ' has-error' : '' }}">
                                <label for="envato_username" class="col-sm-2 control-label">Envato Username </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="envato_username" name="envato_username" value="{{ old('envato_username') }}"  placeholder="UstaScriptci">
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('envato_purchase_code') ? ' has-error' : '' }}">
                                <label for="envato_purchase_code" class="col-sm-2 control-label">Envato Purchase Code</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="envato_purchase_code" name="envato_purchase_code" value="{{ old('envato_purchase_code') }}"  placeholder="UstaScriptci">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-md-offset-2">
                                    <button type="submit" class="btn btn-default" id="sub">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer">
                        <p style="text-align: right">Submit to proceed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><a href="https://ustascriptci.com">Powered by UstaScriptci | Designed by UstaScriptci © Copyright 2018, </a><br/></center>
<script>
    $(function(){
        $('#sub').click(function(){
            $(this).text('Processing...');
            $('.loading').removeClass('hide');
        });
    });
</script>
</body>
</html>