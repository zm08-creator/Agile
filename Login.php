<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Health Matters</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="login-container">
            <h2>Login</h2>

            <label for="role">Select Role</label>
            <select id="role">
                <option value="">-- Choose Role --</option>
                <option value="practitioner">Practitioner</option>
                <option value="referring_manager">Referring Manager</option>
                <option value="patient">Patient</option>
                <option value="admin">Admin</option>
            </select>

            <button onclick="showLogin()">Next</button>

            <div class="credentials" id="credentials">
                <input type="text" placeholder="Username" id="username">
                <input type="password" placeholder="Password" id="password">
                <button onclick="login()">Login</button>
            </div>
        </div>

        <script>
            function showLogin() {
                const role = document.getElementById("role").value;
                if (role === "") {
                    alert("Please select a role first.");
                    return;
                }
                document.getElementById("credentials").style.display = "block";
            }

            function login() {
                const role = document.getElementById("role").value;
                const username = document.getElementById("username").value;
                const password = document.getElementById("password").value;

                if (!username || !password) {
                    alert("Please enter username and password.");
                    return;
                }

                alert(`Logging in as ${role.replace("_", " ")}\nUsername: ${username}`);
                // Later: send data to backend

                // Redirect to MakeAppt1.html after successful login
                window.location.href = "MakeAppt1.html";
            }
        </script>
    </body>

</html>