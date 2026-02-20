# Private Data

This directory is for local development runtime data only.
In production, configure `APP_PRIVATE_DIR`/`USERS_FILE_PATH` so user files live outside the Apache web root.
`users.json`, `processed-events.json`, and `login-attempts.json` are generated at runtime and gitignored.
