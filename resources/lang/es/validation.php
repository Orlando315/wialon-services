<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El elemento :attribute debe ser aceptado.',
    'active_url'           => 'El elemento :attribute no es una URL valida.',
    'after'                => 'El elemento :attribute debe ser una fecha después de :date.',
    'after_or_equal'       => 'El elemento :attribute debe ser una fecha después o igual a :date.',
    'alpha'                => 'El elemento :attribute solo debe contener letras.',
    'alpha_dash'           => 'El elemento :attribute solo debe contener letras, números, guiones y guion bajo.',
    'alpha_num'            => 'El elemento :attribute solo debe contener letras y números.',
    'array'                => 'El elemento :attribute Debe ser un array.',
    'before'               => 'El elemento :attribute debe ser una fecha antes de :date.',
    'before_or_equal'      => 'El elemento :attribute debe ser una fecha antes o igual a :date.',
    'between'              => [
        'numeric' => 'El elemento :attribute debe estar entre :min y :max.',
        'file'    => 'El elemento :attribute debe estar entre :min y :max kilobytes.',
        'string'  => 'El elemento :attribute debe estar entre :min y :max caracteres.',
        'array'   => 'El elemento :attribute debe tener entre :min y :max items.',
    ],
    'boolean'              => 'El elemento :attribute debe ser verdadero (true) o false (false).',
    'confirmed'            => 'La verificación de :attribute no coincide.',
    'date'                 => 'El elemento :attribute no es una fecha valida.',
    'date_format'          => 'El elemento :attribute no coincide con el formato de fecha dd/mm/aaaa.',
    'different'            => 'El elemento :attribute y :other deben ser diferentes.',
    'digits'               => 'El elemento :attribute debe tener :digits digitos.',
    'digits_between'       => 'El elemento :attribute debe estar entre :min y :max digits.',
    'dimensions'           => 'El elemento :attribute tiene dimensiones invalidas.',
    'distinct'             => 'El elemento :attribute tiene valores duplicados.',
    'email'                => 'El elemento :attribute debe ser una direccion de email valida.',
    'exists'               => 'El elemento :attribute seleccionado es invalido.',
    'file'                 => 'El elemento :attribute debe ser un archivo.',
    'filled'               => 'El elemento :attribute debe tener un valor.',
    'gt'                   => [
        'numeric' => 'El elemento :attribute debe ser mayor a :value.',
        'file'    => 'El elemento :attribute debe ser mayor a :value kilobytes.',
        'string'  => 'El elemento :attribute debe ser mayor a :value caracteres.',
        'array'   => 'El elemento :attribute debe tener mas de :value items.',
    ],
    'gte'                  => [
        'numeric' => 'El elemento :attribute debe ser mayor o igual a :value.',
        'file'    => 'El elemento :attribute debe ser mayor o igual a :value kilobytes.',
        'string'  => 'El elemento :attribute debe ser mayor o igual a :value caracteres.',
        'array'   => 'El elemento :attribute debe tener :value items o mas.',
    ],
    'image'                => 'El elemento :attribute debe ser una imagen.',
    'in'                   => 'El elemento :attribute seleccionado es invalido.',
    'in_array'             => 'El elemento :attribute no existe en :other.',
    'integer'              => 'El elemento :attribute debe ser un entero.',
    'ip'                   => 'El elemento :attribute debe ser una direccion IP valida.',
    'ipv4'                 => 'El elemento :attribute debe ser una direccion IPv4 valida.',
    'ipv6'                 => 'El elemento :attribute debe ser una direccion IPv6 valida.',
    'json'                 => 'El elemento :attribute debe ser una cadena JSON valida.',
    'lt'                   => [
        'numeric' => 'El elemento :attribute debe ser menor a :value.',
        'file'    => 'El elemento :attribute debe ser menor a :value kilobytes.',
        'string'  => 'El elemento :attribute debe ser menor a :value caracteres.',
        'array'   => 'El elemento :attribute debe tener menos de :value items.',
    ],
    'lte'                  => [
        'numeric' => 'El elemento :attribute debe ser menos o igual a :value.',
        'file'    => 'El elemento :attribute debe ser menos o igual a :value kilobytes.',
        'string'  => 'El elemento :attribute debe ser menos o igual a :value caracteres.',
        'array'   => 'El elemento :attribute no debe tener mas de :value items.',
    ],
    'max'                  => [
        'numeric' => 'El elemento :attribute no debe ser mayor a :max.',
        'file'    => 'El elemento :attribute no debe ser mayor a :max kilobytes.',
        'string'  => 'El elemento :attribute no debe ser mayor a :max caracteres.',
        'array'   => 'El elemento :attribute no debe tener más de :max items.',
    ],
    'mimes'                => 'El elemento :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El elemento :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'El elemento :attribute debe ser al menos :min.',
        'file'    => 'El elemento :attribute debe ser de al menos :min kilobytes.',
        'string'  => 'El elemento :attribute debe ser de al menos :min caracteres.',
        'array'   => 'El elemento :attribute debe contener al menos :min items.',
    ],
    'not_in'               => 'El elemento :attribute seleccionado es invalido.',
    'not_regex'            => 'El formato del elemento :attribute es invalido.',
    'numeric'              => 'El elemento :attribute debe ser un número.',
    'present'              => 'El elemento :attribute debe estar presente.',
    'regex'                => 'El formato del elemento :attribute es invalido.',
    'required'             => 'El elemento :attribute es requerido.',
    'required_if'          => 'El elemento :attribute es requerido cuando :other es :value.',
    'required_unless'      => 'El elemento :attribute es requerido a menos que :other este en :values.',
    'required_with'        => 'El elemento :attribute es requerido cuando el elemento :values esta presente.',
    'required_with_all'    => 'El elemento :attribute es requerido cuando :values esta presente.',
    'required_without'     => 'El elemento :attribute es requerido cuando :values no esta presente.',
    'required_without_all' => 'El elemento :attribute es requerido cuando ninguno de los elementos :values este presente.',
    'same'                 => 'El elemento :attribute y :other deben ser iguales.',
    'size'                 => [
        'numeric' => 'El elemento :attribute debe tener :size.',
        'file'    => 'El elemento :attribute debe tener :size kilobytes.',
        'string'  => 'El elemento :attribute debe tener :size caracteres.',
        'array'   => 'El elemento :attribute debe contener :size items.',
    ],
    'string'               => 'El elemento :attribute debe ser una cadena de texto.',
    'timezone'             => 'El elemento :attribute debe ser una zona valida.',
    'unique'               => 'El elemento :attribute ya ha sido registrado.',
    'uploaded'             => 'El elemento :attribute no se ha podido cargar.',
    'url'                  => 'El formato del elemento :attribute es invalido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
