<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Restore Domain Wizard</title>

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

    input {
        border-radius: 0 !important;
        color: #000 !important;
    }

    .form-control {
        padding-left: 2px;
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
        background-color: rgba(0, 0, 0, 0.3);
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

    label{
        font-weight:normal !important;
    }

</style>
<body>
<div class="loading hide">Loading&#8230;</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div id="install-form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Welcome to <strong>Restore</strong> panel to new domain wizard.</h4>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('error'))
                            <p style="color:red; font-weight: bold">{{ Session::get('error') }}</p>
                        @endif
                        <p>
                            Please insert database information.
                        </p>
                        <form
                                role="form"
                                method="POST"
                                action="{{ url('/transfer/restore/process') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('host_name') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label class="control-label">Host Name</label>
                                        <input type="text" class="form-control" value="localhost" name="host_name" id="host_name">
                                        @if ($errors->has('host_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('host_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('database_name') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label class="control-label">Database Name</label>
                                        <input type="text" class="form-control" value="{{ old('database_name') }}" name="database_name" id="database_name">
                                        @if ($errors->has('database_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('database_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('database_username') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label class="control-label">Database Username</label>
                                        <input type="text" class="form-control" value="{{ old('database_username') }}" name="database_username" id="database_username">
                                        @if ($errors->has('database_username'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('database_username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('database_password') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label class="control-label">Database Password</label>
                                        <input type="password" class="form-control" value="{{ old('database_password') }}" name="database_password" id="database_password">
                                        @if ($errors->has('database_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('database_password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="sub" class="btn  btn-primary">Proceed</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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