@extends('layouts.admin.frame')

@section('content')
<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/product') }}">Product Item</a></li>
    <li class="active">Product Item Details</li>
</ol>
<div class="container">
    <div class="row">

        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Product Item Details</div>
                <div class="panel-body">

                    <p><a href="{{ url('/admin/product') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></p>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr><th>Product Brand</th><td>
                                    @foreach($brand as $b)
                                        @if($b->id == $productItem->brand_id)
                                            {{ $b->brand }}
                                        @endif
                                    @endforeach
                                </td></tr>
                                <tr><th> Name Product </th><td> {{ $productItem->name }} </td></tr>
                                <tr><th> Color </th>
                                    <td> @foreach($color as $c)
                                            @if($c->id == $productItem->color_id)
                                                {{ $c->color_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr><th> Price (Rp.) </th><td> {{ number_format($productItem->price,2) }} </td></tr>
                                <tr><th> Disc (%) </th><td> {{ $productItem->diskon }} </td></tr>
                                <tr><th> Quantity </th><td> {{ $productItem->quantity }} </td></tr>
                                <tr><th> Quality </th><td> {{ $productItem->quality }} </td></tr>
                                <tr><th> Size </th><td> {{ $productItem->size }} </td></tr>
                                <tr><th> Image </th><td> <img src="{{ url('/') }}/files/product/{{ $productItem->image_name }}" alt="{{ $productItem->slug }}" /></td></tr>
                                <tr><th> Description </th><td> {{ $productItem->description }} </td></tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
