@extends('layouts.app')
@section('title', getOption('app_name') . ' - API Documentation')
@section('content')
    <style>
        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-body">
                    <h4>API 2.0</h4>
                    <table class="table table-bordered">
                        <tr>
                            <td width="30%">HTTP Method</td>
                            <td width="70%">POST</td>
                        </tr>
                        <tr>
                            <td>API URL</td>
                            <td>
                                {{url('/api/v2')}}
                            </td>
                        </tr>
                        <tr>
                            <td>Response Format</td>
                            <td>
                                JSON
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-body">
                    <h4><span style="font-weight: normal">Method:</span>add</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Parameters</th>
                            <th width="70%">Descriptions</th>
                        </tr>
                        <tr>
                            <td>api_token</td>
                            <td>Your API token</td>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>Method Name</td>
                        </tr>
                        <tr>
                            <td>package</td>
                            <td>ID of package</td>
                        </tr>
                        <tr>
                            <td>link</td>
                            <td>Link to page</td>
                        </tr>
                        <tr>
                            <td>quantity</td>
                            <td>Needed quantity</td>
                        </tr>
                        <tr>
                            <td>custom_data</td>
                            <td>optional, needed for custom comments, mentions and other relaed packages only.<br/> each separated by '\n', '\n\r'</td>
                        </tr>
                    </table>
                    <p style="margin-top: 10px; margin-bottom: 0">Success Response:</p>
                    <pre>
{
  "order":"23501"
}</pre>
                </div>
            </div>
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-body">
                    <h4><span style="font-weight: normal">Method:</span>status</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Parameters</th>
                            <th width="70%">Descriptions</th>
                        </tr>
                        <tr>
                            <td>api_token</td>
                            <td>Your API token</td>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>Method Name</td>
                        </tr>
                        <tr>
                            <td>order</td>
                            <td>Order ID</td>
                        </tr>
                    </table>
                    <p style="margin-top: 10px; margin-bottom: 0">Success Response:</p>
                    <pre>
{
  "status": "Completed",
  "start_counter": "600",
  "remains": "600"
}</pre>
                </div>
            </div>
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-body">
                    <h4><span style="font-weight: normal">Method:</span>balance</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Parameters</th>
                            <th width="70%">Descriptions</th>
                        </tr>
                        <tr>
                            <td>api_token</td>
                            <td>Your API token</td>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>Method Name</td>
                        </tr>
                    </table>
                    <p style="margin-top: 10px; margin-bottom: 0">Example Response:</p>
                    <pre>
{
  "balance": "100.78",
  "currency": "USD"
}</pre>
                </div>
            </div>
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-body">
                    <h4><span style="font-weight: normal">Method:</span>packages</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Parameters</th>
                            <th width="70%">Descriptions</th>
                        </tr>
                        <tr>
                            <td>api_token</td>
                            <td>Your API token</td>
                        </tr>
                        <tr>
                            <td>action</td>
                            <td>Method Name</td>
                        </tr>
                    </table>
                    <p style="margin-top: 10px; margin-bottom: 0">Example Response:</p>
                    <pre>
[
  {
    "id":"1",
    "name":"Instagram Followers",
    "type":"default"
  },
  {
    "id":"2",
    "name":"Instagram Likes",
    "type":"default"
  },
  {
    "service":"3",
    "name":"Facebook Custom Comments",
    "type":"custom_data"
  }
]</pre>
                </div>
            </div>
            <a target="_blank" class="btn btn-inverse" href="{{ url('/example.txt') }}">Example of PHP Code</a>
        </div>
    </div>
@endsection