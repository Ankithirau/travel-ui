<?php if($status==1){ ?>
@php
$i = 1;
$num = 0;
@endphp
@foreach ($points as $collections)
    @foreach ($collections as $item)
        @if ($item->county_name)
            <tr>
                <td>{{ $i++ }}</td>
                <td>
                    <input type="hidden" value="{{$total_booking}}" id="total_booking">
                    {{ $item->county_name }}
                    <input type="hidden" name="counties_id[]" class="counties_id" value="{{ $item->counties_id }}"
                        id="counties_id">
                    <input type="hidden" name="product_id" class="product_id" value="{{ $item->product_id }}"
                        id="product_id" data-url="{{ route('assign-bus.index', $item->product_id) }}">
                </td>
                <td>
                    {{ $item->name }}
                    <input type="hidden" name="pickup_point_id[]" class="pickup_point_id" value="{{ $item->id }}"
                        id="pickup_point_id">
                </td>
                <td>
                    {{ $item->seat_count }}
                    <input type="hidden" name="seat_count[]" class="seat_count" value="{{ $item->seat_count }}"
                        id="seat_count">
                </td>
                <td>{{ date('d-M-y', strtotime($item->date_concert)) }}
                    <input type="hidden" name="date_concert[]" class="date_concert"
                        id="date_concert_{{ $item->id }}" value="{{ $item->date_concert }}">
                    <div class="date_concert {{ $num }} text-danger error-inline">
                    </div>
                </td>
                @if ($item->routes)
                    @if ($flag=='all')
                    <td>
                        <select name="routes[]" class="form-control routes" id="route_name_{{ $item->id }}">
                            <option value="" selected>Select Route</option>
                            @foreach ($item->routes as $route)
                                <option value="{{ $route['route_id'] }}">
                                    {{ trim($route['route_name']) }}</option>
                            @endforeach
                        </select>

                    </td>             
                    @endif
                    <td>
                        <select name="buses[]" class="form-control buses" id="buses_{{ $item->id }}">
                            <option value="" selected>Select Bus</option>
                            @foreach ($buses as $bus)
                                <option value="{{ $bus->id }}">
                                    {{ $bus->bus_number }}
                                </option>
                            @endforeach
                        </select>
                        <div class="buses {{ $num }} text-danger error-inline">
                        </div>
                    </td>
                @else
                    <td colspan="2">
                        <p class="text-capitalize text-warning text-center">no route assign
                            for
                            this pickup point</p>
                    </td>
                @endif
                <td>
                    @if ($item->routes)
                        <button type="button"
                            class="btn btn-icon btn-icon rounded-circle btn-flat-success waves-effect schedule_update"
                            data-action="{{ route('schedule-bus.add_schedule', isset($item->id) ? $item->id : 0) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-arrow-up-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="16 12 12 8 8 12"></polyline>
                                <line x1="12" y1="16" x2="12" y2="8"></line>
                            </svg>
                        </button>
                    @else
                        <button type="button"
                            class="btn btn-icon btn-icon rounded-circle btn-flat-danger waves-effect">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-arrow-down-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="8 12 12 16 16 12"></polyline>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                            </svg>
                        </button>
                    @endif
                </td>
            </tr>
        @endif
    @endforeach
    @php
        $num++;
    @endphp
@endforeach
<?php 
}else{
    echo '<tr><input type="hidden" value='.$total_booking.' id="total_booking"><td colspan=8 class="text-center text-danger text-capitalize" style="width:1147px">no record found</td></tr>';
}
?>
