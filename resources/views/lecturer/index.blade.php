@extends('lecturer.layouts.master')

@section('styles')
<style>
    #modalOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    #joinModal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        width: 300px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        z-index: 1000;
        text-align: center;
    }

    #joinButton {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    #joinButton:hover {
        background: #0056b3;
    }

    #closeButton {
        background: #ccc;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    #closeButton:hover {
        background: #999;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Calendar</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="row">

            <div class="col-xl-3">
                @if (auth()->user()->role == App\Enums\RoleEnum::LECTURER->value)
                    <div class="card card-h-100">
                        <div class="card-body">
                            <button class="btn btn-primary w-100" id="btn-new-event">
                                <i class="mdi mdi-plus"></i>
                                Create New Event
                            </button>

                            <div id="external-events">

                            </div>
                        </div>
                    </div>
                @endif
                <div>
                    <h5 class="mb-1">Upcoming Events</h5>
                    <p class="text-muted">Don't miss scheduled events</p>
                    <div class="pe-2 me-n1 mb-3" data-simplebar style="height: 580px">
                        <div id="upcoming-event-list"></div>
                    </div>
                </div>
            </div> <!-- end col-->

            <div class="col-xl-9">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
        <!--end row-->

        <div style='clear:both'></div>

        <!-- Add New Event MODAL -->
        <div class="modal fade" id="event-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-info-subtle">
                        <h5 class="modal-title" id="modal-title">Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form class="needs-validation" name="event-form" id="form-event">
                            <div class="text-end">
                                <a href="#" class="btn btn-sm btn-soft-primary" id="edit-event-btn" data-id="edit-event"
                                    onclick="editEvent(this)" role="button">Edit</a>
                            </div>
                            <div class="event-details">
                                <div class="d-flex mb-2">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-calendar-event-line text-muted fs-16"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="d-block fw-semibold mb-0" id="event-start-date-tag"></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-time-line text-muted fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="d-block fw-semibold mb-0"><span id="event-timepicker1-tag"></span> -
                                            <span id="event-timepicker2-tag"></span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-discuss-line text-muted fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="d-block text-muted mb-0" id="event-description-tag"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row event-form">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Event Name</label>
                                        <input class="form-control" placeholder="Enter event name" type="text"
                                            name="title" id="event-title" />
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label>Event Date</label>
                                        <div class="input-group">
                                            <input type="text" id="event-start-date"
                                                class="form-control flatpickr flatpickr-input"
                                                placeholder="Select date">
                                            <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-12" id="event-time">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">Start Time</label>
                                                <div class="input-group">
                                                    <input id="timepicker1" type="text"
                                                        class="form-control flatpickr flatpickr-input"
                                                        placeholder="Select start time">
                                                    <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">End Time</label>
                                                <div class="input-group">
                                                    <input id="timepicker2" type="text"
                                                        class="form-control flatpickr flatpickr-input"
                                                        placeholder="Select end time">
                                                    <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" id="event-description"
                                            placeholder="Enter a description" rows="3" spellcheck="false"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-soft-danger" id="btn-delete-event"><i
                                        class="ri-close-line align-bottom"></i> Delete</button>
                                <button type="submit" class="btn btn-success" id="btn-save-event">Add Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalOverlay"></div>

<div id="joinModal">
    <h2 id="modal-title"></h2>
    <button id="joinButton">Join</button>
    <button id="closeButton">Close</button>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="success-toast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Thành công</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Thao tác thành công!
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    var scheduleListUrl = "{{ route('lecturer.schedule') }}";
    var scheduleListForStudentUrl = "{{ route('lecturer.scheduleListForStudent') }}";
    var scheduleStoreUrl = "{{ route('lecturer.schedule.store') }}";
    var updateScheduleUrl = "{{ route('lecturer.schedule.update', ':id') }}";
    var role = "{{ Auth::user()->role }}";
    var lecturerRole = "{{ App\Enums\RoleEnum::LECTURER->value }}";

    var joinSchedule = "{{ route('lecturer.join.schedule') }}";
</script>

@endsection

@section('script-libs')
<!-- calendar min js -->
<script src="{{ asset('theme/admin/assets/libs/fullcalendar/index.global.min.js') }}"></script>

<!-- Calendar init -->
<script src="{{ asset('theme/admin/assets/js/pages/calendar-month-grid.init.js') }}"></script>
@endsection