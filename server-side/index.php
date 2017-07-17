<?php
  
  require_once './vendor/autoload.php';  
  require_once './php/config.php';  
  use Config;

  use MicrosoftAzure\Storage\Common\ServicesBuilder;
  use MicrosoftAzure\Storage\Common\ServiceException;
  use MicrosoftAzure\Storage\Table\Models\Entity;
  use MicrosoftAzure\Storage\Table\Models\EdmType;
  use MicrosoftAzure\Storage\Table\Models\QueryEntitiesOptions;
  
  /* Init Table query variables */
  $getLevel = 'High';
  $tableName = 'YOUR_AZURE_TABLE_NAME';
  // Filter on Timestamp: events occurred during the last 5 minutes
  $datetime = new DateTime('UTC');
  $di = new DateInterval('PT5M'); 
  $di->invert = 1; 
  $datetime->add($di);
  $dataString = $datetime->format('Y-m-d');
  $timeString = $datetime->format('H:i:s');
  // Build a query filter for the Azure Table 
  $filter = "Level eq '".$getLevel."' and Timestamp gt datetime'".$dataString ."T".$timeString."'";
  
  // Connect to Azure Table  
  $connectionString = Config::getConnectionString();
  $tableService = ServicesBuilder::getInstance()->createTableService($connectionString);
  
  // Define Pusher object
  $options = array(
    'cluster' => 'eu',
    'encrypted' => true
  );
  $pusher = new Pusher\Pusher(
    'YOUR_APP_KEY',
    'YOUR_APP_SECRET',
    'YOUR_APP_ID',
    $options
  );

  /* Query Azure Table */
  try    {
    $result = $tableService->queryEntities($tableName, $filter);
    $entities = $result->getEntities();
    // Send each result to Pusher
    foreach($entities as $entity){  
      $data['FunctionName'] = $entity->getProperty('PartitionKey')->getValue();
      $data['CorrelationId']= $entity->getProperty('RowKey')->getValue();
      $data['Message']      = $entity->getProperty('Message')->getValue(); 
      $data['LogTimestamp'] = $entity->getProperty('LogTimestamp')->getValue()->format('Y-m-d H:i:s');; 
      // Send information to Pusher      
      $pusher->trigger('notifications', 'new_notification', $data);
    }
    
  }
  catch(ServiceException $e){
      // Handle exception based on error codes and messages.
      // Error codes and messages are here:
      // http://msdn.microsoft.com/library/azure/dd179438.aspx
      $code = $e->getCode();
      $error_message = $e->getMessage();
      echo $error_message;
  }  
  
?>