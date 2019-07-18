@extends('layouts.admin.frame')

@section('title', 'Create New Product Item')

@section('content')

<ol class="breadcrumb breadcrumb-col-blue">
    <li><a href="{{ url('/admin') }}">Home</a></li>
    <li><a href="{{ url('/admin/product') }}">Product Item</a></li>
    <li class="active">Create New Product Item</li>
</ol>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12">
                            <h2>Create New Product Item<span class="pull-right"><a href="{{ url('/admin/product') }}" title="Back"><button class="btn bg-green waves-effect">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="body">

                    {!! Form::open(['url' => '/admin/product', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('admin.product-item.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
    function tandaPemisahTitik(b){
        var _minus = false;
        if (b<0) _minus = true;
        b = b.toString();
        b=b.replace(",","");
        b=b.replace("-","");
        c = "";
        panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--){
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)){
                c = b.substr(i-1,1) + "," + c;
            } else {
                c = b.substr(i-1,1) + c;
            }
        }
        if (_minus) c = "-" + c ;
        return c;
    }

    function notNull(value){
        if(parseInt(value) == 0 || value == ''){
            $('#quantity').val(0);
        }
    }

    function numbersonly(ini, e){
        if (e.keyCode>=49){
            if(e.keyCode<=57){
                a = ini.value.toString().replace(",","");
                b = a.replace(/[^\d]/g,"");
                b = (b=="0")?String.fromCharCode(e.keyCode):b + String.fromCharCode(e.keyCode);
                ini.value = tandaPemisahTitik(b);
                return false;
            }
            else if(e.keyCode<=105){
                if(e.keyCode>=96){
                    //e.keycode = e.keycode - 47;
                    a = ini.value.toString().replace(",","");
                    b = a.replace(/[^\d]/g,"");
                    b = (b=="0")?String.fromCharCode(e.keyCode-48):b + String.fromCharCode(e.keyCode-48);
                    ini.value = tandaPemisahTitik(b);
                    //alert(e.keycode);
                    return false;
                }
                else {return false;}
            }
            else {
            return false; }
        }else if (e.keyCode==48){
            a = ini.value.replace(",","") + String.fromCharCode(e.keyCode);
            b = a.replace(/[^\d]/g,"");
            if (parseFloat(b)!=0){
                ini.value = tandaPemisahTitik(b);
                return false;
            } else {
                return false;
            }
        }else if (e.keyCode==95){
            a = ini.value.replace(",","") + String.fromCharCode(e.keyCode-48);
            b = a.replace(/[^\d]/g,"");
            if (parseFloat(b)!=0){
                ini.value = tandaPemisahTitik(b);
                return false;
            } else {
                return false;
            }
        }else if (e.keyCode==8 || e.keycode==46){
            a = ini.value.replace(",","");
            b = a.replace(/[^\d]/g,"");
            b = b.substr(0,b.length -1);
            if (tandaPemisahTitik(b)!=""){
                ini.value = tandaPemisahTitik(b);
            } else {
                ini.value = "";
            }

            return false;
        } else if (e.keyCode==9){
            return true;
        } else if (e.keyCode==17){
            return true;
        } else {
            //alert (e.keyCode);
            return false;
        }

    }
</script>
@endpush
