<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #5f6865;
            color: white;
        }
    </style>
</head>

<body>
    <table id="customers">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>{{ $makeRequest->name }}</th>
            </tr>
        </thead>
        <tbody>


            <tr>
                <td>type</td>
                <td>{{ $makeRequest->type }}</td>
            </tr>

            <tr>
                <td>E-mail</td>
                <td>{{ $makeRequest->email}}</td>

            </tr>

            <tr>
                <td>name</td>
                <td>{{ $makeRequest->name}}</td>

            </tr>

            <tr>
                <td>phone</td>
                <td>{{ $makeRequest->phone}}</td>

            </tr>
            <tr>
                <td>commodity</td>
                <td>{{ $makeRequest->commodity}}</td>

            </tr>

            <tr>
                <td>freight_terms</td>
                <td>{{ $makeRequest->freight_terms}}</td>

            </tr>

            <tr>
                <td>movement</td>
                <td>{{ $makeRequest->movement}}</td>

            </tr>

            <tr>
                <td>service_at</td>
                <td>{{ $makeRequest->service_at}}</td>

            </tr>

            <tr>
                <td>origin_zipcode</td>
                <td>{{ $makeRequest->origin_zipcode}}</td>

            </tr>


            <tr>
                <td>destination_zipcode</td>
                <td>{{ $makeRequest->destination_zipcode}}</td>

            </tr>


            <tr>
                <td>origin_address</td>
                <td>{{ $makeRequest->origin_address}}</td>

            </tr>

            <tr>
                <td>destination_address</td>
                <td>{{ $makeRequest->destination_address }}</td>

            </tr>
            <tr>
                <td>description</td>
                <td>{{ $makeRequest->description }}</td>

            </tr>
            <tr>
                <td>company</td>
                <td>{{ $makeRequest->company }}</td>

            </tr>
            <tr>
                <td>package_number</td>
                <td>{{ $makeRequest->package_number}}</td>

            </tr>

            <tr>
                <td>gross_weight</td>
                <td>{{ $makeRequest->gross_weight}}</td>

            </tr>

            <tr>
                <td>volume</td>
                <td>{{ $makeRequest->volume}}</td>

            </tr>

            <tr>
                <td>size_length</td>
                <td>{{ $makeRequest->size_length}}</td>

            </tr>

            <tr>
                <td>size_width</td>
                <td>{{ $makeRequest->size_width}}</td>

            </tr>

            <tr>
                <td>size_height</td>
                <td>{{ $makeRequest->size_height}}</td>

            </tr>

            <tr>
                <td>insurance</td>
                <td>{{ $makeRequest->insurance}}</td>

            </tr>

            <tr>
                <td>active</td>
                <td>{{ $makeRequest->active}}</td>

            </tr>

            <tr>
                <td>order id</td>
                <td>{{ $makeRequest->order_id}}</td>

            </tr>

            @foreach (DB::table('countries')->where('id', $makeRequest->origin_country_id)->pluck('name') as $date)
                <tr>
                    <td>origin country</td>
                    <td>{{ $date }}</td>
                </tr>
            @endforeach

            @foreach (DB::table('countries')->where('id', $makeRequest->destination_country_id)->pluck('name') as $date)
                <tr>
                    <td>destination country</td>
                    <td>{{ $date }}</td>
                </tr>
            @endforeach

            @foreach (DB::table('contact_people')->where('user_id',  $makeRequest->user_id)->pluck('name') as $date)
                <tr>
                    <td>user</td>
                    <td>{{ $date }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>



</body>

</html>
