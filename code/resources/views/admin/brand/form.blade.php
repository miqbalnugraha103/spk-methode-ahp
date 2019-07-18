<div class="row">
	<div class="col-md-6">
		<div class="form-group form-float {{ $errors->has('brand') ? 'has-error' : ''}}">
		    <div class="form-line">
		        {!! Form::text('brand', null, ['class' => 'form-control']) !!}
		        {!! Form::label('brand', 'Name Brand', ['class' => 'form-label']) !!}
                <label class="form-label">Name Brand <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
		    </div>
		    	{!! $errors->first('brand', '<p class="help-block">:message</p>') !!}
		</div>
	</div>
	<div class="col-sm-3 col-xs-6" style="margin-top: -10px;">
        <label class="form-label" style="margin-top: 10px; font-weight: normal; color: #aaa;">Image Brand <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
        <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
            {!! Form::file('files', null, ['class' => 'form-control']) !!}
            {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
        </div>
        @if(isset($brand->image_brand) != '')
        	<a href="{{ url('/') }}/files/brand/{{ $brand->image_brand }}" style="margin-top: 10px">{{ $brand->image_brand }}</a>
    	@endif
    </div>
</div>


<br>
<div class="form-group">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
    <input type="reset" value="Clear" class="btn bg-grey waves-effect">
</div>