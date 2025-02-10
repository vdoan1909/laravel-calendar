var start_date = document.getElementById("event-start-date"),
    timepicker1 = document.getElementById("timepicker1"),
    timepicker2 = document.getElementById("timepicker2"),
    date_range = null,
    T_check = null;

function flatPickrInit() {
    var e = {
        enableTime: !0,
        noCalendar: !0
    };
    flatpickr(start_date, {
        enableTime: !1,
        mode: "range",
        minDate: "today",
        onChange: function (e, t, n) {
            1 < t.split("to").length ? document.getElementById("event-time").setAttribute("hidden", !0) : (document.getElementById("timepicker1").parentNode.classList.remove("d-none"), document.getElementById("timepicker1").classList.replace("d-none", "d-block"), document.getElementById("timepicker2").parentNode.classList.remove("d-none"), document.getElementById("timepicker2").classList.replace("d-none", "d-block"), document.getElementById("event-time").removeAttribute("hidden"))
        }
    });
    flatpickr(timepicker1, e), flatpickr(timepicker2, e)
}

function flatpicekrValueClear() {
    start_date.flatpickr().clear(), timepicker1.flatpickr().clear(), timepicker2.flatpickr().clear()
}

function eventClicked() {
    document.getElementById("form-event").classList.add("view-event"), document.getElementById("event-title").classList.replace("d-block", "d-none"), document.getElementById("event-start-date").parentNode.classList.add("d-none"), document.getElementById("event-start-date").classList.replace("d-block", "d-none"), document.getElementById("event-time").setAttribute("hidden", !0), document.getElementById("timepicker1").parentNode.classList.add("d-none"), document.getElementById("timepicker1").classList.replace("d-block", "d-none"), document.getElementById("timepicker2").parentNode.classList.add("d-none"), document.getElementById("timepicker2").classList.replace("d-block", "d-none"), document.getElementById("event-description").classList.replace("d-block", "d-none"), document.getElementById("event-start-date-tag").classList.replace("d-none", "d-block"), document.getElementById("event-timepicker1-tag").classList.replace("d-none", "d-block"), document.getElementById("event-timepicker2-tag").classList.replace("d-none", "d-block"), document.getElementById("event-description-tag").classList.replace("d-none", "d-block"), document.getElementById("btn-save-event").setAttribute("hidden", !0)
}

function editEvent(e) {
    var t = e.getAttribute("data-id");
    ("new-event" == t ? (document.getElementById("modal-title").innerHTML = "", document.getElementById("modal-title").innerHTML = "Add Event", document.getElementById("btn-save-event").innerHTML = "Add Event", eventTyped) : "edit-event" == t ? (e.innerHTML = "Cancel", e.setAttribute("data-id", "cancel-event"), document.getElementById("btn-save-event").innerHTML = "Update Event", e.removeAttribute("hidden"), eventTyped) : (e.innerHTML = "Edit", e.setAttribute("data-id", "edit-event"), eventClicked))()
}

function eventTyped() {
    document.getElementById("form-event").classList.remove("view-event"), document.getElementById("event-title").classList.replace("d-none", "d-block"), document.getElementById("event-start-date").parentNode.classList.remove("d-none"), document.getElementById("event-start-date").classList.replace("d-none", "d-block"), document.getElementById("timepicker1").parentNode.classList.remove("d-none"), document.getElementById("timepicker1").classList.replace("d-none", "d-block"), document.getElementById("timepicker2").parentNode.classList.remove("d-none"), document.getElementById("timepicker2").classList.replace("d-none", "d-block"), document.getElementById("event-description").classList.replace("d-none", "d-block"), document.getElementById("event-start-date-tag").classList.replace("d-block", "d-none"), document.getElementById("event-timepicker1-tag").classList.replace("d-block", "d-none"), document.getElementById("event-timepicker2-tag").classList.replace("d-block", "d-none"), document.getElementById("event-description-tag").classList.replace("d-block", "d-none"), document.getElementById("btn-save-event").removeAttribute("hidden")
}

