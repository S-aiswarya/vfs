<table>

    <thead>
           
    <tr>
        <th colspan="{{count($headings)}}" style="text-align: center; font-size: 19px; border:3px solid #000;">{{$excelheadings}}</th>
    </tr>
    
        <tr>
      @foreach($headings as $heading)
        <th>{{$heading}}</th>
      @endforeach
      </tr>

    </thead>

    <tbody>
    @foreach($collections as $collection)
        @php
            $items = $collection->toArray();
        @endphp
        <tr>
            @foreach($items as $item)
                <td>{{$item}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>