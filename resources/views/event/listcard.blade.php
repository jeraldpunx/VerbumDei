@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Events</h1>
            <hr>

            @foreach($events as $event)
            <div class="col-md-4">
                <div class="card">
                    <a href="{{ route('viewEvent', [$event->id]) }}">
                    <div class="card-image">
                        <img class="img-responsive" style="width: 100%; height: 200px;" src="http://52.74.115.167:703/{{ $event->iiImg }}">
                    </div><!-- card image -->
                    
                    <div class="card-content">
                        <span class="card-title">{{$event->iiName}}({{$event->iiDesc}})</span>
                        <span>₱{{$event->iiUnitPrice}}</span>                    
                        <button type="button" id="show" class="btn btn-custom pull-right" aria-label="Left Align">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </div><!-- card content -->
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $('#example').dataTable();
</script>
@endsection