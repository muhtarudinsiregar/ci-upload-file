<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UploadFile
{
    public $data;

    public $file;

    public $field;

    // check if input request has file
    public function hasFile($field)
    {
        $this->file = $_FILES[$field];
        $this->field = $field;

        $error = "error";

        return ($this->file) ? $this : $error ;
    }

    // check mimes file type
    public function isValid()
    {
        $allowed_mime_type_arr = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword'
        ];

        $mime = mime_content_type($this->file['tmp_name']);

        if (in_array($mime, $allowed_mime_type_arr)) {
            return true;
        }

        return false;
    }

    public function store($field, $directory = 'uploads')
    {
        $CI =& get_instance();
        $config['upload_path'] = $directory;
        $config['allowed_types'] = 'pdf|xlsx|xls|doc|docx';
        $config['max_size']     = '2048';

        $CI->upload->initialize($config);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($CI->upload->do_upload($field)) {
            $this->setData($CI->upload->data());

            return true;
        }

        $CI->session->set_flashdata('validation_errors', $CI->upload->display_errors());

        return false;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData($param = false)
    {
        if ($param) {
            return $this->data[$param];
        }

        return $this->data;
    }
}

/* End of file FilesUpload.php */
/* Location: ./application/libraries/FilesUpload.php */
