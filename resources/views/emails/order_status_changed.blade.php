<!DOCTYPE html>
<html>
<head>
    <title> Order Status Changed</title>
</head>
<body>
    <h3>Customer Name: {{$orderStatus['customer_name']}}</h3>
    <p>Order ID: IFAD-{{$orderStatus['order_id'] }}</p>
    <p>Order status: {{ $orderStatus['status'] }}</p>
</body>
</html>