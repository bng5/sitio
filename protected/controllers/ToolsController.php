<?php

class ToolsController extends Controller {

    public function actionIndex() {
        $tools = array(
            'request' => array(
                'Request',
                'Descripción',
            ),
        );
        $this->render('index', array(
            'tools' => $tools,
        ));
    }
    

    
    public function actionInfo() {
        if($tool = $this->actionParams['id']) {
            $this->render('info/'.$tool, array(
                'tools' => $tools,
            ));
        }
        else {
            return $this->actionIndex();
        }
    }
    
    public function actionRequest($format = null) {

        $formatos = array(
            'json' => 'application/json',
            'plain' => 'text/plain',
        );
        if($formatos[$format]) {
            header("Content-type: {$formatos[$format]}; charset=UTF-8");
        }

        $captura = array();
        //ob_start();

        $captura['HTTP_RAW_POST_DATA'] = $HTTP_RAW_POST_DATA;

        $fp = fopen("php://input", "r");// stdin
        $data = '';
        while(!feof($fp))
          $data .= fgets($fp);
        fclose($fp);
        $captura['php://input'] = $data;

        $fp = fopen("php://stdin", "r" );//
        $data = '';
        while( !feof( $fp ) )
            $data .= fgets( $fp );
        fclose($fp);
        $captura['php://stdin'] = $data;

        $captura['Headers'] = array();
        if(function_exists('apache_request_headers')) {
            $captura['Headers']['Request'] = apache_request_headers();
            flush();
            $captura['Headers']['Response'] = apache_response_headers();
        }
        else {
            
            $captura['Headers'][] = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']} {$_SERVER['SERVER_PROTOCOL']}";
            foreach($_SERVER as $key => $value) {
//                if(substr($key, 0, 5) == "HTTP_") {
                if(preg_match('/^(HTTP_|(CONTENT_))(.*)/', $key, $matches)) {
                    $key = $matches[2].$matches[3];
                    $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", $key))));
                    $captura['Headers'][$key] = $value;
                }
            }
        }

        parse_str($_SERVER['QUERY_STRING'], $captura['Get']);
        $captura['Post'] = $_POST;
        $captura['Files'] = $_FILES;

        if(count($_FILES)) {
            foreach($_FILES AS $file) {
        //		move_uploaded_file($file['tmp_name'], '/home/pablo/Escritorio/ww/r_'.$file['name']);
            }
            //$data = file_get_contents($file['tmp_name']);
            //file_put_contents( '/home/pablo/Escritorio/ww/r_'.$file['name'], $data);
        }

        //$buffer = ob_get_contents();
        //file_put_contents('./colecta/'.time().'.html', $buffer);
        //ob_flush();
        //ob_clean();

        switch($format) {
            case 'json':
                echo json_encode($captura);
                return;
                break;
            case 'plain':
                $this->renderPartial('request_plain', array(
                    'captura' => $captura,
                ));
                break;
            default:
                $this->render('request', array(
                    'captura' => $captura,
                ));
                break;
        }

    }
    
    public function actionPhpinfo() {
        phpinfo();
        return;
    }

}