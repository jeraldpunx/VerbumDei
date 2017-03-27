@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Birthdate</th>
                                <th>Gender</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
          type: 'GET',
          url: 'http://52.74.115.167:703/index.php',
          crossDomain: true,
          data: {
            mtmaccess_api: true, 
            transaction: 20020, 
            userName: "test6",
            passWord: MD5("1234")
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

    $('form').on('click', 'button', function(e){
                e.preventDefault();

        $.ajax({
          type: 'GET',
          url: 'http://52.74.115.167:703/index.php',
          crossDomain: true,
          data: {
            mtmaccess_api: true, 
            transaction: 110, 
            userName: "test6",
            passWord: MD5("1234")
          },
          
          cache: false,
          success: function(data) {
            var data = JSON.parse(data);
            if(data.success) {
                window.location = "{{ url('profile/edit') }}";
            }
          }
        });
    });
</script>
@endsection