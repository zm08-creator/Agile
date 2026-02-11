<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Health Matters</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container">
            <h1>Health Matters</h1>

            <form action="MakeAppt2.html" method="get">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <span>Preferred Location:</span>
                    <label>
                        <input type="radio" name="location" value="preston" required>
                        Preston
                    </label>
                    <label>
                        <input type="radio" name="location" value="burnley">
                        Burnley
                    </label>
                    <label>
                        <input type="radio" name="location" value="west-lakes">
                        West Lakes
                    </label>
                </div>

                <div class="form-group">
                    <label for="discussion">What would you like to discuss?</label>
                    <textarea id="discussion" name="discussion" rows="4" required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit">Next</button>
                </div>

            </form>
        </div>
        <script>
            document.querySelector('form').addEventListener('submit', function (e) {
                sessionStorage.setItem('userName', document.getElementById('name').value);
                sessionStorage.setItem('userDOB', document.getElementById('dob').value);
                sessionStorage.setItem('userAddress', document.getElementById('address').value);
                sessionStorage.setItem('userLocation', document.querySelector('input[name="location"]:checked').value);
            });
        </script>


    </body>

</html>