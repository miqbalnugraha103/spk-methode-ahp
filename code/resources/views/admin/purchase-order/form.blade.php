<div class="row">
    <div class="col-sm-3 col-xs-12">
        <div class="form-float {{ $errors->has('quote_list_code_id') ? 'has-error' : ''}}">
            <label class="form-label" style="font-weight: 100; color: #aaa;">Quote Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
            <select name="quote_list_code_id" id="quote_list_code_id" class="form-control show-tick">
                <option value=""> -- Select --</option>
                @foreach($QuoteCode as $qc)
                <option value="{!! $qc->id !!}">{!! $qc->quote_list_code !!}</option>
                @endforeach
            </select>
            {!! $errors->first('quote_list_code_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-3 col-xs-12">
        <div class="form-float {{ $errors->has('purchase_order_list_code') ? 'has-error' : ''}}">
            <label class="form-label" style="font-weight: 100; color: #aaa;">Purchase Order Name : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
            <input type="text" name="purchase_order_list_code" id="purchase_order_list_code" class="form-control" value="{{ old('purchase_order_list_code') }}" placeholder="">
            {!! $errors->first('purchase_order_list_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-6 col-xs-12">
        {!! Form::label('files', 'Purchase Order File(attachment)', ['class' => 'form-label', 'style' => 'font-weight: 100; color: #aaa']) !!}
        <div class="form-group form-float {{ $errors->has('files') ? 'has-error' : ''}}">
            {!! Form::file('files', null, ['class' => 'form-control']) !!}
            {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<hr>
{!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', ['class' => 'btn bg-green waves-effect']) !!}