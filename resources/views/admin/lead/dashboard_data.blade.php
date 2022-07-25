<div class="main-search">
    <div class="col-md-9" style="background-color: #f6f7f9; margin-top:10px; border-radius:10px">
        <div class="card">
            <div class="card-body">
                
                <div>
                    <div class="simplebar-wrapper" style="margin: 0px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden;">
                                    <div class="simplebar-content" style="padding: 0px;">
                                        <ul class="nav nav-tabs nav-justified mb-3">
                                            <li class="nav-item">
                                                <a href="#available" id="available-tab" data-toggle="tab"
                                                    aria-expanded="false" class="nav-link active">
                                                    <i class="mdi mdi-home-variant d-md-none d-block"></i>

                                                    <span class="d-none d-md-block">Available
                                                        (<span
                                                            id="total_leads_count">{{ $totalAvailable > 0 ? $totalAvailable : 0 }}</span>)

                                                    </span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#completed_leads" id="leads-tab" data-toggle="tab"
                                                    aria-expanded="true" class="nav-link">
                                                    <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                    <span class="d-none d-md-block">Completed

                                                        (<span
                                                            id="total_leads_count">{{ $totalCompleted > 0 ? $totalCompleted : 0 }}</span>)

                                                    </span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#follow_up_leads" id="close-task" data-toggle="tab"
                                                    aria-expanded="true" class="nav-link">
                                                    <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                    <span class="d-none d-md-block">Follow Up


                                                        (<span
                                                            id="total_leads_count">{{ $totalFollow > 0 ? $totalFollow : 0 }}</span>)

                                                    </span>
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane tab_status active" id="available">
                                                <div class="table-responsive">
                                                    <div id="avilable-server-datatable_wrapper"
                                                        class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <table class="table tabel-bordered" id="table_id">
                                                                    <thead class="thead-light">
                                                                        <tr role="row">
                                                                            <th>
                                                                                ID</th>
                                                                            <th>
                                                                                Name</th>
                                                                            <th>
                                                                                Phone</th>
                                                                            <th>
                                                                                Campaign Status</th>
                                                                            <th>
                                                                                Lead Status</th>
                                                                            <th>
                                                                                Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (!empty($leads))
                                                                            @foreach ($leads as $lead)
                                                                                <tr>
                                                                                    <td>{{ $lead->id }}</td>
                                                                                    <td>{{ $lead->lead->company_name }}
                                                                                    </td>
                                                                                    <td>{{ $lead->lead->mobile }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="form-control">
                                                                                            <option>Available
                                                                                            </option>
                                                                                            <option>Completed
                                                                                            </option>
                                                                                            <option>Follow
                                                                                            </option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>


                                                                                        <select class="form-control">
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

                                                                                    </td>
                                                                                    <td><a class="mr-1"
                                                                                            style="margin-right: 5px"
                                                                                            href="javascript:void(0);"
                                                                                            onclick='oppenRadioModal("{{ $lead->id }}"
                                                                                    , "{{ $lead->lead->mobile }}"
                                                                                    )'><i
                                                                                                class=" fa
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: 817px;"></div>
                    </div>
                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                    </div>
                    <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        @foreach ($callPurposes as $callPurpose)
            <div class="card" style="background-color: #f6f7f9; margin-top:10px; border-radius:10px">
                <div class="card-body">
                    <div class="row align-items-center my-2">

                        <div class="col-lg-6">
                            <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Campaign Sent">
                                {{ $callPurpose->purpose }}</h5>
                            <a class="btn btn-light prayer_request_redirect"
                                href="http://crm-dev.cleverstack.in/crm/admin/leads/call-log-reports?startdate=05/15/2022&amp;enddate=05/15/2022&amp;purpose=1"><span
                                    id="prayer_request_count">0</span></a>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-right" style="position: relative;">
                                <div id="campaign-sent-chart" data-colors="#727cf5" style="min-height: 60px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
