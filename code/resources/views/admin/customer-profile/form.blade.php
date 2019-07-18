<div class="row">
    <div class="col-sm-6 col-xs-12">
        <div class="form-group form-float {{ $errors->has('company_name') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
                {!! Form::label('company_name', 'Company Name', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('company_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-6 col-xs-12">
        <div class="form-group form-float {{ $errors->has('name_pic') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('name_pic', null, ['class' => 'form-control']) !!}
                {!! Form::label('name_pic', 'PIC Name', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('name_pic', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        <div class="form-group form-float {{ $errors->has('company_phone') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::tel('company_phone', null, ['class' => 'form-control']) !!}
                {!! Form::label('company_phone', 'Company Phone', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('company_phone', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-8 col-xs-12">
        <h2 class="card-inside-title" style="margin-top: 10px; font-weight: normal; color: #aaa;">Company Address</h2>
        <div class="form-float{{ $errors->has('company_address') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::textarea('company_address', null, ['class' => 'form-control', 'rows' => '3'] ) !!}
            </div>
            {!! $errors->first('company_address', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

    <div class="form-group">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
    <input type="reset" value="Clear" class="btn bg-grey waves-effect">
</div>
