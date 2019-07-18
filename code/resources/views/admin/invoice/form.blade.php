<div class="row">
    <div class="col-sm-4 col-xs-12">
        <div class="form-group form-float {{ $errors->has('purchase_order_list_code_id') ? 'has-error' : ''}}">
            {!! Form::label('purchase_order_list_code_id', 'Purchase Order Name', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
            {{ Form::select('purchase_order_list_code_id', $purchaseorderlists, null, ['class' => 'form-control']) }}
            {!! $errors->first('purchase_order_list_code_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4 col-xs-12" style="margin-top: 23px;">
        <div class="form-group form-float {{ $errors->has('invoice_list_code') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('invoice_list_code', null, ['class' => 'form-control']) !!}
                {!! Form::label('invoice_list_code', 'Invoice Name', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('invoice_list_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        {!! Form::label('files', 'Invoice Files', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
        <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
            {!! Form::file('files', null, ['class' => 'form-control']) !!}
            @if(isset($invoicelist->file) != '')
                <a href="{{ url('/') }}/files/invoice/{{ $invoicelist->file }}" style="margin-top: 10px">{{ $invoicelist->file }}</a>
            @endif
            {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<hr>
{!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green waves-effect']) !!}