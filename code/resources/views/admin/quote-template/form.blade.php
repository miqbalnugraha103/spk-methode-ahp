<div class="row">
	<div class="col-md-12">
        <div class="form-group form-float {{ $errors->has('code') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('code', null, ['class' => 'form-control']) !!}
                {!! Form::label('code', 'Code', ['class' => 'form-label']) !!}
                <label class="form-label">Code<span style="font-size: 15px;color: red;line-height:15px;"> *</span></label>
            </div>
                {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
	   <div class="col-sm-12 col-xs-12" style="margin-top: -10px;">
        <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Header :</h2>
        <div class="form-float {{ $errors->has('header') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::textarea('header', null, ['class' => 'form-control', 'id' => 'tinymce', 'rows' => '3'] ) !!}
            </div>
            {!! $errors->first('header', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-12 col-xs-12" style="margin-top: -10px;">
        <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Footer :</h2>
        <div class="form-float {{ $errors->has('footer') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::textarea('footer', null, ['class' => 'form-control', 'id' => 'tinymce', 'rows' => '3'] ) !!}
            </div>
            {!! $errors->first('footer', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>


<br>
<div class="form-group">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
    <input type="reset" value="Clear" class="btn bg-grey waves-effect">
</div>