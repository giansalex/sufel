<?php
// Database connection
putenv('SUFEL_DB_HOST=127.0.0.1');
putenv('SUFEL_DB_DATABASE=sufel');
putenv('SUFEL_DB_USER=root');
putenv('SUFEL_DB_PASS=');

// Jwt and Admin Token
putenv('SUFEL_JWT_KEY=yYa3Nmalk1a56fhA');
putenv('SUFEL_ADMIN=jsAkl34Oa2Tyu');

require 'sufel.phar';