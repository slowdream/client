<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&amp;subset=cyrillic" rel="stylesheet">
	<title>Document</title>
</head>
<body>


<style type="text/css">
* {
  box-sizing: border-box;
}
body,html {
  margin: 0;
  padding: 0;
}
body {
font-family: 'Roboto Condensed', sans-serif;
font-size: 8px;
padding: 5px;
}
</style>


<table>
  <tbody>
    <tr>
      <td>Товар</td>
      <td>Кол-во</td>
      <td>Цена</td>
    </tr>
  @php
      $summ = 0;
  @endphp
@foreach ($products as $product)

    <tr>
      <td>{{ $product['guid'] }}</td>
      <td>{{ $product['count'] }}</td>
      <td>{{ $product['price'] }}</td>
    </tr>
  @php
      $summ += $product['count'] * $product['price'];
  @endphp
@endforeach
    <tr>
      <td>Итого: {{ $summ }}</td>
    </tr>
  </tbody>
</table>


</body>
</html>