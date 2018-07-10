# Dockerizing FlightPath

Running FlightPath (an open-source, web-based academic advising system) in docker containers.

## Getting Started

These instructions will get you a copy of the official FlightPath 4.x core release up and running on your local machine for development and testing purposes.

### Prerequisites

You need to install Docker on your machine. You can follow [Docker Documentation](https://docs.docker.com/install/) for installing Docker according to your platform.

### Installing

Download/unzip or clone the repository to create a *local* copy on your development environment.

## Using the containers

Open the terminal. Then change directory to where you downloaded or cloned the repository.

```
cd /path/to/repo
```

All of the following commands must be run in that directory.

### Running the containers

Enter the following command to build the images if they do not exist and start the containers:

```
docker-compose up -d
```

Two containers will be fired up. The web server container which is based on the Docker Image for Apache with PHP 5.6. Also the database container which requires MySQL 5.6 server Docker image.

To stop the containers run the following command:

```
docker-compose stop
```

To start the containers again run the following command:

```
docker-compose start
```

### Accessing FlightPath

Open the following URL in your browser:

```
http://localhost:8080/
```

Make sure that the port 8080 is unused on your machine.

If you are running FlightPath for the first time you will see the installation page. Use the following MySQL credentials:

```
DB_HOST: mysql
DB_DATABASE: demo
DB_USERNAME: demo
DB_PASSWORD: demo
```
The usual 3306 port will be used to internally connect the two containers. You can externally connect to the MySQL server on ip 127.0.0.1 and port 13306 from the host.

### Backing up the database

Enter the following command to dump the database out of the running mysql container into this file backup/dump.sql:

```
docker exec -it flightpath_mysql_1 /home/backup.sh
```

### Restore the database

First, destroy the containers. You will lose the database, so make sure to back it up before running the command:

```
docker-compose down
```

Then fire up the containers:

```
docker-compose up -d
```

The dump in this file backup/dump.sql will be imported automatically when you fire up the containers.

### Reinstalling a clean FlightPath

First, destroy the containers by running the command:

```
docker-compose down
```

Then delete the settings file and the database dump file:

```
public/custom/settings.php
backup/dump.sql
```

Then fire up the containers:

```
docker-compose up -d
```

## Built With

* [FlightPath Version 4.8.2](http://getflightpath.com/project/flightpath) - This is the official FlightPath core release.
* [Docker](https://www.docker.com/) - Docker is an open platform for developers and sysadmins to build, ship, and run distributed applications.

## License

This project is licensed under the GPLv3+ License - see the [LICENSE](LICENSE) file for details
