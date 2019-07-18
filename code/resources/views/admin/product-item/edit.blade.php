@extends('layouts.admin.frame')

@section('title', 'Edit Brand')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/product') }}">Prodict Item</a></li>
    <li class="active">Edit Product Item</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Edit Product Item <span class="pull-right"><a href="{{ url('/admin/product') }}" title="Back"><button class="btn bg-green waves-effect">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::model($productItem, [
                        'method' => 'PATCH',
                        'url' => ['/admin/product', $productItem->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group form-float {{ $errors->has('brand_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: 100; color: #aaaaaa;">Product Brand <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    {!! Form::select('brand_id', $brand, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true']) !!}
                                    {!! $errors->first('brand_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12" style="margin-top: 23px;">
                                <div class="form-group form-float {{ $errors->has('product_code') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        {!! Form::text('product_code', null, ['class' => 'form-control']) !!}
                                        {!! Form::label('product_code', 'Product Code', ['class' => 'form-label']) !!}
                                    </div>
                                    {!! $errors->first('product_code', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12" style="margin-top: 23px;">
                                <div class="form-group form-float {{ $errors->has('name') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <label class="form-label">Name Product <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    </div>
                                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group form-float {{ $errors->has('color_id') ? 'has-error' : ''}}">
                                    <label class="form-label" style="font-weight: normal; color: #aaa;">Color <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    <select name="color_id" class="form-control show-tick" id="color_id" data-live-search="true">
                                        <option value="" disabled=""> Selected Color </option>
                                        @foreach($color as $co)
                                                <option value="{{ $co->id }}" @if($productItem->color_id == $co->id) selected @endif>{{ $co->color_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    {!! $errors->first('color_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-4 col-xs-12" style="margin-top: 23px;">
                                <div class="form-group form-float {{ $errors->has('price') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        <input type="text" name="price" value="{{ number_format($productItem->price,0) }}" class="form-control text-right" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                        {!! Form::label('price', 'Price (Rp.)', ['class' => 'form-label']) !!}
                                    </div>
                                    {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-6" style="margin-top: 23px;">
                                <div class="form-group form-float {{ $errors->has('diskon') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        {!! Form::text('diskon', null, ['class' => 'form-control']) !!}
                                        {!! Form::label('diskon', 'Discount&nbsp;(%)', ['class' => 'form-label']) !!}
                                    </div>
                                        {!! $errors->first('diskon', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-6" style="margin-top: 23px;">
                                <div class="form-group form-float {{ $errors->has('quantity') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        {!! Form::number('quantity', '0', ['class' => 'form-control text-right', 'id' => 'quantity', 'onkeydown' => 'quantityy(this.value)', 'min' => '0']) !!}
                                        <label class="form-label">Quantity <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                                    </div>
                                    {!! $errors->first('quantity', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12" style="margin-top: 23px;">
                                <div class="form-group form-float {{ $errors->has('quality') ? 'has-error' : ''}}">
                                    <div class="form-line">
                                        {!! Form::text('quality', null, ['class' => 'form-control']) !!}
                                        {!! Form::label('quality', 'Quality', ['class' => 'form-label']) !!}
                                    </div>
                                    {!! $errors->first('quality', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12" style="margin-top: 23px;">
                            <div class="form-group form-float {{ $errors->has('size') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::text('size', null, ['class' => 'form-control']) !!}
                                    {!! Form::label('size', 'Size', ['class' => 'form-label']) !!}
                                </div>
                                {!! $errors->first('size', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            {!! Form::label('files', 'Image', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
                            <div class="form-group form-float {{ $errors->has('image_name') ? 'has-error' : ''}}">
                                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                                @if(isset($productItem->image_name) != '')
                                    <a href="{{ url('/') }}/files/product/{{ $productItem->image_name }}" target="_blank">{{ $productItem->image_name }}</a>
                                @endif
                                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group form-float {{ $errors->has('description') ? 'has-error' : ''}}">
                                <div class="form-line">
                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                    {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                </div>
                                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="form-group">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green waves-effect']) !!}
                        <input type="reset" value="Clear" class="btn bg-grey waves-effect">
                    </div>


                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
    function quantityy(quantity) {
        if(isNaN(parseInt(quantity) || quantity == ''))
        {
            $('#quantity').val(0);
        }
    }

    function tandaPemisahTitik(b){
        var _minus = false;
        if (b<0) _minus = true;
        b = b.toString();
        b=b.replace(",","");
        b=b.replace("-","");
        c = "";
        panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--){
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)){
                c = b.substr(i-1,1) + "," + c;
            } else {
                c = b.substr(i-1,1) + c;
            }
        }
        if (_minus) c = "-" + c ;
        return c;
    }

    function numbersonly(ini, e){
        if (e.keyCode>=49){
            if(e.keyCode<=57){
                a = ini.value.toString().replace(",","");
                b = a.replace(/[^\d]/g,"");
                b = (b=="0")?String.fromCharCode(e.keyCode):b + String.fromCharCode(e.keyCode);
                ini.value = tandaPemisahTitik(b);
                return false;
            }
            else if(e.keyCode<=105){
                if(e.keyCode>=96){
                    //e.keycode = e.keycode - 47;
                    a = ini.value.toString().replace(",","");
                    b = a.replace(/[^\d]/g,"");
                    b = (b=="0")?String.fromCharCode(e.keyCode-48):b + String.fromCharCode(e.keyCode-48);
                    ini.value = tandaPemisahTitik(b);
                    //alert(e.keycode);
                    return false;
                }
                else {return false;}
            }
            else {
                return false;
            }
        }else if (e.keyCode==48){
            a = ini.value.replace(",","") + String.fromCharCode(e.keyCode);
            b = a.replace(/[^\d]/g,"");
            if (parseFloat(b)!=0){
                ini.value = tandaPemisahTitik(b);
                return false;
            } else {
                return false;
            }
        }else if (e.keyCode==95){
            a = ini.value.replace(",","") + String.fromCharCode(e.keyCode-48);
            b = a.replace(/[^\d]/g,"");
            if (parseFloat(b)!=0){
                ini.value = tandaPemisahTitik(b);
                return false;
            } else {
                return false;
            }
        }else if (e.keyCode==8 || e.keycode==46){
            a = ini.value.replace(",","");
            b = a.replace(/[^\d]/g,"");
            b = b.substr(0,b.length -1);
            if (tandaPemisahTitik(b)!=""){
                ini.value = tandaPemisahTitik(b);
            } else {
                ini.value = "";
            }

            return false;
        } else if (e.keyCode==9){
            return true;
        } else if (e.keyCode==17){
            return true;
        } else {
            //alert (e.keyCode);
            return false;
        }

    }
</script>
@endpush
