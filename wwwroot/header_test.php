<?php

ob_start();

sleep(5);

echo 'test';

header('Location:http://google.com');
