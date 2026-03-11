<?php
// This file is only for generating a password hash once.
// After using it, you can delete this file.

echo password_hash("Test123!", PASSWORD_DEFAULT);