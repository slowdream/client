<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400&amp;subset=cyrillic" rel="stylesheet">
  <title>Document</title>
</head>
<body>


<style type="text/css">
  /* http://meyerweb.com/eric/tools/css/reset/
     v2.0 | 20110126
     License: none (public domain)
  */

  html, body, div, span, applet, object, iframe,
  h1, h2, h3, h4, h5, h6, p, blockquote, pre,
  a, abbr, acronym, address, big, cite, code,
  del, dfn, em, img, ins, kbd, q, s, samp,
  small, strike, strong, sub, sup, tt, var,
  b, u, i, center,
  dl, dt, dd, ol, ul, li,
  fieldset, form, label, legend,
  table, caption, tbody, tfoot, thead, tr, th, td,
  article, aside, canvas, details, embed,
  figure, figcaption, footer, header, hgroup,
  menu, nav, output, ruby, section, summary,
  time, mark, audio, video {
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 100%;
    vertical-align: baseline;
  }
  /* HTML5 display-role reset for older browsers */
  article, aside, details, figcaption, figure,
  footer, header, hgroup, menu, nav, section {
    display: block;
  }
  body {
    line-height: 1;
  }
  ol, ul {
    list-style: none;
  }
  blockquote, q {
    quotes: none;
  }
  blockquote:before, blockquote:after,
  q:before, q:after {
    content: '';
    content: none;
  }
  table {
    border-collapse: collapse;
    border-spacing: 0;
  }
</style>
<style type="text/css">
  * {
    box-sizing: border-box;
  }
  @font-face {
    font-family: 'Roboto';
    font-weight: normal;
    src: url("{{asset('fonts/Roboto-Regular.ttf')}}")  format('truetype');
  }
  @font-face {
    font-family: 'Roboto';
    font-weight: bold;
    src: url("{{asset('fonts/Roboto-Bold.ttf')}}")  format('truetype');
  }
  body {
    font-family: 'Roboto', sans-serif !important;
    font-size: 10px;
    padding: 5px 10px;
    color: #000;
  }
  h1 {
    font-size: 17px;
    font-weight: bold;
    text-align: center;
    line-height: 20px;
    margin-top: 10px;
    margin-bottom: 20px;
  }
  h2 {
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
  }
  h3 {
    font-weight: bold;
  }
  .name {
    margin-bottom: 5px;
    line-height: 15px;
    height: 40px;
    overflow: hidden;
    display: block;
  }
  table {
    width: 100%;
    margin-bottom: 15px;
  }

  table td:last-child {
    text-align: right;
  }
</style>

<h1>Магазин "Клуб мастеров" <br> ИП шитиков В.Н <br>{{ $date }}</h1>

<h2>Заказ № {{ $id }}</h2>
@foreach ($products as $product)
<span class="name">{{ $product['guid'] }} {{ $product['name'] }} </span>
<table>
  <tbody>
    <tr>
      <td>Количество</td>
      <td>{{ $product['count'] }}</td>
    </tr>
    <tr>
      <td>Цена</td>
      <td>{{ $product['price'] }}</td>
    </tr>
    <tr>
      <td>Сумма</td>
      <td>{{ $product['count']*$product['price'] }}</td>
    </tr>


  </tbody>
</table>
@endforeach
<table>
  <tbody>
    <tr>
      <td>Сумма заказа:</td>
      <td>{{ $summ }}р</td>
    </tr>
    <tr>
      <td>Доставка:</td>
      <td>{{ $delivery }}р</td>
    </tr>
    <tr>
      <td>Итого:</td>
      <td>{{ $summ + $delivery }}р</td>
    </tr>
    <tr>
      <td>Оплачено: </td>
      <td>{{ $cash }}</td>
    </tr>
  </tbody>
</table>

<div class="">
  <h3>Указанный телефон:</h3>
  <p>{{ $tel }}</p>
  <h3>Адрес доставки:</h3>
  <p>{{ $address }}</p>
  <h3>Телефон оператора:</h3>
  <p>8(8443)55-63-86</p>
</div>
<br>
@if ($reason == 'canceled')
  <p><h4>ВНИМАНИЕ!</h4> Ваш заказ не будет обработан, пожалуйста свяжитесь с оператором по телефону указанному на чеке</p>
@endif
@if ($cash > $summ + $delivery)
  <p><h4>ВНИМАНИЕ!</h4> Сдачу в размере {{ $cash - ($summ + $delivery) }}р Вам доставит курьер вместе с заказом</p>
@endif
</body>
</html>