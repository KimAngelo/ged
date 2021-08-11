<?php


namespace Source\Models;

use Gumlet\ImageResize;
use Source\Support\Message;


/**
 * Class Upload
 * @package Source\Models
 */
class Upload
{
    /**
     * @var Message
     */
    private $message;

    /**
     * @var
     */
    private $optimizeFile;

    /**
     * Upload constructor.
     */
    public function __construct()
    {
        $this->message = new Message();
    }

    /**
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * @param $file
     * @param $file_name
     * @param $path
     * @return bool
     */
    public function send($file, $file_name, $path = __DIR__ . "/../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/"): bool
    {
        $permission = array('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png');
        $error = false;

        /*$ext = ($file['type'] == 'image/jpeg' ? '.jpg' : '.png');
        $new_name = $file_name . $ext;*/

        if (!in_array($file['type'][0], $permission)) {
            $this->message->warning(" O formato do arquivo não é válido");
            return false;
        } elseif ($file['tmp_name'][0] == '') {
            $this->message->error(" Você não selecionou nenhum arquivo");

            return false;
        } elseif ($file['size'][0] > 5242880) {
            $this->message->error(" O tamanho da foto é muito grande");
            return false;
        } else if (!move_uploaded_file($file['tmp_name'][0], $path . $file_name)) {
            $this->message->error(" Erro ao enviar");
            return false;
        }
        return true;
    }


    public function sends($file, $file_name, $path = __DIR__ . "/../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/", bool $optimize = false): bool
    {
        $permission = array('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png');
        $error = false;

        /*$ext = ($file['type'] == 'image/jpeg' ? '.jpg' : '.png');
        $new_name = $file_name . $ext;*/
        if (!in_array($file['type'], $permission)) {
            $this->message->warning(" O formato do arquivo não é válido");
            return false;
        } elseif ($file['tmp_name'] == '') {
            $this->message->error(" Você não selecionou nenhum arquivo");
            return false;
        } elseif ($file['size'] > 20000000) {
            $this->message->error(" O tamanho da foto é muito grande");
            return false;
        } else if (!move_uploaded_file($file['tmp_name'], $path . $file_name)) {
            $this->message->error(" Erro ao enviar");
            return false;
        }

        /*$image = new ImageResize($path . $file_name);
        $image->quality_png = 1;
        $image->quality_jpg = 10;
        $image->save($path . "teste/" . $file_name);
        var_dump($image);*/

        /*  $info = getimagesize($path . $file_name);

          if ($info['mime'] == 'image/jpeg') {
              $image = imagecreatefromjpeg($path . $file_name);
              imagejpeg($image, $path . $file_name, CONF_IMAGE_QUALITY['jpg']);
          } elseif ($info['mime'] == 'image/png') {
              $image = imagecreatefrompng($path . $file_name);
              imagepng($image, $path . $file_name, CONF_IMAGE_QUALITY['png']);
          }*/
        if ($optimize) {
            $this->optimizeFile = $path . $file_name;
            $this->optimize();
        }
        return true;
    }

    /**
     *
     */
    private function optimize()
    {
        $image = new ImageResize($this->optimizeFile);
        $image->resizeToWidth(250);
        $image->save($this->optimizeFile);
    }

    /**
     * @param string $file_name
     * @param $path
     * @return bool
     */
    public function remove(string $file_name, string $path = __DIR__ . "/../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/"): bool
    {
        if (file_exists($path . $file_name)) {
            unlink($path . $file_name);
        } else {
            return false;
        }
        return true;
    }
}