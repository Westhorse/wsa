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
                <th>{{ $user->name }}</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>E-mail </td>
                <td>{{ $user->email }}</td>

            </tr>
            <tr>
                <td>Address</td>
                <td>{{ $user->address_line1 }}</td>

            </tr>
            <tr>
                <td>city</td>
                <td>{{ $user->city }}</td>

            </tr>

            <tr>
                <td>state</td>
                <td>{{ $user->state }}</td>

            </tr>

            <tr>
                <td>postal code</td>
                <td>{{ $user->postal_code }}</td>

            </tr>
            <tr>
                <td>country</td>
                <td>{{ $user->country->name }}</td>

            </tr>
            <tr>
                <td>phone </td>
                <td>{{ $user->phone }}</td>

            </tr>
            <tr>
                <td>fax </td>
                <td>{{ $user->fax }}</td>

            </tr>
            <tr>
                <td>Website</td>
                <td>{{ $user->website }}</td>

            </tr>


            @foreach ($user->conferences as $data)
                <tr>
                    <td> conferences </td>
                    <td>{{ $data->name }}</td>
                </tr>
            @endforeach


        </tbody>
    </table>

    <hr>

    <p> The message has been sent from 103.230.180.121 (
        {{ DB::table('countries')->where('id', $user->detected_country_id)->pluck('name')->first() }} ) at
        {{ $user->created_at }} </p>

</body>

</html>
