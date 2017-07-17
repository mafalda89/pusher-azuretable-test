<?php

class Config
{
    // Connection String to Azure Table
    const ACCOUNT_NAME = 'YOUR_AZURE_ACCOUNT_NAME';
    const ACCOUNT_KEY = 'YOUR_AZURE_ACCOUNT_KEY';
    const IS_EMULATED = false;
    
    public static function getConnectionString()
    {
      if(Config::IS_EMULATED)
        return "UseDevelopmentStorage=true";
      else
        return "DefaultEndpointsProtocol=https;AccountName=" . Config::ACCOUNT_NAME . ";AccountKey=" . Config::ACCOUNT_KEY;
    }
}
?>