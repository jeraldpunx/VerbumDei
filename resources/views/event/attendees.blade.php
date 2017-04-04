@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-">
            <h1>Attendees</h1>
            <div class="panel panel-default">
                <div class="panel-body">
                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($attendees as $attendee)
                            <tr>
                                <td>{{ $attendee->firstname . " " . $attendee->lastname }}</td>
                                <td>&#8369;{{ $attendee->ilUnitPrice }}</td>
                                <td></td>
                                <td>
                                    <a class='btn btn-success' href="{{ route('printUserId', ['id'=>$attendee->profileId]) }}">Print ID</a>
                                    <a class='btn btn-success' href="{{ route('printUserQr', ['id'=>$attendee->profileId]) }}">Print QR Code</a>
                                </td>
                            </tr>
                        @endforeach
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
    $('#example').dataTable();
</script>
@endsection