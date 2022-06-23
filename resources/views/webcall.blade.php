<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta name="description" content="CleverStack - administration panel">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="author" content="CleverStack - administration panel">

    <link rel="stylesheet" type="text/css"
        href="https://dtd6jl0d42sve.cloudfront.net/lib/Normalize/normalize-v8.0.1.css" />
    <link rel="stylesheet" type="text/css"
        href="https://dtd6jl0d42sve.cloudfront.net/lib/fonts/font_roboto/roboto.css" />
    <link rel="stylesheet" type="text/css"
        href="https://dtd6jl0d42sve.cloudfront.net/lib/fonts/font_awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://dtd6jl0d42sve.cloudfront.net/lib/Croppie/Croppie-2.6.4/croppie.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('webcall/Phone/phone.css') }}" />

    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery.md5-min.js"></script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/Chart/Chart.bundle-2.7.2.js"></script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/SipJS/sip-0.20.0.min.js"></script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/FabricJS/fabric-2.4.6.min.js"></script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/Moment/moment-with-locales-2.24.0.min.js">
    </script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/Croppie/Croppie-2.6.4/croppie.min.js">
    </script>
    <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/XMPP/strophe-1.4.1.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>

    <script>
        var user = {
            server: "{{ company()->sip_gateway->endpoint }}",
            id: "{{ Auth::user()->sip_user }}",
            xid: "{{ Auth::user()->sip_pass }}",
            name: "{{ Auth::user()->name }}",
            hostingPrefex: "{{ url('/webcall/Phone/') }}",
            base_url: "{{ url('') }}",
            end_url: "{{ route('member.leads.callEndReport') }}",
            csrf_token: "{{ csrf_token() }}"
        }
    </script>
    <script type="text/javascript" src="{{ asset('webcall/Phone/phone.js') }}"></script>
</head>

<body>
    <script type="text/javascript">
        var socket = io.connect("{{ env('SIP_SOCKET') }}");
        socket.on('click2call', function(data) {
            console.log(data)
            if (data.auth == user.id) {
                $('#BtnFreeDial').trigger("click")
                $('.dialTextInput').val('+' + data.data)
            }
        })
        let EnableOutgoing = true
        var phoneOptions = {
            loadAlternateLang: true
        }

        var web_hook_on_transportError = function(t, ua) {
            // console.warn("web_hook_on_transportError",t, ua);
        }
        var web_hook_on_register = function(ua) {
            // console.warn("web_hook_on_register", ua);
        }
        var web_hook_on_registrationFailed = function(e) {
            // console.warn("web_hook_on_registrationFailed", e);
        }
        var web_hook_on_unregistered = function() {
            // console.warn("web_hook_on_unregistered");
        }
        var web_hook_on_invite = function(session) {
            let data

            if (session.data.calldirection == 'inbound') {
                data = {
                    type: 'inbound',
                    user: session.remoteIdentity.uri.user,
                    source: user.id,
                    session_id: session.id
                }
            }
            if (session.data.calldirection == 'outbound') {
                data = {
                    type: 'outbound',
                    user: session.data.dst,
                    source: user.id,
                    session_id: session.id
                }
            }

            socket.emit('call', data);




        }
        var web_hook_on_message = function(message) {
            // console.warn("web_hook_on_message", message);
        }
        var web_hook_on_modify = function(action, session) {
            // console.warn("web_hook_on_modify", action, session);
        }
        var web_hook_on_dtmf = function(item, session) {
            // console.warn("web_hook_on_dtmf", item, session);
        }
        var web_hook_on_terminate = function(session) {
            // console.warn("web_hook_on_terminate", session);
            console.warn(session)

            socket.emit('callEnd', 'call ended');
        }
    </script>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div id=Phone></div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
