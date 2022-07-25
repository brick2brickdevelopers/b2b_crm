<table class="table tabel-bordered" id="table_id">
    <thead class="thead-light">
        <tr role="row">
            <th>
                ID</th>
            <th>
                Name</th>
            <th>
                Phone</th>
            {{-- <th>
                Campaign Status</th>
            <th>
                Lead Status</th> --}}
            <th>
                Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($leads))
        @foreach ($leads as $lead)
        
        <tr>
            <td>{{ $lead->id}}</td>
            <td>{{
                $lead->lead->client_name
                }}
            </td>
            <td>{{
                $lead->lead->mobile}}
            </td>
            {{-- <td>
                {{<select
                    class="form-control">
                    <option>Available
                    </option>
                    <option>Completed
                    </option>
                    <option>Follow
                    </option>
                </select>}}
            </td>
            <td>


                <select
                    class="form-control">
                    <option>Assigned
                    </option>
                    <option>Opened
                    </option>
                    <option>Converted
                    </option>
                    <option>Follow
                    </option>
                    <option>Closed
                    </option>
                </select>

            </td> --}}
            <td><a class="mr-1"
                    style="margin-right: 5px"
                    href="javascript:void(0);"
                    onclick='oppenRadioModal("{{ $lead->id }}"
                    , "{{ $lead->lead->mobile }}"
                    )'><i class=" fa
                    fa-plus"></i></a>
                <a href="javascript:void(0);"
                    onclick="manualsinglecall(1234567890)"><i
                        class="fa fa-phone"></i></a>

            </td>
        </tr>
        @endforeach
        @endif

    </tbody>
</table>