<div class="row">
    <div class="col-sm-6 col-xs-12" style="margin-top: 23px;">
        <div class="form-group form-float {{ $errors->has('delivery_order_list_code') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('delivery_order_list_code', null, ['class' => 'form-control']) !!}
                {!! Form::label('delivery_order_list_code', 'Delivery Order Name', ['class' => 'form-label']) !!}
            </div>
            {!! $errors->first('delivery_order_list_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-6 col-xs-12">
        {!! Form::label('files', 'Delivery Order Files', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
        <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
            {!! Form::file('files', null, ['class' => 'form-control']) !!}
            @if(isset($deliveryorderlist->file) != '')
                <a href="{{ url('/') }}/files/delivery-order/{{ $deliveryorderlist->file }}">{{ $deliveryorderlist->file }}</a>
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