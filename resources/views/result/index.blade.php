@extends('layouts.base')
@section('title', '検索結果')
@section('page-title', '検索結果')
@section('content')
<div class="col-6 mx-auto pb-4 pt-4 bg-white">
@if($error)
    <h2> {{ $book_title }} </h2>
    <p>{{ $error }}</p>
    <p class="mb-0 text-center"><a href="{{ $response['Items']['MoreSearchResultsUrl'] }}" class="btn btn-primary" target="_blank"  >Amazonで確認する</a></p>
@else
  @foreach($response as $res)
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="text-left h4"><small>{{ $loop_count++ }}</small>  {{ $res['ItemAttributes']['PublicationDate'] }}</h3>
      </div>
      <div class="card-body">
        <div class="row bg-white mb-2">
          <div class="col-4"><a href="{{ $res['DetailPageURL'] }}" target="_blank"><img class="img-fluid" src="{{ $res['LargeImage']['URL'] }}" alt="$res['ItemAttributes']['Title']" ></a></div>
          <div class="col-8 text-left">
            <h4><a class="text-dark" href="{{ $res['DetailPageURL'] }}" target="_blank">{{ $res['ItemAttributes']['Title'] }}</a></h4>
            <p>{{ $res['ItemAttributes']['Author'] }}</p>
            <p>{{ $res['OfferSummary']['LowestNewPrice']['Amount'] }}</p>
            <p class="mb-0 text-center"><a href="{{ $res['DetailPageURL'] }}" class="btn btn-primary" target="_blank"  >Amazonで確認する</a></p>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endif
</div>
@endsection