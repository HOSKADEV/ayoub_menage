<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .logo {
            float: left;
            width: 140px;
        }
        .company-info {
            float: right;
            text-align: right;
        }
        .company-info h2 {
            margin-top: 38px;
            font-size: 18px;
        }
        .company-info p {
            margin: 0;
            font-size: 12px;
            color: #777;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 5px;
        }
        .table th {
            text-align: center;
            background-color: #f1f1f1;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        /* .hr{
            margin-top: 80px;
        } */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img width="100px" src="https://storage.googleapis.com/hoskashopping-afc1c.appspot.com/products/125/8979c9542081e89a0ec7b063c366f16b.PNG" alt="Company logo">
            </div>
            <div class="company-info">
                <h2>Maruizon</h2>
            </div>
        </div>
        {{-- {{ dd($data)}} --}}
        <div class="content">
            <h1 align="center">فاتورة</h1>
            <p>رقم الفاتورة: Ayoub Menage-00{{$invoice->id}}</p>
            <p>تاريخ الفاتورة: {{$data['date']}}</p>
            <hr>
            <p>الزبون:</p>
            <p>الإسم:{{ $users->name }}</p>
            <p> الهاتف: {{ $users->phone }}</p>
            <p> البريد الإلكتروني: {{ $users->email}}</p>
            <p> الولاية: {{ $wilayas->name }}</p>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>نسبة التخفيض</th>
                        <th>المبلغ الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                      <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $item->unit_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }} Dzd</td>
                        <td>{{ $item->discount }}%</td>
                        <td>{{ $item->amount}} Dzd</td>
                        {{-- <td>{{$quantities[$loop->index]}}</td> --}}
                      </tr>
                      @endforeach
                      <tr>
                        <td colspan="5" class="total">الإجمالي لمنتجات </td>
                        <td class="total"> {{ $invoice->purchase_amount }} Dzd</td>
                      </tr>
                      <tr>
                        <td colspan="5" class="total">سعر التوصيل</td>
                        <td class="total"> {{ $orders->delivery_price }} Dzd</td>
                      </tr>
                      <tr>
                        <td colspan="5" class="total">طريقة الدفع </td>
                        <td class="total"> {{ $orders->payement_method }} </td>
                      </tr>
                      <tr>
                        <td colspan="5" class="total">الإجمالي</td>
                        <td class="total"> {{ $invoice->total_amount }} Dzd </td>
                      </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
