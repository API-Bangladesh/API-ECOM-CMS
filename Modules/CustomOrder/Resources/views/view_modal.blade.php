<!-- CSS -->
<style>
    @media print {
        /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body */
        body.modal-open {
            visibility: hidden;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -1.6rem;
            margin-left: -1.6rem;
        }
        .rowm {
            display: flex !important;
            flex-wrap: wrap !important;
            margin-right: -1.6rem;
            margin-left: -1.6rem;
            margin-bottom: 0px;
        }
        body.modal-open .modal .modal-body {
            visibility: visible; /* make visible modal body and header */
            overflow: visible !important;
            width: auto !important;
            height: auto !important;
        }
        .modal-content{
            overflow: visible !important;
            width: auto !important;
            height: auto !important;
        }
        .modal-dialog {
            width: auto;
            margin: 30px; /
        }
        .modal-header{
            display:none;
        }
        .modal {
            display: block !important;
        }
        .print {display: block;}


    }

    .company_name_text, .company_address_text, .company_phone_text, .company_mail_text{
        font-size: 11px !important;
    }

    .text-uppercase{
        font-size: 12px !important;
    }



    .tablec thead th {
        color: #000 !important;
    }

    .tablec {
        width: 100%;
    }

    .text-bold {
        font-weight: 500;
        font-size: 12px;
    }
    .bold {
        font-weight: 500;
    }

    #table_tr td{
        font-size: 11px;
    }
    #table_tr th{
        display: flex;
        align-items: center;
    }

    th,
    td {
        padding: 0.6em 1.5em;
    }

    .text-right {
        text-align: right !important;
    }

    .rowm {
        display: flex;
        flex-wrap: wrap;
        margin-right: -1.6rem;
        margin-left: -1.6rem;
        margin-bottom: 0px;
    }

    .mbm-4 {
        margin-bottom: 10px !important;
    }
    .offset-8 {
        margin-left: 66.6666666667%;
    }

    /* Custom Grid System */
    .col-md-4{
        width: 33.333%;
        padding: 0 1.6rem;
    }
    .col-md-5 {
        width: 41.666%;
    }
    .logo{
        width: 250px;
        height: auto;
    }
    th {
        display: table-cell;
        vertical-align: inherit;
        font-weight: 500 !important;
        text-align: -internal-center;
    }
    table {
        border-collapse: separate;
        text-indent: initial;
        border-spacing: 1px !important;
    }
    hr {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        border: 0;
        border-top: 1px solid #ced4da;
    }
    @media (min-width: 768px) {
        .col-md-4 {
            width: 33.333%;
        }

        .col-md-5 {
            width: 41.666%;
        }
    }
    .mb-0{
        margin-bottom:0px;
    }
    .p-m-b {
        margin-bottom: 0px;
        font-size: 12px;
    }
</style>


<!-- HTML -->
<div class="modal" id="view_modal">
    <div class="modal-dialog modal-lg">
        <!-- Modal Content -->
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1">Modal Title</h3>
                <span class="close" data-dismiss="modal" aria-label="Close">&times;</span>
            </div>
            <!-- /modal header -->
            <div id="pdf-content">

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="rowm mbm-4 scharge">
                        <div class="col-md-4">
                            <img src="" class="logo" alt="Logo" />
                        </div>

                        <div class="col-md-4 offset-4">
                            <label class="text-bold">Company:&nbsp;</label>&nbsp;<span class="company_name_text" style="width: 100%;"></span><br />
                            <label class="text-bold">Address:&nbsp;</label><span class="company_address_text"></span>&nbsp;<br />
                            <label class="text-bold">Phone:&nbsp;</label>&nbsp;<span class="company_phone_text"></span><br />
                            <label class="text-bold">Mail:&nbsp;</label>&nbsp;<span class="company_mail_text"></span>
                        </div>
                    </div>

                    <div class="rowm mbm-4 scharge">
                        <div class="col-md-4">
                            <h4 class="text-bold mb-1 pb-0">Billing</h4>
                            <span class="billing"></span>
                        </div>

                        <div class="col-md-4">
                            <h4 class="text-bold mb-1 pb-0">Shipping</h4>
                            <span class="shipping"></span>
                        </div>

                        <div class="col-md-4">
                            <h4 class="text-bold mb-1 pb-0">Invoice</h4>
                            <label class="text-bold">Order ID: </label>&nbsp;<span class="order_id" id="order_id"></span><br />
                            <label class="text-bold">Order Date: </label>&nbsp;<span class="order_date"></span><br />
                        </div>
                    </div>



                    <hr/>



                    <div class="rowm mbm-4 scharge">
                        <table class="tablec">
                            <thead>
                            <tr>
                                <th class="text-uppercase" scope="col">SL</th>
                                <th class="text-uppercase" scope="col">PRODUCT NAME</th>
                                <th class="text-uppercase" scope="col">QTY</th>
                                <th class="text-uppercase" scope="col">PRICE</th>
                                <th class="text-uppercase" scope="col">ITEM TOTAL</th>
                            </tr>
                            </thead>
                            <tbody id="table_tr">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /modal body -->
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-sm btn-print" data-dismiss="modal">Print</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>

            </div>
            <!-- /modal footer -->
        </div>
        <!-- /modal content -->
    </div>
</div>

<script src="js/html2canvas.min.js"></script>
<script src="js/html2pdf.bundle.min.js"></script>

<!-- jsPDF -->
<script src="js/jspdf.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.btn-print').addEventListener('click', function() {


            let pdfName = $('#order_id').text();
            pdfName = pdfName.replace('#','');
            pdfName = pdfName.trim(); // Remove the '#' character


            const pdf = html2pdf().set({
                margin: [0, 0.5, 0, 0.5],
                filename: `order-${pdfName}.pdf`, // Using the cleaned order ID in the filename
                image: { type: 'png', quality: 1 },
                html2canvas: { scale: 2},
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            }).from($('#pdf-content')[0]);

            pdf.output('bloburl').then(function(pdfData) {
                // Open the PDF file in a new window
                window.open(pdfData, '_blank');

                // Optionally, you can handle any completion logic here
            });


        });


    });




</script>
