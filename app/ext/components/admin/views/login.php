<form  id="login" method="POST" action="?action=login">
    <input type="hidden" name="token" value="SecurityToken">
    <label>Вход в панель администратора</label>
    <input type="text" name="login" id="login" placeholder="Ваш логин">
    <input type="password" name="password" id="password" placeholder="Ваш пароль">
    <input type="submit" class="button button--center" value="Войти">
</form>