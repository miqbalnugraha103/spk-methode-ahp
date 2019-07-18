<div class="row">
    <div class="col-md-12">
        <div class="form-group form-float {{ $errors->has('color_name') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('color_name', null, ['class' => 'form-control']) !!}
                <label class="form-label">Color Name <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
            </div>
            {!! $errors->first('color_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<br>
<div class="form-group">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
    <input type="reset" value="Clear" class="btn bg-grey waves-effect">
</div>