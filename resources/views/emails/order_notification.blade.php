<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body bgcolor="#f6f6f6">

<!-- body -->
<h2>Hello {{$data['name']}},</h2>
<p>{{ $data['message'] }}</p>
<a href="{{ $data['order_tracking_no'] }}">{{ $data['order_tracking_no'] }}</a>
<h5>IBCS-PRIMAX E-COMMERCE</h5>
<!-- /body -->

</body>
</html>