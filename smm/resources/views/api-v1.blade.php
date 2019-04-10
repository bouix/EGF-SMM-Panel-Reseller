@extends('layouts.app')
@section('title', getOption('app_name') . ' - API Documentation')
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default ">
                <div class="panel-body">
                    <h4>API Documentation</h4>
                    <table class="table table-bordered">
                        <tr class="info">
                            <td width="25%"><strong>API For</strong></td>
                            <td width="75%"><strong>Place New Order</strong></td>
                        </tr>
                        <tr>
                            <td>End Point</td>
                            <td>
                                {{url('/api/order')}}
                            </td>
                        </tr>
                        <tr>
                            <td>HTTP Method</td>
                            <td>
                                POST
                            </td>
                        </tr>
                        <tr>
                            <td>Required Parameters</td>
                            <td>

                                api_token<br/>
                                package_id<br/>
                                quantity<br/>
                                link<br/>
                                comments (optional for custom comments only,<br/>
                                each separated by '\n', '\n\r')
                            </td>
                        </tr>
                        <tr>
                            <td>Success Response</td>
                            <td><pre>
{
  "order": 6012
}</pre>
                            </td>
                        </tr>
                        <tr class="info">
                            <td width="200"><strong>API For</strong></td>
                            <td><strong>Get Order Status</strong></td>
                        </tr>
                        <tr>
                            <td>End Point</td>
                            <td>
                                <pre>{{ url('/api/status') }}</pre>
                            </td>
                        </tr>
                        <tr>
                            <td>HTTP Method</td>
                            <td>
                                <pre>GET</pre>
                            </td>
                        </tr>
                        <tr>
                            <td>Required Parameters</td>
                            <td>
<pre>
api_token
order</pre>
                            </td>
                        </tr>
                        <tr>
                            <td>Success Response</td>
                            <td><pre>
{
  "status": "Completed",
  "start_counter": "600",
  "remains": "600"
}</pre>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection