@extends('layouts.app')
@section('pageTitle', 'Books :: EzBook')

@section('content')
    <x-layouts.page>
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <!--Side Col Sidebar Start-->
                <div class="col-md-2">
                    <div id="kt_aside_menu" class="aside-menu " data-menu-vertical="1" data-menu-scroll="1"
                        data-menu-dropdown-timeout="500">
                        <!--begin::Menu Nav-->
                        @php
                            // dd( $data);
                            extract($data);
                            $LocationOptions = ['0' => 'All'] + array_combine(array_column($ResourceLocation, 'id'), array_column($ResourceLocation, 'Name'));

                        @endphp

                        <ul class="menu-nav pb-0">
                            <li class="menu-section">
                                <h4 class="menu-text">Location</h4>
                                <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                            </li>
                            <li>
                                <x-forms.form class="form p-5">
                                    <div class="form-group">

                                        <x-forms.select selected="{{ request()->segment(3) }}"
                                            onchange="let url = '{!! route('book.location', ':id') !!}';url = url.replace(':id', $(this).val()); window.location.href=url"
                                            name="resourceLocation" :options="$LocationOptions"
                                            class="form-control form-control-md location-select2" id="show" />
                                    </div>
                                    <div class="form-group">
                                        <x-forms.input name="searchResources" placeholder="Search Resources" type="text"
                                            class="font-size-base form-control form-control-md" />

                                    </div>
                                </x-forms.form>
                            </li>
                        </ul>

                        <x-layouts.menu-vertical name="Choose A Resource" :menus="$ResourceTypes" childkey="resources" />
                        {{-- @dd($ResourceTypes) --}}
                        <!--end::Menu Nav-->
                    </div>
                </div>
                <!--Side Col Sidebar End-->

                <div class="col-md-10" id="id-resource-sub-resource">
                    <!--begin::Card-->
                    <div class="card card-custom">

                        <div class="card-header" id="ajax-header">
                            @php
                                $Resources = isset($Resources) && count($Resources) == 1 ? $Resources[0] : $Resources;
                                // dd($Resources);
                                extract($Resources);
                                $slotDuration = date('H:i:s', mktime(0, $SlotLength));
                                $events = [];
                                if (isset($bookings) && count($bookings)) {
                                    foreach ($bookings as $key => $booking) {
                                        $booking = (object) $booking;
                                        $events[] = [
                                            'id' => $booking->ID,
                                            'title' => $Name,
                                            'start' => $booking->FromTime,
                                            'description' => "[{$booking->FromTime} - {$booking->ToTime} : {$Name} (Booked by {$booking->BookedBy})]",
                                            'end' => $booking->ToTime,
                                            'className' => 'fc-event-success',
                                        ];
                                    }
                                }
                                // dd($events);
                            @endphp
                            <div class="card-title">
                                <h3 class="card-label">{{ $Name }}</h3>
                            </div>
                            <div class="card-toolbar">
                                @if (count($sub_resources) > 0)
                                    <form class="form p-5">
                                        <div class="form-group mb-0">
                                            <select class="form-control form-control-md " id="id-sub_resource">
                                                <option>All</option>
                                                <option>Any</option>
                                                @foreach ($sub_resources as $sub)
                                                    <option value="{{ $sub['ID'] }}">{{ $sub['Name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>

                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="calendar"></div>
                            {{-- <div id="kt_calendar"></div> --}}
                        </div>
                    </div>
                    <!--end::Card-->

                </div>
            </div>
        </div>
        <!--end::Container-->
    </x-layouts.page>


    <!-- start::booking-create-modal -->
    {{-- <div class="modal fade" id="booking-create-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <x-forms.form id="ajax-create-booking" action="{{ route('book.store') }}">
                    <x-forms.input name="FacID" type="hidden" value="{{ $ID }}" />
                    {{-- <x-forms.input name="SubID" type="hidden" value="0" /> -- }}
                    <div class="modal-header">
                        <h4 class="modal-title">New Booking for {{ $Name }} </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @if (count($sub_resources) > 0)
                            @php
                                $options = ['0' => 'All', '1' => 'Any'] + array_combine(array_column($sub_resources, 'ID'), array_column($sub_resources, 'Name'));
                            @endphp
                            <x-forms.select design="1" name="SubID" :options="$options" label="Sub Resource" />
                        @endif
                        <x-forms.input design="2" name="FromTime" type="datetime-local" label="From" />
                        <x-forms.input design="2" name="ToTime" type="datetime-local" label="To" />
                        <x-forms.input design="2" name="BookedFor" label="For" />
                        <x-forms.textarea design="1" name="Purpose" label="Additional Info" />

                        <x-forms.checkbox design="2" name="Recurrence" id="recurrence" label="Repeat this booking?" />


                        <div id="recurrence-container" class="form-group row"></div>

                        {{-- <x-forms.input design="2" name="end" type="color" value="#22A6DB" label="Color" title="Choose your color" /> -- }}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </x-forms.form>
            </div>

        </div>
    </div> --}}
    <x-layouts.modal id="booking-create-modal">
        <x-slot:title>New Booking for {{ $Name }}</x-slot:title>

        <x-slot:form id="ajax-create-booking" action="{{ route('book.store') }}"></x-slot:form>
        <x-forms.input name="FacID" type="hidden" value="{{ $ID }}" />
        @if (count($sub_resources) > 0)
            @php
                $options = ['0' => 'All', '1' => 'Any'] + array_combine(array_column($sub_resources, 'ID'), array_column($sub_resources, 'Name'));
            @endphp
            <x-forms.select design="1" name="SubID" :options="$options" label="Sub Resource" />
        @endif

        <x-forms.input design="2" name="FromTime" type="datetime-local" label="From" />
        <x-forms.input design="2" name="ToTime" type="datetime-local" label="To" />
        <x-forms.input design="2" name="BookedFor" label="For" />
        <x-forms.textarea design="1" name="Purpose" label="Additional Info" />

        <x-forms.checkbox design="2" name="BookingType" value="1" id="recurrence" label="Repeat this booking?" />
        {{-- <x-forms.checkbox design="2" name="Recurrence" value="1" id="recurrence" label="Repeat this booking?" /> --}}

        <div id="recurrence-container" class="form-group row"></div>

        <x-slot:footer>
            <button type="submit" class="btn btn-default">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </x-slot:footer>

    </x-layouts.modal>
    <!-- end::booking-create-modal -->

    <!-- start::booking-update-modal -->

    <div class="modal fade" id="booking-update-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <x-forms.form method="PUT" id="ajax-update-booking" action="">
                    <x-forms.input name="ID" type="hidden" value="" />
                    <div class="modal-header">
                        <h4 class="modal-title">New Booking for {{ $Name }} </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @if (count($sub_resources) > 0)
                            @php
                                $options = ['0' => 'All', '1' => 'Any'] + array_combine(array_column($sub_resources, 'ID'), array_column($sub_resources, 'Name'));
                            @endphp
                            <x-forms.select design="1" name="SubID" :options="$options" label="Sub Resource" />
                        @endif
                        <x-forms.input design="2" name="FromTime" type="datetime-local" label="From" />
                        <x-forms.input design="2" name="ToTime" type="datetime-local" label="To" />
                        <x-forms.input design="2" name="BookedFor" label="For" />
                        <x-forms.textarea design="1" name="Purpose" label="Additional Info" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Update</button>
                </x-forms.form>
                <x-forms.form method="DELETE" id="ajax-delete-booking" action="">
                    <x-forms.input id="id-delete" name="ID" type="hidden" value="" />
                    <button type="submit" class="btn btn-default">Delete</button>
                </x-forms.form>
            </div>
        </div>

    </div>
    </div>
    <!-- end::booking-update-modal -->


    <div id="id-recurrence-items" class="" style="display: none">
        <div class="col recurrence">
            <div class="form-group row">
                <div class="form-check form-check-inline col">
                    <x-forms.radio class="radio" name="recurrenceType" value="1" id="day-intervals"
                        label="Day intervals" />
                </div>
                <div class="form-check form-check-inline col">
                    <x-forms.radio class="radio" name="recurrenceType" value="2" id="week-intervals"
                        label="Week intervals" />
                </div>
                <div class="form-check form-check-inline col">
                    <x-forms.radio class="radio" name="recurrenceType" value="3" id="month-intervals"
                        label="Month intervals" />
                </div>
            </div>
            <div class="form-group row intervals"></div>
        </div>

        <div class="form-group col day-intervals">
            <div class="form-group row">
                <label class="col" for="id-day">Every</label>
                <input class="col form-control" type="number" name="recurrenceInterval" id="id-day" />
                <div class="col col-form-label">days</div>
            </div>
            <x-forms.input design="2" size="2" type="date" name="untilDate" label="Until" />

        </div>

        <div class="form-group col week-intervals">
            <div class="form-group row">
                <label class="col" for="id-day">Every</label>
                <input class="col form-control" type="number" name="recurrenceInterval" id="id-day" />
                <label class="col col-form-label">Week on</label>
                <x-forms.select multiple class="col form-control multiple-select2" :options="['Sunday', 'Monday', 'Tuesday', 'Wednessday', 'Thursday', 'Friday', 'Saturday']"
                    name="daysOfWeek[]" />
            </div>
            <x-forms.input design="2" size="2" type="date" name="untilDate" label="Until" />

        </div>
        <div class="form-group col month-intervals">
            <div class="form-group row">
                <div class="pt-1"><input value="1" class="radio-2" type="radio" checked
                        name="monthIntervalsType" id="month-intervals-type-2" />
                </div>
                <div class="ml-0 pl-0 month-intervals-type-1 month-intervals-type row col">
                    <label class="col">Day</label>
                    <input class="col form-control" type="number" name="MonthIntervalDay" />
                    <label class="col col-form-label">of every</label>
                    <input class="col form-control" type="number" name="recurrenceInterval" />
                    <label class="col col-form-label">month(s)</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="pt-1"><input value="2" class="radio-2" type="radio" name="monthIntervalsType"
                        id="month-intervals-type-1" /></div>
                <div class="ml-0 pl-0 month-intervals-type-2 month-intervals-type row col">
                    <label class="col">The </label>
                    <x-forms.select disabled class="col form-control multiple-select2" :options="['First', 'Second', 'Third', 'Fourth', 'Last']"
                        name="MonthIntervalDateSelection" />
                    <label class="col">Day</label>
                    <x-forms.select disabled class="col form-control multiple-select2" :options="['Sunday', 'Monday', 'Tuesday', 'Wednessday', 'Thursday', 'Friday', 'Saturday']"
                        name="daysOfWeek[]" />
                    <label class="col col-form-label">of every</label>
                    <input disabled class="col form-control" type="number" name="recurrenceInterval" />
                    <label class="col col-form-label">month(s)</label>
                </div>
            </div>
            <x-forms.input design="2" size="2" type="date" name="untilDate" label="Until" />
            {{-- <x-forms.input design="2" size="2" type="datetime-local" name="untilDate" label="Until" /> --}}


        </div>
    </div>








@endsection
@pushOnce('scripts')
    <!--begin::Page Scripts(used by this page)-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    {{-- <script src="{{ asset('js/pages/features/calendar/google.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/pages/features/calendar/external-events.js') }}"></script> --}}
    <script src="{{ asset('js/ajax-full-calendar.js') }}"></script>
    <!--end::Page Scripts-->

    <script>
        "use strict";

        $(document).ready(function() {
            // console.log();
            let CalendarOptions = {
                plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'googleCalendar'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    // right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    right: 'timeGridWeek,timeGridDay,listWeek'
                },
                defaultView: 'timeGridWeek',
                navLinks: true,
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                selectable: true,
                // selectHelper: true,
                // displayEventTime: false,
                // slotDuration: "{{ $slotDuration }}", // Duration, default: '00:30:00' (30 minutes)
                // {{-- events: "{{ route('getbookedresource', ['resource' => 104]) }}", --}}
                events: {!! json_encode($events) !!},
                select: function(info) {
                    var s = moment(info.start).format("YYYY-MM-DD HH:mm:ss");
                    var e = moment(info.end).format("YYYY-MM-DD HH:mm:ss");
                    var sr = 0
                    // alert($('#id-sub_resource').length);
                    if ($('#id-sub_resource').length) {
                        sr = $('#id-sub_resource').val();
                        // alert(sr);
                    }
                    $("#id-FromTime").val(s);
                    $("#id-ToTime").val(e);
                    $("#id-SubID").val(sr);
                    $("#booking-create-modal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    // $('#modal').modal({backdrop: 'static', keyboard: false}, 'show');
                    $("#ajax-create-booking").submit(function(e) {
                        e.preventDefault();
                        $('button[type=submit], input[type=submit]').prop('disabled', true);

                        var $form = $(this);
                        var $actionUrl = $form.attr('action');
                        var $type = $form.attr('method');
                        var $data = $form.serialize();
                        var $success = function(response) {
                            $("#booking-create-modal").modal('hide');
                            //  swal.fire(JSON.stringify(response));
                            // console.log(response);
                            window.location.reload(true);

                        };
                        var $complete = function(response) {};
                        ajaxCall($actionUrl, {
                            type: $type,
                            data: $data,
                            success: $success,
                            complete: $complete
                        });
                    });

                    // console.log(info);
                },
                eventClick: function(info) {

                    $("#booking-update-modal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    let url = "{!! route('book.getbooking', ':id') !!}";
                    url = url.replace(':id', info.event.id);
                    let updateUrl = "{!! route('book.update', ':id') !!}";
                    updateUrl = updateUrl.replace(':id', info.event.id);

                    ajaxCall(url, {
                        success: function(response) {
                            $("#ajax-update-booking").attr("action", updateUrl);
                            $.each(response, function(indexInArray, valueOfElement) {
                                $("#ajax-update-booking input[name=" + indexInArray +
                                    "]").val(valueOfElement);
                                $("#ajax-update-booking textarea[name=" + indexInArray +
                                    "]").val(valueOfElement);
                                $("#ajax-update-booking select[name=" + indexInArray +
                                    "]").val(valueOfElement);
                            });
                            console.log(response);
                            return response;
                        }
                    });
                    // alert(JSON.stringify(bookingData));
                    // console.log(Object.values(bookingData) );
                    $("#ajax-update-booking").submit(function(e) {
                        e.preventDefault();
                        $('button[type=submit], input[type=submit]').prop('disabled', true);

                        var $form = $(this);
                        var $actionUrl = $form.attr('action');
                        var $type = $form.attr('method');
                        var $data = $form.serialize();
                        var $success = function(response) {
                            // alert("success: " + JSON.stringify(response));
                            $("#booking-update-modal").modal('hide');
                            window.location.reload(true);
                        };
                        var $complete = function(response) {
                            // alert("complete:" + JSON.stringify(response));
                            // console.log(response);
                        };
                        ajaxCall($actionUrl, {
                            type: $type,
                            data: $data,
                            success: $success,
                            complete: $complete
                        });
                    });

                    let deleteUrl = "{!! route('book.destroy', ':id') !!}";
                    deleteUrl = deleteUrl.replace(':id', info.event.id);
                    $("#ajax-delete-booking").submit(function(e) {
                        e.preventDefault();
                        $('input[type="submit"]', this).attr('disabled', 'disabled');

                        var $form = $(this);
                        $form.attr("action", deleteUrl);
                        deleteUrl = $form.attr("action");
                        var $type = $form.attr('method');
                        var $data = $form.serialize();
                        var $success = function(response) {
                            // alert("success: " + JSON.stringify(response));
                            $("#booking-update-modal").modal('hide');
                            window.location.reload(true);
                        };
                        var $complete = function(response) {
                            // console.log(response);
                            // alert("complete:" + JSON.stringify(response));
                        };
                        ajaxCall(deleteUrl, {
                            type: $type,
                            data: $data,
                            success: $success,
                            complete: $complete
                        });
                    });

                    return false;
                },
                eventRender: function(info) {
                    var element = $(info.el);
                    // element.attr('title', info.event.extendedProps.description);
                    // $(info.el).popover({
                    //     title: titleCase(info.event.title),
                    //     content: multiline('Start: ' + info.event.start.toDateString() + ' ' + info
                    //         .event.start.toLocaleTimeString() + '\n' + 'End: ' + info.event.end
                    //         .toDateString() + ' ' + info.event.end.toLocaleTimeString()),
                    //     html: true,
                    //     trigger: 'hover',
                    //     container: 'body',
                    //     placement: 'top',
                    // });
                    if (info.event.extendedProps && info.event.extendedProps.description) {
                        if (element.hasClass('fc-day-grid-event')) {
                            element.data('content', info.event.extendedProps.description);
                            element.data('placement', 'top');
                            KTApp.initPopover(element);
                        } else if (element.hasClass('fc-time-grid-event')) {
                            element.data('content', info.event.extendedProps.description);
                            element.data('placement', 'top');
                            KTApp.initPopover(element);
                        } else if (element.find('.fc-list-item-title').lenght !== 0) {
                            element.data('content', info.event.extendedProps.description);
                            element.data('placement', 'top');
                            KTApp.initPopover(element);
                        }
                    }
                    if (info.allDay === 'true') {
                        info.allDay = true;
                    } else {
                        info.allDay = false;
                    }
                }

            };
            let CalendarElement = $('#calendar').get(0);
            var calendar = new FullCalendar.Calendar(CalendarElement, CalendarOptions);
            // var calendar = $("#calendar").fullCalendar(CalendarOptions);
            calendar.render();

            $("#id-sub_resource").change(function(e) {
                e.preventDefault();
                console.log(e);
                let $url = "{!! route('book.getbooking.SubID', ':id') !!}";
                $url = $url.replace(':id', $(this).val());
                // alert($url);
                ajaxCall($url, {
                    success: function(response) {
                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                        calendar.refetchEvents();
                        // Swal.close();
                        console.log(response);
                        // console.log(eventSources);
                        // return events;
                    }
                });
            });

            $("#recurrence").click(function(e) {
                // e.preventDefault();
                var $this = $(this);
                if ($this.is(":checked")) {
                    $("#id-recurrence-items .recurrence").appendTo("#recurrence-container");
                } else {
                    $("#recurrence-container .recurrence").appendTo("#id-recurrence-items");
                }
            });

            $(document).on("click", "#recurrence-container .recurrence .radio", function() {
                var $this = $(this);
                var id = $this.attr("id");
                if ($("#recurrence-container .intervals>div").length) {
                    $("#recurrence-container .intervals>div").appendTo("#id-recurrence-items");
                }
                $("#id-recurrence-items ." + id).appendTo("#recurrence-container .intervals");
                // swal.fire("#recurrence-container .recurrence input:radio=="+id);
                $('.multiple-select2').select2();

            });

            $(document).on("click", "#recurrence-container .recurrence .month-intervals .radio-2", function() {
                var $this = $(this);
                var id = $this.attr("id");
                $(".month-intervals-type input, .month-intervals-type select").prop("disabled", false);
                $("." + id + " input, ." + id + " select").prop("disabled", true);
            });







            /**
             * END::READY
             */
        });
    </script>
@endPushOnce
