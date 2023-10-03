<!DOCTYPE html>
<html>
<head>
    <title>Print Example</title>
    <style>
        @media print {
            body.modalprinter * {
                visibility: hidden;
            }

            body.modalprinter .modal-dialog.focused {
                position: absolute;
                padding: 0;
                margin: 0;
                left: 0;
                top: 0;
            }

            body.modalprinter .modal-dialog.focused .modal-content {
                border-width: 0;
            }

            body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title,
            body.modalprinter .modal-dialog.focused .modal-content .modal-body,
            body.modalprinter .modal-dialog.focused .modal-content .modal-body * {
                visibility: visible;
            }

            body.modalprinter .modal-dialog.focused .modal-content .modal-header,
            body.modalprinter .modal-dialog.focused .modal-content .modal-body {
                padding: 0;
            }

            body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title {
                margin-bottom: 20px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div id="printDoc">
    <p> Print Me ! </p>
</div>
<input type='button' id='btn' value='Print' onclick='printDiv("printDoc");' >

<script type="text/javascript">
    $().ready(function () {
        $('.modal.printable').on('shown.bs.modal', function () {
            $('.modal-dialog', this).addClass('focused');
            $('body').addClass('modalprinter');

            if ($(this).hasClass('autoprint')) {
                window.print();
            }
        }).on('hidden.bs.modal', function () {
            $('.modal-dialog', this).removeClass('focused');
            $('body').removeClass('modalprinter');
        });
    });
</script>
</body>
</html>
