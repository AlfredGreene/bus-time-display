# Brighton and Hove Bus Times Display

This is designed as an eternally running process for display on something like a Raspberry Pi hooked to a screen.
This will output the time to the next bus for a given stop ID.

![Bus Time Display](http://prnt.sc/bz6fvr)

**NOTE**
Requires [figlet](http://www.figlet.org/) to be installed with the [univers font](http://www.figlet.org/fontdb_example.cgi?font=univers.flf) to use the bus time display.

# Usage

Install the dependencies with
```
composer install
```
then run the app with
```
php app.php buses:service:next_bus --stop=7080`
```
from the command line to get the next bus for *Churchill Square (Stop G)*

## Options

* --stop (required) The ID of the bus stop you want to display
* --width (optional, default 80) The width of your console window. You may need to tweak this so the console output fills the screen perfectly.

# Finding your bus stop's ID

As I haven't yet added a way for this to happen from the app itself, you will need to navigate to the [Stops API](http://bh.buscms.com/BrightonBuses/api/XmlEntities/v1/stops.aspx?datatype=jsonp) and search for your stop.
The **StopId** is what you need to use the bus time display.

# Events

As this is a display that is designed to be turned on 24/7, there is support for custom events as well. These will be displayed underneath the bus times when it is the appropriate time for them to show.

## Creating Events

Open *src/Service/EventService.php*, and add new Event's to the events array.

```php
$event = new Event();
// Give it a unique name. Although this currently doesn't do anything.
$event->setName('unique_name');
// Give it a description. This is what is shown on the display, so keep it within 20 or so characters.
$event->setDescription('This will be shown on the display');
// Set the date/time it should show. This is a cron compatible string.
$event->setDate('* * * * * *');
$events[] = $event;
```
