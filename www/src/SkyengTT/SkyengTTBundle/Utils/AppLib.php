<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 02.12.15
 * Time: 16:30
*/
namespace SkyengTT\SkyengTTBundle\Utils;
use Symfony\Component\HttpFoundation\Response;

class AppLib
{
    /**
     * Обертка для быстрого добавлению к JSON ответу 404 статуса
     */
    static public function json404($data)
    {
        $data['message'] = '404 Not Found';
        $response = self::json($data);
        $response->setStatusCode(404);
        return $response;
    }
    /**
     * json response
     */
    static public function json($data)
    {
        $data = json_encode($data);
        $response = new Response($data);
        $response->headers->set("Content-Type", "application/json");
        return $response;
    }
    /**
     * Преобразует модель в массив
    */
    static public function toArray($model)
    {
        $data = get_class_methods($model);
        //var_dump($data); die;
        $result = array();
        foreach ($data as $method) {
            if (strpos($method, 'get') === 0) {
                $field = self::toSnake($method);
                $result[$field] = $model->$method();
            }
        }
        return $result;
    }
    /**
     * Получает из имени get метода класса в camelCase имя свойства в snake_case
    */
    static private function toSnake($s)
    {
        $q = strtoupper($s);
        $count = strlen($s);
        $words = array();
        $word = '';
        for ($i = 0; $i < $count; $i++){
            if ($q[$i] == $s[$i]) {
                if ($word != 'get') {
                    $words[] = strtolower($word);
                }
                $word = '';
            }
            $word .= $s[$i];
        }
        if ($word) {
            $words[] = strtolower($word);
        }
        $r = join('_', $words);
        return $r;
    }
}
