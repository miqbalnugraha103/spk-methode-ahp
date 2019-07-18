<html>
<head>
    <style>
        @page { margin: 180px 50px; }
        #header { left: 0px; margin-top: -150px; right: 0px;background-color: white; }
        #footer { position: fixed; left: 0px; bottom: -200px; right: 0px; height: 150px; background-color: white; }
        #footerto { margin-top: 100px; margin-left: 50px;}
        #footer .page:after {content: counter(page, upper-roman); }
        #content { margin-top: -20px;}
    </style>
    
<!-- Bootstrap Core Css -->
<link href="{{ url('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="{{ url('/') }}/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="{{ url('/') }}/font-awesome/css/font-awesome-fa.css" rel="stylesheet" />
</head>
<body>
<div id="header">
    <img src="{{ URL('/') }}/images/icon-ajd.jpg" width="150" style="margin-top: 10px;">
    <div style="float: right; margin-right:170px;margin-top: -30px;"><h1>PT. Adika Jaya Dwata</h1></div>
    <div style="width: 500px; margin-left:170px;margin-top: -140px;">
        <h4>Supplier kebutuhan kamar mandi dan sanitari serta menyediakan berbagai macam kebutuhan lantai (Keramik, Granit dan Vinnyl)</h4>
        <div style="margin-top:0;">Alamat : Jl. Teuku Umar 200 Denpasar 80114</div>
        <div style="">Telepon/ Fax : 0361 264410 / 246939</div>
        <div style="">Email : adikajaya@yahoo.com</div>
    </div>
</div>
<hr>
<div id="content">
    <h2>Report Prospect Sales Detail</h2>
    <p>Company Name &nbsp;&nbsp;&nbsp;&nbsp;: {!! $prospectSales->company_name  !!}</p>
    <p>Company Address : <div style="width: 300px;">{!! $prospectSales->company_address !!} Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem quibusdam reiciendis doloribus consectetur animi velit quas ratione cum, eligendi alias vitae eveniet obcaecati natus praesentium, sequi commodi ea deleniti quae. </div></p>
    <p>Company Phone &nbsp;&nbsp;&nbsp;: {!! $prospectSales->company_hone  !!} </p>
    <p>CP Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {!! $prospectSales->name_pic  !!}</p>
    <p>Progress Notes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {!! $prospectSales->progress_notes  !!}</p>
    <p>Brand : </p>
    <div style="margin-left: 50px;">
        @foreach($brandData as $td)
            {!! $td->brand !!}
        @endforeach
    </div>


    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="15%">Company Name</th><td width="1%">:</td><td>{!! $prospectSales->company_name  !!}</td>
            </tr>
            <tr>
                <th width="15%">Company Address</th><td width="1%">:</td><td>{!! $prospectSales->company_address !!}</td>
            </tr>
            <tr>
                <th>Company Phone</th><td>:</td><td>{!! $prospectSales->company_phone !!} </td>
            </tr>
            <tr>
                <th>CP Name</th><td>:</td><td>{!! $prospectSales->name_pic !!} </td>
            </tr>
            <tr>
                <th>Progress Notes</th><td>:</td><td>{!! $prospectSales->progress_notes !!} </td>
            </tr>
            <tr>
                <th>Brand</th>
                <td>:</td>
                <td>
                    @foreach($brandData as $td)
                    {!! $td->brand !!}<br>

                    @endforeach
                </td>
            </tr>

        </tbody>
    </table>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="5%">#</th><th width="25%">Sales Person</th><th width="25%">Company Name</th><th width="20%">Company Phone</th><th width="15%">Action Date</th>
            </tr>
            @foreach($historysales as $no => $history)
            <tr>
                <td>{!! ++$no !!}</td>
                <td width="15%">{!! $history->name_sales !!}</td>
                <td width="1%">{!! $history->assignment_date !!}</td>
                <td>{!! $history->notes !!}</td>
                <td>{!! $history->name_progress !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="footer">
    <p class="page">Page </p>
</div>
</body>
</html>