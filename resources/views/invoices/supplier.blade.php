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
            <p>رقم الفاتورة: Maurizon-00{{$data['id']}}</p>
            <p>تاريخ الفاتورة: {{$data['date']}}</p>
            <hr>
            <p>العميل:</p>
            <p>{{$data['supplier']}}</p>
            <p>{{$data['supplierPhone']}}</p>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الفئة</th>
                        <th>الصنف</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>المبلغ الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$data['name']}}</td>
                        <td>{{$data['category']}}</td>
                        <td>{{$data['subcategory']}}</td>
                        <td>{{$data['price']}}</td>
                        <td>{{$data['quantity']}}</td>
                        <td>{{$data['total']}} MRU</td>
                    </tr>

                    <tr>
                        <td colspan="5" class="total">الإجمالي</td>
                        <td class="total"> {{$data['total']}} MRU</td>
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
