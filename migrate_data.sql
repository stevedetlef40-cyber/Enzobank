-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- Migrate admins
INSERT INTO ibanking.admins (firstname, lastname, username, email, password, status, created_at, updated_at)
SELECT 
    SUBSTRING_INDEX(name, ' ', 1) AS firstname,
    IF(LOCATE(' ', name) > 0, SUBSTRING_INDEX(name, ' ', -1), '') AS lastname,
    username,
    email,
    password,
    1 AS status,
    NOW() AS created_at,
    NOW() AS updated_at
FROM ecoma.admins;

-- Migrate users
INSERT INTO ibanking.users (firstname, lastname, username, email, password, status, created_at, updated_at)
SELECT 
    firstname,
    lastname,
    username,
    email,
    password,
    1 AS status,
    NOW() AS created_at,
    NOW() AS updated_at
FROM ecoma.users;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Checksum validation
SELECT 'admins' AS table_name, COUNT(*) AS row_count FROM ecoma.admins
UNION ALL
SELECT 'admins' AS table_name, COUNT(*) AS row_count FROM ibanking.admins WHERE username IN (SELECT username FROM ecoma.admins);

SELECT 'users' AS table_name, COUNT(*) AS row_count FROM ecoma.users
UNION ALL
SELECT 'users' AS table_name, COUNT(*) AS row_count FROM ibanking.users WHERE username IN (SELECT username FROM ecoma.users);
