<?php
//https://stackoverflow.com/questions/7767961/converting-an-array-from-one-to-multi-dimensional-based-on-parent-id-values
//Функция принимающая массив, строит дерево массивов
//добавить level
    function getTree ($array = [], $id_name = 'id', $id_parent_name = 'id_parent', $childs_name = 'childs') {
        $keyed = array();
        foreach($array as &$value)
        {
            $keyed[$value[$id_name]] = &$value;
        }
        unset($value);
        $array = $keyed;
        unset($keyed);

        $tree = array();
        foreach($array as &$value)
        {
            if ($parent = $value[$id_parent_name])
                $array[$parent][$childs_name][] = &$value;
            else
                $tree[] = &$value;
        }
        unset($value);
        return $tree;
    }
