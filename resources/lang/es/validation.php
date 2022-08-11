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

    'accepted'        => 'El campo :attribute debe ser aceptado.',
    'accepted_if'     => 'El campo :attribute debe ser aceptado cuyo :other es :value.',
    'active_url'      => 'El campo :attribute no es valido URL.',
    'after'           => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'  => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'           => 'El campo :attribute solo debe contener letras.',
    'alpha_dash'      => 'El campo :attribute solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'       => 'El campo :attribute solo debe contener letras y números.',
    'array'           => 'El campo :attribute de ser un array.',
    'before'          => 'El campo :attribute debe ser una fecha anterior :date.',
    'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'         => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file'    => 'El campo :attribute debe estar entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute debe estar entre :min y :max caracteres.',
        'array'   => 'El campo :attribute debe tener entre :min y :max artículos.',
    ],
    'boolean'          => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'        => 'El campo de confirmación :attribute no coincide.',
    'current_password' => 'Contraseña incorrecta.',
    'date'             => 'El campo :attribute no es valido date.',
    'date_equals'      => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format'      => 'El campo :attribute no coincide con el formato :format.',
    'different'        => 'El campo :attribute y :other son diferentes.',
    'digits'           => 'El campo :attribute debe ser :digits digitos.',
    'digits_between'   => 'El campo :attribute debe estar entre :min y :max digitos.',
    'dimensions'       => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct'         => 'El campo :attribute tiene un valor duplicado.',
    'email'            => 'El campo :attribute Debe ser una dirección de correo electrónico válida.',
    'ends_with'        => 'El campo :attribute debe terminar con uno de los siguientes: :values.',
    'exists'           => 'El campo seleccionado :attribute es invalido.',
    'file'             => 'El campo :attribute debe ser un archivo.',
    'filled'           => 'El campo :attribute debe tener un valor.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file'    => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'string'  => 'El campo :attribute debe ser mayor que :value caracteres.',
        'array'   => 'El campo :attribute debe tener más de :value artículos.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor o igual :value.',
        'file'    => 'El campo :attribute debe ser mayor o igual :value kilobytes.',
        'string'  => 'El campo :attribute debe ser mayor o igual :value caracteres.',
        'array'   => 'El campo :attribute debe tener :value artículos o más.',
    ],
    'image'    => 'El campo :attribute debe ser una imágen.',
    'in'       => 'El campo seleccionado :attribute es invalido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer'  => 'El campo :attribute debe ser un entero.',
    'ip'       => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'     => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'     => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'     => 'El campo :attribute debe ser una cádena JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file'    => 'El campo :attribute debe ser menor que :value kilobytes.',
        'string'  => 'El campo :attribute debe ser menor que :value caracteres.',
        'array'   => 'El campo :attribute debe tener menos de :value artículos.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor o igual :value.',
        'file'    => 'El campo :attribute debe ser menor o igual :value kilobytes.',
        'string'  => 'El campo :attribute debe ser menor o igual :value caracteres.',
        'array'   => 'El campo :attribute no debe tener más de :value artículos.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'file'    => 'El campo :attribute no debe ser mayor que :max kilobytes.',
        'string'  => 'El campo :attribute no debe ser mayor que :max caracteres.',
        'array'   => 'El campo :attribute no debe tener más de :max artículos.',
    ],
    'mimes'     => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser como mínimo :min.',
        'file'    => 'El campo :attribute debe ser como mínimo :min kilobytes.',
        'string'  => 'El campo :attribute debe ser como mínimo :min caracteres.',
        'array'   => 'El campo :attribute debe tener al menos :min artículos.',
    ],
    'multiple_of'          => 'El campo :attribute debe ser un múltiplo de :value.',
    'not_in'               => 'El campo seleccionado :attribute es invalido.',
    'not_regex'            => 'El campo :attribute tiene formato invalido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'password'             => 'Contraseña incorrecta.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El campo :attribute tiene formato invalido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other se encuentre en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values están presentes.',
    'prohibited'           => 'El campo :attribute está prohibido.',
    'prohibited_if'        => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless'    => 'El campo :attribute está prohibido a menos que :other se encuentre en :values.',
    'same'                 => 'El campo :attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El campo :attribute debe ser :size kilobytes.',
        'string'  => 'El campo :attribute debe ser :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size artículos.',
    ],
    'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes: :values.',
    'string'      => 'El campo :attribute debe ser una cadena.',
    'timezone'    => 'El campo :attribute debe ser una zona horaria válida.',
    'unique'      => 'El campo :attribute ya se ha tomado.',
    'uploaded'    => 'El campo :attribute no se pudo cargar.',
    'url'         => 'El campo :attribute bebe ser una URL válida.',
    'uuid'        => 'El campo :attribute Debe ser un UUID válido.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
