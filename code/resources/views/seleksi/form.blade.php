<div class="row">
    <div class="col-md-6" style="margin-top:20px;">
        <div class="form-group form-float {{ $errors->has('color_name') ? 'has-error' : ''}}">
            <div class="form-line">
                {!! Form::text('color_name', 'Seleksi Bagian Marketing', ['class' => 'form-control']) !!}
                <label class="form-label">Nama Seleksi <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
            </div>
            {!! $errors->first('color_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-6 col-xs-6">
        <div class="form-float {{ $errors->has('date_out') ? 'has-error' : ''}}">
            <label class="form-label" style="font-weight: 100; color: #aaa;"> Tahun Seleksi : <span style="font-size: 15px;color: red;line-height:15px;">*</span></label>
            <input name="date_out" class="form-control" type="number" min="1900" max="2099" step="1" value="2015" />
            {!! $errors->first('date_out', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-12 col-xs-12">
        <div class="form-float">
            <label class="form-label" style="font-weight: 100; color: #aaa;">Note :</label>
            <textarea name="note" id="note" class="form-control" rows="3">Penyeleksian Bagian Marketing Cabang Baru</textarea>
        </div>
    </div>
</div>
<br>
<div class="form-group">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Tambah Data', ['class' => 'btn bg-green waves-effect']) !!}
</div>