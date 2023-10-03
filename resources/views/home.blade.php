@extends('layouts.app')

@push('stylesheet')
<link rel="stylesheet" href="css/chart.min.css">
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row mt-2">

        <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="sale" class="m-1">{{ $customers }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Total Customer</p>
                </div>
            </div>
            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="sale" class="m-1">{{ $total_orders }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Total Order</p>
                </div>
            </div>
            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="sale" class="m-1">{{ $today_orders }} </h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Today Order</p>
                </div>
            </div>

            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="income" class="m-1">{{ $pending_orders }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Pending Order</p>
                </div>
            </div>
            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="income" class="m-1">{{ $process_orders }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Processing Order</p>
                </div>
            </div>
            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="income" class="m-1">{{ $shipped_orders }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Shipped Order</p>
                </div>
            </div>
            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="income" class="m-1">{{ $delivered_orders }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Delivered Order</p>
                </div>
            </div>
            
            <div class="col-md-3 mb-5 pt-2">
                <div class="bg-white text-center py-3  rounded-xl">
                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-3">
                        
                    </span>
                    <h6 id="income" class="m-1">{{ $cancel_orders }}</h6>
                    <p class="font-weight-bold font-size-h7 mb-1">Cancel Order</p>
                </div>
            </div>

        </div>
        <h3>Inventory Product Quantity Notification</h3>
        @foreach($inventory_qtys as $inventory_qty)
         <li>{{ $inventory_qty->title }} - Stock Qty ({{ $inventory_qty->stock_quantity }}) Less then Reorder Qty ({{ $inventory_qty->reorder_quantity }}).</li> </br>

        @endforeach

        <h3>Combo Product Quantity Notification</h3>
        @foreach($combo_qtys as $combo_qty)
         <li>{{ $combo_qty->title }} - Stock Qty ({{ $combo_qty->stock_quantity }}) Less then Reorder Qty ({{ $combo_qty->reorder_quantity }}).</li> </br>

        @endforeach
    </div>
    
</div>
@endsection

@push('script')
<script src="js/chart.min.js"></script>
<script>
$(document).ready(function(){

  


  var brandPrimary;
  var brandPrimaryRgba;

  //Cash Flow Chart
  var CASHFLOW = $('#cashFlow');
  if(CASHFLOW.length > 0)
  {
    brandPrimary = CASHFLOW.data('color');
    brandPrimaryRgba = CASHFLOW.data('color_rgba');
    var received = CASHFLOW.data('received');
    var sent = CASHFLOW.data('sent');
    var month = CASHFLOW.data('month');
    var label1 = CASHFLOW.data('label1');
    var label2 = CASHFLOW.data('label2');
    var cashFlow_chart = new Chart(CASHFLOW, {
      type:'line',
      data:{
        labels:[month[0],month[1],month[2],month[3],month[4],month[5],month[6]],
        datasets:[
          {
            label:label1,
            fill:true,
            lineTension:0.3,
            backgroundColor: 'transparent',
            borderColor: brandPrimary,
            borderCapStyle: 'butt',
            borderDash:[],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderWidth:3,
            pointBorderColor: brandPrimary,
            pointBackgroundColor:'#fff',
            pointBorderWidth:5,
            pointHoverRadius:5,
            pointHoverBackgroundColor:brandPrimary,
            pointHoverBorderColor:brandPrimaryRgba,
            pointHoverBorderWidth:2,
            pointRadius:1,
            pointHitRadius:10,
            data:[received[0],received[1],received[2],received[3],received[4],received[5],received[6]],
            spanGaps:false
          },
          {
            label:label2,
            fill:true,
            lineTension:0.3,
            backgroundColor: 'transparent',
            borderColor: '#f5222d',
            borderCapStyle: 'butt',
            borderDash:[],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderWidth:3,
            pointBorderColor: 'rgba(245, 34, 45, 1)',
            pointBackgroundColor:'#fff',
            pointBorderWidth:5,
            pointHoverRadius:5,
            pointHoverBackgroundColor:'#f5222d',
            pointHoverBorderColor:'rgba(245, 34, 45, 1)',
            pointHoverBorderWidth:2,
            pointRadius:1,
            pointHitRadius:10,
            data:[sent[0],sent[1],sent[2],sent[3],sent[4],sent[5],sent[6]],
            spanGaps:false
          }
        ]
      }
    });
  }

  //Transaction Chart
  var TRANSACTIONCHART = $('#transactionChart');
  if(TRANSACTIONCHART.length > 0)
  {
    brandPrimary = TRANSACTIONCHART.data('color');
    brandPrimaryRgba = TRANSACTIONCHART.data('color_rgba');
    var sale = TRANSACTIONCHART.data('sale');
    var purchase = TRANSACTIONCHART.data('purchase');
    var expense = TRANSACTIONCHART.data('expense');
    var label1 = TRANSACTIONCHART.data('label1');
    var label2 = TRANSACTIONCHART.data('label2');
    var label3 = TRANSACTIONCHART.data('label3');
    var transaction_chart = new Chart(TRANSACTIONCHART, {
      type:'doughnut',
      data:{
        labels:[label1,label2,label3],
        datasets:[
          {
            data:[purchase,sale,expense],
            borderWidth:[1,1,1],
            backgroundColor:[ brandPrimary,'#52c41a','#f5222d'],
            hoverBackgroundColor:[
              brandPrimaryRgba,
              'rgba(82, 196, 26, 1)',
              'rgba(245, 34, 45, 1)'
            ],
            hoverBorderWidth:[4,4,4],
            hoverBorderColor:[
              brandPrimaryRgba,
              'rgba(82, 196, 26, 1)',
              'rgba(245, 34, 45, 1)'
            ]
          }
        ]
      }
    });
  }

  //Yearly Report Chart
  var YEARLYREPORTCHART = $('#yearlyReportChart');

  if(YEARLYREPORTCHART.length > 0)
  {
    var yearly_sale_amount = YEARLYREPORTCHART.data('sale_chart_value');
    var yearly_purchase_amount = YEARLYREPORTCHART.data('purchase_chart_value');
    var label1 = YEARLYREPORTCHART.data('label1');
    var label2 = YEARLYREPORTCHART.data('label2');

    var yearly_report_chart = new Chart(YEARLYREPORTCHART, {
      type:'bar',
      data:{
        labels:["January","February","March","April","May","June","July","August","September","October","November","December"],
        datasets:[
          {
            label:label1,
            backgroundColor:[
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
            ],
            borderColor:[
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
            ],
            borderWidth:1,
            data:[
              yearly_purchase_amount[0],yearly_purchase_amount[1],yearly_purchase_amount[2],yearly_purchase_amount[3],
              yearly_purchase_amount[4],yearly_purchase_amount[5],yearly_purchase_amount[6],yearly_purchase_amount[7],
              yearly_purchase_amount[8],yearly_purchase_amount[9],yearly_purchase_amount[10],yearly_purchase_amount[11], 0
              ],
          },
          {
            label:label2,
            backgroundColor:[
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
            ],
            borderColor:[
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
            ],
            borderWidth:1,
            data:[
              yearly_sale_amount[0],yearly_sale_amount[1],yearly_sale_amount[2],yearly_sale_amount[3],
              yearly_sale_amount[4],yearly_sale_amount[5],yearly_sale_amount[6],yearly_sale_amount[7],
              yearly_sale_amount[8],yearly_sale_amount[9],yearly_sale_amount[10],yearly_sale_amount[11], 0
              ],
          },
        ]
      }
    });
  }
});
</script>
@endpush
