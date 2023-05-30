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

### SPFW compatibility table

|SPFW version|ER2 version|note|
|---|---|---|
|1.0.0-1.x.x|2.0.x|Still supported :-)|
|2.0.0-2.0.x|2.1.x|Still supported :-)|
|>2.0.x     |2.2.x|Still supported :-)|

SPFW 2.1.0 dropped support for legacy global configuration.
The usage of environments is required now.
If you already use environments, install ER2 server in version 2.2.0 or newer.

Older releases of ER2 can be found in the release-section on GitHub.

## Installation

1. Include this git-project as submodule in your modules-directory,
2. execute the sql-batch file _er2.sql_ in your database,
3. extend your environment (or global config, when you are using older version of SPFW) with ErrorReport2ServerConfigTrait,
4. add a ErrorReport2ServerConfig to your environment (or global config),
5. add a route for the listener to your routing repository and
6. configure ER2 Server (e.g. set up access token).

It is recommended to allow only secure connections.
This must be set in your web servers configuration.

### SPFW using environments (step 3)
If not existing yet, create a customizable environment for your application first.
To do this, extend SPFW\system\config\Environment class.
Don't forget to update the used environment in the `src/htdocs/index.php` file.

Add `ErrorReport2ServerConfigTrait` as a trait to your environment.

### SPFW with legacy configuration (step 3)
A customizable config must exist.
If it does not exist yet, create a new class for your application config.
To do this,extend SPFW\config\Config class.
Then replace SPFW\config\Config class by new application config class in _config.php_ file.

Add `ErrorReport2ServerConfigTrait` as a trait to your config class.

### Examples

__Example for step 1:__
```
cd src/modules
git submodule add URL_TO_REPOSITORY
```

__Example for step 5:__

```
Routing::addRoute((new StaticRoute(\ErrorReport2\ErrorReport2Server::class, 'listener'))
		->setFile('/er2_listener'));
```

__Example for step 6:__

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
An access token is a non-empty-string.
It is sent by the client within the json-structure.

It is recommended to create an individual token for each service you want to connect.

### Optional configuration

There are no optional parameter yet.

## Future development

If you have any requests, please open a ticket on GitHub!

In planning phase:
* Different methods for authentication will be implemented.

## Related projects

_ER2 visualizer_ offers a web-gui for viewing and analyzing ER2 reports.
It is in early stage of development.
