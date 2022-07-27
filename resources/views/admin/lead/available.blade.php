{{-- <div id="id="statusType"">
    <table class="table tabel-bordered" id="tab-table">
        <thead class="thead-light">
            <tr role="row">
                <th> ID</th>
                <th> Name</th>
                <th> Phone</th>
                <th> Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($leads))
                @foreach ($leads as $lead)
                    <tr>
                        <td>{{ $lead->id }}</td>
                        <td>{{ $lead->lead->client_name }} </td>
                        <td>{{ $lead->lead->mobile }} </td>
                        <td>
                            <a class="mr-1" style="margin-right: 5px" href="javascript:void(0);"
                                onclick='oppenRadioModal("{{ $lead->id }}" , "{{ $lead->lead->mobile }}" )'>
                                <i class="fa fa-plus"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="manualsinglecall(1234567890)"><i
                                    class="fa fa-phone"></i></a>

                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>

    </table>

</div> --}}
