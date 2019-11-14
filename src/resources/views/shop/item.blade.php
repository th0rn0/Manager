@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | ' . $item->name)

@section ('content')
		
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> <!-- 16 KB -->

<div class="container">
	<div class="page-header">
		<h1>
			Shop - {{ $item->name }}
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<div class="row">
		<div class="col-xs-12 col-sm-4">
			<div class="fotorama" data-nav="thumbs" data-allowfullscreen="full">
				@if ($item->getDefaultImageUrl())
					<img src="{{ $item->getDefaultImageUrl() }}">
				@endif
				@foreach ($item->images as $image)
					@if (!$image->default)
						<img src="{{ $image->path }}">
					@endif
				@endforeach
			</div>
			<br><br>
		</div>
		<div class="col-xs-12 col-sm-8">
			<h4>
				{{ $item->name }} - <small>@if($item->stock > 0) In Stock: {{ $item->stock }} @else Out of Stock @endif</small>
			</h4>
			<p>{!! $item->description !!}</p>
			<h5>
				@if ($item->price != null)
					{{ Settings::getCurrencySymbol() }}{{ $item->price }}
					@if ($item->price_credit != null && Settings::isCreditEnabled())
						/
					@endif
				@endif
				@if ($item->price_credit != null && Settings::isCreditEnabled())
					{{ $item->price_credit }} Credits
				@endif
			</h5>
			@if ($item->hasStockByItemId($item->id))
				{{ Form::open(array('url'=>'/shop/basket/')) }}
					<div class="form-group">
						{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
						{{ Form::number('quantity', 1, array('id'=>'quantity','class'=>'form-control')) }}
					</div>
					{{ Form::hidden('shop_item_id', $item->id) }}
					<button type="submit" class="btn btn-success">Add to Cart</button>
				{{ Form::close() }}
			@else
				<div class="alert alert-info">
					Not in Stock
				</div>
			@endif	
		</div>
	</div>
</div>

@endsection
