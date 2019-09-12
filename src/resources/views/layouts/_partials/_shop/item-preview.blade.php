<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<a href="/shop/{{ $item->category->slug }}/{{ $item->slug }}">
				{{ $item->name }} @if (@$admin) <small> - Preview</small> @endif
			</a>
		</h3>
	</div>
	<div class="panel-body">
		@if (@$admin)
			<a href="/admin/shop/{{ $item->category->slug }}/{{ $item->slug }}">
				<center>
					<img class="img img-rounded img-responsive" src="{{ $item->getDefaultImageUrl() }}">
				</center>
			</a>
		@else
			<a href="/shop/{{ $item->category->slug }}/{{ $item->slug }}">
				<center>
					<img class="img img-rounded img-responsive" src="{{ $item->getDefaultImageUrl() }}">
				</center>
			</a>
		@endif
	</div>
	<div class="panel-footer">
		<p>
			@if ($item->price != null)
				{{ Settings::getCurrencySymbol() }}{{ $item->price }}
				@if ($item->price_credit != null && Settings::isCreditEnabled())
					/
				@endif
			@endif
			@if ($item->price_credit != null && Settings::isCreditEnabled())
				{{ $item->price_credit }} Credits
			@endif
		</p>
		<p>
			@if ($item->stock > 0)
				In Stock
			@else
				Out of Stock
			@endif
		</p>
	</div>
</div>