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
        font-size: 9px;
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
    table {
        width: 100%;
        margin-bottom: 15px;
    }
    table td {
        padding-bottom: 3px;
    }
    table td:last-child {
        text-align: right;
    }
</style>

<h1>Инкассация проведена <br> {{ $date }}</h1>

<h2>Терминал {{ $id_term }}</h2>
<br>

<table>
    <tbody>
    <tr>
        <td>Банкнот:</td>
        <td>{{ $count }} шт</td>
    </tr>
    <tr>
        <td>Сумма:</td>
        <td>{{ $summ }} р</td>
    </tr>
    </tbody>
</table>

<br>
<div class="">
    <h3>Телефон оператора:</h3>
    <p>8(8443)55-63-86</p>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<p style="text-align: center">______________________________</p>
</body>
</html>