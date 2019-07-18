<div class="row">
    <div class="col-sm-4 col-xs-6" style="margin-top: 10px;">
        <div class="form-group form-float  {{ $errors->has('quote_list_code') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('quote_list_code', null, ['class' => 'form-control']) !!}
                {!! Form::label('quote_list_code', 'Quote Lists', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('quote_list_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4 col-xs-6" style="margin-top: 10px;">
        <div class="form-group form-float {{ $errors->has('name_file') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('name_file', null, ['class' => 'form-control']) !!}
                {!! Form::label('name_file', 'File Name', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('name_file', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        {!! Form::label('files', 'Files', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
        <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
            {!! Form::file('files', null, ['class' => 'form-control']) !!}
            @if(isset($quotelist->file) != '')
                <a href="{{ url('/') }}/files/quote-list/{{ $quotelist->file }}">{{ $quotelist->file }}</a>
            @endif
            {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
        </div>
        
    </div>

    <div class="col-sm-12 col-xs-12">
        <div class="form-group">
            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn bg-green waves-effect']) !!}
            <input type="reset" value="Clear" class="btn bg-grey waves-effect">
        </div>
    </div>
</div>