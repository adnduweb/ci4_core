<?php

if (!function_exists('arrayToArray')) {
    function arrayToArray(array $array)
    {
        $newArray = [];
        $i = 0;
        foreach ($array as $k => $v) {
            $newArray[$v['name']] = $v['value'];
            $i++;
        }
        return $newArray;
    }
}

if (!function_exists('generate_menu')) {
    function generate_menu($parent, $niveau, $section, $array)
    {
        print_r($array);
        $html = "";
        $niveau_precedent = 0;
        $section_precedent = 0;
        if (!$niveau && !$niveau_precedent) {
            $html .= "\n<ul>\n";
        }
        // echo $section;
        foreach ($array as $noeud) {
            if ($parent == $noeud->id_parent) {
                if ($niveau_precedent < $niveau) {
                    $html .= "\n<ul>\n";
                }
                if ($noeud->section == '1') {
                    $html .= '<li class="kt-menu__section">' . $noeud->name . "</li>\n";
                } else {
                    if ($noeud->id_parent == '0') {
                        $html .= '<li class="kt-menu__item">' . $noeud->name;
                    } else {
                        $html .= '<li class="kt-menu__item  kt-menu__item--submenu">' . $noeud->name;
                    }
                }
                if ($noeud->section == '1') {
                    $section = 1;
                } else {
                    $section = 0;
                }
                $niveau_precedent = $niveau;
                $html .= generate_menu($noeud->id_tab, ($niveau + 1), $section, $array);
            }
        }
        if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) {
            $html .= "</ul>\n</li>\n";
        } elseif ($niveau_precedent == $niveau) {
            $html .= "</ul>\n";
        } else {
            $html .= "</li>\n";
        }
        return $html;
    }
}

if (!function_exists('generate_menuOption')) {
    function generate_menuOption($parent, $niveau, $array, $id_parent)
    {
        $html = "";
        foreach ($array as $noeud) {
            if ($parent == $noeud->id_parent) {
                $html .= "<option ";

                $selected = ($id_parent == $noeud->getIdItem()) ? ' selected ' : '';
                $html .= $selected . " class='" . $noeud->getIdItem() . "' value='" . $noeud->getIdItem()  . "'>";
                for ($i = 0; $i < $niveau; $i++) {
                    $html .= "-";
                }
                $html .= $noeud->name;
                $html .=  "</option>";
                $html .= generate_menuOption($noeud->getIdItem(), ($niveau + 1), $array, $id_parent);
            }
        }
        return $html;
    }
}

if (!function_exists('afficher_menu_nestable')) {
    function afficher_menu_nestable($parent, $niveau, $array, $table = 'navs')
    {
        if (!isset($html)) $html = "";
        $niveau_precedent = 0;
        if (!$niveau && !$niveau_precedent) {
            $html .= "\n<ol class=\"dd-list\">\n";
        }
        //print_r($array);exit;
        foreach ($array as $noeud) {
            if ($parent == $noeud->id_parent) {
                if ($niveau_precedent < $niveau) {
                    $html .= "\n<ol data-id=\"$noeud->id_parent\" class=\"dd-list\">\n";
                }
                $html .= "<li class=\"dd-item dd-item-active-$noeud->active\" data-method=\"$noeud->slug\" data-id=\"$noeud->id\" data-niveau=\"$niveau\" data-niveau_precedent=\"$niveau_precedent\" data-parent=\"$parent\">";
                $html .= "<div class=\"dd-handle dd3-handle\"></div>";
                $html .= "<div class=\"dd3-content\"><strong><a href=" . CI_AREA_ADMIN . '/settings-advanced/' . $table . '/edit/' . $noeud->id . ">" . ucfirst($noeud->getBName()) . "</a></strong> /" . CI_AREA_ADMIN . "/" . $noeud->slug;
                $html .= '<div class="dropdown dropdown-inline dd3-action">
                                <button type="button" class="btn btn-hover-danger btn-elevate-hover btn-icon btn-sm btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-more-1"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right " x-placement="top-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-149px, -189px, 0px);">
                                    <a data-id="' . $noeud->id . '" class="dropdown-item edit" href="/' . CI_AREA_ADMIN . '/settings-advanced/' . $table . '/edit/' . $noeud->id . '"><i class="kt-nav__link-icon flaticon2-contract"></i>' . lang('Core.edit') . '</a>
                                    <a data-id="' . $noeud->id . '" class="dropdown-item delete" href="/' . CI_AREA_ADMIN . '/settings-advanced/' . $table . '/delete/' . $noeud->id . '"><i class="kt-nav__link-icon flaticon2-trash"></i>' . lang('Core.delete') . '</a>
                                </div>
                            </div>';
                $html .= "</div>";

                $niveau_precedent = $noeud->depth;
                $html .= afficher_menu_nestable($noeud->id, ($noeud->depth + 1), $array);
            }
        }
        if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) {
            $html .= "</li></ol>\n\n";
        } elseif ($niveau_precedent == $niveau) {
            $html .= "</ol>\n";
        } else {
            $html .= "\n";
        }


        return $html;
    }
}
