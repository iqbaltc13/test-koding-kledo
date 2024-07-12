<?php

namespace App\Http;

trait ApiResponder
{
    protected function array_remove_null($item)
    {
        if(is_null($item))
            return NULL;

        // if(method_exists($item, "toArray"))
        //     $item = $item->toArray();

        if(!is_array($item))
            $item = json_decode(json_encode($item), True);

        if(is_array($item) || is_object($item))
            foreach($item as $key=>$value)
            {
                if(is_object($value))
                {
                    //if(method_exists($value, "toArray"))
                    $value = $value->toArray();
                    // else
                    //     $value = (array) $value;
                }

                if(is_array($value))
                {
                    $value = $this->array_remove_null($value);
                    $item[$key] = $value;
                }

                if(is_null($value))
                {
                    // choose one, comment the other

                    $item[$key] = "";       // replace null value to empty string
                    // unset($item[$key]);  // remove null value
                }

                if(is_int($value))
                {
                    $item[$key] = strval($value);
                }
            }

        return $item;
    }

    protected function removeStringToNull($item,$str)
    {
        if(is_null($item))
            return NULL;



        if(!is_array($item))
            $item = json_decode(json_encode($item), True);

        if(is_array($item) || is_object($item))
            foreach($item as $key=>$value)
            {
                if(is_object($value))
                {
                    //if(method_exists($value, "toArray"))
                    $value = $value->toArray();
                    // else
                    //     $value = (array) $value;
                }

                if(is_array($value))
                {
                    $value = $this->removeStringToNull($value,$str);
                    $item[$key] = $value;
                }

                if($value == $str )
                {

                    // choose one, comment the other

                    $item[$key] = NULL;
                }

                if(is_int($value))
                {
                    $item[$key] = strval($value);
                }
            }

        return $item;
    }


    protected $responseFormat = [
        'response_code' => NULL,
        'message' => NULL,
        'errors' => NULL,
        'data' => NULL
    ];

    protected function success($data = NULL, $message = 'Permintaan berhasil diproses.', $removeNull = true) {
        if($removeNull)
            $data = $this->array_remove_null($data);

        return response()->json(array_merge($this->responseFormat, [
            'response_code' => 200,
            'message' => $message,
            'data' => $data,
        ]));
    }

    protected function failure($errors = ['Permintaan gagal diproses.']) {
        return response()->json(array_merge($this->responseFormat, [
            'response_code' => 400,
            'errors' => (is_null($errors) ? $errors : is_array($errors)) ? $errors : [$errors],
        ]));
    }

    protected function unauthorized($errors = ['Hak akses tidak tersedia.']) {
        return response()->json(array_merge($this->responseFormat, [
            'response_code' => 401,
            'errors' => (is_null($errors) ? $errors : is_array($errors)) ? $errors : [$errors],
        ]));
    }

    protected function notFound($errors = ['Data tidak ditemukan.']) {
        return response()->json(array_merge($this->responseFormat, [
            'response_code' => 404,
            'errors' => (is_null($errors) ? $errors : is_array($errors)) ? $errors : [$errors],
        ]));
    }

    protected function invalidParameters($errors = [])
    {
        return response()->json(array_merge($this->responseFormat, [
            'response_code' => 422,
            'errors' => (is_null($errors) ? $errors : is_array($errors)) ? $errors : [$errors],
        ]));
    }

    protected function customResponse($responseCode = 200, $message = '', $errors = NULL, $data = NULL, $removeNull = true)
    {
        if($removeNull)
            $data = $this->array_remove_null($data);

        return response()->json(array_merge($this->responseFormat, [
            'response_code' => $responseCode,
            'message' => $message,
            'errors' => (is_null($errors) ? $errors : is_array($errors)) ? $errors : [$errors],
            'data' => $data,
        ]));
    }
}
