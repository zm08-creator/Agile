<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Health Matters - Appointment Confirmed</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container">
            <h1>Health Matters</h1>

            <div class="confirmation">
                <h2>Appointment Confirmed</h2>

                <div class="details-grid">
                    <div class="detail-item">
                        <strong>Name:</strong>
                        <span id="user-name"></span>
                    </div>

                    <div class="detail-item">
                        <strong>Location:</strong>
                        <span id="user-location"></span>
                    </div>


                    <div class="detail-item">
                        <strong>Appointment Date:</strong>
                        <span id="appt-date"></span>
                    </div>

                    <div class="detail-item">
                        <strong>Time Slot:</strong>
                        <span id="appt-time"></span>
                    </div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <strong>Reference Number:</strong>
                    <span id="ref-number"></span>
                </div>


                <div class="form-actions" style="margin-top: 30px;">
                    <a href="MakeAppt1.html" class="btn-back">Book Another Appointment</a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                console.log('Script running');

                // Get data from sessionStorage
                const name = sessionStorage.getItem('userName') || 'Not provided';
                const date = sessionStorage.getItem('apptDate') || 'Not provided';
                const time = sessionStorage.getItem('apptTime') || 'Not provided';
                const location = sessionStorage.getItem('userLocation') || 'Not provided';

                document.getElementById('user-name').textContent = name;
                document.getElementById('appt-date').textContent = date;
                document.getElementById('appt-time').textContent = time;
                document.getElementById('user-location').textContent =
                    location.replace(/\b\w/g, l => l.toUpperCase());

                // Generate reference number
                const now = new Date();
                const yearShort = now.getFullYear().toString().slice(-2);
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const randomLetters = 'ABCDEFGHJKMNPQRSTUVWXYZ';

                const refNum = 'HM' + yearShort + month + day +
                    randomLetters[Math.floor(Math.random() * 21)] +
                    randomLetters[Math.floor(Math.random() * 21)] +
                    Math.floor(Math.random() * 900 + 100);

                console.log('Reference:', refNum);
                document.getElementById('ref-number').textContent = refNum;
            });
        </script>




    </body>

</html>