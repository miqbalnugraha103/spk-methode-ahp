<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin', function () {
    return view('auth.login');
});

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/home', function () {
    return view('admin.dashboard');
});

Route::get('/seleksi', function () { return view('seleksi.index'); });
Route::get('/seleksi/tambah', function () { return view('seleksi.create'); });
Route::get('/seleksi/edit', function () { return view('seleksi.edit'); });
Auth::routes();
// Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin', function () {
    return redirect('/admin/quote-list');
});
Route::get('/admin/logout', 'Auth\\LoginController@logout')->name('logout');
Route::get('/admin/profile', 'Admin\\UserController@profile');

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['AuthAdmin']], function() {
        Route::get('/users/data', ['as' => 'users.data', 'uses' => 'Admin\\UserController@anyData']);
        Route::resource('users', 'Admin\\UserController');
    // Master
        //sales & detail sales
        Route::get('/sales/data', ['as' => 'sales.data', 'uses' => 'Admin\\SalesController@anyData']);
        Route::get('/sales/{id}/edit', ['as' => 'sales.edit', 'uses' => 'Admin\\SalesController@edit']);
        Route::resource('/sales', 'Admin\\SalesController');
        Route::get('/sales/sales-detail/data/{id}', ['as' => 'detail-sales.data', 'uses' => 'Admin\\SalesController@dataSalesDetail']);
        Route::get('/sales/sales-detail/{id}', 'Admin\\SalesController@detail');
        //End sales & detail sales
        //Customer Profile
        Route::get('/customer-profile/data', ['as' => 'customer-profile.data', 'uses' => 'Admin\\CustomerProfileController@anyData']);
        Route::resource('/customer-profile', 'Admin\\CustomerProfileController');
        //end Customer Profile
        //Color
        Route::get('/color/data', ['as' => 'color.data', 'uses' => 'Admin\\ColorController@anyData']);
        Route::resource('/color', 'Admin\\ColorController');
        //end Color
        //Brand
        Route::get('/brand/data', ['as' => 'brand.data', 'uses' => 'Admin\\BrandController@anyData']);
        Route::resource('/brand', 'Admin\\BrandController');
        //end Brand
        //Product Item
        Route::get('/product/data', ['as' => 'product.data', 'uses' => 'Admin\\ProductItemController@anyData']);
        Route::resource('/product', 'Admin\\ProductItemController');
        Route::get('/product/view/{id}', 'Admin\\ProductItemController@show');
        //end Product Item
        //status progress
        Route::get('/status-progress/data', ['as' => 'status-progress.data', 'uses' => 'Admin\\StatusProgressController@anyData']);
        Route::resource('/status-progress', 'Admin\\StatusProgressController');
        //End status progress
        //Term & Condition
        Route::get('/term-and-condition/data', ['as' => 'term-and-condition.data', 'uses' => 'Admin\\TermConditionController@anyData']);
        Route::resource('/term-and-condition', 'Admin\\TermConditionController');

        //end Term & Condition
        //Quote Template
        Route::get('/quote-template/data', ['as' => 'quote-template.data', 'uses' => 'Admin\\QuoteTemplateController@anyData']);
        Route::resource('/quote-template', 'Admin\\QuoteTemplateController');
        //End Quote Template
    });

