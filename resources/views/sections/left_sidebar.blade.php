{{-- @php

function getIndex()

$getIndex = array_search('attendance', $modules);

dd($getIndex);
@endphp --}}

<style>
    .slimScrollDiv {
        overflow: initial !important;
    }
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="navbar-header">
        <!-- Toggle icon for mobile view -->
        <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
            data-target=".navbar-collapse"><i class="ti-menu"></i></a>

        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo hidden-xs text-center" href="{{ route('admin.dashboard') }}">
                <span class="visible-md"><img src="{{ $global->logo_url }}" alt="home"
                        class=" admin-logo" /></span>
                <span class="visible-sm"><img src="{{ $global->logo_url }}" alt="home"
                        class=" admin-logo" /></span>
            </a>

        </div>
        <!-- /Logo -->

        <!-- This is the message dropdown -->
        <ul class="nav navbar-top-links navbar-right pull-right visible-xs">
            @if (isset($activeTimerCount))
                <li class="dropdown hidden-xs">
                    <span id="timer-section">
                        <div class="nav navbar-top-links navbar-right pull-right m-t-10">
                            <a class="btn btn-rounded btn-default timer-modal" href="javascript:;">@lang('modules.projects.activeTimers')
                                <span class="label label-danger" id="activeCurrentTimerCount">
                                    @if ($activeTimerCount > 0)
                                        {{ $activeTimerCount }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </a>
                        </div>
                    </span>
                </li>
            @endif


            <li class="dropdown">
                <select class="selectpicker language-switcher" data-width="fit">
                    @if ($global->timezone == 'Europe/London')
                        <option value="en" @if ($global->locale == 'en') selected @endif
                            data-content='<span
                            class="flag-icon flag-icon-gb"></span>'>En
                        </option>
                    @else
                        <option value="en" @if ($global->locale == 'en') selected @endif
                            data-content='<span
                            class="flag-icon flag-icon-us"></span>'>En
                        </option>
                    @endif
                    @foreach ($languageSettings as $language)
                        <option value="{{ $language->language_code }}"
                            @if ($global->locale == $language->language_code) selected @endif
                            data-content='<span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ $language->language_code }}'>
                            {{ $language->language_code }}</option>
                    @endforeach
                </select>
            </li>

            <!-- .Task dropdown -->
            <li class="dropdown" id="top-notification-dropdown">
                <a class="dropdown-toggle waves-effect waves-light show-user-notifications" data-toggle="dropdown"
                    href="#">
                    <i class="icon-bell"></i>
                    @if ($unreadNotificationCount > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
                <ul class="dropdown-menu  dropdown-menu-right mailbox animated slideInDown">
                    <li>
                        <a href="javascript:;">...</a>
                    </li>

                </ul>
            </li>
            <!-- /.Task dropdown -->


            <li class="dropdown">
                <a href="{{ route('logout') }}" title="Logout"
                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"><i
                        class="fa fa-power-off"></i>
                </a>
            </li>



        </ul>

    </div>
    <!-- /.navbar-header -->

    <div class="top-left-part">
        <a class="logo hidden-xs hidden-sm text-center" href="{{ route('admin.dashboard') }}">
            <img src="{{ $global->logo_url }}" alt="home" class=" admin-logo" />
        </a>
    </div>
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">

        <!-- .User Profile -->
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                        <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li>

            <li class="user-pro hidden-sm hidden-md hidden-lg">
                @if (is_null($user->image))
                    <a href="#" class="waves-effect"><img src="{{ asset('img/default-profile-3.png') }}"
                            alt="user-img" class="img-circle"> <span
                            class="hide-menu">{{ strlen($user->name) > 24 ? substr(ucwords($user->name), 0, 20) . '..' : ucwords($user->name) }}
                            <span class="fa arrow"></span></span>
                    </a>
                @else
                    <a href="#" class="waves-effect"><img src="{{ asset_url('avatar/' . $user->image) }}"
                            alt="user-img" class="img-circle"> <span class="hide-menu">{{ ucwords($user->name) }}
                            <span class="fa arrow"></span></span>
                    </a>
                @endif
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('member.dashboard') }}">
                            <i class="fa fa-sign-in"></i> @lang('app.loginAsEmployee')
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"><i
                                class="fa fa-power-off"></i> @lang('app.logout')</a>

                    </li>
                </ul>
            </li>

            <li><a href="{{ route('admin.dashboard') }}" class="waves-effect"><i class="icon-speedometer fa-fw"></i>
                    <span class="hide-menu"> @lang('app.menu.dashboard') <span class="fa arrow"></span> </span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                            @lang('app.menu.dashboard')
                        </a>
                    </li>
                    @if (in_array('projects', $modules))
                        <li>
                            <a href="{{ route('admin.projectDashboard') }}" class="waves-effect">
                                @lang('app.menu.projectDashboard')
                            </a>
                        </li>
                    @endif
                    @if (in_array('clients', $modules) || in_array('leads', $modules))
                        <li>
                            <a href="{{ route('admin.clientDashboard') }}" class="waves-effect">



                                @lang('app.menu.clientDashboard')
                            </a>
                        </li>
                    @endif
                    @if (in_array('employees', $modules) || in_array('attendance', $modules) || in_array('holidays', $modules) || in_array('leaves', $modules))
                        <li>
                            <a href="{{ route('admin.hrDashboard') }}" class="waves-effect">
                                @lang('app.menu.hrDashboard')
                            </a>
                        </li>
                    @endif
                    @if (in_array('tickets', $modules))
                        <li>
                            <a href="{{ route('admin.ticketDashboard') }}" class="waves-effect">
                                @lang('app.menu.ticketDashboard')
                            </a>
                        </li>
                    @endif
                    @if (in_array('estimates', $modules) || in_array('invoices', $modules) || in_array('payments', $modules) || in_array('expenses', $modules))
                        <li>
                            <a href="{{ route('admin.financeDashboard') }}" class="waves-effect">
                                @lang('app.menu.financeDashboard')
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            @if (in_array('leads', $modules))
                <li>
                    <a href="{{ route('admin.leads.index') }}" class="waves-effect"><i
                            class="fa fa-doc fa-fw"></i>
                        <span class="hide-menu">@lang('app.menu.lead') <span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ route('admin.leads.index') }}">Lead List </a> </li>
                        <li><a href="{{ route('admin.leads.dashboard') }}">Leads Dashboard </a></li>
                    </ul>
                </li>
                {{-- <li><a href="{{ route('admin.leads.index') }}" class="waves-effect"><i class="icon-doc fa-fw"></i><span
                        class="hide-menu">@lang('app.menu.lead')</span></a>
            </li> --}}
            @endif
            @if (in_array('clients', $modules))
                <li><a href="{{ route('admin.clients.index') }}" class="waves-effect"><i
                            class="icon-people fa-fw"></i>

                        @php
                            $getClientIndex = array_search('clients', $modules);
                        @endphp

                        {{-- <span class="hide-menu">@lang('app.menu.clients')</span> --}}
                        <span class="hide-menu">
                            {{ $customModuels[$getClientIndex] === null ? 'Clients' : $customModuels[$getClientIndex] }}
                        </span>
                    </a>
                </li>
            @endif
            @if (in_array('employees', $modules) || in_array('attendance', $modules) || in_array('holidays', $modules) || in_array('leaves', $modules))
                <li><a href="{{ route('admin.employees.index') }}"
                        class="waves-effect
                    {{ request()->is('admin/leave*') ? 'active' : '' }}
                    "><i
                            class="ti-user fa-fw"></i> <span class="hide-menu"> @lang('app.menu.hr') <span
                                class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level {{ request()->is('admin/leave*') ? 'collapse in' : '' }}">
                        @if (in_array('employees', $modules))
                            <li><a href="{{ route('admin.employees.index') }}">
                                    {{-- @lang('app.menu.employeeList') --}}
                                    @php
                                        $getEmployeeIndex = array_search('employees', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getEmployeeIndex] === null ? 'Employee' : $customModuels[$getEmployeeIndex] }}
                                    </span>
                                </a>
                            </li>
                            <li><a href="{{ route('admin.teams.index') }}">@lang('app.department')</a></li>
                            <li><a href="{{ route('admin.designations.index') }}">@lang('app.menu.designation')</a></li>
                        @endif
                        @if (in_array('attendance', $modules))
                            <li><a href="{{ route('admin.attendances.summary') }}" class="waves-effect">
                                    @php
                                        $getAttenadanceIndex = array_search('attendance', $modules);
                                    @endphp

                                    {{-- <span class="hide-menu">@lang('app.menu.clients')</span> --}}
                                    <span class="hide-menu">
                                        {{ $customModuels[$getAttenadanceIndex] === null ? 'Attendance' : $customModuels[$getAttenadanceIndex] }}
                                    </span>

                                    {{-- @lang('app.menu.attendance') --}}
                                </a>
                            </li>
                        @endif
                        @if (in_array('holidays', $modules))
                            <li><a href="{{ route('admin.holidays.index') }}" class="waves-effect">
                                    {{-- @lang('app.menu.holiday') --}}
                                    @php
                                        $getHolidaysIndex = array_search('holidays', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getHolidaysIndex] === null ? 'Holidays' : $customModuels[$getHolidaysIndex] }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (in_array('leaves', $modules))
                            <li><a href="{{ route('admin.leaves.pending') }}"
                                    class="waves-effect  {{ request()->is('admin/leave*') ? 'active' : '' }}">
                                    {{-- @lang('app.menu.leaves') --}}
                                    @php
                                        $getLeavesIndex = array_search('leaves', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getLeavesIndex] === null ? 'Leaves' : $customModuels[$getLeavesIndex] }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (in_array('projects', $modules) || in_array('tasks', $modules) || in_array('timelogs', $modules) || in_array('contracts', $modules))
                <li><a href="{{ route('admin.task.index') }}" class="waves-effect"><i
                            class="icon-layers fa-fw"></i> <span class="hide-menu"> @lang('app.menu.work') <span
                                class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level">
                        @if (in_array('contracts', $modules))
                            <li><a href="{{ route('admin.contracts.index') }}" class="waves-effect">
                                    {{-- @lang('app.menu.contracts') --}}
                                    @php
                                        $getContractsIndex = array_search('contracts', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getContractsIndex] === null ? 'Contracts' : $customModuels[$getContractsIndex] }}
                                    </span>
                                </a></li>
                        @endif
                        @if (in_array('projects', $modules))
                            <li><a href="{{ route('admin.projects.index') }}" class="waves-effect">
                                    {{-- @lang('app.menu.projects') --}}
                                    @php
                                        $getProjectsIndex = array_search('projects', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getProjectsIndex] === null ? 'Projects' : $customModuels[$getProjectsIndex] }}
                                    </span>
                                </a> </li>
                        @endif
                        @if (in_array('tasks', $modules))
                            <li><a href="{{ route('admin.all-tasks.index') }}">
                                    {{-- @lang('app.menu.tasks') --}}
                                    @php
                                        $getTasksIndex = array_search('tasks', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getTasksIndex] === null ? 'Tasks' : $customModuels[$getTasksIndex] }}
                                    </span>
                                </a></li>
                            <li class=""><a
                                    href="{{ route('admin.taskboard.index') }}">@lang('modules.tasks.taskBoard')</a>
                            </li>
                            <li><a href="{{ route('admin.task-calendar.index') }}">@lang('app.menu.taskCalendar')</a></li>
                        @endif
                        @if (in_array('timelogs', $modules))
                            <li><a href="{{ route('admin.all-time-logs.index') }}" class="waves-effect">
                                    {{-- @lang('app.menu.timeLogs') --}}
                                    @php
                                        $getTimeLogsIndex = array_search('timelogs', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getTimeLogsIndex] === null ? 'Time Logs' : $customModuels[$getTimeLogsIndex] }}
                                    </span>
                                </a> </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if (in_array('estimates', $modules) || in_array('invoices', $modules) || in_array('payments', $modules) || in_array('expenses', $modules))
                <li><a href="{{ route('admin.finance.index') }}" class="waves-effect"><i
                            class="fa fa-money fa-fw"></i>
                        <span class="hide-menu"> @lang('app.menu.finance') @if ($unreadExpenseCount > 0)
                                <div class="notify notification-color"><span class="heartbit"></span><span
                                        class="point"></span>
                                </div>
                            @endif <span class="fa arrow"></span>
                        </span></a>
                    <ul class="nav nav-second-level">
                        @if (in_array('estimates', $modules))
                            <li><a href="{{ route('admin.estimates.index') }}">
                                    {{-- @lang('app.menu.estimates') --}}
                                    @php
                                        $getEstimatesIndex = array_search('estimates', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getEstimatesIndex] === null ? 'Estimates' : $customModuels[$getEstimatesIndex] }}
                                    </span>
                                </a> </li>
                        @endif

                        @if (in_array('invoices', $modules))
                            <li><a href="{{ route('admin.all-invoices.index') }}">
                                    {{-- @lang('app.menu.invoices') --}}
                                    @php
                                        $getInvoicesIndex = array_search('invoices', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getInvoicesIndex] === null ? 'Invoices' : $customModuels[$getInvoicesIndex] }}
                                    </span>
                                </a> </li>
                            <li><a href="{{ route('admin.invoice-recurring.index') }}">@lang('app.invoiceRecurring') </a></li>
                        @endif

                        @if (in_array('payments', $modules))
                            <li><a href="{{ route('admin.payments.index') }}">
                                    {{-- @lang('app.menu.payments') --}}
                                    @php
                                        $getPaymentsIndex = array_search('payments', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getPaymentsIndex] === null ? 'Payments' : $customModuels[$getPaymentsIndex] }}
                                    </span>
                                </a> </li>
                        @endif

                        @if (in_array('expenses', $modules))
                            <li><a href="{{ route('admin.expenses.index') }}">
                                    {{-- @lang('app.menu.expenses') --}}
                                    @php
                                        $getExpensesIndex = array_search('expenses', $modules);
                                    @endphp
                                    <span class="hide-menu">
                                        {{ $customModuels[$getExpensesIndex] === null ? 'Expenses' : $customModuels[$getExpensesIndex] }}
                                    </span>
                                    @if ($unreadExpenseCount > 0)
                                        <div class="notify notification-color"><span class="heartbit"></span><span
                                                class="point"></span></div>
                                    @endif
                                </a>
                            </li>
                            <li> <a href="{{ route('admin.expenses-recurring.index') }}">@lang('app.menu.expensesRecurring')</a>
                            </li>
                        @endif

                        @if (in_array('invoices', $modules))
                            <li><a href="{{ route('admin.all-credit-notes.index') }}">@lang('app.menu.credit-note')</a> </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if (in_array('products', $modules))
                <li><a href="{{ route('admin.products.index') }}" class="waves-effect"><i
                            class="icon-basket fa-fw"></i>
                        <span class="hide-menu">
                            {{-- @lang('app.menu.products') --}}
                            @php
                                $getProductsIndex = array_search('products', $modules);
                            @endphp
                            {{ $customModuels[$getProductsIndex] === null ? 'Products' : $customModuels[$getProductsIndex] }}
                        </span></a> </li>
            @endif

            {{-- Campaign Menu --}}
            @if (in_array('campaign', $modules))
                <li><a href="#sidebarCampaign" class="waves-effect"><i class="fa fa-list fa-fw"></i> <span
                            class="hide-menu">Campaigns <span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ route('admin.campaigns.index') }}">Campaign List </a> </li>
                        <li><a href="{{ route('admin.campaigns.create') }}">Create Campaign </a></li>
                        <li><a href="{{ route('admin.campaigns.call-purpose') }}">Call Purpose </a></li>
                        <li><a href="{{ route('admin.calling-group.index') }}">Calling Group </a></li>
                        <li><a href="{{ route('admin.ivr-greetings.index') }}">IVR Greetings </a></li>
                        <li><a href="{{ route('admin.ivr-voicemail.index') }}">IVR Voicemail </a></li>
                        <li><a href="{{ route('admin.call-flow-design.index') }}">IVR Call Flow Design </a></li>
                        <li><a href="{{ route('admin.call-outcome.index') }}">Call OutCame </a></li>
                        <li><a href="{{ route('admin.campaign-lead-status.index') }}">Campaign Lead Status</a></li>
                    </ul>
                </li>
            @endif
            {{-- Campaign Menu End --}}

            @if (in_array('tickets', $modules))
                <li><a href="{{ route('admin.tickets.index') }}" class="waves-effect"><i
                            class="ti-ticket fa-fw"></i> <span class="hide-menu">
                            {{-- @lang('app.menu.tickets') --}}
                            @php
                                $getTicketsIndex = array_search('tickets', $modules);
                            @endphp
                            {{ $customModuels[$getTicketsIndex] === null ? 'Tickets' : $customModuels[$getTicketsIndex] }}
                        </span>
                        @if ($unreadTicketCount > 0 && $openTickets > 0)
                            <div class="notify notification-color"><span class="heartbit"></span><span
                                    class="point"></span>
                            </div>
                        @endif
                    </a>
                </li>
            @endif


            @if (in_array('messages', $modules))
                <li><a href="{{ route('admin.user-chat.index') }}" class="waves-effect"><i
                            class="icon-envelope fa-fw"></i>
                        <span class="hide-menu">
                            {{-- @lang('app.menu.messages') --}}
                            @php
                                $getMessagesIndex = array_search('messages', $modules);
                            @endphp
                            {{ $customModuels[$getMessagesIndex] === null ? 'Messages' : $customModuels[$getMessagesIndex] }}
                            @if ($unreadMessageCount > 0)
                                <span
                                    class="label label-rouded label-custom pull-right">{{ $unreadMessageCount }}</span>
                            @endif
                        </span></a> </li>
            @endif

            @if (in_array('events', $modules))
                <li><a href="{{ route('admin.events.index') }}" class="waves-effect"><i
                            class="icon-calender fa-fw"></i>
                        <span class="hide-menu">
                            {{-- @lang('app.menu.Events') --}}
                            @php
                                $getEventsIndex = array_search('events', $modules);
                            @endphp
                            {{ $customModuels[$getEventsIndex] === null ? 'Events' : $customModuels[$getEventsIndex] }}
                        </span></a> </li>
            @endif

            @if (in_array('notices', $modules))
                <li><a href="{{ route('admin.notices.index') }}" class="waves-effect"><i
                            class="ti-layout-media-overlay fa-fw"></i> <span class="hide-menu">
                            {{-- @lang('app.menu.noticeBoard') --}}
                            @php
                                $getNoticeIndex = array_search('notices', $modules);
                            @endphp
                            {{ $customModuels[$getNoticeIndex] === null ? 'Notice Board' : $customModuels[$getNoticeIndex] }}
                        </span></a> </li>
            @endif
            @if (in_array('reports', $modules))
                <li><a href="{{ route('admin.reports.index') }}" class="waves-effect"><i
                            class="ti-pie-chart fa-fw"></i>
                        <span class="hide-menu">
                            {{-- @lang('app.menu.reports') --}}
                            @php
                                $getReportsIndex = array_search('reports', $modules);
                            @endphp
                            {{ $customModuels[$getReportsIndex] === null ? 'Reports' : $customModuels[$getReportsIndex] }}
                            <span class="fa arrow"></span>
                        </span></a>
                    <ul class="nav nav-second-level">
                        @if (in_array('tasks', $modules))
                            <li><a href="{{ route('admin.task-report.index') }}">
                                    {{-- @lang('app.menu.taskReport') --}}
                                    @php
                                        $getTasksIndex = array_search('tasks', $modules);
                                    @endphp
                                    {{ $customModuels[$getTasksIndex] === null ? 'Tasks' : $customModuels[$getTasksIndex] }}
                                </a></li>
                        @endif

                        @if (in_array('timelogs', $modules))
                            <li><a href="{{ route('admin.time-log-report.index') }}">
                                    {{-- @lang('app.menu.timeLogReport') --}}
                                    @php
                                        $getTimeLogIndex = array_search('timelogs', $modules);
                                    @endphp
                                    {{ $customModuels[$getTimeLogIndex] === null ? 'Time Logs' : $customModuels[$getTimeLogIndex] }}
                                </a></li>
                        @endif

                        @if (in_array('estimates', $modules) || in_array('invoices', $modules) || in_array('payments', $modules) || in_array('expenses', $modules))
                            <li><a href="{{ route('admin.finance-report.index') }}">@lang('app.menu.financeReport')</a></li>
                            <li><a href="{{ route('admin.income-expense-report.index') }}">@lang('app.menu.incomeVsExpenseReport')</a>
                            </li>
                        @endif

                        @if (in_array('leaves', $modules))
                            <li><a href="{{ route('admin.leave-report.index') }}">@lang('app.menu.leaveReport')</a></li>
                        @endif

                        @if (in_array('attendance', $modules))
                            <li><a href="{{ route('admin.attendance-report.index') }}">@lang('app.menu.attendanceReport')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @role('admin')
                <li><a href="{{ route('admin.billing') }}" class="waves-effect"><i class="icon-book-open fa-fw"></i>
                        <span class="hide-menu"> @lang('app.menu.billing')</span></a>
                </li>
            @endrole

            @foreach ($worksuitePlugins as $item)
                @if (in_array(strtolower($item), $modules) || in_array($item, $modules))
                    @if (View::exists(strtolower($item) . '::sections.left_sidebar'))
                        @include(strtolower($item) . '::sections.left_sidebar')
                    @endif
                @endif
            @endforeach
            <li><a href="{{ route('admin.employee-faq.index') }}"
                    class="waves-effect
                    {{ request()->is('admin/employee-faq*') ? 'active' : '' }}"><i
                        class="icon-docs fa-fw"></i> <span class="hide-menu"> @lang('app.faq') <span
                            class="fa arrow"></span> </span></a>
                <ul class="nav nav-second-level {{ request()->is('admin/employee-faq*') ? 'collapse in' : '' }}">
                    <li><a href="{{ route('admin.faqs.index') }}" class="waves-effect"><i
                                class="icon-docs fa-fw"></i>
                            <span class="hide-menu"> @lang('app.myFaq')</span></a></li>
                    <li><a href="{{ route('admin.employee-faq.index') }}" class="waves-effect"><i
                                class="icon-docs fa-fw"></i> <span class="hide-menu">
                                @lang('app.menu.employeeFaq')</span></a></li>

                </ul>
            </li>
            @if (in_array('calling', $modules))
                <li><a href="{{ route('admin.call-log-reports.index') }}" class="waves-effect"><i
                            class="icon-phone fa-fw"></i>
                        <span class="hide-menu"> Call Log Reports</span></a>
                </li>
            @endif

            <li><a href="{{ route('admin.settings.index') }}" class="waves-effect"><i
                        class="ti-settings fa-fw"></i>
                    <span class="hide-menu"> @lang('app.menu.settings')</span></a>
            </li>

            {{-- <li><a href="" class="waves-effect"><i class="ti-settings fa-fw"></i> <span class="hide-menu">
                        @lang('app.menu.settings') <span class="fa arrow"></span> </span></a> --}}
            {{-- <ul class="nav nav-second-level collapse"> --}}
            {{-- <li><a href="{{ route('admin.settings.index') }}" class="waves-effect"><i
                                class="ti-settings fa-fw"></i> <span class="hide-menu">
                                @lang('app.menu.settings')</span></a> --}}
            {{-- </li> --}}
            {{--  --}}{{-- <li><a href="#" class="waves-effect" id="rtl"><i class="ti-settings fa-fw"></i> <span
                                class="hide-menu"> RTL</span></a></li> --}}

            {{-- </ul> --}}
            {{-- </li> --}}

        </ul>



    </div>

    <div class="menu-footer">
        <div class="menu-user row">
            <div class="col-lg-4 m-b-5">
                <div class="btn-group dropup user-dropdown">

                    <img aria-expanded="false" data-toggle="dropdown" src="{{ $user->image_url }}"
                        alt="user-img" class="img-circle dropdown-toggle h-30 w-30">
                    <ul role="menu" class="dropdown-menu">
                        <li><a class="bg-inverse"><strong
                                    class="text-info">{{ ucwords($user->name) }}</strong></a>
                        </li>
                        <li>
                            <a href="{{ route('member.dashboard') }}">
                                <i class="fa fa-sign-in"></i> @lang('app.loginAsEmployee')
                            </a>
                        </li>
                        @if ($isClient)
                            <li>
                                <a href="{{ route('client.dashboard.index') }}">
                                    <i class="fa fa-sign-in"></i> @lang('app.loginAsClient')
                                </a>
                            </li>
                        @endif
                        @if (in_array('ticket support', $modules))
                            <li>
                                <a href="{{ route('admin.support-tickets.index') }}">
                                    <i class="fa fa-ticket"></i> @lang('app.supportTicket')
                                </a>
                            </li>
                        @endif
                        <li><a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"><i
                                    class="fa fa-power-off"></i> @lang('app.logout')</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="col-lg-4 text-center  m-b-5">
                <div class="btn-group dropup shortcut-dropdown">
                    <a class="dropdown-toggle waves-effect waves-light text-uppercase" data-toggle="dropdown"
                        href="#">
                        <i class="fa fa-plus"></i>
                    </a>
                    <ul class="dropdown-menu">

                        @if (in_array('projects', $modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.projects.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.project')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if (in_array('tasks', $modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.all-tasks.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.task')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if (in_array('clients', $modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.clients.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.client')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if (in_array('employees', $modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.employees.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.employee')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if (in_array('payments', $modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.payments.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('modules.payments.addPayment')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if (in_array('tickets', $modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.tickets.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add')
                                                @lang('modules.tickets.ticket')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 text-right m-b-5">
                <div class="btn-group dropup notification-dropdown">
                    <a class="dropdown-toggle show-user-notifications" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                        @if ($unreadNotificationCount > 0)
                            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                        @endif
                    </a>
                    <ul class="dropdown-menu mailbox ">
                        <li>
                            <a href="javascript:;">...</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="menu-copy-right">
            <a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i
                    class="ti-angle-double-right ti-angle-double-left"></i> <span
                    class="collapse-sidebar-text">@lang('app.collapseSidebar')</span></a>
        </div>

    </div>


</div>

<style>
    .slimScrollDiv {
        overflow: initial !important;
    }

    /* .nav>li>a:focus{
    background-color: #041731
} */
</style>
