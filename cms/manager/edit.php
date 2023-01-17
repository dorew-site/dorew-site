<?php
/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

define('_DOREW', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
$title = 'Template | Chỉnh sửa dữ liệu';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    //get data
    $act = $_GET['act'];
    $filename = $_GET['file'];
    $url_file = $dir_tpl . '/' . $filename;
    $file = $filename ? $filename : 'ERROR';
    $checkExt = strtolower(array_pop(explode('.', $file)));
    $type = '';
    if (in_array($checkExt, array('css', 'js'))) {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $http_host = $protocol . $_SERVER['HTTP_HOST'];
        $clipboard = '<div class="list1"><i class="fa fa-clipboard" aria-hidden="true"></i> <input type="text" value="' . $http_host . '/' . $file . '"></div>';
        switch ($checkExt) {
            case 'css':
                $type = ' / <a href="/cms?type=css">CSS</a>';
                break;
            case 'js':
                $type = ' / <a href="/cms?type=js">Javascript</a>';
                break;
        }
    }
    echo '<div class="phdr"><a href="/cms"><i class="fa fa-home" aria-hidden="true"></i></a>' . $type . ' / <b>' . $file . '</b></div>';
    //check file
    if (!file_exists($url_file) || !$filename) {
        echo '<div class="rmenu">Tập tin <b>' . $filename . '</b> không tồn tại</div>';
    } else {
        if ($act == 'rename') {
            //rename current file
            $new_file_name = rwurl(htmlspecialchars($_POST['rename']));
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $new_url_file = $dir_tpl . '/' . $new_file_name;
                rename($url_file, $new_url_file);
                header('Location: /cms/manager/edit.php?file=' . $new_file_name);
            }
            echo '
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b>Nhập tên mới cho tệp:</b></p>
                    <p><input type="text" name="rename" value="' . $filename . '" /></p>
                    <p><button type="submit" class="submit">Đổi tên</button></p>
                </form>
            </div>
            ';
        } elseif ($act == 'delete') {
            //remove current file
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                unlink($url_file);
                header('Location: /cms/manager');
                exit();
            }
            echo '
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b style="color:red">Bạn có thực sự muốn xoá tập tin này không?</b></p>
                    <p><button type="submit" class="button">Xoá luôn ngại gì</button></p>
                </form>
            </div>
            ';
        } else {
            $data = file_get_contents($url_file);
            chmod($url_file, 0777);
            //get data from file
            $old_code = file_get_contents($url_file);
            //query
            $new_code = $_POST['contents'];
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                file_put_contents($url_file, $new_code);
                header('Location: ' . $request_uri);
                exit();
            }
            //form edit
            $layout = display_layout();
            if ($layout != 'mobile') {
                $rows_code = '200';
	            echo '
	        <link rel="stylesheet" href="https://codemirror.net/5/lib/codemirror.css">';
            } else $rows_code = '35';
            echo '
            <div class="menu">
                <form action="" method="post">
                    <textarea id="code" name="contents" style="width: 100%; min-height: 200px" rows="'. $rows_code . '">' . htmlspecialchars($old_code) . '</textarea>
                        <p style="text-align:center">
                            <button type="submit" name="submit" class="button">Cập nhật</button>
                        </p>
                </form>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
            <script	src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>

            <script>
                function getQueryVariable(r){for(var i=window.location.search.substring(1).split("&"),t=0;t<i.length;t++){var n=i[t].split("=");if(n[0]==r)return n[1]}}
                function saveToFile(){var e=document.getElementById("code").value,t=new Blob([e],{type:"text/plain;charset=utf-8"}),e=getQueryVariable("file");saveAs(t,e)} 
                
                
                if(Cookies.get("editor_mode")==="two"){
                    window.location.href = window.location.href.replace("\/manager\/edit.php?file","\/manager\/edit2.php?file");
                }
            </script>
            ';
            
            if ($layout != 'mobile') {
	        echo '


            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/codemirror.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/mode/overlay.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/mode/twig/twig.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/mode/xml/xml.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/mode/css/css.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/mode/javascript/javascript.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/mode/htmlmixed/htmlmixed.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/mode/multiplex.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/selection/active-line.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/edit/closetag.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/edit/matchbrackets.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/edit/closebrackets.min.js"></script>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/dialog/dialog.min.css" />

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/matchesonscrollbar.min.css" /><script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/search.min.js"></script>
            <script src="hhttps://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/searchcursor.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/matchesonscrollbar.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/match-highlighter.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/jump-to-line.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/search/match-highlighter.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/dialog/dialog.min.js"></script>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/show-hint.min.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/show-hint.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/html-hint.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/css-hint.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/javascript-hint.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/xml-hint.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/hint/anyword-hint.min.js"></script>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/scroll/simplescrollbars.min.css" />

            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/scroll/annotatescrollbar.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/scroll/scrollpastend.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/scroll/simplescrollbars.min.js"></script>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/display/fullscreen.min.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.7/addon/display/fullscreen.min.js"></script>
            
            <script>
                CodeMirror.defineMode("twigOverlay", function(config, parserConfig) {
                    return CodeMirror.overlayMode(CodeMirror.getMode(config, "htmlmixed"), CodeMirror.getMode(config, "twig"));
                });
                var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                    lineWrapping: true,
                    lineNumbers: true,
                    styleActiveLine: true,
                    matchBrackets: true,
                    autoCloseBrackets: true,
                    matchTags: true,
                    autoCloseTags: true,
                    mode: "twigOverlay",
                    extraKeys: {
                        "Ctrl-Space": "autocomplete",
                        "F11": function(cm) {
                          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                        },
                        "Esc": function(cm) {
                          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                        }
                    }
                    
                    
                    
                    
                    
                    
                });
                var cmSize = 1;
                if (document.getElementById("code").getAttribute("data-cm-size")) {
                    cmSize = document.getElementById("code").getAttribute("data-cm-size");
                    console.log(cmSize);
                }
                editor.setSize("100%", (window.innerHeight / cmSize ) - 100);
                window.onresize = function(event){
                    editor.setSize("100%", (window.innerHeight / cmSize ) - 100);
                };


                function changeEditMode() {
                    let current_url = window.location.href;
                    if(window.location.href.includes("/manager/edit2.php?file")){
                        Cookies.set("editor_mode", "one");
                        window.location.href = current_url.replace("\/manager\/edit2.php?file","\/manager\/edit.php?file");
        
                    }
                    else{
                        Cookies.set("editor_mode", "two");
                        window.location.href = current_url.replace("\/manager\/edit.php?file","\/manager\/edit2.php?file");
                        
                    }
                }
            

            </script>';
            }
            echo '
            
            <div class="phdr"><b><i class="fa fa-cogs" aria-hidden="true"></i> Công cụ</b></div>
            ' . $clipboard . '
            <a href="?' . $_SERVER['QUERY_STRING'] . '&act=rename"><div class="list1"><i class="fa fa-pencil" aria-hidden="true"></i> Đổi tên tập tin</div></a>
            <a href="?' . $_SERVER['QUERY_STRING'] . '&act=delete"><div class="list1"><i class="fa fa-trash" aria-hidden="true"></i> Xóa tập tin</div></a>
            <a onclick="saveToFile()"><div class="list1"><i class="fa fa-download" aria-hidden="true"></i> Tải về tập tin</div></a>
            <a onclick="changeEditMode()"><div class="list1"><i class="fa fa-text-height"></i> Chuyển chế độ chỉnh sửa</div></a>

            ';
        }
    }
} else {
    header('Location: /cms');
    exit();
}
include $root . '/cms/layout/footer.php';
