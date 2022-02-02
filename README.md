# Error Report 2 (Server)

An error reporting service for SPFW.
Clients send error and exception logs to an API.

## Requirements 

* SPFW >=2.0.0,
* PHP >=7.4 and
* MySQL >= 5.7 (or Maria DB >= 10.1).

__Note:__
The client is available for SPFW version 2.x.x and 1.x.x (separate versions for SPFW 1 and 2).
The serverside requires SPFW 1.3.0 for ER2 Version 2.0.x or SPFW 2.x.x for ER2 Version 2.1.x and newer.

## Installation

1. Include this git-project as submodule in your modules-directory,
2. execute the sql-batch file _er2.sql_ in your database,
3. extend your config with ErrorReport2ServerConfigTrait,
4. add a ErrorReport2ServerConfig to your global config,
5. add a route for the listener to your routing repository and
6. configure ER2 Server (e.g. set up access token).

For step 3, a customizable config must exist.
If it does not exist yet, create a new class for your application config.
Extend SPFW\config\Config class.
Extend class by using _use_ keyword for ErrorReport2ServerConfigTrait.
Then replace SPFW\config\Config class by new application config class in _config.php_ file.

It is recommended to allow only secure connections.
This must be set in your web servers configuration.

__Example for 1.:__
```
cd src/modules
git submodule add URL_TO_REPOSITORY
```

__Example for 5.:__

```
Routing::addRoute((new StaticRoute(\ErrorReport2\ErrorReport2Server::class, 'listener'))
		->setFile('/er2_listener'));
```

__Example for 6.:__

```
$config->setER2Config((new \ErrorReport2\ErrorReport2ServerConfig())->addToken('3915753d8765a0'));
```

## Update
When ER2 is included as git submodule, update the submodule.
Some updates come with database structure changes.
The database updates (sql batch files) can be found in db-directory.
They must be executed in the database manually.

## Configuration

### Required configuration

Every connection requires an access token.
It is recommended to create an individual token for each service you want to connect.

### Optional configuration

There are no optional parameter yet.

## Future development

A parallel project will be started to offer a web-gui for viewing and analyzing ER2 reports.
