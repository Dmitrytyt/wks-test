<?php
class Model_upload extends Model {

    const DELIMITER = ';';
    private $filter;
    public $successAdd = 0;
    public $failAdd = 0;
    public $addedUsers = array();

    public function __construct()
    {
        $this->db = Main::getDB();
        $this->filter = false;
    }

    public function upload()
    {
        $uploadFile = join(DIRECTORY_SEPARATOR, array(
            $_SERVER['DOCUMENT_ROOT'] ,
            'uploads',
            $_FILES['file']['name']
        ));

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $csvArray = $this->csvToArray($uploadFile);
            if (is_array($csvArray) && count($csvArray) < 0){
                return false;
            }
            $userModel = Controller::loadModel("user");;

            foreach ($csvArray as $item){
                $status = $userModel->addNewUser($item);
                $name = ($status === FALSE) ? 'failAdd' : 'successAdd';
                $this->set( $name, 1);

                if ($status === TRUE){
                    $this->setAddUser($item);
                }
            }

        } else {
            return FALSE;
        }

        return TRUE;
    }

    public function set($name, $item){
        $this->$name += $item;
    }

    public function setAddUser($item){
        $this->addedUsers[] = $item;
    }

    public function csvToArray($filename = '')
    {
        if( !file_exists($filename) || !is_readable($filename)){
            return FALSE;
        }

        $header = NULL;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, self::DELIMITER)) !== FALSE) {
                if(!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }

            fclose($handle);
        }

        return $data;
    }

}