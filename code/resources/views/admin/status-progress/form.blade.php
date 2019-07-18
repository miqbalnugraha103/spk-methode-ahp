<div class="form-group form-float {{ $errors->has('name_progress') ? 'has-error' : ''}}">
    <div class="form-line">
        {!! Form::text('name_progress', null, ['class' => 'form-control']) !!}
        {!! Form::label('name_progress', 'Name Status', ['class' => 'form-label']) !!}
        <label class="form-label">Name Status <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
    </div>
	{!! $errors->first('name_progress', '<p class="help-block">:message</p>') !!}
</div>
<br>


<br>
<div class="form-group">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
    <input type="reset" value="Clear" class="btn bg-grey waves-effect">
</div>