//prospect & detail prospect
    Route::get('/prospect/data', ['as' => 'prospect.data', 'uses' => 'Admin\\ProspectSalesController@anyData']);
    Route::resource('/prospect', 'Admin\\ProspectSalesController');

    Route::patch('/edit-company/{id}', 'Admin\\ProspectSalesController@updateCompany');
    Route::patch('/edit-assignment/{id}', 'Admin\\ProspectSalesController@updateAssignment');
    Route::patch('/edit-progress/{id}', 'Admin\\ProspectSalesController@updateProgress');

    Route::get('/prospect/prospect-assignment/data/{id}', ['as' => 'prospect-assignment.data', 'uses' => 'Admin\\ProspectSalesController@assignment']);
    Route::get('/prospect/prospect-progress/data/{id}', ['as' => 'prospect-progress.data', 'uses' => 'Admin\\ProspectSalesController@progress']);
    Route::get('/get-customer-profile', ['as' => 'get_customer_profile.data', 'uses' => 'Admin\\ProspectSalesController@getCustomerProfile']);

    Route::get('/prospect/history/data/{id}', ['as' => 'prospect-sales-history.data', 'uses' => 'Admin\\ProspectSalesHistoryController@anyData']);
    Route::get('/prospect/history/{id}', 'Admin\\ProspectSalesHistoryController@index');

    Route::get('/sales-progress', 'Admin\\PitchingController@index');
    Route::get('/sales-progress/data/{id}', ['as' => 'sales-progress.data', 'uses' => 'Admin\\PitchingController@anyData']);
    Route::get('/sales-progress-all', ['as' => 'sales-progress-all.data', 'uses' => 'Admin\\PitchingController@anyData']);
    Route::get('/sales-progress/{id}', 'Admin\\PitchingController@dataTable');
    Route::get('/sales-progress/detail/data/{id}', ['as' => 'sales-progress-detail.data', 'uses' => 'Admin\\PitchingController@detailDataTable']);
    Route::get('/sales-progress/detail/{id}', 'Admin\\PitchingController@detail');
//end Prospect & detail prospect
//Quote
    Route::get('/quote-list/data', ['as' => 'quote-list.data', 'uses' => 'Admin\\QuoteListController@anyData']);
    Route::resource('/quote-list', 'Admin\\QuoteListController');
//    Route::delete('/quote-list/delete/{id}', 'Admin\\QuoteListController@destroy');
    Route::get('/quote-list/view/{id}', 'Admin\\QuoteListController@show');
    Route::post('/quote-list/createNewQuote', 'Admin\\QuoteListController@createNewQuote');
    Route::get('/quote-list/{id}/create', 'Admin\\QuoteListController@createQuote');
    Route::PATCH('/quote-list/do_create/{id}', ['as' => 'quote-list.do_create', 'uses' => 'Admin\\QuoteListController@do_create']);
    Route::post('/quote-list/update_data_sum/{id}', 'Admin\\QuoteListController@do_update_sum');
    Route::post('/quote-list/choose_tax', 'Admin\\QuoteListController@choose_tax');
    Route::post('/quote-list/tax', 'Admin\\QuoteListController@tax');
    Route::post('/quote-list/getTemplate', 'Admin\\QuoteListController@getTemplate');
    Route::post('/quote-list/getTermCondition', 'Admin\\QuoteListController@getTermCondition');

    Route::post('/quote-list/sales', 'Admin\\QuoteListController@getSales');
    Route::get('/quote-list/{id}/create_requote', 'Admin\\QuoteListController@createRequote');
    Route::get('/quote-list/{id}/requote', 'Admin\\QuoteListController@requote');
    Route::post('/quote-list/get_data_detail', ['as' => 'quote-list.get_data_detail', 'uses' => 'Admin\\QuoteListController@getDataDetail']);
    Route::post('/quote-list/save_data_detail', ['as' => 'quote-list.save_data_detail', 'uses' => 'Admin\\QuoteListController@do_save_detail']);

    Route::post('/quote-list/do_requote/{id}', 'Admin\\QuoteListController@do_requote');
    Route::post('/quote-list/new_data_detail', ['as' => 'quote-list.new_data_detail', 'uses' => 'Admin\\QuoteListController@NewRows']);
    Route::post('/quote-list/do_update_product/{id}', 'Admin\\QuoteListController@do_update_product');
    Route::post('/quote-list/do_btn_update/{id}', 'Admin\\QuoteListController@do_btn_update');
    Route::post('/quote-list/delete_data_detail/{id}', 'Admin\\QuoteListController@delete_data_detail');
    Route::post('/quote-list/refresh_data', 'Admin\\QuoteListController@RefreshData');
    Route::post('/filter/sales', 'Admin\\QuoteListController@getSalesCreate');
    Route::post('/quote-list/getproduct', 'Admin\\QuoteListController@getProduct');
    Route::post('/quote-list/update_data_qty/{id}', 'Admin\\QuoteListController@do_update_qty');
    Route::post('/quote-list/update_data_diskon/{id}', 'Admin\\QuoteListController@do_update_diskon');
    Route::post('/quote-list/update_data_diskon_nominal/{id}', 'Admin\\QuoteListController@do_update_diskon_nominal');
    Route::PATCH('/quote-list/requote/{id}', 'Admin\\QuoteListController@requote');
    
    Route::get('/requote/data', ['as' => 'requote.data', 'uses' => 'Admin\\RequoteController@anyData']);
    Route::resource('/requote', 'Admin\\RequoteController');
    Route::get('/requote/{id}/requote', 'Admin\\RequoteController@edit');
    Route::PATCH('/requote/{id}', 'Admin\\RequoteController@update');
    Route::get('/quote-list/{id}/generate-pdf', 'Admin\\QuoteListController@generatePdf');

    Route::PATCH('/transaction/{id}', 'Admin\\QuoteListController@getTransaction');
    //  End Quote
    