function upcomingEvent(e) {
    e.sort(function (e, t) {
        return new Date(e.start) - new Date(t.start)
    }), document.getElementById("upcoming-event-list").innerHTML = null, Array.from(e).forEach(function (e) {
        var t = e.title,
            n = (i = e.end ? (endUpdatedDay = new Date(e.end)).setDate(endUpdatedDay.getDate() - 1) : i) || void 0;
        n = "Invalid Date" == n || null == n ? null : (a = new Date(n).toLocaleDateString("en", {
            year: "numeric",
            month: "numeric",
            day: "numeric"
        }), new Date(a).toLocaleDateString("en-GB", {
            day: "numeric",
            month: "short",
            year: "numeric"
        }).split(" ").join(" "));
        (e.start ? str_dt(e.start) : null) === (i ? str_dt(i) : null) && (n = null);
        var a = e.start,
            d = (a = "Invalid Date" === a || void 0 === a ? null : (d = new Date(a).toLocaleDateString("en", {
                year: "numeric",
                month: "numeric",
                day: "numeric"
            }), new Date(d).toLocaleDateString("en-GB", {
                day: "numeric",
                month: "short",
                year: "numeric"
            }).split(" ").join(" ")), n ? " to " + n : ""),
            l = e.description || "",
            e = tConvert(getTime(e.start)),
            i = (e == (i = tConvert(getTime(i))) && (e = "Full day event", i = null), i ? " to " + i : "");
        u_event = "<div class='card mb-3'>                        <div class='card-body'>                            <div class='d-flex mb-3'>                                <div class='flex-grow-1'><i class='mdi mdi-checkbox-blank-circle me-2 text-" + n[1] + "'></i><span class='fw-medium'>" + a + d + " </span></div>                                <div class='flex-shrink-0'><small class='badge bg-primary-subtle text-primary ms-auto'>" + e + i + "</small></div>                            </div>                            <h6 class='card-title fs-16'> " + t + "</h6>                            <p class='text-muted text-truncate-two-lines mb-0'> " + l + "</p>                        </div>                    </div>", document.getElementById("upcoming-event-list").innerHTML += u_event
    })
}

function getTime(e) {
    if (null != (e = new Date(e)).getHours()) return e.getHours() + ":" + (e.getMinutes() ? e.getMinutes() : 0)
}

function tConvert(e) {
    var e = e.split(":"),
        t = e[0],
        e = e[1],
        n = 12 <= t ? "PM" : "AM";
    return (t = (t %= 12) || 12) + ":" + (e < 10 ? "0" + e : e) + " " + n
}

