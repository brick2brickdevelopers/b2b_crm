/* globals SIP,user,moment, Stopwatch */

var microSip;

$(document).ready(function () {


    if (typeof (user) === 'undefined') {
        user = JSON.parse(localStorage.getItem('SIPCreds'));
    }

    microSip = {

        config: {
            password: user.Pass,
            displayName: user.Display,
            uri: 'sip:' + user.User + '@' + user.Realm,
            wsServers: user.WSServer,
            registerExpires: 60,
            traceSip: true,
            log: {
                level: 1,
            },
            instanceId: "f9d2e487-8759-43f6-ad0c-affb2ab8553b",
            keepAliveInterval:500,
            register: true
        },
        ringtone: document.getElementById('ringtone'),
        ringbacktone: document.getElementById('ringbacktone'),
        dtmfTone: document.getElementById('dtmfTone'),

        Sessions: [],
        callTimers: {},
        callActiveID: null,
        callVolume: 1,
        Stream: null,

        /**
         * Parses a SIP uri and returns a formatted US phone number.
         *
         * @param  {string} phone number or uri to format
         * @return {string}       formatted number
         */
        formatPhone: function (phone) {

            var num;

            if (phone.indexOf('@')) {
                num = phone.split('@')[0];
            } else {
                num = phone;
            }

            num = num.toString().replace(/[^0-9]/g, '');

            if (num.length === 10) {
                return '(' + num.substr(0, 3) + ') ' + num.substr(3, 3) + '-' + num.substr(6, 4);
            } else if (num.length === 11) {
                return '(' + num.substr(1, 3) + ') ' + num.substr(4, 3) + '-' + num.substr(7, 4);
            } else {
                return num;
            }
        },

        // Sound methods
        startRingTone: function () {
            try { microSip.ringtone.play(); } catch (e) { }
        },

        stopRingTone: function () {
            try { microSip.ringtone.pause(); } catch (e) { }
        },

        startRingbackTone: function () {
            try { microSip.ringbacktone.play(); } catch (e) { }
        },

        stopRingbackTone: function () {
            try { microSip.ringbacktone.pause(); } catch (e) { }
        },

        // Genereates a rendom string to ID a call
        getUniqueID: function () {
            return Math.random().toString(36).substr(2, 9);
        },

        newSession: function (newSess) {

            newSess.displayName = newSess.remoteIdentity.displayName || newSess.remoteIdentity.uri.user;
            newSess.xenid = microSip.getUniqueID();

            var status;

            if (newSess.direction === 'incoming') {
                status = "Incoming: " + newSess.displayName;
                microSip.startRingTone();
                // localStorage.setItem('newSess', JSON.stringify(newSess))
            } else {
                status = "Trying: " + newSess.displayName;
                microSip.startRingbackTone();
            }

            microSip.logCall(newSess, 'ringing');

            microSip.setCallSessionStatus(status);

            // EVENT CALLBACKS

            newSess.on('progress', function (e) {
                if (e.direction === 'outgoing') {
                    microSip.setCallSessionStatus('Calling...');
                }
            });

            newSess.on('connecting', function (e) {
                if (e.direction === 'outgoing') {
                    microSip.setCallSessionStatus('Connecting...');
                }
            });

            newSess.on('accepted', function (e) {
                // If there is another active call, hold it
                if (microSip.callActiveID && microSip.callActiveID !== newSess.xenid) {
                    microSip.phoneHoldButtonPressed(microSip.callActiveID);
                }

                microSip.stopRingbackTone();
                microSip.stopRingTone();
                microSip.setCallSessionStatus('Answered');
                microSip.logCall(newSess, 'answered');
                microSip.callActiveID = newSess.xenid;
            });

            newSess.on('hold', function (e) {
                microSip.callActiveID = null;
                microSip.logCall(newSess, 'holding');
            });

            newSess.on('unhold', function (e) {
                microSip.logCall(newSess, 'resumed');
                microSip.callActiveID = newSess.xenid;
            });

            newSess.on('muted', function (e) {
                microSip.Sessions[newSess.xenid].isMuted = true;
                microSip.setCallSessionStatus("Muted");
            });

            newSess.on('unmuted', function (e) {
                microSip.Sessions[newSess.xenid].isMuted = false;
                microSip.setCallSessionStatus("Answered");
            });

            newSess.on('cancel', function (e) {
                microSip.stopRingTone();
                microSip.stopRingbackTone();
                microSip.setCallSessionStatus("Canceled");
                if (this.direction === 'outgoing') {
                    microSip.callActiveID = null;
                    newSess = null;
                    microSip.logCall(this, 'ended');
                }
            });

            newSess.on('bye', function (e) {
                microSip.stopRingTone();
                microSip.stopRingbackTone();
                microSip.setCallSessionStatus("");
                microSip.logCall(newSess, 'ended');
                microSip.callActiveID = null;
                newSess = null;
            });

            newSess.on('failed', function (e) {
                microSip.stopRingTone();
                microSip.stopRingbackTone();
                microSip.setCallSessionStatus('Terminated');
            });

            newSess.on('rejected', function (e) {
                microSip.stopRingTone();
                microSip.stopRingbackTone();
                microSip.setCallSessionStatus('Rejected');
                microSip.callActiveID = null;
                microSip.logCall(this, 'ended');
                newSess = null;
            });

            microSip.Sessions[newSess.xenid] = newSess;


        },

        // getUser media request refused or device was not present
        getUserMediaFailure: function (e) {
            window.console.error('getUserMedia failed:', e);
            microSip.setError(true, 'Media Error.', 'You must allow access to your microphone.  Check the address bar.', true);
        },

        getUserMediaSuccess: function (stream) {
            microSip.Stream = stream;
        },

        /**
         * sets the ui call status field
         *
         * @param {string} status
         */
        setCallSessionStatus: function (status) {
            $('#txtCallStatus').html(status);
        },

        /**
         * sets the ui connection status field
         *
         * @param {string} status
         */
        setStatus: function (status) {
            $("#txtRegStatus").html('<i class="fa fa-signal"></i> ' + status);
        },

        /**
         * logs a call to localstorage
         *
         * @param  {object} session
         * @param  {string} status Enum 'ringing', 'answered', 'ended', 'holding', 'resumed'
         */
        logCall: function (session, status) {

            var log = {
                clid: session.displayName,
                uri: session.remoteIdentity.uri.toString(),
                id: session.xenid,
                time: new Date().getTime()
            },
                calllog = JSON.parse(localStorage.getItem('sipCalls'));

            if (!calllog) { calllog = {}; }

            if (!calllog.hasOwnProperty(session.xenid)) {
                calllog[log.id] = {
                    id: log.id,
                    clid: log.clid,
                    uri: log.uri,
                    start: log.time,
                    flow: session.direction
                };
            }

            if (status === 'ended') {
                calllog[log.id].stop = log.time;
            }

            if (status === 'ended' && calllog[log.id].status === 'ringing') {
                calllog[log.id].status = 'missed';
            } else {
                calllog[log.id].status = status;
            }

            localStorage.setItem('sipCalls', JSON.stringify(calllog));
            microSip.logShow();
        },

        /**
         * adds a ui item to the call log
         *
         * @param  {object} item log item
         */
        logItem: function (item) {

            var callActive = (item.status !== 'ended' && item.status !== 'missed'),
                callLength = (item.status !== 'ended') ? '<span id="' + item.id + '"></span>' : moment.duration(item.stop - item.start).humanize(),
                callClass = '',
                callIcon,
                i;
            if (callActive) {


                switch (item.status) {
                    case 'ringing':
                        callClass = 'list-group-item-success';
                        callIcon = 'fa-bell';
                        break;

                    case 'missed':
                        callClass = 'list-group-item-danger';
                        if (item.flow === "incoming") { callIcon = 'fa-chevron-left'; }
                        if (item.flow === "outgoing") { callIcon = 'fa-chevron-right'; }
                        break;

                    case 'holding':
                        callClass = 'list-group-item-warning';
                        callIcon = 'fa-pause';
                        break;

                    case 'answered':
                    case 'resumed':
                        callClass = 'list-group-item-info';
                        callIcon = 'fa-phone-square';
                        break;

                    case 'ended':
                        if (item.flow === "incoming") { callIcon = 'fa-chevron-left'; }
                        if (item.flow === "outgoing") { callIcon = 'fa-chevron-right'; }
                        break;
                }


                i = '<div class="list-group-item sip-logitem clearfix ' + callClass + '" data-uri="' + item.uri + '" data-sessionid="' + item.id + '" title="Double Click to Call">';
                i += '<div class="clearfix"><div class="pull-left">';
                i += '<i class="fa fa-fw ' + callIcon + ' fa-fw"></i> <strong>' + microSip.formatPhone(item.uri) + '</strong><br><small>' + moment(item.start).format('MM/DD hh:mm:ss a') + '</small>';
                i += '</div>';
                i += '<div class="pull-right text-right"><em>' + item.clid + '</em><br>' + callLength + '</div></div>';

                if (callActive) {
                    i += '<div class="btn-group btn-group-xs pull-right">';
                    if (item.status === 'ringing' && item.flow === 'incoming') {
                        i += '<button class="btn btn-xs btn-success btnCall" title="Call"><i class="fa fa-phone"></i></button>';
                    } else {
                        i += '<button class="btn btn-xs btn-primary btnHoldResume" title="Hold"><i class="fa fa-pause"></i></button>';
                        i += '<button class="btn btn-xs btn-info btnTransfer" title="Transfer"><i class="fa fa-random"></i></button>';
                        i += '<button class="btn btn-xs btn-warning btnMute" title="Mute"><i class="fa fa-fw fa-microphone"></i></button>';
                    }
                    i += '<button class="btn btn-xs btn-danger btnHangUp" title="Hangup"><i class="fa fa-stop"></i></button>';
                    i += '</div>';
                }
                i += '</div>';

                $('#sip-logitems').append(i);


                // Start call timer on answer
                if (item.status === 'answered') {
                    var tEle = document.getElementById(item.id);
                    microSip.callTimers[item.id] = new Stopwatch(tEle);
                    microSip.callTimers[item.id].start();
                }

                if (callActive && item.status !== 'ringing') {
                    microSip.callTimers[item.id].start({ startTime: item.start });
                }

                $('#sip-logitems').scrollTop(0);
            }
        },

        /**
         * updates the call log ui
         */
        logShow: function () {

            var calllog = JSON.parse(localStorage.getItem('sipCalls')),
                x = [];

            if (calllog !== null) {

                $('#sip-splash').addClass('hide');
                $('#sip-log').removeClass('hide');

                // empty existing logs
                $('#sip-logitems').empty();

                // JS doesn't guarantee property order so
                // create an array with the start time as
                // the key and sort by that.

                // Add start time to array
                $.each(calllog, function (k, v) {
                    x.push(v);
                });

                // sort descending
                x.sort(function (a, b) {
                    return b.start - a.start;
                });

                $.each(x, function (k, v) {
                    microSip.logItem(v);
                });

            } else {
                $('#sip-splash').removeClass('hide');
                $('#sip-log').addClass('hide');
            }
        },

        /**
         * removes log items from localstorage and updates the UI
         */
        logClear: function () {

            localStorage.removeItem('sipCalls');
            microSip.logShow();
        },

        sipCall: function (target) {

            try {

                var s = microSip.phone.invite(target, {
                    media: {
                        stream: microSip.Stream,
                        constraints: { audio: true, video: false },
                        render: {
                            remote: $('#audioRemote').get()[0]
                        },
                        RTCConstraints: { "optional": [{ 'DtlsSrtpKeyAgreement': 'true' }] }
                    }
                });
                s.direction = 'outgoing';

                microSip.newSession(s);

            } catch (e) {
                throw (e);
            }
        },

        sipTransfer: function (sessionid) {

            var s = microSip.Sessions[sessionid],
                target = window.prompt('Enter destination number', '');

            microSip.setCallSessionStatus('<i>Transfering the call...</i>');
            s.refer(target);
        },

        sipHangUp: function (sessionid) {

            var s = microSip.Sessions[sessionid];
            // s.terminate();
            if (!s) {
                return;
            } else if (s.startTime) {
                s.bye();
            } else if (s.reject) {
                s.reject();
            } else if (s.cancel) {
                s.cancel();
            }
            $.ajax({
                url: hangUpAppi,
                type: "GET",
                data: null,

            });

            localStorage.removeItem('sipCalls')

        },

        sipSendDTMF: function (digit) {

            try { microSip.dtmfTone.play(); } catch (e) { }

            var a = microSip.callActiveID;
            if (a) {
                var s = microSip.Sessions[a];
                s.dtmf(digit);
            }
        },

        phoneCallButtonPressed: function (sessionid) {

            var s = microSip.Sessions[sessionid],
                target = $("#numDisplay").val();

            if (!s) {

                $("#numDisplay").val("");
                microSip.sipCall(target);

            } else if (s.accept && !s.startTime) {

                s.accept({
                    media: {
                        stream: microSip.Stream,
                        constraints: { audio: true, video: false },
                        render: {
                            remote: { audio: $('#audioRemote').get()[0] }
                        },
                        RTCConstraints: { "optional": [{ 'DtlsSrtpKeyAgreement': 'true' }] }
                    }
                });
            }
        },

        phoneMuteButtonPressed: function (sessionid) {

            var s = microSip.Sessions[sessionid];

            if (!s.isMuted) {
                s.mute();
            } else {
                s.unmute();
            }
        },

        phoneHoldButtonPressed: function (sessionid) {

            var s = microSip.Sessions[sessionid];

            if (s.isOnHold().local === true) {
                s.unhold();
            } else {
                s.hold();
            }
        },


        setError: function (err, title, msg, closable) {

            // Show modal if err = true
            if (err === true) {
                $("#mdlError p").html(msg);
                $("#mdlError").modal('show');

                if (closable) {
                    var b = '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                    $("#mdlError .modal-header").find('button').remove();
                    $("#mdlError .modal-header").prepend(b);
                    $("#mdlError .modal-title").html(title);
                    $("#mdlError").modal({ keyboard: true });
                } else {
                    $("#mdlError .modal-header").find('button').remove();
                    $("#mdlError .modal-title").html(title);
                    $("#mdlError").modal({ keyboard: false });
                }
                $('#numDisplay').prop('disabled', 'disabled');
            } else {
                $('#numDisplay').removeProp('disabled');
                $("#mdlError").modal('hide');
            }
        },

        /**
         * Tests for a capable browser, return bool, and shows an
         * error modal on fail.
         */
        hasWebRTC: function () {

            if (navigator.webkitGetUserMedia) {
                return true;
            } else if (navigator.mozGetUserMedia) {
                return true;
            } else if (navigator.getUserMedia) {
                return true;
            } else {
                microSip.setError(true, 'Unsupported Browser.', 'Your browser does not support the features required for this phone.');
                window.console.error("WebRTC support not found");
                return false;
            }
        }
    };




    // Throw an error if the browser can't hack it.
    if (!microSip.hasWebRTC()) {
        return true;
    }

    microSip.phone = new SIP.UA(microSip.config);
    // localStorage.setItem('authToken',JSON.parse())     
    console.log(microSip.phone)

    microSip.phone.on('connected', function (e) {
        microSip.setStatus("Connected");
    });

    microSip.phone.on('disconnected', function (e) {
        microSip.setStatus("Disconnected");

        // disable phone
        microSip.setError(true, 'Websocket Disconnected.', 'An Error occurred connecting to the websocket.');

        // remove existing sessions
        $("#sessions > .session").each(function (i, session) {
            microSip.removeSession(session, 500);
        });
    });

    microSip.phone.on('registered', function (e) {

        var closeEditorWarning = function () {
            return 'If you close this window, you will not be able to make or receive calls from your browser.';
        };

        var closePhone = function () {
            // stop the phone on unload
            localStorage.removeItem('xenPhone');
            microSip.phone.stop();
        };

        // window.onbeforeunload = closeEditorWarning;
        window.onunload = closePhone;

        // This key is set to prevent multiple windows.
        localStorage.setItem('xenPhone', 'true');

        $("#mldError").modal('hide');
        microSip.setStatus("Ready");

        // Get the userMedia and cache the stream
        if (SIP.WebRTC.isSupported()) {
            SIP.WebRTC.getUserMedia({ audio: true, video: false }, microSip.getUserMediaSuccess, microSip.getUserMediaFailure);
        }
    });

    microSip.phone.on('registrationFailed', function (e) {
        microSip.setError(true, 'Registration Error.', 'An Error occurred registering your phone. Check your settings.');
        microSip.setStatus("Error: Registration Failed");
    });

    microSip.phone.on('unregistered', function (e) {
        microSip.setError(true, 'Registration Error.', 'An Error occurred registering your phone. Check your settings.');
        microSip.setStatus("Error: Registration Failed");
    });

    microSip.phone.on('invite', function (incomingSession) {

        var s = incomingSession;

        s.direction = 'incoming';
        microSip.newSession(s);
    });

    // Auto-focus number input on backspace.
    $('#sipClient').keydown(function (event) {
        if (event.which === 8) {
            $('#numDisplay').focus();
        }
    });

    $('#numDisplay').keypress(function (e) {
        // Enter pressed? so Dial.
        if (e.which === 13) {
            microSip.phoneCallButtonPressed();
        }
    });

    $('.digit').click(function (event) {
        event.preventDefault();
        var num = $('#numDisplay').val(),
            dig = $(this).data('digit');

        $('#numDisplay').val(num + dig);

        microSip.sipSendDTMF(dig);
        return false;
    });

    $('#phoneUI .dropdown-menu').click(function (e) {
        e.preventDefault();
    });

    $('#phoneUI').delegate('.btnCall', 'click', function (event) {
        microSip.phoneCallButtonPressed();
        // to close the dropdown
        return true;
    });

    $('.sipLogClear').click(function (event) {
        event.preventDefault();
        microSip.logClear();
    });

    $('#sip-logitems').delegate('.sip-logitem .btnCall', 'click', function (event) {
        var sessionid = $(this).closest('.sip-logitem').data('sessionid');
        microSip.phoneCallButtonPressed(sessionid);
        return false;
    });

    $('#sip-logitems').delegate('.sip-logitem .btnHoldResume', 'click', function (event) {
        var sessionid = $(this).closest('.sip-logitem').data('sessionid');
        microSip.phoneHoldButtonPressed(sessionid);
        return false;
    });

    $('#sip-logitems').delegate('.sip-logitem .btnHangUp', 'click', function (event) {
        var sessionid = $(this).closest('.sip-logitem').data('sessionid');
        microSip.sipHangUp(sessionid);
        return false;
    });

    $('#sip-logitems').delegate('.sip-logitem .btnTransfer', 'click', function (event) {
        var sessionid = $(this).closest('.sip-logitem').data('sessionid');
        microSip.sipTransfer(sessionid);
        return false;
    });

    $('#sip-logitems').delegate('.sip-logitem .btnMute', 'click', function (event) {
        var sessionid = $(this).closest('.sip-logitem').data('sessionid');
        microSip.phoneMuteButtonPressed(sessionid);
        return false;
    });

    $('#sip-logitems').delegate('.sip-logitem', 'dblclick', function (event) {
        event.preventDefault();

        var uri = $(this).data('uri');
        $('#numDisplay').val(uri);
        microSip.phoneCallButtonPressed();
    });

    $('#sldVolume').on('change', function () {

        var v = $(this).val() / 100,
            // player = $('audio').get()[0],
            btn = $('#btnVol'),
            icon = $('#btnVol').find('i'),
            active = microSip.callActiveID;

        // Set the object and media stream volumes
        if (microSip.Sessions[active]) {
            microSip.Sessions[active].player.volume = v;
            microSip.callVolume = v;
        }

        // Set the others
        $('audio').each(function () {
            $(this).get()[0].volume = v;
        });

        if (v < 0.1) {
            btn.removeClass(function (index, css) {
                return (css.match(/(^|\s)btn\S+/g) || []).join(' ');
            })
                .addClass('btn btn-sm btn-danger');
            icon.removeClass().addClass('fa fa-fw fa-volume-off');
        } else if (v < 0.8) {
            btn.removeClass(function (index, css) {
                return (css.match(/(^|\s)btn\S+/g) || []).join(' ');
            }).addClass('btn btn-sm btn-info');
            icon.removeClass().addClass('fa fa-fw fa-volume-down');
        } else {
            btn.removeClass(function (index, css) {
                return (css.match(/(^|\s)btn\S+/g) || []).join(' ');
            }).addClass('btn btn-sm btn-primary');
            icon.removeClass().addClass('fa fa-fw fa-volume-up');
        }
        return false;
    });

    // Hide the spalsh after 3 secs.
    setTimeout(function () {
        microSip.logShow();
    }, 3000);





    /**
     * Stopwatch object used for call timers
     *
     * @param {dom element} elem
     * @param {[object]} options
     */
    var Stopwatch = function (elem, options) {

        // private functions
        function createTimer() {
            return document.createElement("span");
        }

        var timer = createTimer(),
            offset,
            clock,
            interval;

        // default options
        options = options || {};
        options.delay = options.delay || 1000;
        options.startTime = options.startTime || Date.now();

        // append elements
        elem.appendChild(timer);

        function start() {
            if (!interval) {
                offset = options.startTime;
                interval = setInterval(update, options.delay);
            }
        }

        function stop() {
            if (interval) {
                clearInterval(interval);
                interval = null;
            }
        }

        function reset() {
            clock = 0;
            render();
        }

        function update() {
            clock += delta();
            render();
        }

        function render() {
            timer.innerHTML = moment(clock).format('mm:ss');
        }

        function delta() {
            var now = Date.now(),
                d = now - offset;

            offset = now;
            return d;
        }

        // initialize
        reset();

        // public API
        this.start = start; //function() { start; }
        this.stop = stop; //function() { stop; }
    };

});