//Orders Transaction
    Route::get('/order/data', ['as' => 'order.data', 'uses' => 'Admin\\OrderContoller@anyData']);
    Route::resource('/order', 'Admin\\OrderController');


//End Orders
    
    // Start Purchase Order

    Route::get('/purchase-order-list/data', ['as' => 'purchase-order.data', 'uses' => 'Admin\\PurchaseOrderListController@anyData']);
    Route::resource('/purchase-order-list', 'Admin\\PurchaseOrderListController');
    Route::get('/purchase-order-list/view/{id}', 'Admin\\PurchaseOrderListController@show');

    Route::post('/purchase-order-list/createNewPurchaseOrder', 'Admin\\PurchaseOrderListController@createNewPurchaseOrder');
    Route::get('/purchase-order-list/{id}/create', 'Admin\\PurchaseOrderListController@CreatePurchaseOrder');
   
    Route::get('/purchase-order-list/{id}/repurchase-order', 'Admin\\PurchaseOrderListController@editRepurchaseOrder');
    Route::PATCH('/purchase-order-list/repurchase-order/{id}', 'Admin\\PurchaseOrderListController@repurchaseOrder');

    Route::get('/purchase-order-list/{id}/edit', 'Admin\\PurchaseOrderListController@edit');
    Route::post('/po/sales', 'Admin\\PurchaseOrderListController@getSales');
    Route::post('/po/refresh_data', 'Admin\\PurchaseOrderListController@RefreshData');
    Route::post('/po/getquote', 'Admin\\PurchaseOrderListController@getQuote');
    Route::post('/po/get_data_detail', 'Admin\\PurchaseOrderListController@getDataDetail');
    Route::post('/po/new_data_detail', 'Admin\\PurchaseOrderListController@NewRows');
    Route::post('/po/getproduct', 'Admin\\PurchaseOrderListController@getProduct');
    Route::post('/po/save_data_detail', 'Admin\\PurchaseOrderListController@do_save_detail');
    Route::post('/po/do_update_product/{id}', 'Admin\\PurchaseOrderListController@do_update_product');
    Route::post('/po/do_btn_update/{id}', 'Admin\\PurchaseOrderListController@do_btn_update');
    Route::post('/po/delete_data_detail/{id}','Admin\\PurchaseOrderListController@delete_data_detail');

    Route::PATCH('/purchase-order-list/do_create/{id}', ['as' => 'purchase-order-list.do_create', 'uses' => 'Admin\\PurchaseOrderListController@do_create']);
    Route::get('/purchase-order-list/approve/{id}', ['as' => 'purchase-order-list.approve', 'uses' => 'Admin\\PurchaseOrderListController@approve']);
    Route::PATCH('/purchase-order-list/do_edit/{id}', ['as' => 'purchase-order-list.do_edit', 'uses' => 'Admin\\PurchaseOrderListController@do_edit']);

    Route::post('/po/update_data_sum/{id}', 'Admin\\PurchaseOrderListController@do_update_sum');
    
    //Start Invoice
    Route::get('/invoice-list/data', ['as' => 'invoice.data', 'uses' => 'Admin\\InvoiceListController@anyData']);
    Route::resource('/invoice-list', 'Admin\\InvoiceListController');
    Route::get('/invoice-list/view/{id}', 'Admin\\InvoiceListController@show');

    Route::post('/invoice-list/createNewInvoice', 'Admin\\InvoiceListController@createNewInvoice');
    Route::get('/invoice-list/{id}/create', 'Admin\\InvoiceListController@CreateInvoice');

    Route::PATCH('/invoice-list/do_create/{id}', 'Admin\\InvoiceListController@do_create');
    Route::post('/invoice-list/create_payment', 'Admin\\InvoiceListController@create_payment');

    Route::get('/invoice-list/{id}/transaction', 'Admin\\InvoiceListController@transaction');
    Route::PATCH('/invoice-list/do_transaction/{id}', 'Admin\\InvoiceListController@do_transaction');
    Route::post('/invoice-list/getPurchaseOrder', 'Admin\\InvoiceListController@getPOT');
    Route::post('/invoice-list/getPurchaseOrderCreate', 'Admin\\InvoiceListController@getPOTCreate');
    
    Route::post('/invoice-list/choose_tax', 'Admin\\InvoiceListController@choose_tax');
    Route::post('/invoice-list/tax', 'Admin\\InvoiceListController@tax');

    Route::post('/invoice/delete_file_payment/{id}', 'Admin\\InvoiceListController@do_delete_file_payment');
    Route::post('/invoice/do_btn_update/{id}', 'Admin\\InvoiceListController@do_btn_update');
    Route::post('/invoice/create_all_invoice', 'Admin\\InvoiceListController@create_all_invoice');
    Route::post('/invoice/create_all_invoice_transaction', 'Admin\\InvoiceListController@create_all_invoice_transaction');

    // End Invoice 
    
    //Start Delivery Order
    Route::get('/delivery-order-list/data', ['as' => 'delivery-order.data', 'uses' => 'Admin\\DeliveryOrderListController@anyData']);
    Route::resource('/delivery-order-list', 'Admin\\DeliveryOrderListController');
    Route::get('/delivery-order-list/view/{id}', 'Admin\\DeliveryOrderListController@show');

    Route::post('/delivery-order-list/createNewDO', 'Admin\\DeliveryOrderListController@createNewDO');
    Route::get('/delivery-order-list/{id}/create', 'Admin\\DeliveryOrderListController@CreateDO');

    Route::get('/delivery-order-list/{id}/edit', 'Admin\\DeliveryOrderListController@edit');
    Route::PATCH('/delivery-order-list/do_create/{id}', ['as' => 'delivery-order-list.do_create', 'uses' => 'Admin\\DeliveryOrderListController@do_create']);
    Route::PATCH('/delivery-order-list/do_transaction/{id}', ['as' => 'delivery-order-list.do_transaction', 'uses' => 'Admin\\DeliveryOrderListController@do_transaction']);

    Route::post('/delivery-order-list/getPurchaseOrder', 'Admin\\DeliveryOrderListController@getPO');
    Route::post('/delivery-order-list/getPurchaseOrderCreate', 'Admin\\DeliveryOrderListController@getPOCreate');
    Route::post('/delivery-order-list/do_save_qty_create', 'Admin\\DeliveryOrderListController@do_save_qty_create');
    Route::post('/delivery-order-list/do_save_qty_transaction', 'Admin\\DeliveryOrderListController@do_save_qty_transaction');
    Route::post('/delivery-order-list/update_data_qty_create/{id}', 'Admin\\DeliveryOrderListController@do_update_qty_create');
    Route::post('/delivery-order-list/update_data_qty_transaction/{id}', 'Admin\\DeliveryOrderListController@do_update_qty_transaction');
    Route::post('/delivery-order-list/update_data_qty/{id}', 'Admin\\DeliveryOrderListController@do_update_qty');
    Route::post('/delivery-order-list/delete_qty/{id}', 'Admin\\DeliveryOrderListController@do_delete_qty');

    Route::post('/delivery-order-list/create_all_do', 'Admin\\DeliveryOrderListController@create_all_do');
    Route::post('/delivery-order-list/create_all_do_transaction', 'Admin\\DeliveryOrderListController@create_all_do_transaction');
    
    //Reporting
    Route::get('/report/by-sales/', 'Admin\\ReportController@showBySales');
    Route::get('/report/by-sales/data/', ['as' => 'report.by-sales.data', 'uses' => 'Admin\\ReportController@anyDataBySales']);

    Route::get('/report/pdf-sales-transaction', 'Admin\\ReportController@pdfTransaction');
    Route::get('/report/print-sales-transaction', 'Admin\\ReportController@printTransaction');

    Route::get('/report/by-sales-activity/', 'Admin\\ReportController@showByActivity');
    Route::get('/report/by-sales-activity/data/', ['as' => 'report.by-sales-activity.data', 'uses' => 'Admin\\ReportController@anyDataByActivity']);
    Route::get('/report/search-sales-activity/', 'Admin\\ReportController@searchActivity');
    Route::get('/report/print-sales-activity', 'Admin\\ReportController@printActivityAll');
    Route::get('/report/excel-sales-activity/{format}', 'Admin\\ReportController@excelActivityAll');
    Route::get('/report/print-sales-activity/{data}', 'Admin\\ReportController@printActivityGetId');
    Route::get('/report/pdf-sales-activity', 'Admin\\ReportController@pdfActivityAll');
    Route::get('/report/pdf-sales-activity/{data}', 'Admin\\ReportController@pdfActivityGetId');

    Route::get('/report/by-brand/', 'Admin\\ReportController@showByBrand');
    Route::get('/report/by-brand/data/', ['as' => 'report.by-brand.data', 'uses' => 'Admin\\ReportController@anyDataByBrand']);
    Route::get('/report/search-brand/', 'Admin\\ReportController@searchBrand');

    Route::get('/report/start-date-brand/', 'Admin\\ReportController@startDateBrand');
    Route::get('/report/end-date-brand/', 'Admin\\ReportController@endDateBrand');

    Route::get('/report/pdf-brand', 'Admin\\ReportController@pdfBrandAll');
    Route::get('/report/pdf-brand/{start_date}', 'Admin\\ReportController@pdfBrandStartDate');
    Route::get('/report/pdf-brand/{start_date}/{end_date}', 'Admin\\ReportController@pdfBrandStartEndDate');
    Route::get('/report/pdf-brand/{start_date}/{end_date}/{search}', 'Admin\\ReportController@pdfBrandStartEndDateSearch');
    Route::get('/report/pdf-brand-search/{search}', 'Admin\\ReportController@pdfBrandSearch');
    Route::get('/report/pdf-brand/{end_date}/{search}', 'Admin\\ReportController@pdfBrandEndDateSearch');
    Route::get('/report/pdf-brand/{start_date}/{search}', 'Admin\\ReportController@pdfBrandStartDateSearch');
    Route::get('/report/pdf-brand/{end_date}', 'Admin\\ReportController@pdfBrandEndDate');


    Route::get('/report/print-brand', 'Admin\\ReportController@printBrandAll');
    Route::get('/report/print-brand/{start_date}', 'Admin\\ReportController@printBrandStartDate');
    Route::get('/report/print-brand/{start_date}/{end_date}', 'Admin\\ReportController@printBrandStartEndDate');
    Route::get('/report/print-brand/{start_date}/{end_date}/{search}', 'Admin\\ReportController@printBrandStartEndDateSearch');
    Route::get('/report/print-brand-search/{search}', 'Admin\\ReportController@printBrandSearch');
    Route::get('/report/print-brand/{end_date}/{search}', 'Admin\\ReportController@printBrandEndDateSearch');
    Route::get('/report/print-brand/{start_date}/{search}', 'Admin\\ReportController@printBrandStartDateSearch');
    Route::get('/report/print-brand/{end_date}', 'Admin\\ReportController@printBrandEndDate');

    Route::get('/report/excel-brand/{format}', 'Admin\\ReportController@excelBrandAll');




    Route::get('/report/prospect-sales/', 'Admin\\ReportController@showProspectSales');
    Route::get('/report/prospect-sales/data/', ['as' => 'report.prospect-sales.data', 'uses' => 'Admin\\ReportController@anyDataProspectSales']);
    Route::get('/report/prospect-sales-detail/{id}', 'Admin\\ReportController@showProspectSalesHistory');
    Route::get('/report/prospect-sales-detail/data/{id}', ['as' => 'report.prospect-sales-history.data', 'uses' => 'Admin\\ReportController@anyDataProspectSalesHistory']);
    Route::get('/report/prospect-sales-detail/{id}/generate-pdf', 'Admin\\ReportController@ProspectSalesHistoryPdf');
    
    Route::get('/report/quote-list/', 'Admin\\ReportController@showQuote');
    Route::get('/report/quote-list/data/', ['as' => 'report.quote-list.data', 'uses' => 'Admin\\ReportController@anyDataQuote']);
    Route::get('/report/quote-list-detail/{id}', 'Admin\\ReportController@detailQuote');
    Route::get('/report/quote-list-detail/{id}/generate-pdf', 'Admin\\ReportController@QuoteDetailPdf');
    
    Route::get('/report/purchase-order-list/', 'Admin\\ReportController@showPurchaseOrder');
    Route::get('/report/purchase-order-list/data/', ['as' => 'report.purchase-order-list.data', 'uses' => 'Admin\\ReportController@anyDataPurchaseOrder']);
    Route::get('/report/purchase-order-list-detail/{id}', 'Admin\\ReportController@detailPurchaseOrder');
    Route::get('/report/purchase-order-list-detail/{id}/generate-pdf', 'Admin\\ReportController@PurchaseOrderDetailPdf');
    
    Route::get('/report/invoice-list/', 'Admin\\ReportController@showInvoice');
    Route::get('/report/invoice-list/data/', ['as' => 'report.invoice-list.data', 'uses' => 'Admin\\ReportController@anyDataInvoice']);
    Route::get('/report/invoice-list-detail/{id}', 'Admin\\ReportController@detailInvoice');
    Route::get('/report/invoice-list-detail/{id}/generate-pdf', 'Admin\\ReportController@invoiceDetailPdf');
    
    Route::get('/report/delivery-order-list/', 'Admin\\ReportController@showDeliveryOrder');
    Route::get('/report/delivery-order-list/data/', ['as' => 'report.delivery-order-list.data', 'uses' => 'Admin\\ReportController@anyDataDeliveryOrder']);
    Route::get('/report/delivery-order-list-detail/{id}', 'Admin\\ReportController@detailDeliveryOrder');
    Route::get('/report/delivery-order-list-detail/{id}/generate-pdf', 'Admin\\ReportController@deliveryOrderDetailPdf');
    

    
    //End Reporting
});