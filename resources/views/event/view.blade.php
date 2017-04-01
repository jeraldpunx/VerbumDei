@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3><a href="{{ url('event') }}">Events</a> / {{ $event->iiName }}</h3>
            <hr>
            <div class="col-md-9"> 
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>{{ $event->iiName }}</h1>
                        <hr>
                        <center>
                            <img class="img-responsive" style="height: 200px;" src="http://52.74.115.167:703/{{ $event->iiImg }}">
                        </center>
                        <br><br>
                        <p><b>Localtion:</b> {{$event->location}}</p>
                        <p><b>Price:</b> {{$event->iiUnitPrice}}</p>
                        <p>
                            <b>Description:</b><br>
                            {{ $event->iiDesc }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3"> 
                <div class="panel panel-default">
                    <div class="panel-body">
                        <center>
                            <a class="btn btn-success btn-block">JOIN</a>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        var kycId = {{ Route::input('id') }};
// http://52.74.115.167:703/index.php?mtmaccess_api=true&transaction=20020&userName=test6&imei=359861054037926
        $.ajax({
          type: 'GET',
          url: 'http://52.74.115.167:703/index.php',
          crossDomain: true,
          data: {
            mtmaccess_api: true, 
            transaction: 20020, 
            userName: "{{Session::get('user')}}",
            passWord: "{{Session::get('password')}}"
          },
          cache: false,
          success: function(data) {
            var data = JSON.parse(data);
            if(data.success) {
                console.log(data.result);
                $('#example').dataTable( {
                    "aaData": data.result,
                    'columns': [
                    { "data": null, render: function ( data, type, row ) {
                            return data.lastname+', '+data.firstname+' '+data.middlename;
                        }
                    },
                    { "data": null, render: function ( data, type, row ) {
                            return formatDate(data.birthDate);
                        } 
                    },
                    { "data": "gender" },
                    { "data": null, render: function ( data, type, row) {
                            return "<a class='btn btn-success' href='"+data.profileId+"'>Print</a>";
                        }
                    }
                    ],
                } );
            }
          }
        });       
    } );
</script>
@endsection