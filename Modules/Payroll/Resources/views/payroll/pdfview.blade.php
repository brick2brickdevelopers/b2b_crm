<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            table.details tr th {
                background-color: #F2F2F2 !important;
            }

            .print_bg {
                background-color: #F2F2F2 !important;
            }

        }

        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/TH_Sarabun.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: bold;
            src: url("{{ asset('fonts/TH_SarabunBold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ asset('fonts/TH_SarabunBoldItalic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ asset('fonts/THSarabunNew001.ttf') }}") format('truetype');
        }


        @if($company->is_chinese_lang)
            @font-face {
            font-family: SimHei;
            /*font-style: normal;*/
            font-weight: bold;
            src: url('{{ asset('fonts/simhei.ttf') }}') format('truetype');
        }
        @endif

        @php
            $font = '';
            if($company->locale == 'ja') {
                $font = 'ipag';
            } else if($company->locale == 'hi') {
                $font = 'hindi';
            } else if($company->locale == 'th') {
                $font = 'THSarabun';
            }else if($company->is_chinese_lang) {
                $font = 'SimHei';
            } else {
                $font = 'noto-sans';
            }
        @endphp

        * {
            font-family: {{$font}}, DejaVu Sans , sans-serif;
        }

        @if($company->is_chinese_lang)
        body
        {
            font-weight: normal !important;
        }
        @endif

        .print_bg {
            background-color: #F2F2F2 !important;
        }

        body {
            /* font-family: "Open Sans", helvetica, sans-serif; */
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000000;
        }

        table.logo {
            -webkit-print-color-adjust: exact;
            border-collapse: inherit;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 2px solid #25221F;

        }

        table.emp {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px 0;
        }

        table.details, table.payment_details {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 10px; */
        }

        table.payment_total {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            text-align: center;
        }

        table.emp tr td {
            /* width: 100%; */
            padding: 10px 0;
        }

        table.details tr th {
            /* border: 1px solid #000000; */
            background-color: #F2F2F2;
            font-size: 15px;
            padding: 10px
        }

        table.details tr td {
            vertical-align: top;
            width: 30%;
            padding: 3px
        }

        table.payment_details > tbody > tr > td {
            /* border: 1px solid #000000; */
            padding: 12px 7px;
        }

        table.payment_total > tbody > tr > td {
            padding: 5px;
            width: 60%
        }

        table.logo > tbody > tr > td {
            border: 1px solid transparent;
        }

        .active {
            text-transform: uppercase;
        }

        .netpay {
            padding: 15px 10px;
            text-align: center;
            border: 1px solid #edeff0;
        }

        .text-info {
            color: #03a9f3;
            font-size: 15px;
        }

        .netsalary-title {
            text-transform: uppercase;
            font-size: 20px;
        }

        .netsalary-formula {
            font-size: 16px;
            color: #555555;
        }
    </style>
</head>
<body>
<table class="logo">
    <tr>
        <td>

        </td>
        <td><p style="text-align: right;">
                <img src="{{ $global->logo_url }}" height="40px">
            </p>

            <p style="text-align: right;">

                <b>{{ ucwords($global->company_name) }}</b><br/>
                {{$global->address}}<br/>
                <b>@lang('app.phone')</b>: {{ $global->company_phone }}<br/>
                <b>@lang('app.email')</b>: {{$global->company_email}}

            </p>
        </td>
    </tr>
</table>
<table class="emp">
    <tbody>
        <tr>
            <td colspan="3" style="font-size: 18px; padding-bottom: 20px;"><strong>@lang('payroll::modules.payroll.salarySlipHeading') {{ $salarySlip->duration }}
                </strong></td>
        </tr>
        <tr>
            <td><strong>@lang('modules.employees.employeeId'):</strong> {{ $salarySlip->user->employeeDetail->employee_id }} </td>
            <td><strong>@lang('app.name'):</strong> {{ ucwords($salarySlip->user->name) }}</td>
            <td><strong>@lang("payroll::modules.payroll.salarySlipNumber"):</strong> {{ $salarySlip->id }}</td>
        </tr>

        <tr>
            <td><strong>@lang('app.department'):</strong> {{ (!is_null($salarySlip->user->employeeDetail->department)) ? $salarySlip->user->employeeDetail->department->team_name : '-' }}</td>
            <td><strong>@lang('app.designation'):</strong> {{ (!is_null($salarySlip->user->employeeDetail->designation)) ? $salarySlip->user->employeeDetail->designation->name : '-' }}</td>
            <td><strong>@lang('modules.employees.joiningDate')
                    :</strong> {{ $salarySlip->user->employeeDetail->joining_date->format($global->date_format) }}</td>
        </tr>

        @php $numberCheck = 0; @endphp
        <tr>
        @foreach($fields as $key => $field)
            @if($numberCheck == 2)
                @php $numberCheck = 0;@endphp
            @endif
            <td>
                <strong>{{ $field->label }}:</strong>
                @if( $field->type == 'text')
                    {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '--'}}
                @elseif($field->type == 'password')
                    {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '--'}}
                @elseif($field->type == 'number')
                    {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '--'}}
                @elseif($field->type == 'textarea')
                    {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '--'}}
                @elseif($field->type == 'radio')
                    {{ !is_null($employeeDetail->custom_fields_data['field_'.$field->id]) ? $employeeDetail->custom_fields_data['field_'.$field->id] : '--' }}
                @elseif($field->type == 'select')
                    {{ (!is_null($employeeDetail->custom_fields_data['field_'.$field->id]) && $employeeDetail->custom_fields_data['field_'.$field->id] != '') ? $field->values[$employeeDetail->custom_fields_data['field_'.$field->id]] : '--' }}
                @elseif($field->type == 'checkbox')
                    <ul>
                        @foreach($field->values as $key => $value)
                            @if($employeeDetail->custom_fields_data['field_'.$field->id] != '' && in_array($value ,explode(', ', $employeeDetail->custom_fields_data['field_'.$field->id]))) <li>{{$value}}</li> @endif
                        @endforeach
                    </ul>
                @elseif($field->type == 'date')
                    {{ isset($employeeDetail->custom_fields_data['field_'.$field->id])? Carbon\Carbon::parse($employeeDetail->custom_fields_data['field_'.$field->id])->format($global->date_format): ''}}
                @endif
            </td>

            @if(($numberCheck == 0 && $key != 0) || sizeof($fields) == $key+1 )
                </tr>
                <tr>
            @endif
            @php $numberCheck++ @endphp
        @endforeach
    </tbody>
