<?php
if(getenv('VCAP_SERVICES')){
    $creds = json_decode(getenv('VCAP_SERVICES'))->mariadb[0]->credentials;

    if($creds) {
        $container->setParameter('database_host', $creds->host);
        $container->setParameter('database_name', $creds->database);
        $container->setParameter('database_user', $creds->username);
        $container->setParameter('database_password', $creds->password);
        $container->setParameter('database_port', $creds->port);
    }
}
