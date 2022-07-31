@if (empty(user()->sip_pass))
    <a class="mr-1" style="margin-right: 5px" href="javascript:void(0);"
        onclick='oppenRadioModal("{{ $lead->lead->mobile }}")'>
        <i class="fa fa-plus"></i></a>

    <a href="tel:{{ $lead->lead->mobile }}"><i class="fa fa-phone"></i></a>
@else
    <a class="mr-1" style="margin-right: 5px" href="javascript:void(0);"
        onclick='updateCallDetail("{{ $lead->lead_id }}","lead","{{ $lead->campaign_id }}")'>
        <i class="fa fa-plus"></i>
    </a>

    <a href="">
        <i class="fa fa-phone"></i>
    </a>
@endif
