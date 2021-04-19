<?php

use Config\Services;

/**
 * CodeIgniter Form Helpers
 *
 * @package CodeIgniter
 */


//--------------------------------------------------------------------

if (!function_exists('form_input_spread')) {
    /**
     * Text Input Field. If 'type' is passed in the $type field, it will be
     * used as the input type, for making 'email', 'phone', etc input fields.
     *
     * @param mixed  $data
     * @param string string
     * @param mixed  $extra
     * @param string $type
     *
     * @return string
     */
    function form_input_spread($data = '', $value = null, $extra = '', string $type = 'text', $required = false, $builder = false): string
    {
        $html = '<div class="kt-input-icon kt-input-icon--left">';
        if (service('Settings')->setting_activer_multilangue == true) {

            $setting_supportedLocales = json_decode(service('Settings')->setting_supportedLocales);
            if (!empty($setting_supportedLocales) && is_array($setting_supportedLocales)) {

                if ($required == true) {
                    $extra = $extra . ' required ';
                }
                //print_r($setting_supportedLocales);
                foreach ($setting_supportedLocales as $k => $v) {
                    $extraFormat  = '';
                    $extraFormat = $extra;
                    $langExplode = explode('|', $v);

                    if (service('Settings')->setting_id_lang != $langExplode[0]) {
                        $extraFormat = $extraFormat . ' style="display:none;" ';
                    }
                    //print_r($langExplode);
                    //print_r($value);
                    $extraFormat = $extraFormat . ' data-id_lang="' . $langExplode[0] . '" data-iso_lang="' . $langExplode[1] . '"';
                    //echo $extraFormat; 
                    if (is_array($data)) {
                        $valueFormat = !isset($value[$langExplode[0]]->{$data[1]}) ? '' : $value[$langExplode[0]]->{$data[1]};
                    } else {
                        $valueFormat = !isset($value[$langExplode[0]]->{$data}) ? '' : $value[$langExplode[0]]->{$data};
                    }


                    if ($builder == true) {
                        $html .= form_input(is_array($data) ?  $builder . '[' . $data[0] . '][lang][' . $langExplode[0] . '][' . $data[1] . ']' : 'lang[' . $langExplode[0] . '][' . $data . ']', $valueFormat, $extraFormat, $type);
                    } else {
                        $html .= form_input(is_array($data) ?  'lang[' . $langExplode[0] . '][' . $data[0] . '][' . $data[1] . ']' : 'lang[' . $langExplode[0] . '][' . $data . ']', $valueFormat, $extraFormat, $type);
                    }
                }
                $html .= '<span class="kt-input-icon__icon kt-input-icon__icon--left"><span><i class="la la-language"></i></span></span>';
                if ($required == true) {
                    $html .= ' <div class="invalid-feedback">' . lang('Core.this_field_is_requis') . '</div>';
                }
            }
        } else {
            //print_r($value);exit;

            if ($required == true) {
                $extra = $extra . ' required ';
            }
            $defaults = [
                'type'  => $type,
                'name'  => is_array($data) ? '' : $data,
                'value' => $value,
            ];
            //print_r($value);
            if ($builder == true) {
                $old = is_array($data) ?  old($builder.'.' . $data[0] . '.lang.' . service('Settings')->setting_id_lang . '.' . $data[1]) : old('lang.' . service('Settings')->setting_id_lang . '.' . $data);
                $old = (string)$old;
                $newValue = is_array($data) ? end($data) : $data;
                $html .=  form_input(
                    is_array($data) ? $builder.'[' . $data[0] . '][lang][' . service('Settings')->setting_id_lang . '][' . $data[1] . ']'  : 'lang[' . service('Settings')->setting_id_lang . ']' . '[' . $data . ']',
                    $value = ($value == null) ? $old : $value[service('Settings')->setting_id_lang]->{$newValue},
                    $extra,
                    $type
                );
            } else {
                $old = is_array($data) ? old('lang.' . service('Settings')->setting_id_lang . '.' . $data[0] . '.' . $data[1]) : old('lang.' . service('Settings')->setting_id_lang . '.' . $data);
                $old = (string)$old;
                $html .=  form_input(
                    is_array($data) ? 'lang[' . service('Settings')->setting_id_lang . '][' . $data[0] . '][' . $data[1] . ']' : 'lang[' . service('Settings')->setting_id_lang . ']' . '[' . $data . ']',
                    $value = (!isset($value[service('Settings')->setting_id_lang]->{$data})) ? $old : $value[service('Settings')->setting_id_lang]->{$data},
                    $extra,
                    $type
                );
            }

            $html .= '<span class="kt-input-icon__icon kt-input-icon__icon--left"><span><i class="la la-language"></i></span></span>';
            if ($required == true) {
                $html .= ' <div class="invalid-feedback">' . lang('Core.this_field_is_requis') . '</div>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}


if (!function_exists('form_input')) {
    /**
     * Text Input Field. If 'type' is passed in the $type field, it will be
     * used as the input type, for making 'email', 'phone', etc input fields.
     *
     * @param mixed  $data
     * @param string $value
     * @param mixed  $extra
     * @param string $type
     *
     * @return string
     */
    function form_input($data = '', string $value = '', $extra = '', string $type = 'text'): string
    {
        $defaults = [
            'type'  => $type,
            'name'  => is_array($data) ? '' : $data,
            'value' => $value,
        ];

        return '<input ' . parse_form_attributes($data, $defaults) . stringify_attributes($extra) . " />\n";
    }
}


if (!function_exists('form_textarea_spread')) {
    /**
     * Text Input Field. If 'type' is passed in the $type field, it will be
     * used as the input type, for making 'email', 'phone', etc input fields.
     *
     * @param mixed  $data
     * @param string string
     * @param mixed  $extra
     * @param string $type
     *
     * @return string
     */
    function form_textarea_spread($data = '', $value = null, $extra = '', $required = false, string $type = '', $builder = false): string
    {
        $html = '<div class="kt-input-icon kt-input-icon--left textarea">';
        if (service('Settings')->setting_activer_multilangue == true) {

            $setting_supportedLocales = json_decode(service('Settings')->setting_supportedLocales);
            if (!empty($setting_supportedLocales) && is_array($setting_supportedLocales)) {

                if ($required == true) {
                    $extra = $extra . ' required ';
                }
                //print_r($setting_supportedLocales);
                foreach ($setting_supportedLocales as $k => $v) {

                    $langExplode = explode('|', $v);
                    $extraFormat  = '';
                    if ($builder == true) {
                        $extraFormat = 'id="' . $data[1] . $data[0] . '_' . $langExplode[1] . '" ' . $extra;
                    } else {
                        $extraFormat = 'id="' . $data . '_' . $langExplode[1] . '" ' . $extra;
                    }


                    if (service('Settings')->setting_id_lang != $langExplode[0]) {
                        $extraFormat = $extraFormat . ' style="display:none;" ';
                    }
                    //print_r($langExplode);
                    //print_r($value);
                    $extraFormat = $extraFormat . ' data-id_lang="' . $langExplode[0] . '" data-iso_lang="' . $langExplode[1] . '"';
                    //echo $extraFormat; 
                    if (is_array($data)) {
                        $valueFormat = !isset($value[$langExplode[0]]->{$data[1]}) ? '' : $value[$langExplode[0]]->{$data[1]};
                    } else {
                        $valueFormat = !isset($value[$langExplode[0]]->{$data}) ? '' : $value[$langExplode[0]]->{$data};
                    }
                    $html .= '<div class="textarea ' . $type . ' lang" data-id_lang="' . $langExplode[0] . '" data-iso_lang="' . $langExplode[1] . '">';
                    if ($builder == true) {
                        if (is_array($data)) {
                            $html .= form_textarea(is_array($data) ? $builder . '[' . $data[0] . '][lang][' . $langExplode[0] . '][' . $data[1] . ']' : 'lang[' . $langExplode[0] . '][' . $data . ']', $valueFormat, $extraFormat);
                        } else {
                            $html .= form_textarea(is_array($data) ? $builder . '[' . $langExplode[0] . '][' . $data . ']' : 'lang[' . $langExplode[0] . '][' . $data . ']', $valueFormat, $extraFormat);
                        }
                    } else {
                        $html .= form_textarea(is_array($data) ? '' : 'lang[' . $langExplode[0] . '][' . $data . ']', $valueFormat, $extraFormat);
                    }

                    $html .= '</div>';
                }
                $html .= '<span class="kt-input-icon__icon kt-input-icon__icon--left"><span><i class="la la-language"></i></span></span>';
                if ($required == true) {
                    $html .= ' <div class="invalid-feedback">' . lang('Core.this_field_is_requis') . '</div>';
                }
            }
        } else {
            $defaults = [
                'type'  => $type,
                'name'  => is_array($data) ? '' : $data,
                'value' => $value,
            ];
            if ($builder == true) {
                $old = is_array($data) ? old($builder . '.' . $data[0] . '.lang.' . service('Settings')->setting_id_lang . '.' . $data[1]) : old('lang.' . service('Settings')->setting_id_lang . '.' . $data);
                $old = (string)$old;
                $html .=  form_textarea(
                    is_array($data) ? $builder . '[' . $data[0] . '][lang][' . service('Settings')->setting_id_lang . '][' . $data[1] . ']' : 'lang[' . service('Settings')->setting_id_lang . ']' . '[' . $data . ']',
                    $value = ($value == null) ? $old : $value[1]->{$data[1]},
                    $extra = 'id="' . $data[1] . $data[0]  . '_' . service('Settings')->setting_lang_iso . '" ' . $extra,
                    $type
                );
            } else {
                $old = is_array($data) ? '' : old('lang.' . service('Settings')->setting_id_lang . '.' . $data);
                $old = (string)$old;
                $html .=  form_textarea(
                    is_array($data) ? '' : 'lang[' . service('Settings')->setting_id_lang . ']' . '[' . $data . ']',
                    $value = (!isset($value[1]->{$data})) ? $old : $value[1]->{$data},
                    $extra = 'id="' . $data . '_' . service('Settings')->setting_lang_iso . '" ' . $extra,
                    $type
                );
            }

            $html .= '<span class="kt-input-icon__icon kt-input-icon__icon--left"><span><i class="la la-language"></i></span></span>';
            if ($required == true) {
                $html .= ' <div class="invalid-feedback">' . lang('Core.this_field_is_requis') . '</div>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}


if (!function_exists('form_textarea')) {
    /**
     * Text Input Field. If 'type' is passed in the $type field, it will be
     * used as the input type, for making 'email', 'phone', etc input fields.
     *
     * @param mixed  $data
     * @param string $value
     * @param mixed  $extra
     * @param string $type
     *
     * @return string
     */
    function form_textarea($data = '', string $value = '', $extra = '', string $type = 'text'): string
    {
        $defaults = [
            'name' => is_array($data) ? '' : $data,
            'cols' => '40',
            'rows' => '10',
        ];
        if (!is_array($data) || !isset($data['value'])) {
            $val = $value;
        } else {
            $val = $data['value'];
            unset($data['value']); // textareas don't use the value attribute
        }

        return '<textarea ' . parse_form_attributes($data, $defaults) . stringify_attributes($extra) . '>'
            . htmlspecialchars($val)
            . "</textarea>\n";
    }
}
