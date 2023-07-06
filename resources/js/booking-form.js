import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('booking-form');
    const dateInput = document.getElementById('date-f-booking');
    const name = document.getElementById('name-booking');
    const clearDateButton = document.getElementById('clear-date-button');
    const timeSlotContainer = document.getElementById('time-slot-container');
    const selectTimeSlotOption = document.createElement('option');
    selectTimeSlotOption.value = '';
    selectTimeSlotOption.text = 'Select appointment time';
    let timeSlotSelect = null;
    const bookingTimeSlotLabel = document.getElementById('booking-time-slot-label');

    clearDateButton.addEventListener('click', function () {
        dateInput.value = '';
        removeTimeSlotSelect();
        validateForm();
    });

    const consultantInput = document.getElementById('consultant');

    if (consultantInput) {
        consultantInput.addEventListener('change', function () {
            fetchAppointments();
        });
    }

    if (dateInput) {
        const currentDate = new Date();
        flatpickr(dateInput, {
            enableTime: false,
            dateFormat: 'Y-m-d',
            minDate: currentDate,
            disable: [
                function(date) {
                    return (date.getDay() === 6 || date.getDay() === 0);
                }
            ],
            onChange: function (selectedDates, dateStr) {
                fetchAppointments();
            },
        });
    }

    form.addEventListener('submit', function (event) {
        if (!isFormValid()) {
            event.preventDefault();
        }
    });

    const requiredInputs = [consultantInput, dateInput, name];

    for (let i = 0; i < requiredInputs.length; i++) {
        requiredInputs[i].addEventListener('change', validateForm);
    }
    name.addEventListener('input', validateForm);

    function validateForm() {
        const isValid = isFormValid();
        const isTimeSlotSelected = timeSlotSelect && timeSlotSelect.value !== '';
        if (isValid && isTimeSlotSelected) {
            document.getElementById('submit-button-book')
                .removeAttribute('disabled');
        } else {
            document.getElementById('submit-button-book')
                .setAttribute('disabled', 'disabled');
        }
    }

    function isFormValid() {
        let isValid = true;

        for (let i = 0; i < requiredInputs.length; i++) {
            const input = requiredInputs[i];
            if (input.value === '') {
                isValid = false;
            }
        }

        return isValid;
    }

    // Fetch appointments for the selected consultant and date
    function fetchAppointments() {
        const consultantId = consultantInput.value;
        const date = dateInput.value;

        if (consultantId && date) {
            const bookingContainer = document.getElementById('bookingContainer');
            const loader = document.getElementById('loader');

            loader.classList.remove('hidden');
            bookingContainer.style.display = 'none';

            axios
                .get(`/appointments/${consultantId}/${date}`)
                .then(function (response) {
                    const appointments = response.data;
                    populateTimeSlots(appointments);
                    loader.classList.add('hidden');
                    bookingContainer.style.display = 'block';
                })
                .catch(function (error) {
                    console.error(error);
                    loader.classList.add('hidden');
                    bookingContainer.style.display = 'block';
                });
        } else {
            removeTimeSlotSelect();
        }
    }

    function populateTimeSlots(appointments) {
        removeTimeSlotSelect();

        timeSlotSelect = document.createElement('select');
        timeSlotSelect.id = 'time-slot';
        timeSlotSelect.name = 'time_slot';
        timeSlotSelect.classList.add('w-full', 'px-3', 'py-2', 'border', 'rounded-md');
        timeSlotSelect.addEventListener('change', validateForm);

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Select appointment time';
        timeSlotSelect.appendChild(defaultOption);

        if (appointments.length > 0) {
            for (let i = 0; i < appointments.length; i++) {
                const appointment = appointments[i];
                const option = document.createElement('option');
                option.value = JSON.stringify(appointment);
                option.text = `${appointment.start_time} - ${appointment.end_time}`;
                timeSlotSelect.appendChild(option);
            }
        }

        timeSlotContainer.innerHTML = '';
        timeSlotContainer.appendChild(timeSlotSelect);

        if (timeSlotSelect && timeSlotSelect.options.length >= 1) {
            bookingTimeSlotLabel.style.display = 'block';
        } else {
            bookingTimeSlotLabel.style.display = 'none';
        }
    }

    function removeTimeSlotSelect() {
        if (timeSlotSelect && timeSlotSelect.parentNode) {
            timeSlotSelect.parentNode.removeChild(timeSlotSelect);
            timeSlotSelect = null;
            bookingTimeSlotLabel.style.display = 'none';
        }
    }
});
