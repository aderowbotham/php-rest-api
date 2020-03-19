## PHP REST API

Simple lightweight REST API for PHP. Built on CI3 with [chriskacerguis/codeigniter-restserver](https://github.com/chriskacerguis/codeigniter-restserver)

**NOTE** the vendor files for the `codeigniter-restserver` library have *not* been ignored inside the vendor directory because there was a problem getting the latest versioned **release** of that to work. So it was installed specifying version **dev-master** in Composer (see [issue here](https://github.com/chriskacerguis/codeigniter-restserver/issues/1065)).

Given that dev-master is not necessarily a stable version those files are included in this package.

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
