# pusher-azuretable-test

Solution that queries a generic LOG storage hosted on Azure Table to display realtime errors details on a front-end web page.

## Requirements
- Azure WebJob
- Azure Table Storage
- Free Pusher account

Moreover, you will need composer to install:
- pusher/pusher-php-server
- microsoft/windowsazure

## Details
The solution includes:
- `./client-side/index.html` a front-end page to display new notifications through Pusher JavaScript library 
- `./server-side/index.php` a server-side application designed to be triggered every 5 minutes. The routine queries an Azure Storage Table and retrieves the latest log events stored (of level 'High'); the log details are then sent to the front-end page through Pusher triggered events. In the provided demo the PHP solution is hosted on an Azure WebJob scheduled by a CRON.
- `./server-side/php/config.php` Azure Table configuration class to allow easy connection to the cloud storage

### Demo
http://thenotificationtest.azurewebsites.net/
