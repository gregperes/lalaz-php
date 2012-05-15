<?php
/**
 * View for errors.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Erro</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }
        
        html {
            text-align: center;
        }
        
        body {
            background: #EEE;
            font: 12px "Arial","Verdana","Tahoma",sans-serif;
            margin: 0 auto;
            text-align: left;
            width: 770px;
        }
        
        #header {
            background: #C00;
            margin-top: 15px;
            padding: 15px 0 15px 15px;
        }
        
        #header h1 {
            color: #760000;
            font: bold 18px "Helvetica", "Arial", sans-serif;
            letter-spacing: -1px;
            width: 640px;
        }
        
        #content {
        	height: 300px;
            border: 1px solid #CCC;
            background: #FFF;
            padding: 20px;
        }
        
        #content h1 {
            color: #AAA;
            font: bold 32px "Helvetica", "Arial", sans-serif;
            letter-spacing: -2px;
        }
            
        #content p {
            color: #666;
            font-size: 13px;
            line-height: 20px;
        }
        
        #footer {
            color: #666;
            padding: 10px 10px;
            text-align: right;
        }
        
        #footer small {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div id="header">
        <h1>Ooops!</h1>
    </div>
    <div id="content">
        <h1><?php echo $title; ?></h1>
        <p><?php echo $message; ?></p>
    </div>
    <div id="footer">
        <small>WMD</small>
    </div>
</body>
</html>