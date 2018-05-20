@extends('layouts.base')
@section('title', '検索結果がありません')
@section('page-title', '検索結果がありません')
@section('content')
<div class="col-6 mx-auto pb-4 pt-4 bg-white">
  <p>{{ $error }}</p>
</div>
@endsection