document.addEventListener("DOMContentLoaded", function () {
    flatPickrInit();

    var g = new bootstrap.Modal(document.getElementById("event-modal"), {
        keyboard: !1
    });

    var l = document.getElementById("modal-title"),
        i = document.getElementById("form-event"),
        v = null,
        y = [];

    var calendarEl = document.getElementById("calendar");

    // du lieu cua gv
    if (role === lecturerRole) {
        fetch(scheduleListUrl)
            .then(response => response.json())
            .then(data => {
                // console.log(data);
                y = data.schedules.map(event => ({
                    id: event.id,
                    title: event.title,
                    day: event.start,
                    start: `${event.start}T${event.startTime}`,
                    end: `${event.start}T${event.endTime}`,
                    extendedProps: {
                        description: event.description || "",
                    },
                }));

                b.addEventSource(y);
                console.log(y);
            })
            .catch(error => console.error("Lỗi tải sự kiện:", error));
    } else {
        // du lieuj cua hs
        fetch(scheduleListForStudentUrl)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                y = data.schedules.map(event => ({
                    id: event.id,
                    title: event.title,
                    day: event.start,
                    start: `${event.start}T${event.startTime}`,
                    end: `${event.start}T${event.endTime}`,
                    extendedProps: {
                        description: event.description || "",
                    },
                }));

                b.addEventSource(y);
                console.log(y);
            })
            .catch(error => console.error("Lỗi tải sự kiện:", error));
    }


    var b = new FullCalendar.Calendar(calendarEl, {
        timeZone: "local",
        editable: true,
        droppable: true,
        selectable: true,
        navLinks: true,
        initialView: "multiMonthYear",
        themeSystem: "bootstrap",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "multiMonthYear,dayGridMonth,timeGridWeek,timeGridDay,listMonth"
        },

        dateClick: function (info) {
            openEventModal(info);
        },

        eventClick: function (info) {
            if (role === lecturerRole) {
                openEditModal(info); // gv mo
            } else {
                openJoinModal(info); // vs mo
            }
        },

        eventDrop: function (info) {
            updateEvent(info.event);
        },

        eventResize: function (info) {
            updateEvent(info.event);
        },

        events: y,
    });

    b.render();

    function openEventModal(info) {
        document.getElementById("form-event").reset();
        document.getElementById("btn-delete-event").setAttribute("hidden", true);
        g.show();
        i.classList.remove("was-validated");
        v = null;
        l.innerText = "Thêm sự kiện";
    }

    function openEditModal(info) {
        v = info.event;
        console.log(v.start, v.end);

        g.show();
        l.innerText = "Chỉnh sửa sự kiện";

        document.getElementById("event-title").value = v.title || "";
        document.getElementById("event-start-date").value = formatDateToLocal(v.start);

        if (v.start) {
            document.getElementById("timepicker1").value = formatTimeToLocal(v.start);
        }
        if (v.end) {
            document.getElementById("timepicker2").value = formatTimeToLocal(v.end);
        }

        if (v.extendedProps) {
            document.getElementById("event-description").value = v.extendedProps.description || "";
        }
    }

    function openJoinModal(info) {
        let v = info.event;
        let modal = document.getElementById("joinModal");
        let joinButton = document.getElementById("joinButton");
        let overlay = document.getElementById("modalOverlay");

        document.getElementById("modal-title").innerText = v.title;
        joinButton.setAttribute("data-class-id", v.id);

        modal.style.display = "block";
        overlay.style.display = "block";
    }

    function closeJoinModal() {
        document.getElementById("joinModal").style.display = "none";
        document.getElementById("modalOverlay").style.display = "none";
    }

    document.getElementById("modalOverlay").addEventListener("click", closeJoinModal);
    document.getElementById("closeButton").addEventListener("click", closeJoinModal);

    document.getElementById("joinButton").addEventListener("click", function () {
        let scheduleId = this.getAttribute("data-class-id");
        // console.log(scheduleId);

        fetch(joinSchedule, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({ id: scheduleId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var toast = new bootstrap.Toast(document.getElementById("success-toast"));
                    toast.show();
                    closeJoinModal();
                }
            })
            .catch(error => console.error("Lỗi:", error));
    });

    function saveEvent(eventData) {
        // console.log('data trc khi them', eventData);
        fetch(scheduleStoreUrl, {
            method: "POST",
            body: JSON.stringify(eventData),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // console.log('data từ server:', data);
                    data.schedules.forEach(event => {
                        y.push({
                            title: event.title,
                            start: event.start,
                            startTime: event.startTime,
                            endTime: event.endTime,
                            description: event.description,
                        });

                        b.addEvent(event);
                    });

                    g.hide();

                    var toast = new bootstrap.Toast(document.getElementById("success-toast"));
                    toast.show();
                } else {
                    alert("Lỗi khi lưu sự kiện!");
                }
            })
            .catch(error => console.error("Lỗi:", error));
    }

    function updateEvent(event) {
        let scheduleUpdateUrl = updateScheduleUrl.replace(':id', event.id);

        fetch(scheduleUpdateUrl, {
            method: "POST",
            body: JSON.stringify({
                _method: "PATCH",
                title: event.title,
                start: event.start,
                startTime: event.startTime,
                endTime: event.endTime,
                description: event.description
            }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert("Lỗi khi cập nhật sự kiện!");
                } else {
                    g.hide();

                    var toast = new bootstrap.Toast(document.getElementById("success-toast"));
                    toast.show();
                }
            })
            .catch(error => console.error("Lỗi:", error));
    }


    i.addEventListener("submit", function (e) {
        e.preventDefault();
        var title = document.getElementById("event-title").value;
        var start = document.getElementById("event-start-date").value;
        var startTime = document.getElementById("timepicker1").value;
        var endTime = document.getElementById("timepicker2").value;
        var description = document.getElementById("event-description").value;

        // console.log(startTime, endTime);
        if (!title || !start || !startTime || !endTime) {
            alert("Vui lòng nhập đủ thông tin!");
            return;
        }

        var newEvent = {
            title: title,
            start: start,
            startTime: startTime,
            endTime: endTime,
            description: description ?? null,
        };

        if (v) {
            newEvent.id = v.id;
            updateEvent(newEvent);
        } else {
            saveEvent(newEvent);
        }
    });

    document.getElementById("btn-delete-event").addEventListener("click", function () {
        if (v) {
            fetch("/delete", {
                method: "POST",
                body: JSON.stringify({ id: v.id }),
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        v.remove();
                        g.hide();
                    } else {
                        alert("Lỗi khi xóa sự kiện!");
                    }
                })
                .catch(error => console.error("Lỗi:", error));
        }
    });

    document.getElementById("btn-new-event").addEventListener("click", function () {
        openEventModal();
    });

    function formatDateToLocal(dateString) {
        const date = new Date(dateString);
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        return date.toISOString().split("T")[0];
    }

    function formatTimeToLocal(dateTime) {
        if (typeof dateTime === "string") {
            return dateTime.split("T")[1].slice(0, 5);
        } else if (dateTime instanceof Date) {
            return dateTime.toTimeString().slice(0, 5);
        }
        return "";
    }

});

var str_dt = function (e) {
    var e = new Date(e),
        t = "" + ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][e.getMonth()],
        n = "" + e.getDate(),
        e = e.getFullYear();
    return t.length < 2 && (t = "0" + t), [(n = n.length < 2 ? "0" + n : n) + " " + t, e].join(",")
};