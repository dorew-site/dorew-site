{% use '_layout' %}
{% set title = 'Đăng nhập' %}
{{block('head')}}
{% from '_functions' import rwurl %}
{% from '_users' import auto %}

{% if is_login() %}
  {{redirect('/')}}
{% else %}
  <div class="phdr">Đăng Nhập</div>
  {% if get_get('reg') == 's' %}
    <div class="gmenu">Đăng ký thành công. Giờ đây bạn có thể đăng nhập vào Dorew</div>
  {% endif %}
  {# kiểm tra và lưu tài khoản #}
  {% set user = rwurl(get_post('user')) %}
  {% set pass = get_post('pass') %}
  {% set bbb = '3' %}
  {% set pass = pass|replace({'0':'9','1':'8','2':'7','3':'6','4':'5','9':'0','8':'1','7':'2','6':'3','5':'4','a':'z',b:'y','c':'xx','d':'w','e':'v','f':'u','g':'t','h':'s','i':'r','j':'q','k':'p','l':'o','m':'n','z':'a',y:'b','x':'c','w':'d','v':'e','u':'f','t':'g','s':'h','r':'i','q':'j','p':'k','o':'l','n':'m','A':'Z',B:'Y','C':'XX','D':'W','E':'V','F':'U','G':'T','H':'S','I':'R','J':'Q','K':'P','L':'O','M':'N','Z':'A',Y:'B','X':'C','W':'D','V':'E','U':'F','T':'G','S':'H','R':'I','Q':'J','P':'K','O':'L','N':'M'}) %}
  {% if request_method()|lower == 'post' %} 
    {% if user and pass %}
      {% set user_data = select_table_row_data('users', 'nick', user) %}
      {% if user_data.id %}
         {% if user_data.pass != pass%}
           <div class="menu">Mật khẩu không đúng.</div>
        {% else %}
          <div class="menu">Đăng nhập thành công.</div>
          {% if get_post('token') == 'change' %}
            {% set auto = auto()|trim %}
            {% do update_row_table('users', 'auto', auto, 'nick', user) %}
            {{set_cookie('dorew',auto)}}
          {% else %}
            {{set_cookie('dorew',user_data.auto)}}      
          {% endif %}
          {{redirect('/')}}
        {% endif %}
      {% endif %}
    {% else %}
      <div class="rmenu"> Vui lòng điền đầy đủ thông tin.</div>
    {% endif %}
  {% endif %} 


<div class="menu">
   <form method="post" action="">
      <p>
         <i class="fa fa-user" aria-hidden="true"></i> Tên tài khoản:<br/>
         <input type="text" class="w3-input" name="user">
      </p>
      <p>
         <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu:<br/>
         <input type="password"  class="w3-input" name="pass">
      </p>
      <p><center>
         <script src="/doomcaptcha.js?1" countdown="on" label="Captcha" enemies="3"></script>
      </center></p>
      <p align="center"> <input type="checkbox" name="token" value="change">   Đăng xuất khỏi các thiết bị khác.</p>
      <p align="center"><button style="border: 4px solid red;" id="submit" disabled type="submit" class="button">Đăng Nhập</button></p>
   </form>
</div>

{% endif %}
{{block('end')}} 


