<table>

    <thead>
           
    <tr>
        <th colspan="{{count($headings)}}" style="text-align: center; font-size: 16px; border: 1px solid black;">{{$excelheadings}}</th>
    </tr>
        <tr>
      @foreach($headings as $heading)
        <th style="text-align: center; font-size: 10px;">{{$heading}}</th>
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
                <th>{{$item}}</th>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>