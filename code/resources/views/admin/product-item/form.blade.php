<div class="row">
    <div class="col-md-12">
    	<div class="col-sm-3 col-xs-12" style="margin-top: 0;">
    		<div class="form-group form-float {{ $errors->has('brand_id') ? 'has-error' : ''}}">
                <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Product Brand <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                {!! Form::select('brand_id', $brand, null, ['class' => 'form-control show-tick', 'data-live-search' => 'true']) !!}
                {!! $errors->first('brand_id', '<p class="help-block">:message</p>') !!}
            </div>
    	</div>
        <div class="col-sm-3 col-xs-12" style="margin-top: 34px;">
            <div class="form-group form-float {{ $errors->has('product_code') ? 'has-error' : ''}}">
                <div class="form-line">
                    {!! Form::text('product_code', null, ['class' => 'form-control']) !!}
                    {!! Form::label('product_code', 'Product Code', ['class' => 'form-label']) !!}
                </div>
                {!! $errors->first('product_code', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="col-sm-3 col-xs-12" style="margin-top: 34px;">
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
                <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Color <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                <select name="color_id" class="form-control show-tick" data-live-search="true">
                    <option value=""> Choose Color </option>
                    @foreach($color as $co)
                        <option value="{{ $co->id }}">{{ $co->color_name }}</option>
                    @endforeach
                </select>
                {!! $errors->first('color_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-sm-4 col-xs-12" style="margin-top: 23px;">
            <div class="form-group form-float {{ $errors->has('price') ? 'has-error' : ''}}">
                <div class="form-line">
                    {!! Form::text('price', null, ['class' => 'form-control text-right',  'onkeydown' => 'return numbersonly(this, event);', 'onkeyup' => 'javascript:tandaPemisahTitik(this);']) !!}
                    <label class="form-label">Price (Rp.) <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
                </div>
                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="col-sm-2 col-xs-6" style="margin-top: 23px;">
            <div class="form-group form-float {{ $errors->has('diskon') ? 'has-error' : ''}}">
                <div class="form-line">
                    {!! Form::text('diskon', '0', ['class' => 'form-control']) !!}
                    {!! Form::label('diskon', 'Discount&nbsp;(%)', ['class' => 'form-label']) !!}
                </div>
                    {!! $errors->first('diskon', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="col-sm-2 col-xs-6" style="margin-top: 23px;">
            <div class="form-group form-float {{ $errors->has('quantity') ? 'has-error' : ''}}">
                <div class="form-line">
                    {!! Form::text('quantity', '0', ['class' => 'form-control']) !!}
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
        <div class="col-sm-3 col-xs-12" style="margin-top: 23px;">
            <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Image Product <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
            <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
                {!! Form::file('files', null, ['class' => 'form-control']) !!}
                @if(isset($productItem->image_name) != '')
                    <a href="{{ url('/') }}/files/product/{{ $productItem->image_name }}">{{ $productItem->image_name }}</a>
                @endif
                {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    <div class="col-sm-6 col-xs-12" style="margin-top: 23px;">
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
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
    <input type="reset" value="Clear" class="btn bg-grey waves-effect">
</div>
