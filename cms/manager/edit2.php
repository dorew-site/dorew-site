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
            echo '
                <style>#editor{position:relative;width:100%;height:600px}</style>
                    <div class="menu">
                        <div class="btn-toolbar" role="toolbar">
                           <div class="btn-group js-ace-toolbar">
                              <button data-cmd="none" data-option="fullscreen" class="btn btn-sm btn-outline-secondary" id="js-ace-fullscreen" title="Fullscreen"><i class="fa fa-expand" title="Fullscreen"></i></button>
                              <button data-cmd="find" class="btn btn-sm btn-outline-secondary" id="js-ace-search" title="Search"><i class="fa fa-search" title="Search"></i></button>
                              <button data-cmd="undo" class="btn btn-sm btn-outline-secondary" id="js-ace-undo" title="Undo"><i class="fa fa-undo" title="Undo"></i></button>
                              <button data-cmd="redo" class="btn btn-sm btn-outline-secondary" id="js-ace-redo" title="Redo"><i class="fa fa-repeat" title="Redo"></i></button>
                              <button data-cmd="none" data-option="wrap" class="btn btn-sm btn-outline-secondary" id="js-ace-wordWrap" title="Word Wrap"><i class="fa fa-text-width" title="Word Wrap"></i></button>
                              <select id="js-ace-theme" data-type="theme" title="Select Theme" class="btn-outline-secondary border-left-0 d-none d-lg-block">
                                 <option>-- Select Theme --</option>
                              </select>
                              <select id="js-ace-fontSize" data-type="fontSize" title="Select Font Size" class="btn-outline-secondary border-left-0 d-none d-lg-block">
                                 <option>-- Select Font Size --</option>
                              </select>
                              <button type="button" class="btn btn-sm btn-outline-primary" onclick="edit_save(this)"><i class="fa fa-floppy-o"></i> Lưu</button>
                              <button type="button" class="btn btn-sm btn-outline-primary" onclick="saveToFile()"><i class="fa fa-download"></i> Tải về</button>

                           </div>
                        </div>
                    </div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.9.5/ace.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.9.5/ext-language_tools.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>

                    <div id="editor" contenteditable="true">' . nl2br(htmlspecialchars($old_code)) . '</div>
                    

                    <script>
                        function getQueryVariable(r){for(var i=window.location.search.substring(1).split("&"),t=0;t<i.length;t++){var n=i[t].split("=");if(n[0]==r)return n[1]}}
                    </script>


            

    <script>
    
        if(Cookies.get("editor_mode")==="one"){
            window.location.href = window.location.href.replace("\/manager\/edit2.php?file","\/manager\/edit.php?file");
        }
    
    
    
        var editor = ace.edit("editor");
        //editor.setTheme("ace/theme/twilight"); //Dark Theme
        
        let file_extension = getQueryVariable("file").split(".").pop();
        console.log(file_extension)
        if(file_extension === "css"){
            editor.session.setMode("ace/mode/css");
        }
        else if(file_extension === "js"){
            editor.session.setMode("ace/mode/javascript");
        }
        else{
            editor.session.setMode("ace/mode/twig");
        }
        

        
 
        
        
        
        
        editor.setFontSize(14)
        editor.setBehavioursEnabled(true)
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: false
    });
    
