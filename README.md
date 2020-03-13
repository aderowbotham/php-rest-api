## PHP REST API

Simple lightweight REST API for PHP

The default API root is /1.0 - see config/routes


### Environment setup

Assumes that some information is set in environment variables as follows:

    SetEnv APPLICATION_ENV "development"
    SetEnv CI_ENCRYPTION_KEY "some-random-encryption-key-CHANGEME"
    SetEnv DATABASE "mydatabase"
    SetEnv DATABASE_USERNAME "my_db_user"
    SetEnv DATABASE_PASSWORD "my_db_pass"



### Migrations

In the **development** environment run migrations by going to at https://your-dev-domain.local/migrate

In the **testing** or **production** environments you can only access migrations using the CLI interface as below. Pass the environment as the argument after the controller name.

e.g.

    cd /var/www/my_site_directory/deploy/public
    php index.php migrate testing`
