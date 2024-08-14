<table>

    <thead>
           
    <tr>
    <th colspan=""></td>
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
                <th>{{$item}}</th>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>