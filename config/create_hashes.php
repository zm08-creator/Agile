<?php

echo "patient: " . password_hash("patient", PASSWORD_DEFAULT) . "<br>";
echo "professional: " . password_hash("professional", PASSWORD_DEFAULT) . "<br>";
echo "admin: " . password_hash("admin", PASSWORD_DEFAULT) . "<br>";

?>