</table>

<!-- Table for Details -->
<table class="details">

    <tr>
        <!-- Payment Info Slip Start-->
        <td>

            <table class="payment_details" >
                <thead>
                    <tr class="active">
                        <th class="text-uppercase">@lang('payroll::modules.payroll.earning')</th>
                        <th align="right" class="text-uppercase">@lang('app.amount')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>@lang('payroll::modules.payroll.basicPay')</td>
                        <td align="right" class="text-uppercase">{{ $salarySlip->basic_salary.' '.$global->currency->currency_code }}</td>
                    </tr>
                    @foreach ($earnings as $key=>$item)
                        <tr>
                            <td>{{ $key }}</td>
                            <td align="right">{{ $item.' '.$global->currency->currency_code }}</td>
                        </tr>
                    @endforeach
                    
                    @forelse ($earningsExtra as $key=>$item)
                        <tr>
                            <td>{{ $key }}</td>
                            <td align="right">{{ $item.' '.$global->currency->currency_code }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Table for Details -->
        </td>
        <!--  Payment Info Slip End-->


        <!-- Deduction start -->
        <td>
            <table class="payment_details">
                <thead>
                    <tr class="active">
                        <th class="text-uppercase">@lang('payroll::modules.payroll.deduction')</th>
                        <th align="right" class="text-uppercase">@lang('app.amount')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($deductions as $key=>$item)
                    <tr>
                        <td>{{ $key }}</td>
                        <td align="right">{{ $item.' '.$global->currency->currency_code }}</td>
                    </tr>
                @endforeach
                @foreach ($deductionsExtra as $key=>$item)
                    <tr>
                        <td>{{ $key }}</td>
                        <td align="right">{{ $item.' '.$global->currency->currency_code }}</td>
                    </tr>
                @endforeach
                
                </tbody>

            </table>
        </td>
        <!--  Deductions End-->
    </tr>
    <tr>
        <td>
            <table class="payment_details">
                <tr>
                    <td><strong>@lang('payroll::modules.payroll.grossEarning')</strong></td>
                    <td align="right"><strong>{{ $salarySlip->gross_salary.' '.$global->currency->currency_code }}</strong></td>
                </tr>
            </table>                    
        </td>
        <td>
            <table class="payment_details">
                <tr>
                    <td><strong>@lang('payroll::modules.payroll.totalDeductions')</strong></td>
                    <td align="right"><strong>{{ $salarySlip->total_deductions.' '.$global->currency->currency_code }}</strong></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table class="payment_details">
                <tr>      
                    <th>
                        @lang('payroll::modules.payroll.reimbursement')
                    </th>
                    <th align="right">
                        @lang('app.amount')
                    </th>
                </tr>
                <tr> 
                    <td>@lang('payroll::modules.payroll.expenseClaims')</td>     
                    <td align="right">
                        {{ $salarySlip->expense_claims.' '.$global->currency->currency_code }}
                    </td>
                </tr>
            </table>
            
        </td>
    </tr>

</table>
<!-- Table for Details -->
<hr>
<!-- TotalTotal -->
<table class="payment_total">

    <tr>
        <td class="netsalary-title">
            <strong style="margin-right: 20px;">@lang('payroll::modules.payroll.netSalary'):</strong> 
            {{ sprintf('%0.2f', $salarySlip->net_salary).' '.$global->currency->currency_code }}
        </td>
    </tr>
    <tr>
        <td class="netsalary-formula">
                <h5 class="text-center text-muted">@lang('payroll::modules.payroll.netSalary') = (@lang('payroll::modules.payroll.grossEarning') - @lang('payroll::modules.payroll.totalDeductions') + @lang('payroll::modules.payroll.reimbursement'))</h5>
        </td>
    </tr>


</table>
<!-- TotalTotal -->
</body>
</html>




