## PHP REST API

Simple lightweight REST API for PHP. Built on CI3 with [chriskacerguis/codeigniter-restserver](https://github.com/chriskacerguis/codeigniter-restserver)

**NOTE** the vendor files for the `codeigniter-restserver` library have *not* been ignored inside the vendor directory because there was a problem getting the latest versioned **release** of that to work. So it was installed specifying version **dev-master** in Composer (see [issue here](https://github.com/chriskacerguis/codeigniter-restserver/issues/1065)).

Given that dev-master is not necessarily a stable version those files are included in this package

The default API root is /1.0 - see config/routes. Includes examples of GET and POST and a GET route that requires Authorization.

### Environment setup

Assumes that some information is set in environment variables as follows:

    SetEnv APPLICATION_ENV "development"
    SetEnv DATABASE "mydatabase"
    SetEnv DATABASE_USERNAME "mydatabaseuser"
    SetEnv DATABASE_PASSWORD "mydatabasepassword"


### Migrations

Start by creating a database and then import the provided SQL file 'IMPORT FIRST.sql' to create the migrations table. Next you can run migrations as below. Included is a demo migration to create a users table which is then demonstrated in the example authenticated query `GET:/1.0/private`.

In the **development** environment run migrations by going to at https://your-dev-domain.local/migrate

In the **testing** or **production** environments you can only access migrations using the CLI interface as below. Pass the environment as the argument after the controller name.

e.g.

    cd /var/www/my_site_directory/deploy/public
    php index.php migrate testing`


**Important note:** The environment in which the CLI command is being run will need to have the database credentials loaded.

#### Setting environment vars on macOS (temporarily)

If you need to use CLI mode on macOS you can temporarily set environment vars as follows:

    export DATABASE="mydatabase"
    export DATABASE_USERNAME="mydatabaseuser"
    export DATABASE_PASSWORD="mydatabasepassword"

There are many guides for installing them more permanently, such as [this one](https://medium.com/@youngstone89/setting-up-environment-variables-in-mac-os-28e5941c771c)


### Creating admin users

You can create an admin user using the command line interface (CLI). Pass the username and new password. You could then give this user the ability to create more users via your own mechanism.

    php index.php mkadminuser development test@example.com ADMIN_USER_SECRET_KEY



### Test routes + Controllers

There are a number of routes set up to test with. They are as follows:

##### Public resources

- `GET:/1.0` – API v1.0 home
- `GET:/1.0/status` – status endpoint. Because we autoload the database library this also verifies that the database is contactable
- `GET:/1.0/products` – Example of GET endpoint (list of items)
- `GET:/1.0/products/id/(:id)` – Get individual item from list (try 1 or 2)
- `POST:/1.0/products` – Post item to a list (example only, nothing gets written)

##### Authenticated resource

- `GET:/1.0/private-content` – example of authenticated resource. The controller `v1/Privatecontent` specifies minimum permissions required is `USER_ADMIN`. You need to provide auth headers to access any non-public resource. Run and then refer to the example migration to find the header values to use with the demo user account. Important! Create your own user, do not use the demo password!

**Headers for authantication**

Authenticated requests require two HTTP headers:

- `access_key` – Must match `api_users.access_key` for one user in the database. This is a 64 character SHA256 hash of their username with the fixed salt `$config['public_key_salt']` set in config.php. This is **not** cryptographically secure and is just a user ID. The salt only serves to prevent a casual attacker working out a user's access key using only their username (email address). To create an access key use the helper method `make_access_key()` in the **api_helper**.

- `secret_key` – This is the user's secret API key. It is recommended to require a very long string similar in length to the `access_key`. A hash of the secret key is stored, and that hash is generated using the native [password_hash()](https://www.php.net/manual/en/function.password-hash.php) function using the BCRYPT algorithm. To create a password hash use the function `make_secret_key_hash()` in the **api_helper**. For demo / testing purposes use the password in `Migration_Create_users_table`. First the user is looked up by access_key. In order to pass authentication the user must exist, and then we compare the provided password to the stored hashed password (in field `secret_key_hash`) using the native [password_verify()](https://www.php.net/manual/en/function.password-verify.php) function.


**Failed attempts**

Failed attempts are recorded against the user and limited by the config value `max_failed_auth_attempts`. Once the maximum number of failed attempts has been reached it is not possible to authenticate as that user, even using the correct password.

A successful login resets the recorded number of failed logins to 0 providing it had not reached the limit. There is no mechanism included for resetting failed logins to zero once it has reached the limit.