// ace.config.loadModule("ace/ext/language_tools", function () {
//     editor.insertSnippet("insert_row_table(table_name, column_name, column_value)");
//     editor.insertSnippet("insert_row_array_table(table_name, array_row)");
//     editor.insertSnippet("update_row_table(table_name, column_name, column_value, where_column_name, where_column_value))");

    
// });    
       
    
    
    
    
        function ace_commend (cmd) { editor.commands.exec(cmd, editor); }
        editor.commands.addCommands([{
            name: "save", bindKey: {win: "Ctrl-S",  mac: "Command-S"},
            exec: function(editor) { edit_save(this); }
        }]);
        function renderThemeMode() {
            var $themeEl = $("select#js-ace-theme"), $fontSizeEl = $("select#js-ace-fontSize"), optionNode = function(type, arr){ var $Option = ""; $.each(arr, function(i, val) { $Option += "<option value="+type+i+">" + val + "</option>"; }); return $Option; },
                _data = {"aceTheme":{"bright":{"chrome":"Chrome","clouds":"Clouds","crimson_editor":"Crimson Editor","dawn":"Dawn","dreamweaver":"Dreamweaver","eclipse":"Eclipse","github":"GitHub","iplastic":"IPlastic","solarized_light":"Solarized Light","textmate":"TextMate","tomorrow":"Tomorrow","xcode":"XCode","kuroir":"Kuroir","katzenmilch":"KatzenMilch","sqlserver":"SQL Server"},"dark":{"ambiance":"Ambiance","chaos":"Chaos","clouds_midnight":"Clouds Midnight","dracula":"Dracula","cobalt":"Cobalt","gruvbox":"Gruvbox","gob":"Green on Black","idle_fingers":"idle Fingers","kr_theme":"krTheme","merbivore":"Merbivore","merbivore_soft":"Merbivore Soft","mono_industrial":"Mono Industrial","monokai":"Monokai","pastel_on_dark":"Pastel on dark","solarized_dark":"Solarized Dark","terminal":"Terminal","tomorrow_night":"Tomorrow Night","tomorrow_night_blue":"Tomorrow Night Blue","tomorrow_night_bright":"Tomorrow Night Bright","tomorrow_night_eighties":"Tomorrow Night 80s","twilight":"Twilight","vibrant_ink":"Vibrant Ink"}},"fontSize":{8:8,10:10,11:11,12:12,13:13,14:14,15:15,16:16,17:17,18:18,20:20,22:22,24:24,26:26,30:30}};

            if(_data && _data.aceTheme) { var lightTheme = optionNode("ace/theme/", _data.aceTheme.bright), darkTheme = optionNode("ace/theme/", _data.aceTheme.dark); $themeEl.html("<optgroup label=\"Bright\">"+lightTheme+"</optgroup><optgroup label=\"Dark\">"+darkTheme+"</optgroup>");}
            if(_data && _data.fontSize) { $fontSizeEl.html(optionNode("", _data.fontSize)); }
            $themeEl.val( editor.getTheme() );
            $fontSizeEl.val(14).change(); //set default font size in drop down
        }

        $(function(){
            renderThemeMode();
            $(".js-ace-toolbar").on("click", "button", function(e){
                e.preventDefault();
                let cmdValue = $(this).attr("data-cmd"), editorOption = $(this).attr("data-option");
                if(cmdValue && cmdValue != "none") {
                    ace_commend(cmdValue);
                } else if(editorOption) {
                    if(editorOption == "fullscreen") {
                        (void 0!==document.fullScreenElement&&null===document.fullScreenElement||void 0!==document.msFullscreenElement&&null===document.msFullscreenElement||void 0!==document.mozFullScreen&&!document.mozFullScreen||void 0!==document.webkitIsFullScreen&&!document.webkitIsFullScreen)
                        &&(editor.container.requestFullScreen?editor.container.requestFullScreen():editor.container.mozRequestFullScreen?editor.container.mozRequestFullScreen():editor.container.webkitRequestFullScreen?editor.container.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT):editor.container.msRequestFullscreen&&editor.container.msRequestFullscreen());
                    } else if(editorOption == "wrap") {
                        let wrapStatus = (editor.getSession().getUseWrapMode()) ? false : true;
                        editor.getSession().setUseWrapMode(wrapStatus);
                    }
                }
            });
            $("select#js-ace-mode, select#js-ace-theme, select#js-ace-fontSize").on("change", function(e){
                e.preventDefault();
                let selectedValue = $(this).val(), selectionType = $(this).attr("data-type");
                if(selectedValue && selectionType == "theme") {
                    editor.setTheme(selectedValue);
                }else if(selectedValue && selectionType == "fontSize") {
                    editor.setFontSize(parseInt(selectedValue));
                }
            });
        });
        
        function saveToFile(){var e=editor.getSession().getValue(),t=new Blob([e],{type:"text/plain;charset=utf-8"}),e=getQueryVariable("file");saveAs(t,e+".txt")}            

        function edit_save(e) {
        	var n = editor.getSession().getValue();
        	if (typeof n !== "undefined" && n !== null) {
        		if (true) {
        			var data = {
        				contents: n
        			};
    
        			$.ajax({
        				type: "POST",
        				url: window.location.href,
        				data: data,
        				// contentType: "multipart/form-data-encoded; charset=utf-8",
        				//dataType: "json",
    
        			});
        			alert("Đã lưu?");
        		}
        	}
        }
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
            
        
            </script>

            ';
            
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
