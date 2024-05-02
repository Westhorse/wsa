<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

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
        <td>Address</td>
        <td>{{ $user->address_line1 }}</td>

      </tr>

      <tr>
        <td>E-mail </td>
        <td>{{ $user->email }}</td>

      </tr>

      <tr>
        <td>phone </td>
        <td>{{ $user->phone }}</td>

      </tr>

      <tr>
        <td>Website</td>
        <td>{{ $user->website }}</td>

      </tr>
      <tr>
        <td>Year business was established</td>
        <td>{{ $user->business_est }}</td>

      </tr>

      <tr>
        <td>Approximate number of employees 1,2,20</td>
        <td>{{ $user->employees_num }}</td>

      </tr>

      <tr>
        <td>Branches Number / Locations</td>
        <td>{{ $user->branches }}</td>

      </tr>

      @foreach (DB::table('contact_people')->where('user_id',24)->pluck('title') as $date)
        <tr>
            <td>Contact Person</td>
            <td>{{ $date }}</td>
        </tr>
      @endforeach

      @foreach (DB::table('contact_people')->where('user_id',24)->pluck('name') as $date)
        <tr>
            <td>Full Name</td>
            <td>{{ $date }}</td>
        </tr>
      @endforeach

      @foreach (DB::table('contact_people')->where('user_id',24)->pluck('job_title') as $date)
        <tr>
            <td>job title</td>
            <td>{{ $date }}</td>
        </tr>
      @endforeach

      @foreach (DB::table('contact_people')->where('user_id',24)->pluck('email') as $date)
      <tr>
          <td> E-mail </td>
          <td>{{ $date }}</td>
      </tr>
    @endforeach

    @foreach (DB::table('contact_people')->where('user_id',24)->pluck('cell') as $date)
      <tr>
          <td> Direct Phone </td>
          <td>{{ $date }}</td>
      </tr>
    @endforeach

    @foreach (DB::table('contact_people')->where('user_id',24)->pluck('phone') as $date)
    <tr>
        <td> Cell Number </td>
        <td>{{ $date }}</td>
    </tr>
     @endforeach

     @foreach ( $user->services as $data)
            <tr>
                <td> Services - {{ $data->name }} </td>
                <td>Yes</td>
            </tr>
     @endforeach

     @if ($user->other_services != null)

     <tr>
        <td> Other Services </td>
        <td>{{ $user->other_services }}</td>
    </tr>
     @endif

     @foreach ( $user->certificates as $data)
            <tr>
                <td> Certificates - {{ $data->name }} </td>
                <td>Yes</td>
            </tr>
     @endforeach
     @if ($user->other_certificates != null)

     <tr>
        <td> Certificates-other </td>
        <td>{{ $user->other_certificates }}</td>
    </tr>
     @endif

     <tr>
        <td>Trade Reference</td>
        <td>:</td>

      </tr>

     <tr>
        <td> Reference 1 </td>
        <td>  {{ $user->tradeReferences[0]->name  }} </td>
     </tr>

     <tr>
        <td> Full Name . job title </td>
        <td> {{ $user->tradeReferences[0]->person  }} . {{ $user->tradeReferences[0]->job_title  }} </td>
     </tr>

     <tr>
        <td> E-mail </td>
        <td>  {{ $user->tradeReferences[0]->email  }} </td>
     </tr>


     <tr>
        <td> Country </td>
        <td>  {{ $user->tradeReferences[0]->country->name  }} </td>
     </tr>

     <tr>
        <td> ReferenceCity </td>
        <td>  {{ $user->tradeReferences[0]->city  }} </td>
     </tr>


     <tr>
        <td> Reference 2 </td>
        <td>  {{ $user->tradeReferences[1]->name  }} </td>
     </tr>

     <tr>
        <td> Full Name . job title </td>
        <td> {{ $user->tradeReferences[1]->person  }} . {{ $user->tradeReferences[1]->job_title  }} </td>
     </tr>

     <tr>
        <td> E-mail </td>
        <td>  {{ $user->tradeReferences[1]->email  }} </td>
     </tr>


     <tr>
        <td> Country </td>
        <td>  {{ $user->tradeReferences[1]->country->name  }} </td>
     </tr>

     <tr>
        <td> ReferenceCity </td>
        <td>  {{ $user->tradeReferences[1]->city  }} </td>
     </tr>

     <tr>
        <td> Reference 3 </td>
        <td>  {{ $user->tradeReferences[2]->name  }} </td>
     </tr>

     <tr>
        <td> Full Name . job title </td>
        <td> {{ $user->tradeReferences[2]->person  }} . {{ $user->tradeReferences[2]->job_title  }} </td>
     </tr>

     <tr>
        <td> E-mail </td>
        <td>  {{ $user->tradeReferences[2]->email  }} </td>
     </tr>


     <tr>
        <td> Country </td>
        <td>  {{ $user->tradeReferences[2]->country->name  }} </td>
     </tr>

     <tr>
        <td> aaaaa </td>
        <td>  {{ $user->detected_country_id  }} </td>
     </tr>

</tbody>

</table>

<hr>

<p>    The message has been sent from 103.230.180.121 ( {{ DB::table('countries')->where('id',$user->detected_country_id)->pluck('name')->first()}} ) at {{ $user->created_at }}  </p>
</body>
</html>


