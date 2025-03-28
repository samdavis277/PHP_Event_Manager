# PHP Event Management System (CRUD)
This is a basic Event Management System built using HTML, PHP, and a plain text file (`events.txt`) to perform CRUD (Create, Read, Update, Delete) operations. The system allows users to manage a list of events, which are stored in a plain text file and displayed on an index page. The application also provides forms for adding, editing, and deleting events, all styled using the [Tailwind CSS framework](https://tailwindcss.com/).

## Project Structure

The project consists of the following files:

1. `events.txt` - A plain text file that stores event data in the format:
   
   EventID|EventName|Description|StartDateTime|EndDateTime
   
    Each line in this file represents a single event. The `|` character is used to separate the values, and each event's start and end date/time must be formatted correctly.

3. `index.php` - The main page that lists events grouped by their start date. It also displays options for updating and deleting events.

4. `new-event.php` - The page that provides a form to create a new event.

5. `new-event-handler.php` - The PHP script that handles the event creation process, validating the form inputs and saving the new event to `events.txt`.

6. `edit-event.php` - The page that provides a form to edit an existing event, pre-populated with the current event's details.

7. `edit-event-handler.php` - The PHP script that processes the updated event information and writes the changes to `events.txt`.

8. `delete-event.php` - The page that deletes an event from the list, identified by its EventID.

## Running the Project in Docker

To run this project in a Docker container, follow the steps below.

### Prerequisites

- Docker installed on your machine. If you haven't already, you can download and install Docker from [here](https://www.docker.com/get-started).

### Docker Setup

1. **Create a `Dockerfile` for the PHP environment**:

   In the root of your `assignment1` folder (where the `index.php` file is located), create a `Dockerfile` with the following content:

   ```dockerfile
   FROM php:8.4-apache

   # Install system dependencies
   RUN apt-get update && apt-get install -y \
       libzip-dev \
       zip \
       unzip \
       git \
       vim

   # Install PHP extensions
   RUN docker-php-ext-install zip

   # Install and enable Xdebug
   RUN pecl install xdebug && docker-php-ext-enable xdebug

   # Configure Xdebug
   COPY php_proj_1/php84.local/xdebug.ini /usr/local/etc/php/conf.d/

   # Configure Apache
   COPY php_proj_1/php84.local/000-default.conf /etc/apache2/sites-available/000-default.conf

   # Copy your application code into the container
   COPY php_proj_1/php84.local/app/. /var/www/html

   # Expose port 80
   EXPOSE 80
It's important to note that depending on your file setup you may need to add the file path after COPY before xdebug.ini, 000-default.conf, and /var/www/html as shown above.
